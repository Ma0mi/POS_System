$(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        var username = $('#username').val();
        var password = $('#password').val();
        
        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                if (response.trim() === 'success') {
                    // เข้าสู่ระบบสำเร็จ ให้แสดง SweetAlert2 และ redirect ไปยังหน้า dashboard.php
                    Swal.fire({
                        icon: 'success',
                        title: 'เข้าสู่ระบบสำเร็จ',
                        text: 'ยินดีต้อนรับเข้าสู่ระบบ!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location = 'dashboard.php';
                    });
                } else {
                    // ไม่สามารถเข้าสู่ระบบได้ ให้แสดง SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'เข้าสู่ระบบไม่สำเร็จ',
                        text: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!'
                    });
                }
            }
        });
    });
});
