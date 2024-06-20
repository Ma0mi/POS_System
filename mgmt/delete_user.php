<?php
// เชื่อมต่อกับไฟล์ config.php
require_once "config.php";

// ตรวจสอบว่ามีการส่งค่า ID ผู้ใช้งานมาหรือไม่
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // เตรียมคำสั่ง SQL สำหรับลบผู้ใช้งาน
    $sql = "DELETE FROM users WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind ค่า parameter
        $stmt->bindParam(":id", $param_id);
        
        // Set ค่า parameter
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // หลังจากที่ลบข้อมูลสำเร็จ ให้ redirect กลับไปยังหน้า user_mm.php
            header("location: user_mm.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    unset($stmt);
}
 
// Close connection
unset($pdo);
?>
