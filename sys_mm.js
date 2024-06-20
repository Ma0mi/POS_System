function uploadImage() {
    var input = document.getElementById('imageInput');
    var file = input.files[0];

    var formData = new FormData();
    formData.append('qrcode', file);

    // ส่งคำขอ HTTP POST ไปยังเซิร์ฟเวอร์เพื่ออัปโหลดไฟล์ QR Code
    fetch('upload_qrcode.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // ตรวจสอบว่าการอัปโหลดเสร็จสมบูรณ์หรือไม่
        if (data.success) {
            // แสดง pop-up บอกว่าอัปโหลดสำเร็จ
            alert('QR Code uploaded successfully!');
            // ปรับปรุงหรือโหลดข้อมูล QR Code ใหม่ที่นี่
        } else {
            // แสดง pop-up บอกว่าอัปโหลดล้มเหลว
            alert('Failed to upload QR Code. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);

        // แสดง pop-up บอกว่ามีข้อผิดพลาด
        alert('An error occurred while uploading the QR Code.');
    });
}
