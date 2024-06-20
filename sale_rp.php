<?php
include 'navbar.php';
include 'sidebar.php';

require_once "config.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ระบบรายงานการขาย</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product_in.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="content">
  <div class="container">
    <h1 class="mt-5">ระบบรายงานการขาย</h1>
    <form method="GET" action="">
  <div class="form-group">
    <label for="start_date">เริ่มจากวัน:</label>
    <input type="date" class="form-control" id="start_date" name="start_date">
  </div>
  <div class="form-group">
    <label for="end_date">จนถึง:</label>
    <input type="date" class="form-control" id="end_date" name="end_date">
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-success">สร้างรายงานการขายสินค้า</button>
    <!-- เพิ่มปุ่มเลือก "ประจำเดือนนี้" -->
    <button type="button" class="btn btn-primary" onclick="setMonth()">ประจำเดือนนี้</button>
    <!-- เพิ่มปุ่มเลือก "วันนี้" -->
    <button type="button" class="btn btn-info" onclick="setToday()">ประจำวันนี้</button>
  </div>
</form>

    <?php
    // Check if form is submitted
    if (isset($_GET["start_date"]) && isset($_GET["end_date"])) {
        // รับค่า start_date และ end_date จากฟอร์ม
        $start_date = $_GET["start_date"];
        $end_date = $_GET["end_date"];

        // ตัดส่วนของเวลาออกเพื่อให้เหลือเฉพาะวันที่
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));

        // ตรวจสอบว่ามีการส่งค่ามาหรือไม่
        if (!empty($start_date) && !empty($end_date)) {
            // ตรวจสอบว่าค่าที่รับมามีรูปแบบที่ถูกต้องหรือไม่
            // และดำเนินการต่อไปตามที่เหมาะสม
        } else {
            // ถ้าไม่มีค่าที่ส่งมา หรือมีค่าเป็นค่าว่าง
            // ดำเนินการตามที่คุณต้องการ
            echo "กรุณาเลือกวันที่เริ่มต้นและวันที่สิ้นสุด";
        }
    
    
      
      // Connect to the database
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "pos";
      
      $conn = new mysqli($servername, $username, $password, $dbname);
      
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      
      // Prepare SQL statement to fetch sales data within the specified date range
      // Prepare SQL statement to fetch sales data within the specified date range
$sql = "SELECT 
            product_name, 
            SUM(quantity) AS total_quantity, 
            SUM(price * quantity) AS total_sales,
            AVG(price) AS average_price
        FROM 
            orders 
        WHERE 
            DATE(order_date) BETWEEN '$start_date' AND '$end_date' 
        GROUP BY 
            product_name";

// Execute SQL statement
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    // Initialize total sales variable
    $totalSales = 0;
    $totalQuantity = 0;
    $totalPrice = 0;

    // Output data in a table
    echo '<div class="mt-5">';
    echo '<h2>สรุปยอดการขายวันที่(ปี-เดือน-วัน): ' . $start_date . ' จนถึง ' . $end_date . '</h2>';
    echo '<table class="table">';
    echo '<thead class="thead-dark">';
    echo '<tr>';

    echo '<th scope="col">ชื่อสินค้า</th>';
    echo '<th scope="col">จำนวนที่ขายได้</th>';
    echo '<th scope="col">ยอดขายทั้งหมด</th>';
    
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Output each row of data
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['product_name'] . '<br>ราคาเฉลี่ยต่อหน่วย: ' . number_format($row['total_sales'] / $row['total_quantity'], 2) . '</td>';
        echo '<td>' . $row['total_quantity'] . '</td>';
        echo '<td>' . $row['total_sales'] . '</td>';
        

        echo '</tr>';

        // Add total sales of each product to the overall total sales
        $totalSales += $row['total_sales'];
        $totalQuantity += $row['total_quantity'];
        
    }

    echo '<tr>';
    echo '<td><strong>รวม</strong></td>';
    echo '</td>';
    echo '</td>';
    echo '<td><strong>' . number_format($totalQuantity,) . '</strong></td>';
    echo '<td><strong>' . number_format($totalSales, 2) . '</strong></td>';

    

    echo '</tr>';

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<div class="mt-5">';
    echo '<p>ไม่มีการขายสินค้าในช่วงเวลาดังกล่าว.</p>';
    echo '</div>';
}

      
      // Close database connection
      $conn->close();
    }
    ?>
  </div>
  </div>
  <script src="sale_rp.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
