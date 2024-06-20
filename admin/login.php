<?php
session_start();
require_once('config.php');

$username = $_POST['username'];
$password = $_POST['password'];

// ค้นหาข้อมูลผู้ใช้งานจากฐานข้อมูลโดยใช้ชื่อผู้ใช้งาน
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

// ตรวจสอบว่าพบผู้ใช้งานหรือไม่
if ($user) {
    // ตรวจสอบว่ารหัสผ่านของผู้ใช้งานเป็นการเข้ารหัสแบบ password_hash() หรือไม่
    if (password_verify($password, $user['password'])) {
        // เก็บ session ของผู้ใช้งาน
        $_SESSION['user_id'] = $user['id'];
        echo 'success';
    } else {
        // กรณีรหัสผ่านไม่ได้ถูกเข้ารหัสด้วย password_hash() ใช้รหัสผ่านเป็น plaintext ในการตรวจสอบ
        if ($password === $user['password']) {
            // เก็บ session ของผู้ใช้งาน
            $_SESSION['user_id'] = $user['id'];
            echo 'success';
        } else {
            echo 'error';
        }
    }
} else {
    echo 'error';
}
?>
