<?php
// ตรวจสอบว่ามีการส่งข้อมูล POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // เชื่อมต่อกับฐานข้อมูล (ให้แก้ไข hostname, username, password, dbname ตามฐานข้อมูลที่ใช้)
    $conn = new mysqli("localhost", "root", "", "pos");

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ดึงข้อมูลจากตารางรายการสินค้าในตะกร้า
    $cartItems = $_POST['cartItems']; // ข้อมูลจากฟอร์ม

    // เพิ่มข้อมูลการสั่งซื้อลงในตาราง orders
    $sql = "INSERT INTO orders (order_number, total_price, payment_method) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $orderNumber, $totalPrice, $paymentMethod);

    // กำหนดค่าพารามิเตอร์
    $orderNumber = generateOrderNumber(); // ใช้ฟังก์ชัน generateOrderNumber() เพื่อสร้างเลขที่รายการสั่งซื้อ
    $totalPrice = $_POST['totalPrice']; // ราคารวมทั้งหมดจากฟอร์ม
    $paymentMethod = "cash"; // รูปแบบการชำระเงิน (สำหรับชำระเงินสด)

    // สั่งประมวลผลคำสั่ง SQL
    $stmt->execute();

    // ตรวจสอบการประมวลผลคำสั่ง SQL
    if ($stmt->affected_rows > 0) {
        echo "บันทึกข้อมูลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
    }

    // ปิดคำสั่ง SQL
    $stmt->close();

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    $conn->close();
}
?>
