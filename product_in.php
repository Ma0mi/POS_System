<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";

// ตรวจสอบค่าก่อนใช้งาน
$name = isset($product['name']) ? $product['name'] : '';
$quantity = isset($product['quantity']) ? $product['quantity'] : '';
$total_price = isset($product['total_price']) ? $product['total_price'] : '';

// สร้าง SQL query เพื่อดึงวันที่ล่าสุด
$sql = "SELECT MAX(DATE(order_date)) AS max_order_date FROM orders";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// ดึงวันที่ล่าสุด
$latest_date = $row['max_order_date'];

// ตรวจสอบว่ามีสินค้าใหม่ในวันที่ล่าสุดหรือไม่
$sql_check_new_product = "SELECT COUNT(*) AS product_count FROM orders WHERE DATE(order_date) = :latest_date";
$stmt_check_new_product = $pdo->prepare($sql_check_new_product);
$stmt_check_new_product->bindParam(':latest_date', $latest_date);
$stmt_check_new_product->execute();
$product_count_row = $stmt_check_new_product->fetch(PDO::FETCH_ASSOC);
$product_count = $product_count_row['product_count'];

// ตั้งค่าค่าเริ่มต้นในฟอร์ม
$filter_start = isset($_GET['start_date']) ? $_GET['start_date'] : ($product_count > 0 ? $latest_date : date("Y-m-d"));
$filter_end = isset($_GET['end_date']) ? $_GET['end_date'] : ($product_count > 0 ? $latest_date : date("Y-m-d"));

// หากไม่มีการเลือกวันที่เริ่มต้นและวันที่สิ้นสุด ให้ใช้วันที่ปัจจุบัน
if (empty($filter_start) && empty($filter_end)) {
    $filter_start = date("Y-m-d");
    $filter_end = date("Y-m-d");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรายงานสินค้าเข้าคลัง</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product_in.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="content">
    <div class="container">
        <h1>ระบบรายงานสินค้าเข้าคลัง</h1>
        <label>กรองช่วงเวลารายงานสินค้าเข้าคลังที่ต้องการค้นหา</label>
        <form method="GET" id="filterForm">
            <div class="form-group">
                <label for="start_date">เริ่มจากวัน:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($filter_start); ?>">
            </div>
            <div class="form-group">
                <label for="end_date">จนถึง:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($filter_end); ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">กรองเวลารายงานสินค้าเข้าคลัง</button>
            </div>
        </form>



        <h1>รายงานสินค้าเข้าคลัง</h1>

        <?php if ($filter_start && $filter_end): ?>
            <h2>เริ่มจาก (<?php echo htmlspecialchars($filter_start); ?>) จนถึง (<?php echo htmlspecialchars($filter_end); ?>)</h2>
        <?php endif; ?>

        <?php
        // เชื่อมต่อกับฐานข้อมูล
        require_once "config.php";

        $sql = "SELECT DISTINCT report_id, date, name, quantity, total_price FROM products_in";

        if ($filter_start && $filter_end) {
            $sql .= " WHERE DATE(date) BETWEEN :start_date AND :end_date";
        } else {
            // ถ้าไม่ได้เลือกวันที่ ให้แสดงเฉพาะรายการของวันที่ปัจจุบัน
            $current_date = date("Y-m-d");
            $sql .= " WHERE DATE(date) = :current_date";
        }

        $sql .= " ORDER BY date DESC";

        $stmt = $pdo->prepare($sql);

        if ($filter_start && $filter_end) {
            $stmt->bindParam(':start_date', $filter_start);
            $stmt->bindParam(':end_date', $filter_end);
        } else {
            $stmt->bindParam(':current_date', $current_date);
        }

        $stmt->execute();

        // แสดงผลรายการสินค้าเข้าคลัง
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='card order-item'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>เลขที่รายงาน: " . htmlspecialchars($row['report_id']) . "</h5>";
            echo "<p class='card-text'>ชื่อสินค้า: " . htmlspecialchars($row['name']) . "</p>";
            echo "<p class='card-text'>จำนวน: " . htmlspecialchars($row['quantity']) . "</p>";
            echo "<p class='card-text'>ราคารวม: " . htmlspecialchars($row['total_price']) . "</p>";
            echo "<p class='card-text'>วันที่-เวลา (ปี-เดือน-วัน): " . htmlspecialchars($row['date']) . "</p>";
            echo "<a href='report_details.php?report_id=" . urlencode($row['report_id']) . "' class='btn btn-primary'>ดูรายละเอียด</a>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<script src="product_in.js"></script>
</body>
</html>