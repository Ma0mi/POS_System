$(document).ready(function() {
    // การใช้ jQuery เพื่อตรวจสอบเมื่อคลิกที่ปุ่ม "เพิ่มผู้ใช้"
    $("#addUserBtn").on("click", function() {
        // แสดง alert ให้ผู้ใช้ทราบว่าเพิ่มผู้ใช้งานสำเร็จ
        Swal.fire({
            icon: 'success',
            title: 'เพิ่มผู้ใช้งานสำเร็จ!',
            showConfirmButton: true, // แสดงปุ่ม "ตกลง"
            confirmButtonText: 'ตกลง' // ข้อความบนปุ่ม "ตกลง"
        });
    });
});


$(document).ready(function() {
    // การใช้ jQuery เพื่อตรวจสอบเมื่อคลิกที่ลิงก์ลบผู้ใช้งาน
    $(".delete-user").on("click", function(e) {
        e.preventDefault();
        var userId = $(this).data("id");
        // แสดงข้อความยืนยันก่อนลบผู้ใช้งาน
        Swal.fire({
            title: "ท่านแน่ใจหรือไม่?",
            text: "หากท่านลบผู้ใช้นี้ ข้อมูลจะถูกลบและไม่สามารถเข้าสู่ระบบได้ถาวร",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "ใช่, ลบผู้ใช้งาน!",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                // ส่งคำขอลบผู้ใช้งานไปยังหน้า delete_user.php
                window.location.href = "delete_user.php?id=" + userId;
            }
        });
    });
});