<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
require "config.php";

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$user_id = $_SESSION["user_id"];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// แสดงชื่อผู้ใช้
$username = $user["username"];

?>
<!-- Navbar -->
<link rel="stylesheet" href="navbar.css">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">POS ร้านบุญล้ำติดดาว</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <p class="nav-link text-white mb-0">ยินดีต้อนรับ <?php echo $username; ?></p>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white mb-0" id="currentDateTime"></a>
            </li>
            <li class="nav-item">
                <button class="btn btn-outline-danger" onclick="window.location.href='logout.php';">ออกจากระบบ</button>
            </li>
        </ul>
    </div>
</nav>
<script src="dashboard.js"></script>