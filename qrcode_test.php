<?php
// เชื่อมต่อกับฐานข้อมูล
require_once "config.php";

// ส่งคำสั่ง SQL เพื่อดึงชื่อของรูปภาพล่าสุด
$sql = "SELECT qrcode FROM system ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($row) {
    $latestQRCode = $row['qrcode'];
} else {
    $latestQRCode = ""; // หากรูปภาพไม่พบ
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest QR Code</title>
</head>
<body>
    <?php if (!empty($latestQRCode)): ?>
        <img src="qrcodes/<?php echo $latestQRCode; ?>" alt="Latest QR Code">
    <?php else: ?>
        <p>No QR Code available</p>
    <?php endif; ?>
</body>
</html>
