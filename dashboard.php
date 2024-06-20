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
$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// แสดงชื่อผู้ใช้
$username = $user["username"];
$user_role = $user["role"];

// ดึงจำนวนสินค้าทั้งหมด
$stmt = $pdo->query("SELECT COUNT(name) AS total_id FROM products");
$total_id = $stmt->fetchColumn();

// ดึงจำนวนสินค้าทั้งหมด
$stmt = $pdo->query("SELECT SUM(quantity) AS total_products FROM products");
$total_products = $stmt->fetchColumn();

// ดึงรายการขายทั้งหมด
$stmt = $pdo->query("SELECT COUNT(order_number) AS total_sale FROM orders");
$total_sale = $stmt->fetchColumn();

// ดึงผู้ใช้
$stmt = $pdo->query("SELECT COUNT(id) AS user_id FROM users");
$user_id = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM products WHERE quantity < 50");
$stmt->execute();
$low_quantity_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
</head>
<body>

   <!-- Navbar -->
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

<!-- Content -->
<div class="content">
    <div class="container">
        <h1>ยินดีต้อนรับสู่ระบบ POS ร้านบุญล้ำติดดาว</h1>
        <div class="row">

            <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-box"></i> จำนวนรายการสินค้า</h5>
                            <h1 class="card-text"><?php echo $total_id; ?></h1>
                        </div>
                    </div>
                </div>

            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-box"></i> จำนวนสินค้า</h5>
                        <h1 class="card-text"><?php echo $total_products; ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-line"></i> จำนวนการขาย</h5>
                        <h1 class="card-text"><?php echo $total_sale; ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> จำนวนผู้ใช้</h5>
                        <h1 class="card-text"><?php echo $user_id; ?></h1>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="container">
     <div class="card">
      <div class="card-body">
        <h2 class="card-title">รายการสั่งซื้อล่าสุด</h2>
        <?php
        // ดึง order_number และเวลาล่าสุดจากฐานข้อมูล
        $stmt = $pdo->query("SELECT order_number, order_date FROM orders ORDER BY order_date DESC LIMIT 1");
        $latest_order = $stmt->fetch(PDO::FETCH_ASSOC);
        $order_number = $latest_order['order_number'];
        $order_date = $latest_order['order_date'];
        ?>
        <p class="card-text">เลขที่ใบเสร็จ: <?php echo $order_number; ?></p>
        <p class="card-text">สั่งซื้อเมื่อเวลา: <?php echo $order_date; ?></p>
    </div>
     </div>
       </div>
       
       <div class="container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">รายงานสินค้าเข้าคลังล่าสุด</h2>
            <?php
            // ดึงข้อมูลสินค้าเข้าคลังล่าสุดจากฐานข้อมูล
            $stmt = $pdo->query("SELECT report_id, date, name, quantity, total_price FROM products_in ORDER BY date DESC LIMIT 1");
            $latest_product_in = $stmt->fetch(PDO::FETCH_ASSOC);
            $report_id = $latest_product_in['report_id'];
            $report_date = $latest_product_in['date'];
            $product_name = $latest_product_in['name'];
            $product_quantity = $latest_product_in['quantity'];
            $product_price = $latest_product_in['total_price'];
            ?>
            <p class="card-text">เลขที่รายงาน: <?php echo $report_id; ?></p>
            <p class="card-text">ชื่อสินค้า: <?php echo $product_name; ?></p>
            <p class="card-text">จำนวน: <?php echo $product_quantity; ?></p>
            <p class="card-text">ราคารวม: <?php echo $product_price; ?></p>
            <p class="card-text">สินค้าเข้าเวลา: <?php echo $report_date; ?></p>
        </div>
    </div>
</div>


    <div class="container">
    <h2>รายการสินค้าที่เหลือจำนวนน้อย ล่าสุด!!!</h2>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวนล่าสุด</th>
                        <th>ราคา</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($low_quantity_products as $product): ?>
                    <tr>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['price']; ?> บาท</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="container">
        <h2>ยอดการขายของรายการสินค้า</h2>
        <div class="sales-chart">
            <?php
            // ดึงข้อมูลยอดการขายของรายการสินค้า 3 รายการจากฐานข้อมูล
            $stmt = $pdo->query("SELECT product_name, SUM(quantity) AS total_sales FROM orders GROUP BY product_name ORDER BY total_sales DESC LIMIT 3");

            // แสดงผลยอดการขาย
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='bar' style='width: " . ($row['total_sales'] * 10) . "px;'>" . htmlspecialchars($row['product_name']) . ": " . htmlspecialchars($row['total_sales']) . "</div>";
            }
            ?>
        </div>
    </div>

</div>



</div>
<script src="dashboard.js"></script>
</body>
</html>
