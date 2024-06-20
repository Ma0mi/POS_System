<?php
// เชื่อมต่อกับฐานข้อมูล
require_once "config.php";

// ส่งคำสั่ง SQL เพื่อดึงชื่อของรูปภาพล่าสุด
$sql = "SELECT qrcode FROM system ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

print_r($row);
if ($row) {
    $latestQRCode = "qrcodes/" . $row['qrcode'];
} else {
    $latestQRCode = ""; // หากรูปภาพไม่พบ
}
?>

<!-- ตำแหน่งที่คุณต้องการแสดงรูปภาพ -->
<td><img src="<?php echo $latestQRCode; ?>" style="width: 75px;"></td>
