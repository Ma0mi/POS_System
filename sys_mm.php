<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตั้งค่าระบบ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product_in.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="content">
    <div class="container">
        <h1>ตั้งค่าระบบ</h1>
        <h3>เพิ่มหรือเปลี่ยนรูป QR Code</h3>
        <form action="upload_qrcode.php" method="post" enctype="multipart/form-data">
            <input type="file" name="qrcode" id="qrcodeInput">
            <input type="submit" value="อัพโหลด QR Code" name="submit">
        </form>
        <h3>QR Code ปัจจุบัน</h3>
        <?php
        // ดึงข้อมูลรูปภาพล่าสุดจากฐานข้อมูล
        $sql = "SELECT qrcode FROM system ORDER BY id DESC LIMIT 1";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $qrcodeURL = $row['qrcode'];

        // แสดงรูปภาพ
        if ($qrcodeURL) {
            echo "<img src='qrcodes/$qrcodeURL' alt='QR Code' style='max-width: 500px;'>";
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
<?php
// ตรวจสอบว่ามีค่า success ใน URL หรือไม่
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "Swal.fire({
        title: 'อัพโหลด QR Code สำเร็จ!',
        icon: 'success',
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'sys_mm.php'; // นำทางกลับไปยังหน้า sys_mm.php
        }
    });";
} elseif (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "Swal.fire({
        title: 'อัพโหลด QR Code ล้มเหลว!',
        text: 'กรุณาเลือกรูปภาพ QRCode ก่อนดำเนินการกดปุ่มอัพโหลด QRCode ',
        icon: 'error',
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'sys_mm.php'; // นำทางกลับไปยังหน้า sys_mm.php
        }
    });";
}

?>
</script>

</body>
</html>
