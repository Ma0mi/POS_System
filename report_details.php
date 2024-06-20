<?php
require_once __DIR__ . '/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [__DIR__ . '/tmp']),
    'fontdata' => $fontData + [
        'sarabun' => [
            'R' => 'Sarabun-Regular.ttf',
            'I' => 'Sarabun-Italic.ttf',
        ]
    ],
    'default_font' => 'sarabun'
]);

ob_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pos";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if order_number is set and valid
if (isset($_GET['report_id'])) {
    $report_id = $_GET['report_id'];

    // Fetch order date from the database
    $date = '';
    $sql_date = "SELECT date FROM products_in WHERE report_id = '$report_id' LIMIT 1";
    $result_date = $conn->query($sql_date);
    if ($result_date->num_rows > 0) {
        $row_date = $result_date->fetch_assoc();
        $date = $row_date['date'];
    }

    // Create HTML for the receipt
    $html = '<div style="text-align: center; margin-bottom: 20px;">
                <h1 style="font-size: 24px; margin-bottom: 10px;">ร้านบุญล้ำ ติดดาว</h1>
                <h1 style="font-size: 18px; margin-bottom: 10px;">39/1 หมู่ 4 ต.หนองเต่า อ.เก้าเลี้ยว จ.นครสวรรค์ 60230</h1>
                <h1 style="font-size: 24px; margin: 0;">รายงานสินค้าเข้าคลัง</h1>
                <p style="font-size: 18px; margin: 0;">เลขที่รายงาน: ' . $report_id . '</p>
                <p style="font-size: 16px; margin: 0;">วันที่ - เวลา: ' . $date . '</p>
            </div>';

    $html .= '<table style="border-collapse: collapse; width: 100%;">
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">ลำดับ</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">สินค้า</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">ราคาต่อหน่วย</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">จำนวน</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">รวม</th>
                </tr>';

    // Fetch product data related to the order_number
    $sql = "SELECT * FROM products_in WHERE report_id = '$report_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $total_price = 0;
        $count = 1;
        while($row = $result->fetch_assoc()) {
            $html .= '<tr style="border: 1px solid #ddd;">';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $count . '</td>';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $row['name'] . '</td>';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $row['total_price'] / $row['quantity'] . '</td>';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $row['quantity'] . '</td>';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $row['total_price'] . '</td>';
            $html .= '</tr>';
            $total_price += $row['total_price'];
            $count++;
        }
        // Display total price
        $html .= '<tr style="border: 1px solid #ddd;">
                    <td colspan="4" style="border: 1px solid #ddd; padding: 8px; text-align: right;">รวม</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $total_price . '</td>
                </tr>';
    } else {
        $html .= '<tr style="border: 1px solid #ddd;">
                    <td colspan="5" style="border: 1px solid #ddd; padding: 8px; text-align: center;">ไม่พบรายการสั่งซื้อ</td>
                </tr>';
    }

    $html .= '</table>';

    // Generate PDF and output
    $mpdf->WriteHTML($html);
    $mpdf->Output();
} else {
    echo "Invalid order number";
}

$conn->close();
?>
