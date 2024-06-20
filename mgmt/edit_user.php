<?php
include 'navbar.php';
include 'sidebar.php';

require_once('config.php');

// ตรวจสอบว่ามีการล็อกอินเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูล ID ผู้ใช้งานมาหรือไม่
if (!isset($_GET['id'])) {
    header('Location: user_mm.php');
    exit();
}

$user_id = $_GET['id'];

// ตรวจสอบว่ามีการส่งข้อมูลฟอร์มแก้ไขมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าที่แก้ไขจากฟอร์ม
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $role = $_POST['role'];

    // อัปเดตข้อมูลผู้ใช้งานในฐานข้อมูล
    $sql = "UPDATE users SET name = :name, surname = :surname, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'surname' => $surname, 'role' => $role, 'id' => $user_id]);

    // Redirect กลับไปที่หน้า user_mm.php
    header('Location: user_mm.php');
    exit();
}

// ดึงข้อมูลผู้ใช้งานจากฐานข้อมูล
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

// ตรวจสอบว่าพบข้อมูลผู้ใช้งานหรือไม่
if (!$user) {
    header('Location: user_mm.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="content">
<div class="container mt-5">
    <h2>แก้ไขผู้ใช้งาน</h2>
    <form method="post">
        <div class="form-group">
            <label for="name">ชื่อ</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
        </div>
        <div class="form-group">
            <label for="surname">นามสกุล</label>
            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $user['surname']; ?>">
        </div>
        <div class="form-group">
            <label for="role">ตำแหน่ง</label>
            <select class="form-control" id="role" name="role">
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">แก้ไข</button>
        <a href="user_mm.php" class="btn btn-danger">ย้อนกลับ</a>
    </form>
    

</div>
</div>
</body>
</html>
