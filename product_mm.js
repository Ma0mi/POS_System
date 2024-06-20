$(document).ready(function() {
    // การใช้ jQuery เพื่อตรวจสอบเมื่อคลิกที่ปุ่ม "เพิ่มสินค้า"
    $("button[type='submit']").on("click", function() {
        // แสดง alert ว่าเพิ่มรายการสินค้าสำเร็จ
        Swal.fire({
            icon: 'success',
            title: 'เพิ่มรายการสินค้าสำเร็จ!',
            showConfirmButton: true, // แสดงปุ่ม "ตกลง"
            confirmButtonText: 'ตกลง' // ข้อความบนปุ่ม "ตกลง"
        });
    });
});

$(document).ready(function() {
    // ใช้ jQuery เพื่อหาปุ่มที่มีคลาส delete-btn และทำการกำหนดการทำงานเมื่อคลิก
    $(".delete-btn").on("click", function(e) {
        // หยุดการทำงานของลิงก์เพื่อป้องกันการเปลี่ยนเส้นทางก่อนที่ SweetAlert2 จะแสดงขึ้น
        e.preventDefault();
        
        // เก็บลิงก์ลบที่คลิกแล้ว
        var deleteLink = $(this).attr("href");
        
        // แสดง SweetAlert2 สอบถามผู้ใช้งาน
        Swal.fire({
            title: 'ท่านแน่ใจหรือไม่?',
            text: 'หากท่านลบรายการสินค้านี้ จะไม่สามารถขายรายการสินค้านี้ได้อีก และจำนวนสินค้าที่มีจะถูกลบทั้งหมด',
            icon: 'warning',
            showCancelButton: true, // แสดงปุ่มยกเลิก
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            // ถ้าผู้ใช้งานคลิก "ใช่, ลบ!" ให้ลบรายการสินค้า
            if (result.isConfirmed) {
                window.location.href = deleteLink;
            }
        });
    });
});

$(document).ready(function() {
    // เมื่อคลิกที่ปุ่ม "เพิ่มจำนวน"
    $("#increaseQuantityBtn").on("click", function(e) {
        // หยุดการทำงานของปุ่มเพื่อป้องกันการส่งแบบฟอร์มก่อนที่ SweetAlert2 จะแสดงขึ้น
        e.preventDefault();
        
        // แสดง SweetAlert2 หลังจากกดปุ่ม
        Swal.fire({
            icon: 'success',
            title: 'เพิ่มจำนวนสำเร็จ',
            showConfirmButton: false, // ไม่แสดงปุ่ม OK เพราะจะปิดให้เองหลังจากไม่กี่วินาที
            timer: 1500 // หน่วงเวลา 1.5 วินาทีก่อนปิดอัตโนมัติ
        });
        
        // ส่งแบบฟอร์มเพื่อเพิ่มจำนวนสินค้า
        // ทำตามคำสั่งที่คุณใช้ในการส่งแบบฟอร์มหรือในลิงก์ที่คุณกำหนด
        // เช่น window.location.href = "increase_quantity.php?id=<?php echo $product['id']; ?>";
    });
});


// ฟังก์ชั่นค้นหาสินค้า
function searchProducts() {
    var input = document.getElementById('searchInput');
    var filter = input.value.toUpperCase();
    var table = document.querySelector('.table');
    var rows = table.getElementsByTagName('tr');

    // วนลูปทุกแถวในตาราง
    for (var i = 0; i < rows.length; i++) {
        var tdName = rows[i].getElementsByTagName('td')[3]; // ชื่อสินค้า
        if (tdName) {
            var productName = tdName.textContent || tdName.innerText;
            if (productName.toUpperCase().indexOf(filter) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}

// เมื่อมีการพิมพ์ในช่องค้นหา ให้เรียกฟังก์ชั่นค้นหาสินค้า
document.getElementById('searchInput').addEventListener('keyup', searchProducts);

