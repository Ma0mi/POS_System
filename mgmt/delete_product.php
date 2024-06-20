<?php
require_once "config.php";

// ตรวจสอบว่ามีการส่งค่า id ของสินค้ามาหรือไม่
if (!isset($_GET['id'])) {
    exit("ไม่พบ ID สินค้า");
}

$id = $_GET['id'];

// ลบข้อมูลสินค้าที่มี id ตามที่ระบุ
$sql = "DELETE FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

// Redirect กลับไปยังหน้า product_mm.php
header("Location: product_mm.php");
exit();
?>
