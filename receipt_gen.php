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
if (isset($_GET['order_number'])) {
    $order_number = $_GET['order_number'];

    // Fetch order date from the database
    $order_date = '';
    $sql_order_date = "SELECT order_date FROM orders WHERE order_number = '$order_number' LIMIT 1";
    $result_order_date = $conn->query($sql_order_date);
    if ($result_order_date->num_rows > 0) {
        $row_order_date = $result_order_date->fetch_assoc();
        $order_date = $row_order_date['order_date'];
    }

    // Fetch payment method, change amount, and received amount from the database
    $sql_payment_info = "SELECT payment_method, change_amount, received_amount FROM orders WHERE order_number = '$order_number' LIMIT 1";
    $result_payment_info = $conn->query($sql_payment_info);
    $payment_method = '';
    $change_amount = '';
    $received_amount = '';
    if ($result_payment_info->num_rows > 0) {
        $row_payment_info = $result_payment_info->fetch_assoc();
        $payment_method = $row_payment_info['payment_method'];
        $change_amount = $row_payment_info['change_amount'];
        $received_amount = $row_payment_info['received_amount'];
    }

    // Create HTML for the receipt with payment information
    $html = '<div style="text-align: center; margin-bottom: 20px;">
                <h1 style="font-size: 24px; margin-bottom: 10px;">ร้านบุญล้ำ ติดดาว</h1>
                <h1 style="font-size: 18px; margin-bottom: 10px;">39/1 หมู่ 4 ต.หนองเต่า อ.เก้าเลี้ยว จ.นครสวรรค์ 60230</h1>
                <h1 style="font-size: 24px; margin: 0;">ใบเสร็จรับเงิน</h1>
                <p style="font-size: 18px; margin: 0;">เลขที่ใบเสร็จ: ' . $order_number . '</p>
                <p style="font-size: 16px; margin: 0;">วันที่ - เวลา: ' . $order_date . '</p>
                <p style="font-size: 16px; margin: 0;">วิธีการชำระ: ' . $payment_method . '</p>
                <p style="font-size: 16px; margin: 0;">รับเงินมา: ' . $received_amount . '</p>
                <p style="font-size: 16px; margin: 0;">เงินทอน: ' . $change_amount . '</p>
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
    $sql = "SELECT * FROM orders WHERE order_number = '$order_number'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $total_price = 0;
        $count = 1;
        while($row = $result->fetch_assoc()) {
            $html .= '<tr style="border: 1px solid #ddd;">';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $count . '</td>';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $row['product_name'] . '</td>';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $row['price'] . '</td>';
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $row['quantity'] . '</td>';
            $subtotal = $row['price'] * $row['quantity'];
            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $subtotal . '</td>';
            $html .= '</tr>';
            $total_price += $subtotal;
            $count++;
        }
        // Display total price
        $html .= '<tr style="border: 1px solid #ddd;">
                    <td colspan="4" style="border: 1px solid #ddd; padding: 8px; text-align: right;">รวม</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $total_price . '</td>
                </tr>';

        $html .= '<tr style="border: 1px solid #ddd;">
                    <td colspan="4" style="border: 1px solid #ddd; padding: 8px; text-align: right;">วิธีการชำระ</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $payment_method . '</td>
                </tr>';

        $html .= '<tr style="border: 1px solid #ddd;">
                    <td colspan="4" style="border: 1px solid #ddd; padding: 8px; text-align: right;">จำนวนชำระ</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $received_amount . '</td>
                </tr>';

        $html .= '<tr style="border: 1px solid #ddd;">
                    <td colspan="4" style="border: 1px solid #ddd; padding: 8px; text-align: right;">เงินทอน</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $change_amount . '</td>
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
