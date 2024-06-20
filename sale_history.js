function setMonth() {
    var today = new Date();
    var month = today.getMonth() + 1;
    var year = today.getFullYear();
    var startDate = year + '-' + month + '-01';
    var endDate = year + '-' + month + '-' + new Date(year, month, 0).getDate();

    document.getElementById('start_date').value = startDate;
    document.getElementById('end_date').value = endDate;
}

function setToday() {
    var today = new Date();
    var date = today.getDate();
    var month = today.getMonth() + 1;
    var year = today.getFullYear();
    var todayDate = year + '-' + month + '-' + date;

    document.getElementById('start_date').value = todayDate;
    document.getElementById('end_date').value = todayDate;
}

function applyFilter() {
    var startDate = document.getElementById('start_date').value;
    var endDate = document.getElementById('end_date').value;

    // สร้าง URL สำหรับการส่งข้อมูลให้กับหน้า PHP
    var url = 'sale_history.php?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);

    // โหลดหน้า PHP ด้วยการส่งข้อมูลการกรองเวลาผ่าน URL
    window.location.href = url;
}

document.addEventListener("DOMContentLoaded", function() {
    // ตั้งค่า input type="date" ให้เป็นวันที่ปัจจุบัน
    var today = new Date().toISOString().split('T')[0];
    var startDateInput = document.getElementById('start_date');
    var endDateInput = document.getElementById('end_date');

    // ตรวจสอบว่ามีการเลือกวันที่หรือไม่
    if (!startDateInput.value) {
        startDateInput.value = today;
    }
    if (!endDateInput.value) {
        endDateInput.value = today;
    }

    // เมื่อผู้ใช้เลือกวันที่
    startDateInput.addEventListener('change', function() {
        applyFilter();
    });

    endDateInput.addEventListener('change', function() {
        applyFilter();
    });
});

function applyFilter() {
    // ส่งฟอร์มเพื่อกรองข้อมูล
    document.getElementById('filterForm').submit();
}