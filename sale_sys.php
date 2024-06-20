<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT qrcode FROM system ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($sql);
$QRCODE = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="sale_sys.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="content">
    <div class="container">
        <h1>ระบบการขาย</h1>
        <!-- Search bar -->
        <div class="input-group mb-3">
        <input id="searchInput" type="text" class="form-control" placeholder="ค้นหาสินค้า">
        <div class="input-group-append">
        <button id="searchButton" class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
        </div>
        </div>


<div class="row">
    <!-- Product list -->
    <div id="products-container" class="col-md-8">
        <!-- รายการสินค้าจะถูกแสดงที่นี่ -->
        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                <img src="product/<?php echo $product['image']; ?>" class="card-img-top image-resize" alt="<?php echo $product['name']; ?>">

                    <div class="card-body">
                        <h5 class="card-title">รหัสสินค้า: <?php echo $product['id']; ?></h5>
                        <h5 class="card-title">ชื่อ: <?php echo $product['name']; ?></h5>
                        <p class="card-text">รายละเอียด: <?php echo $product['description']; ?></p>
                        <p class="card-text">จำนวน: <?php echo $product['quantity']; ?></p>
                        <p class="card-text">ราคา: <?php echo $product['price']; ?> บาท</p>
                        <button class="btn btn-primary" onclick="addToCart('<?php echo $product['name']; ?>', <?php echo $product['price']; ?>, <?php echo $product['quantity']; ?>)" data-quantity="<?php echo $product['quantity']; ?>">เพิ่มลงในตะกร้า</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- ปุ่มถัดไปและย้อนกลับ -->
    <div class="text-center mt-3">
        <button id="prev-button" class="btn btn-primary" onclick="previousPage()">ย้อนกลับ</button>
        <button id="next-button" class="btn btn-primary" onclick="nextPage()">ถัดไป</button>
    </div>
</div>

        <!-- Cart -->
        <div class="col-md-4">
                <div class="cart-container">
                    <h3 class="cart-heading">ตะกร้าสินค้า</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ชื่อสินค้า</th>
                                <th>ราคา</th>
                                <th>จำนวน</th>
                                <th>รวมราคา</th>
                            </tr>
                        </thead>
                        <tbody class="cart-items">
                            <!-- Cart items will be displayed here -->
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>รวมราคา: <span id="totalPrice" class="cart-total">0.00</span> </h4>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary btn-change">ปรับราคาสินค้า</button>
                        <button class="btn btn-success btn-check">ตรวจสอบรายการสินค้า</button>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
    var products = <?php echo json_encode($products); ?>; // ข้อมูลสินค้าทั้งหมด
    const lastestQrcode = `<?php echo $QRCODE['qrcode']; ?>`;
</script>

<script src="sale_sys.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>
</html>
