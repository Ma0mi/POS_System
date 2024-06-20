<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";

// เช็คว่ามีการส่งค่า id มาหรือไม่
if (!isset($_GET['id'])) {
    exit("ไม่พบ ID สินค้า");
}

$id = $_GET['id'];
$name = $description = $price = $quantity = '';
$image = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
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
    

    // อัปเดตข้อมูลสินค้า
    $sql = "UPDATE products SET name = :name, description = :description, price = :price, quantity = :quantity";
    if ($image != '') {
        $sql .= ", image = :image";
    }
    $sql .= " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $params = ['name' => $name, 'description' => $description, 'price' => $price, 'quantity' => $quantity, 'id' => $id];
    if ($image != '') {
        $params['image'] = $image;
    }
    $stmt->execute($params);
    
    
    // Redirect to product management page
    header("Location: product_mm.php");
    exit();
} else {
    // เรียกดูข้อมูลสินค้าที่ต้องการแก้ไข
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();

    // ตรวจสอบว่าพบสินค้าหรือไม่
    if (!$product) {
        exit("ไม่พบสินค้า");
    }
    
    $name = $product['name'];
    $description = $product['description'];
    $price = $product['price'];
    $quantity = $product['quantity'];
    $image = $product['image'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="content">
        <div class="container">
            <h1>แก้ไขข้อมูลสินค้า</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">รหัสสินค้า:</label>
                    <input type="text" class="form-control" id="id" name="id" value="<?php echo $id; ?>"  readonly>
                </div>
                <div class="form-group">
                    <label for="name">ชื่อสินค้า:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">รายละเอียด(ถ้ามี):</label>
                    <textarea class="form-control" id="description" name="description" rows="4" ><?php echo $description; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">ราคา:</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantity">จำนวน:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>" >
                </div>
                <div class="form-group">
                    <label for="image">รูปภาพ:</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">แก้ไขข้อมูลสินค้า</button>
                <a href="product_mm.php" class="btn btn-danger">ย้อนกลับ</a>
            </form>
        </div>
    </div>
</body>
</html>
