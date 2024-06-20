<?php
// ตรวจสอบว่ามีข้อมูลที่ส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูล JSON และแปลงเป็น array
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    // รับค่า orderNumber และ cartData จากข้อมูลที่ส่งมา
    $orderNumber = $data['orderNumber'];
    $cartData = $data['cartData'];
    $paymentMethod = $data['paymentMethod']; // รับค่าวิธีการชำระเงิน
    $changeAmount = $data['changeAmount']; // รับค่าเงินทอน
    $receivedAmount = $data['receivedAmount']; // รับค่าเงินที่รับ

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
    foreach ($cartData as $item) {
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        // SQL สำหรับการบันทึกข้อมูลลงในตาราง orders
        $sql = "INSERT INTO orders (order_number, product_name, price, quantity, payment_method, change_amount, received_amount) 
                VALUES ('$orderNumber', '$name', '$price', '$quantity', '$paymentMethod', '$changeAmount', '$receivedAmount')";

        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        // อัปเดตค่า total_price
        $updateTotalPriceSql = "UPDATE orders SET total_price = price * quantity WHERE order_number = '$orderNumber'";
        if ($conn->query($updateTotalPriceSql) !== TRUE) {
            echo "Error updating total price: " . $conn->error;
        }
    }

    // วนลูปผ่านรายการ order เพื่ออัปเดตจำนวนสินค้าในตาราง products
    foreach ($cartData as $item) {
        $name = $item['name'];
        $quantity = $item['quantity'];
        $orderNumber = $item['orderNumber']; // รับค่า orderNumber ที่ส่งมาจาก JavaScript

        // อัปเดตจำนวนสินค้าในตาราง products
        $updateProductSql = "UPDATE products SET quantity = quantity - $quantity WHERE name = '$name'";
        if ($conn->query($updateProductSql) !== TRUE) {
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
