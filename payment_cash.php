<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pos";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจากการ POST หรือ GET
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ดึงข้อมูลสินค้าจากตัวแปร POST
    $products_json = $_POST['products'];
    $products = json_decode(urldecode($products_json), true);

    // สร้างรายการสั่งซื้อใหม่
    $order_date = date("Y-m-d H:i:s"); // วันที่และเวลาปัจจุบัน
    $total_amount = 0;

    // คำนวณราคารวม
    foreach ($products as $product) {
        $total_amount += $product['price'] * $product['quantity'];
    }

    $payment_method = "cash"; // วิธีการชำระเงิน
    // เพิ่มข้อมูลรายการสั่งซื้อลงในตาราง orders
    $sql = "INSERT INTO orders (order_date, total_amount, payment_method)
    VALUES ('$order_date', '$total_amount', '$payment_method')";

    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // รหัสการสั่งซื้อที่เพิ่มล่าสุด
        // เพิ่มข้อมูลรายละเอียดการสั่งซื้อลงในตาราง order_details
        foreach ($products as $product) {
            $product_id = $product['id'];
            $quantity = $product['quantity'];
            $price_per_unit = $product['price'];
            $sql = "INSERT INTO order_details (order_id, product_id, quantity, price_per_unit)
            VALUES ('$order_id', '$product_id', '$quantity', '$price_per_unit')";
            $conn->query($sql);
        }
        echo "บันทึกข้อมูลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
    }
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
