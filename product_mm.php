<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";

// ตัวแปรสำหรับกำหนดจำนวนสินค้าต่อหน้า
$limit = 50;

// ตรวจสอบหมายเลขหน้าปัจจุบัน
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// คำนวณ offset สำหรับการดึงข้อมูลสินค้า
$offset = ($page - 1) * $limit;

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT * FROM products ORDER BY id LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// คำนวณจำนวนหน้าทั้งหมด
$sql = "SELECT COUNT(*) AS total FROM products";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_pages = ceil($row['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบการจัดการข้อมูลคลังสินค้า</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="content">
    <div class="container">
        <h1>ระบบการจัดการข้อมูลคลังสินค้า</h1>
        
        <!-- Display product table -->
        <input id="searchInput" type="text" class="form-control" placeholder="ค้นหาสินค้า">

        <table class="table">
            <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>รหัสสินค้า</th>
                <th>รูป</th>
                <th>ชื่อ</th>
                <th>รายละเอียด</th>
                <th>ราคา</th>
                <th>จำนวน</th>
                <th>การจัดการ</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $key => $product): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $product['id']; ?></td>
                    <td><img src="product/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 75px;"></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td>
                        <!-- Edit button -->
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">แก้ไข</a>
                        <!-- Delete button -->
                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm delete-btn">ลบ</a>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="product_mm.php?page=<?php echo $page - 1; ?>" tabindex="-1" aria-disabled="true">ก่อนหน้า</a>
                </li>
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link" href="product_mm.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="product_mm.php?page=<?php echo $page + 1; ?>">ถัดไป</a>
                </li>
            </ul>
        </nav>

        <!-- Add product button -->
        <a href="add_product.php" class="btn btn-success">เพิ่มสินค้า</a>
        <a href="add_quantity.php" class="btn btn-info">เพิ่มจำนวนสินค้า</a>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="product_mm.js"></script>
</body>
</html>
