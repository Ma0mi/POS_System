<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get selected product ID and quantity to add
    $product_id = $_POST['product_id'];
    $quantity_to_add = $_POST['quantity_to_add'];

    // Fetch product details from products table
    $stmt = $pdo->prepare("SELECT name, quantity FROM products WHERE id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Update quantity in products table
        $new_quantity = $product['quantity'] + $quantity_to_add;
        $stmt = $pdo->prepare("UPDATE products SET quantity = :quantity WHERE id = :id");
        $stmt->execute(['quantity' => $new_quantity, 'id' => $product_id]);
    
        // Generate report number
        $report_id = 'RP_' . time();
    
        // Fetch product price from database
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :id");
        $stmt->execute(['id' => $product_id]);
        $product_price = $stmt->fetchColumn();
    
        // Insert data into products_in table
        $stmt = $pdo->prepare("INSERT INTO products_in (name, quantity, total_price, report_id) VALUES (:name, :quantity, :total_price, :report_id)");
        $stmt->execute(['name' => $product['name'], 'quantity' => $quantity_to_add, 'total_price' => $quantity_to_add * $product_price, 'report_id' => $report_id]);
    
        // Redirect to success page or display success message
        // header("Location: success.php");
        // exit();
        echo "Product quantity added successfully.";
        // Redirect to product_mm.php after successfully adding product quantity
    header("Location: product_mm.php");
    exit();

    } else {
        echo "Product not found.";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบการเพิ่มจำนวนสินค้า</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="content">
    <div class="container">
        <h2>ระบบการเพิ่มจำนวนสินค้า</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="product_id">เลือกรายการสินค้า:</label>
                <select class="form-control" id="product_id" name="product_id">
                    <?php
                    // Fetch products from database
                    $stmt = $pdo->query("SELECT id, name FROM products");
                    while ($row = $stmt->fetch()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity_to_add">จำนวนที่ต้องการเพิ่ม:</label>
                <input type="number" class="form-control" id="quantity_to_add" name="quantity_to_add" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">เพิ่มจำนวน</button>
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
