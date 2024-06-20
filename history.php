<?php
include 'navbar.php';
include 'sidebar.php';
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการขาย</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="sale_history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="content">
    <div class="container">
    <h1>ระบบบันทึกรายการขาย (ประวัติการสั่งซื้อ)</h1>
    <label>กรองช่วงเวลาใบเสร็จที่ต้องการค้นหา</label>
        <form method="GET" id="filterForm">
            <div class="form-group">
                <label for="start_date">เริ่มจากวัน:</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="form-group">
                <label for="end_date">จนถึง:</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success" onclick="applyFilter()">กรองเวลาใบเสร็จ</button>
            </div>
        </form>

        <h1>บันทึกรายการขาย(ประวัติการสั่งซื้อ)ทั้งหมด</h1>

        <?php
        // เชื่อมต่อกับฐานข้อมูล
        require_once "config.php";

        // ตรวจสอบว่ามีการส่งค่า filter ช่วงเวลาหรือไม่
        $filter_start = $_GET['start_date'] ?? null;
        $filter_end = $_GET['end_date'] ?? null;

        $sql = "SELECT DISTINCT order_number, order_date FROM orders";

        if ($filter_start && $filter_end) {
            $sql .= " WHERE DATE(order_date) BETWEEN :start_date AND :end_date";
        }

        $sql .= " ORDER BY order_date DESC";

        $stmt = $pdo->prepare($sql);

        if ($filter_start && $filter_end) {
            $stmt->bindParam(':start_date', $filter_start);
            $stmt->bindParam(':end_date', $filter_end);
        }

        $stmt->execute();

        // แสดงผลรายการสั่งซื้อ
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='card order-item'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>เลขที่ใบเสร็จ: " . htmlspecialchars($row['order_number']) . "</h5>";
            echo "<p class='card-text'>วันที่-เวลา (ปี-เดือน-วัน): " . htmlspecialchars($row['order_date']) . "</p>";
            echo "<a href='receipt_gen.php?order_number=" . urlencode($row['order_number']) . "' class='btn btn-primary'>ดูรายการสั่งซื้อ</a>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<script src="sale_history.js"></script>
</body>
</html>
