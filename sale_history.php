<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";

// เรียกใช้งานฟังก์ชันเพื่อตรวจสอบบทบาทของผู้ใช้
function getUserRole($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    return $user["role"];
}

// ตรวจสอบบทบาทของผู้ใช้โดยใช้ฐานข้อมูล
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$user_role = $user_id ? getUserRole($pdo, $user_id) : null;

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
    <title>ระบบบันทึกรายการขาย</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="sale_history.css">
    <link rel="stylesheet" href="product_in.css">
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
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($filter_start); ?>">
            </div>
            <div class="form-group">
                <label for="end_date">จนถึง:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($filter_end); ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">กรองเวลาใบเสร็จ</button>
            </div>
        </form>



        <h1>บันทึกรายการขาย(ประวัติการสั่งซื้อ)ทั้งหมด</h1>

        <?php if ($filter_start && $filter_end): ?>
            <h2>เริ่มจาก (<?php echo htmlspecialchars($filter_start); ?>) จนถึง (<?php echo htmlspecialchars($filter_end); ?>)</h2>
        <?php endif; ?>

        <?php
        // เชื่อมต่อกับฐานข้อมูล
        require_once "config.php";

        $sql = "SELECT DISTINCT order_number, order_date FROM orders";

        if ($filter_start && $filter_end) {
            $sql .= " WHERE DATE(order_date) BETWEEN :start_date AND :end_date";
        } else {
            // ถ้าไม่ได้เลือกวันที่ ให้แสดงเฉพาะรายการของวันที่ปัจจุบัน
            $current_date = date("Y-m-d");
            $sql .= " WHERE DATE(order_date) = :current_date";
        }

        $sql .= " ORDER BY order_date DESC";

        $stmt = $pdo->prepare($sql);

        if ($filter_start && $filter_end) {
            $stmt->bindParam(':start_date', $filter_start);
            $stmt->bindParam(':end_date', $filter_end);
        } else {
            $stmt->bindParam(':current_date', $current_date);
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