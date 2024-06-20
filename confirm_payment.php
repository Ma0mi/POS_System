<?php
// ตรวจสอบว่ามีข้อมูลที่ส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูล JSON และแปลงเป็น array
    $data = json_decode(file_get_contents('php://input'), true);

    // เชื่อมต่อกับฐานข้อมูล MySQL
    $servername = "localhost"; // เชื่อมต่อกับ MySQL บน localhost
    $username = "root"; // ชื่อผู้ใช้ของ MySQL
    $password = ""; // รหัสผ่านของ MySQL
    $dbname = "pos"; // ชื่อฐานข้อมูลที่ต้องการเชื่อมต่อ

    // สร้างการเชื่อมต่อ
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // วนลูปเพื่อบันทึกรายการสั่งซื้อลงในฐานข้อมูล
    foreach ($data as $item) {
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $orderNumber = $item['orderNumber']; // รับค่า orderNumber ที่ส่งมาจาก JavaScript
        $paymentMethod = $item['paymentMethod']; // รับค่าวิธีการชำระเงิน

        // SQL สำหรับการบันทึกข้อมูลลงในตาราง orders (ต้องแก้ไขตามโครงสร้างของฐานข้อมูลของคุณ)
        $sql = "INSERT INTO orders (order_number, product_name, price, quantity, payment_method) VALUES ('$orderNumber', '$name', '$price', '$quantity', '$paymentMethod')";

        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // อัปเดตจำนวนสินค้าในตาราง products
        $updateSql = "UPDATE products SET quantity = quantity - $quantity WHERE name = '$name'";
        if ($conn->query($updateSql) !== TRUE) {
            echo "Error updating product quantity: " . $conn->error;
        }
    }

    // ปิดการเชื่อมต่อ
    $conn->close();

    // ส่ง HTTP response code 200 (OK)
    http_response_code(200);
} else {
    // ถ้าไม่ใช่เมธอด POST ส่ง HTTP response code 405 (Method Not Allowed)
    http_response_code(405);
}
?>
