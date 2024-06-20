// payment.js

document.addEventListener('DOMContentLoaded', function() {
    // โหลดข้อมูลเลขที่รายการสั่งซื้อและรายการสินค้าที่เลือกเมื่อหน้าเว็บโหลดเสร็จ
    loadOrderDetails();
    loadSelectedProducts();

    // เพิ่มการดักเหตุการณ์การคลิกปุ่มยืนยันการชำระเงิน
    document.getElementById('confirmPaymentBtn').addEventListener('click', function() {
        confirmPayment();
    });
});

function loadOrderDetails() {
    // โหลดข้อมูลเลขที่รายการสั่งซื้อจากฐานข้อมูล
    // แล้วแสดงผลลัพธ์ในส่วน HTML
    // ตัวอย่างเช่น
    var orderNumber = "ORD123456";
    document.getElementById('orderNumber').innerText = orderNumber;
}

function loadSelectedProducts() {
    // โหลดรายการสินค้าที่เลือกจากฐานข้อมูล
    // แล้วแสดงผลลัพธ์ในส่วน HTML
    // ตัวอย่างเช่น
    var selectedProducts = [
        { id: 1, name: 'Product 1', price: 100 },
        { id: 2, name: 'Product 2', price: 150 },
        // เพิ่มรายการสินค้าอื่นๆที่เลือกได้ตามต้องการ
    ];

    var selectedProductsDiv = document.getElementById('selectedProducts');
    selectedProductsDiv.innerHTML = '<h2>รายการสินค้าที่เลือก</h2>';
    selectedProducts.forEach(function(product) {
        selectedProductsDiv.innerHTML += '<p>' + product.name + ' - ราคา: ' + product.price + '</p>';
    });
}

function confirmPayment() {
    // ทำการยืนยันการชำระเงิน โดยบันทึกข้อมูลการชำระเงินลงในฐานข้อมูล
    // และทำการประมวลผลอื่นๆตามต้องการ เช่น การส่งอีเมลยืนยันการสั่งซื้อ
    // ตัวอย่างเช่น
    alert('การชำระเงินสำเร็จ!');
}
