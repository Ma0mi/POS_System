<?php

// เชื่อมต่อฐานข้อมูล
require "config.php";

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$user_id = $_SESSION["user_id"];
$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// แสดงชื่อผู้ใช้
$username = $user["username"];
$user_role = $user["role"];
?>
    
 <!-- Sidebar -->
    <div class="sidebar">
    <div class="user-info">
    <a href="dashboard.php"><i class="fas fa-home"></i> หน้าแรก</a>

    </div>
    
    <h3 class="sales-menu">เมนูการขาย</h3>
    <a href="sale_history.php"><i class="fas fa-history"></i> ระบบบันทึกรายการขาย</a>
    <a href="sale_sys.php"><i class="fas fa-shopping-cart"></i> ระบบการขาย</a>
    <a href="sale_rp.php"><i class="fas fa-chart-line"></i> ระบบรายงานการขาย</a>
    <a href="product_in.php"><i class="fas fa-box-open"></i> ระบบรายงานสินค้าเข้าคลัง</a>
    
    <?php
    // ตรวจสอบบทบาทของผู้ใช้และแสดงเมนูเพิ่มเติมตามบทบาท
    if ($user_role === 'admin') {
        echo '<h3 class="management-menu">เมนูการจัดการระบบ</h3>';
        echo '<a href="product_mm.php"><i class="fas fa-cogs"></i> ระบบการจัดการคลังสินค้า</a>';
        echo '<a href="user_mm.php"><i class="fas fa-users-cog"></i> ระบบการจัดการผู้ใช้งาน</a>';
        echo '<a href="sys_mm.php"><i class="fas fa-cog"></i> ตั้งค่าระบบ</a>';
    }
    ?>
    </div>
</div> 