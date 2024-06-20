<?php
// ตรวจสอบว่ามีการส่งข้อมูลมาจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // เชื่อมต่อกับฐานข้อมูล
    require_once "config.php";
    
    // รับค่าที่ส่งมาจากฟอร์ม
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
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
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        
        // หลังจากบันทึกข้อมูลสำเร็จ สามารถ redirect หรือแจ้งผลลัพธ์ให้กับผู้ใช้งานได้ตามความเหมาะสม
        header("Location: user_management.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
