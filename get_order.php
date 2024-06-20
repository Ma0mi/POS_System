<?php
// ตรวจสอบว่ามีข้อมูลที่ส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูล JSON และแปลงเป็น array
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    // รับค่า orderNumber และ cartData จากข้อมูลที่ส่งมา
    $orderNumber = $data['orderNumber'];
    $cartData = $data['cartData'];

    // เชื่อมต่อกับฐานข้อมูล MySQL
    $servername = "localhost"; // เชื่อมต่อกับ MySQL บน localhost
    $username = "root"; // ชื่อผู้ใช้ของ MySQL
    $password = ""; // รหัสผ่านของ MySQL
    $dbname = "pos"; // ชื่อฐานข้อมูลที่ต้องการเชื่อมต่อ

    // สร้างการเชื่อมต่อ
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query เพื่อดึงข้อมูลรายการสั่งซื้อจากฐานข้อมูล
    $sql = "SELECT * FROM orders WHERE orderNumber = '$orderNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // หากพบข้อมูลรายการสั่งซื้อ
        $orderData = array();

        while ($row = $result->fetch_assoc()) {
            // เพิ่มข้อมูลรายการสั่งซื้อลงใน array
            $orderData[] = $row;
        }

        // ส่งข้อมูลรายการสั่งซื้อกลับเป็น JSON
        echo json_encode($orderData);
    } else {
        // หากไม่พบข้อมูลรายการสั่งซื้อ
        echo json_encode(array('error' => 'ไม่พบข้อมูลรายการสั่งซื้อ'));
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
} else {
    // หากไม่มีข้อมูลส่งมาทาง POST
    echo json_encode(array('error' => 'ไม่มีข้อมูลส่งมา'));
}
?>
