<?php
include 'navbar.php';
include 'sidebar.php';

// เชื่อมต่อฐานข้อมูล
require_once "config.php";

// กำหนดค่าเริ่มต้นให้กับตัวแปร
$name = $surname = $username = $password = $role = "";
$name_err = $surname_err = $username_err = $password_err = "";

// ถ้ามีการส่งค่าแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ตรวจสอบค่าที่รับมาจากฟอร์ม
    if (empty(trim($_POST["name"]))) {
        $name_err = "กรุณากรอกชื่อ";
    } else {
        $name = trim($_POST["name"]);
    }
    
    if (empty(trim($_POST["surname"]))) {
        $surname_err = "กรุณากรอกนามสกุล";
    } else {
        $surname = trim($_POST["surname"]);
    }
    
    if (empty(trim($_POST["username"]))) {
        $username_err = "กรุณากรอกชื่อผู้ใช้";
    } else {
        $sql = "SELECT id FROM users WHERE username = :username";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = "ชื่อผู้ใช้นี้ถูกใช้แล้ว";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }
    
    if (empty(trim($_POST["password"]))) {
        $password_err = "กรุณากรอกรหัสผ่าน";
    } else {
        $password = trim($_POST["password"]);
    }
    
    $role = $_POST["role"];
    
    // ตรวจสอบข้อมูลทั้งหมดก่อนเพิ่มลงในฐานข้อมูล
    if (empty($name_err) && empty($surname_err) && empty($username_err) && empty($password_err)) {
        // เข้ารหัสรหัสผ่านก่อนเก็บลงในฐานข้อมูล (เช่นใช้ hash function)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลผู้ใช้งานใหม่ลงในฐานข้อมูล
        $sql = "INSERT INTO users (name, surname, username, password, role) VALUES (:name, :surname, :username, :password, :role)";
        
        // ลอง execute คำสั่ง SQL
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            
            // หลังจากบันทึกข้อมูลสำเร็จ สามารถ redirect หรือแจ้งผลลัพธ์ให้กับผู้ใช้งานได้ตามความเหมาะสม
            header("Location: user_mm.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        
        // ปิดการเชื่อมต่อฐานข้อมูล
        unset($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="content">
    <div class="container mt-5">
        <h2>เพิ่มผู้ใช้งาน</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>ชื่อ</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="text-danger"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label>นามสกุล</label>
                <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
                <span class="text-danger"><?php echo $surname_err; ?></span>
            </div>
            <div class="form-group">
                <label>ชื่อผู้ใช้</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="text-danger"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>ตำแหน่ง</label>
                <select name="role" class="form-control">
                    <option value="user">ผู้ใช้ทั่วไป</option>
                    <option value="admin">แอดมิน</option>
                </select>
            </div>
            <div class="form-group">
            <input type="submit" id="addUserBtn" class="btn btn-primary" value="เพิ่มผู้ใช้">
                <a href="user_mm.php" class="btn btn-danger">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</div>    

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="user_mm.js"></script>
</body>
</html>
