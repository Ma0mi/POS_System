<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pos";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจากคำขอแบบ POST
$order_number = $_POST['order_number'];
$total_price = $_POST['total_price'];
$amount_paid = $_POST['amount_paid'];
$products = json_decode($_POST['products'], true);

// บันทึกข้อมูลการชำระเงินสด
$sql = "INSERT INTO cash_payments (order_number, amount_paid) VALUES ('$order_number', '$amount_paid')";
if ($conn->query($sql) === TRUE) {
    $cash_payment_id = $conn->insert_id; // รหัสการชำระเงินสดที่เพิ่มล่าสุด
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// อัปเดตสถานะคำสั่งซื้อเป็น "ชำระเงินแล้ว"
$sql = "UPDATE orders SET payment_status = 'ชำระเงินแล้ว' WHERE order_number = '$order_number'";
if ($conn->query($sql) === TRUE) {
    // บันทึกรายการสินค้า
    foreach ($products as $product) {
        $product_name = $product['name'];
        $quantity = $product['quantity'];
        $price = $product['price'];

        $sql = "INSERT INTO order_items (order_number, product_name, quantity, price) VALUES ('$order_number', '$product_name', '$quantity', '$price')";
        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
