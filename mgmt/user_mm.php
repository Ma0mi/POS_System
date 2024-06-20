<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบการจัดการข้อมูลผู้ใช้งาน</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="content">
    <div class="container">
        <h1>ระบบการจัดการข้อมูลผู้ใช้งาน</h1>
                <!-- แสดงตารางรายชื่อผู้ใช้งาน -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>Username</th>
                    <th>ตำแหน่ง</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                     
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['surname']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <!-- ปุ่มแก้ไข -->
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">แก้ไข</a>
                        <!-- ปุ่มลบ -->
                        <a href="#" class="btn btn-danger btn-sm delete-user" data-id="<?php echo $user['id']; ?>">ลบ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- ปุ่มเพิ่มผู้ใช้ -->
        <a href="add_user.php" class="btn btn-success">เพิ่มผู้ใช้</a>
        <!-- <form action="process_user.php" method="post">
            <div class="form-group">
                <label for="name">ชื่อ:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="surname">นามสกุล:</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">ตำแหน่ง:</label>
                <select class="form-control" id="role" name="role">
                    <option value="user">ผู้ใช้ทั่วไป</option>
                    <option value="admin">แอดมิน</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">เพิ่มผู้ใช้</button>
        </form> -->
    </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="user_mm.js"></script>
</body>
</html>
