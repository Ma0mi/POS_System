<?php
// Include configuration file
require_once "config.php";

// Check if form is submitted for adding a product or updating an existing product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // Process adding product

        // Validate and sanitize input fields
        $name = htmlspecialchars($_POST["name"]);
        $description = htmlspecialchars($_POST["description"]);
        $price = floatval($_POST["price"]);
        $quantity = intval($_POST["quantity"]);

        // Check if image file is uploaded
        if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
            // Upload image file
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $image = $target_file;
        } else {
            $image = ""; // Default value if no image is uploaded
        }

        // Insert product data into database
        $sql = "INSERT INTO products (name, description, price, quantity, image) VALUES (:name, :description, :price, :quantity, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'quantity' => $quantity, 'image' => $image]);

        // Redirect back to product management page
        header("location: product_mm.php");
        exit();
    } elseif (isset($_POST['update'])) {
        // Process updating product

        // Validate and sanitize input fields
        $id = $_POST["id"];
        $name = htmlspecialchars($_POST["name"]);
        $description = htmlspecialchars($_POST["description"]);
        $price = floatval($_POST["price"]);
        $quantity = intval($_POST["quantity"]);

        // Check if image file is uploaded
        if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
            // Upload image file
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $image = $target_file;
        } else {
            $image = $_POST["old_image"]; // Keep the old image if no new image is uploaded
        }

        // Update product data in database
        $sql = "UPDATE products SET name=:name, description=:description, price=:price, quantity=:quantity, image=:image WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'quantity' => $quantity, 'image' => $image, 'id' => $id]);

        // Redirect back to product management page
        header("location: product_mm.php");
        exit();
    }
}
?>
