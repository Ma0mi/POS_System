<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";

$name = $description = $price = $quantity = '';
$image = 'No_Image_Available.jpg'; // Default image

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Check if image is uploaded
    if ($_FILES['image']['name']) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        
        // เพิ่มโค้ดเพื่อย้ายไฟล์ไปยังโฟลเดอร์ C:\xampp\htdocs\POS
        $destination = "C:/xampp/htdocs/POS/product/" . $file_name;
        move_uploaded_file($file_tmp, $destination);
        
        $image = $file_name;
    }

    $sql = "INSERT INTO products (id, name, description, price, quantity, image) VALUES (:id, :name, :description, :price, :quantity, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id, 'name' => $name, 'description' => $description, 'price' => $price, 'quantity' => $quantity, 'image' => $image]);
    
    // Redirect to product management page
    header("Location: product_mm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="content">
        <div class="container">
            <h1>เพิ่มข้อมูลรายการสินค้า</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id">รหัสสินค้า:</label>
                    <input type="text" class="form-control" id="id" name="id" required >
                </div>
                <div class="form-group">
                    <label for="name">ชื่อสินค้า:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">รายละเอียด(ถ้ามี):</label>
                    <textarea class="form-control" id="description" name="description" rows="4" ></textarea>
                </div>
                <div class="form-group">
                    <label for="price">ราคา:</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <!-- <div class="form-group">
                    <label for="quantity">จำนวน:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div> -->
                <div class="form-group">
                    <label for="image">รูปภาพ(ถ้ามี):</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">เพิ่มสินค้า</button>
                <a href="product_mm.php" class="btn btn-danger">ย้อนกลับ</a>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="product_mm.js"></script>
</body>
</html>
