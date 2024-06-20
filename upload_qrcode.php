<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['qrcode']) && $_FILES['qrcode']['error'] === UPLOAD_ERR_OK) {
        // รับข้อมูลของไฟล์ qrcode
        $qrcode_tmp = $_FILES['qrcode']['tmp_name'];
        $qrcode_name = $_FILES['qrcode']['name'];

        // เชื่อมต่อกับฐานข้อมูล
        require_once "config.php";

        // เพิ่มข้อมูล qrcode ลงในฐานข้อมูล
        $sql = "INSERT INTO system (qrcode) VALUES (:qrcode)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['qrcode' => $qrcode_name]);

        // ย้ายไฟล์ qrcode ไปยังโฟลเดอร์ที่เซฟ
        if (move_uploaded_file($qrcode_tmp, "C:/xampp/htdocs/POS/qrcodes/" . $qrcode_name)) {
            // ส่งคำสั่ง JavaScript ไปยังไฟล์ sys_mm.php เพื่อให้แสดง popup และ redirect กลับไปที่ sys_mm.php
            echo "<script>window.location.href = 'sys_mm.php?success=1';</script>";
        } else {
            echo "<script>window.location.href = 'sys_mm.php?error=1';</script>";
        }
    } else {
        echo "<script>window.location.href = 'sys_mm.php?error=1';</script>";
    }
}
?>
