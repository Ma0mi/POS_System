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

// ดึงข้อมูลสินค้าจากตาราง products
$sql = "SELECT id, name, description, quantity, price, image FROM products";
$result = $conn->query($sql);

$products = array();

if ($result->num_rows > 0) {
    // แปลงข้อมูลให้เป็นรูปแบบของอาร์เรย์
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

// ส่งข้อมูลสินค้าในรูปแบบ JSON
header('Content-Type: application/json');
echo json_encode($products);
?>
