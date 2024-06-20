// Function to display products
function displayProducts(products) {
    const productList = document.getElementById('productList');
    productList.innerHTML = '';
    products.forEach(product => {
        const card = `
            <div class="col-md-4">
                <div class="card">
                    <img src="product-placeholder.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="card-text">Price: $${product.price.toFixed(2)}</p>
                        <button class="btn btn-primary btn-sm addToCart" data-id="${product.id}">Add to Cart</button>
                    </div>
                </div>
            </div>
        `; 
        productList.innerHTML += card;
    });
}


 
var totalPrice = 0; // เพิ่มตัวแปร totalPrice เพื่อเก็บรวมราคาทั้งหมดของสินค้า

// Function to add product to shopping cart with quantity limit
function addToCart(name, price, maxQuantity) {
    var cartItems = document.querySelector('.cart-items');

    // Check if the product already exists in the cart
    var existingProduct = Array.from(cartItems.children).find(function(item) {
        return item.cells[0].textContent === name;
    });

    // Check if the quantity limit has been reached or if the product is out of stock
    if (existingProduct) {
        var quantityInput = existingProduct.querySelector('.quantity-input');
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity >= maxQuantity) {
            Swal.fire({
                icon: 'error',
                title: 'เกินจำนวนสินค้าที่มีอยู่!',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }
        if (currentQuantity === 0) {
            Swal.fire({
                icon: 'error',
                title: 'สินค้าหมด!',
                text: 'กรุณาดำเนินการเติมจำนวนสินค้า!',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }
    } else {
        if (maxQuantity === 0) {
            Swal.fire({
                icon: 'error',
                title: 'สินค้าหมด!',
                text: 'กรุณาดำเนินการเติมจำนวนสินค้า!',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }
    }

    if (existingProduct) {
        // If product exists, increase quantity
        var quantityInput = existingProduct.querySelector('.quantity-input');
        var newQuantity = parseInt(quantityInput.value) + 1;
        quantityInput.value = newQuantity;
        editQuantity(existingProduct, quantityInput);
    } else {
        // If product does not exist, add new row to cart
        var newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${name}</td>
            <td>${price.toFixed(2)}</td>
            <td><input type="number" class="form-control quantity-input" value="1" min="1" max="${maxQuantity}" onchange="editQuantity(this.parentNode.parentNode, this)" data-quantity="1"></td>
            <td>${price.toFixed(2)}</td>
            <td><button class="btn btn-danger" onclick="removeItem(this.parentNode.parentNode)">ลบ</button></td>
        `;
        cartItems.appendChild(newRow);
    }
    updateCartTotal(); // Add this line to update the cart total
}


// Function to handle removing item from the cart
function removeItem(row) {
    var price = parseFloat(row.cells[1].textContent); // Get the price of the item being removed
    var quantity = parseInt(row.querySelector('.quantity-input').value); // Get the quantity of the item being removed
    var total = parseFloat(row.cells[3].textContent); // Get the total price of the item being removed
    totalPrice -= total; // Subtract the total price of the item being removed from the total price
    updateTotalPriceInHTML(); // Update the total price in the HTML
    row.parentNode.removeChild(row); // Remove the row from the cart
}

function editQuantity(row, input) {
    var oldQuantity = parseInt(row.querySelector('.quantity-input').getAttribute('data-quantity')); // Get the previous quantity
    var newQuantity = parseInt(input.value); // Get the new quantity
    var maxQuantity = parseInt(input.getAttribute('max')); // Get the maximum quantity allowed

// Check if the new quantity exceeds the maximum quantity
if (newQuantity > maxQuantity) {
    Swal.fire({
        icon: 'error',
        title: 'เกินจำนวนสินค้าที่มีอยู่!',
        text: 'กรุณาปรับจำนวนสินค้าใหม่',
    });
    // Set the quantity to the maximum quantity available
    input.value = maxQuantity;
    newQuantity = maxQuantity; // Update newQuantity to the maximum quantity
}


    var price = parseFloat(row.cells[1].textContent); // Get the price of the item
    var totalPriceElement = document.getElementById('totalPrice'); // Get the total price element
    var totalPrice = parseFloat(totalPriceElement.innerText); // Get the current total price

    var totalDifference = (newQuantity - oldQuantity) * price; // Calculate the difference in total price

    totalPrice += totalDifference; // Add the difference to the total price

    totalPriceElement.innerText = totalPrice.toFixed(2) + ' บาท'; // Update the total price in the HTML

    row.querySelector('.quantity-input').setAttribute('data-quantity', newQuantity); // Update the data-quantity attribute

    var totalCell = row.cells[3]; // Get the total price cell
    totalCell.textContent = (price * newQuantity).toFixed(2); // Update the total price for this row

    updateTotalPriceInHTML(); // Update the total price in the HTML
}



// Function to update total price in HTML
function updateTotalPriceInHTML() {
    var totalPriceElement = document.getElementById('totalPrice');
    if (totalPriceElement) { // Check if element exists
        totalPriceElement.innerText = totalPrice.toFixed(2) + ' บาท'; // Update total price in HTML
    }
}

// Function to update cart total
function updateCartTotal() {
    var cartItemContainer = document.getElementsByClassName('cart-items')[0];
    var cartRows = cartItemContainer.getElementsByTagName('tr');
    var total = 0;
    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i];
        var priceElement = cartRow.getElementsByTagName('td')[1];
        var quantityElement = cartRow.querySelector('.quantity-input');
        var price = parseFloat(priceElement.innerText);
        var quantity = parseInt(quantityElement.value);
        total += price * quantity;
    }
    totalPrice = total; // Set totalPrice variable
    var totalPriceElement = document.querySelector('.cart-total');
    if (totalPriceElement) { // Check if element exists
        totalPriceElement.innerText = total.toFixed(2) + ' บาท'; // Update total price in HTML
    }
    updateTotalPriceInHTML(); // Call this function to update the total price in HTML
}


// Event delegation for adding product to cart
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('addToCart')) {
        const productId = e.target.getAttribute('data-id');
        const product = products.find(product => product.id == productId);
        addToCart(product.name, product.price, 10); // Assuming maxQuantity is 10 for all products
    }
});

// เพิ่ม event listener สำหรับปุ่ม "ตรวจสอบรายการสินค้า"
document.querySelector('.btn-check').addEventListener('click', function() {
    // ตรวจสอบว่าตะกร้าสินค้าว่างหรือไม่
    var cartItemContainer = document.querySelector('.cart-items');
    var cartRows = cartItemContainer.getElementsByTagName('tr');
    
    if (cartRows.length === 0) {
        // ถ้าตะกร้าว่างให้แสดง SweetAlert2 ให้ผู้ใช้เลือกสินค้า
        Swal.fire({
            icon: 'warning',
            title: 'ท่านยังไม่ได้เลือกรายการสินค้า!',
            text: 'กรุณาเลือกรายการสินค้าก่อนดำเนินการสั่งซื้อ',
            confirmButtonText: 'ตกลง',
            timer: 3000
        });
    } else {
        showCartSummary();
    }
});


function showCartSummary() {
    // รวบรวมข้อมูลรายการสินค้าจากตะกร้า
    var cartItemContainer = document.querySelector('.cart-items');
    var cartRows = cartItemContainer.getElementsByTagName('tr');
    var totalPrice = 0;
    var summary = '<h2>รายการสินค้าในตะกร้า</h2><ul>';

    // วนลูปเพื่อเก็บข้อมูลรายการสินค้า
    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i];
        var itemName = cartRow.getElementsByTagName('td')[0].innerText;
        var itemPrice = parseFloat(cartRow.getElementsByTagName('td')[1].innerText);
        var itemQuantity = parseInt(cartRow.getElementsByTagName('input')[0].value);
        summary += '<li>' + itemName + ' - ' + itemPrice + ' บาท x ' + itemQuantity + ' ชิ้น</li>';
        totalPrice += itemPrice * itemQuantity;
    }

    summary += '</ul>';

    // คำนวณราคารวม
    summary += '<p>รวมราคา: ' + totalPrice.toFixed(2) + ' บาท</p>';

    // เพิ่มปุ่มเลือกวิธีการชำระเงิน
    summary += '<button class="btn btn-success" onclick="choosePayment()">เลือกวิธีการชำระเงิน</button>';

    // แสดง Popup ด้วย SweetAlert2
    Swal.fire({
        title: 'รายการสินค้าในตะกร้า',
        html: summary,
        icon: 'info',
        confirmButtonText: 'ตกลง'
    });
}




// ฟังก์ชันเมื่อเลือกชำระเงิน
function choosePayment() {
    Swal.fire({
        title: 'เลือกวิธีการชำระเงิน',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'ชำระเงินสด',
        cancelButtonText: 'ชำระสแกนธนาคาร',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // เลือกชำระเงินสด
            showCashPaymentPopup();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // เลือกแสกนจ่าย
            showScanPaymentPopup();
        }
    });
}


// ฟังก์ชันสร้าง Popup สำหรับการชำระเงินสด
function showCashPaymentPopup() {
    // รวบรวมข้อมูลรายการสินค้าจากตะกร้า
    var cartItemContainer = $('.cart-items');
    var cartRows = cartItemContainer.find('tr');

    var orderNumber = generateOrderNumber(); // สร้างเลขที่รายการสั่งซื้อ

    // คำนวณราคารวมทั้งหมดของสินค้า
    var totalPrice = 0;
    cartRows.each(function(index, cartRow) {
        var itemPrice = parseFloat($(cartRow).find('td').eq(1).text());
        var itemQuantity = parseInt($(cartRow).find('input').eq(0).val());
        totalPrice += itemPrice * itemQuantity;
    });

    // สร้าง input field สำหรับให้ผู้ใช้กรอกจำนวนเงินที่รับ
    Swal.fire({
        title: 'ข้อมูลการชำระเงินสด',
        html:
            '<p>เลขที่รายการสั่งซื้อ: ' + orderNumber + '</p>' +
            '<ul id="cartSummary"></ul>' +
            '<p>ราคารวมทั้งหมด: <span id="totalPrice">' + totalPrice.toFixed(2) + '</span> บาท</p>' +
            '<div class="form-group">' +
                '<label for="cashInput">จำนวนเงินที่รับ (บาท):</label>' +
                '<input type="number" class="form-control" id="cashInput">' +
            '</div>',
        showCancelButton: true,
        confirmButtonText: 'ยืนยันการชำระเงิน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // เมื่อผู้ใช้กดยืนยัน
            var cashInput = document.getElementById('cashInput').value;
            ConfirmCashPayment(orderNumber, totalPrice, cashInput);
        }
    });

    var cartSummaryList = document.getElementById('cartSummary');

    // วนลูปเพื่อเก็บข้อมูลรายการสินค้า
    cartRows.each(function(index, cartRow) {
        var itemName = $(cartRow).find('td').eq(0).text();
        var itemPrice = parseFloat($(cartRow).find('td').eq(1).text());
        var itemQuantity = parseInt($(cartRow).find('input').eq(0).val());

        var listItem = document.createElement('li');
        listItem.textContent = itemName + ' - ' + itemPrice + ' บาท x ' + itemQuantity + ' ชิ้น';
        cartSummaryList.appendChild(listItem);
    });
}


// ฟังก์ชันสร้าง Popup สำหรับการสแกน
function showScanPaymentPopup() {
    // รวบรวมข้อมูลรายการสินค้าจากตะกร้า
    var cartItemContainer = $('.cart-items');
    var cartRows = cartItemContainer.find('tr');

    var totalPrice = 0; // เพิ่มตัวแปร totalPrice เพื่อเก็บราคารวมทั้งหมดของสินค้า

    var orderNumber = generateOrderNumber(); // สร้างเลขที่รายการสั่งซื้อ

    var summary = '<div class="popup">';
    summary += '<h2>ข้อมูลการชำระสแกนธนาคาร</h2>';
    summary += '<p>เลขที่รายการสั่งซื้อ: ' + orderNumber + '</p>'; // แสดงเลขที่รายการสั่งซื้อที่สร้างขึ้น
    summary += '<ul>';

    // วนลูปเพื่อเก็บข้อมูลรายการสินค้า
    cartRows.each(function(index, cartRow) {
        var itemName = $(cartRow).find('td').eq(0).text();
        var itemPrice = parseFloat($(cartRow).find('td').eq(1).text());
        var itemQuantity = parseInt($(cartRow).find('input').eq(0).val());
        summary += '<li>' + itemName + ' - ' + itemPrice + ' บาท x ' + itemQuantity + ' ชิ้น</li>';
        totalPrice += itemPrice * itemQuantity; // รวมราคาสินค้าทั้งหมด
    });

    summary += '</ul>';

    // เพิ่มข้อมูลราคารวมทั้งหมดใน Popup
    summary += '<p>ราคารวมทั้งหมด: ' + totalPrice.toFixed(2) + ' บาท</p>'; 

    // เพิ่มข้อความแสดงวิธีการชำระเงินและ QR Code
    summary += '<h2>กรุณาสแกน QR Code เพื่อชำระรายการ เป็นจำนวนเงิน ' + totalPrice.toFixed(2) + ' บาท</h2>';
    summary += `<h2><img src="qrcodes/${lastestQrcode}" alt="Latest QR Code" width="300" height="300"></h2>`;

    // เพิ่มปุ่มยืนยันการชำระเงิน
    summary += '<button class="btn btn-success" onclick="ConfirmScanPayment(\'' + orderNumber + '\', ' + totalPrice.toFixed(2) + ')">ยืนยันการชำระเงิน</button>';

    // ปิด Popup
    summary += '<span class="close-popup" onclick="closePopup()">&times;</span>';

    summary += '</div>';

    // แสดง Popup ด้วย SweetAlert2
    Swal.fire({
        html: summary,
        showConfirmButton: false
    });
}






// ฟังก์ชันสร้างเลขที่รายการสั่งซื้อ
function generateOrderNumber() {
    var date = new Date();
    return 'ORD-' + date.getTime(); // เลขที่รายการสั่งซื้อจะเป็น ORD- ตามด้วย timestamp
}



// ฟังก์ชันสำหรับยืนยันการชำระเงินและลดจำนวนสินค้าในตาราง products
function confirmPaymentAndReduceProductQuantity() {
    // ส่งคำร้องข้อมูลไปยังเซิร์ฟเวอร์
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "confirm_payment.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // เมื่อข้อมูลถูกส่งและประมวลผลเสร็จสิ้น
            // รีเฟรชหน้าหรือดำเนินการเพิ่มเติมตามที่ต้องการ
            location.reload(); // รีเฟรชหน้า
        }
    };

    // รวบรวมข้อมูลที่ต้องการส่งไปยังเซิร์ฟเวอร์ในรูปแบบ JSON
    var data = JSON.stringify(/* ข้อมูลที่ต้องการส่ง */);

    // ส่งคำร้องข้อมูล
    xhr.send(data);
}


function ConfirmCashPayment(orderNumber, totalPrice, cashReceived) {
// Ensure cashReceived is a number
cashReceived = parseFloat(cashReceived);

// Check if cashReceived is a valid number
if (isNaN(cashReceived)) {
    Swal.fire({
        icon: 'error',
        title: 'การทำรายการล้มเหลว!',
        text: 'กรุณากรอกตัวเลข',
    });
    return;
}

if (isNaN(cashReceived) || cashReceived < totalPrice) {
    Swal.fire({
        icon: 'error',
        title: 'การทำรายการล้มเหลว!',
        text: 'จำนวนเงินไม่เพียงพอ กรุณากรอกจำนวนใหม่',
    });
    return;
}
    // Calculate change
    var change = cashReceived - totalPrice;

    // Collect cart data
    var cartItemContainer = document.querySelector('.cart-items');
    var cartRows = cartItemContainer.getElementsByTagName('tr');
    var cartData = [];

    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i];
        var itemName = cartRow.getElementsByTagName('td')[0].innerText;
        var itemPrice = parseFloat(cartRow.getElementsByTagName('td')[1].innerText);
        var itemQuantity = parseInt(cartRow.getElementsByTagName('input')[0].value);
        cartData.push({ name: itemName, price: itemPrice, quantity: itemQuantity });
    }

    // Send payment data and cart data to server for processing
    fetch('save_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            orderNumber: orderNumber, 
            cartData: cartData,
            paymentMethod: 'เงินสด', // Payment method is cash
            changeAmount: change.toFixed(2), // Change amount
            receivedAmount: cashReceived.toFixed(2) // Received amount
        })
    })
    .then(response => {
        if (response.ok) {
            // Order saved successfully, show option to print receipt
            Swal.fire({
                icon: 'success',
                title: 'ชำระเงินสำเร็จ',
                html:
                    '<p>เลขที่รายการสั่งซื้อ: ' + orderNumber + '</p>' +
                    '<p>จำนวนเงินที่รับ: ' + cashReceived.toFixed(2) + ' บาท</p>' +
                    '<p>ราคารวมทั้งหมด: ' + totalPrice.toFixed(2) + ' บาท</p>' +
                    '<p>เงินทอน: ' + change.toFixed(2) + ' บาท</p>',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show option to print receipt
                    showPrintReceiptOption(orderNumber);
                }
            });
        } else {
            // Failed to save order, show error message
            alert('เกิดข้อผิดพลาดในการบันทึกรายการสั่งซื้อ');
        }
    })
    .catch(error => {
        console.error('เกิดข้อผิดพลาด:', error);
        alert('เกิดข้อผิดพลาดในการบันทึกรายการสั่งซื้อ');
    });
}


// Function to confirm scan payment
function ConfirmScanPayment(orderNumber, totalPrice) {
    // Display payment confirmation using SweetAlert2
    Swal.fire({
        icon: 'success',
        title: 'ชำระเงินสำเร็จ',
        html:
            '<p>เลขที่รายการสั่งซื้อ: ' + orderNumber + '</p>' +
            '<p>ราคารวมทั้งหมด: ' + totalPrice.toFixed(2) + ' บาท</p>',
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show option to print receipt
            showPrintReceiptOption(orderNumber);
        }
    });

    // Collect cart data
    var cartItemContainer = document.querySelector('.cart-items');
    var cartRows = cartItemContainer.getElementsByTagName('tr');
    var cartData = [];

    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i];
        var itemName = cartRow.getElementsByTagName('td')[0].innerText;
        var itemPrice = parseFloat(cartRow.getElementsByTagName('td')[1].innerText);
        var itemQuantity = parseInt(cartRow.getElementsByTagName('input')[0].value);
        cartData.push({ name: itemName, price: itemPrice, quantity: itemQuantity });
    }

    // Send cart data and payment information to server for processing
    fetch('save_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            orderNumber: orderNumber, 
            cartData: cartData,
            paymentMethod: 'สแกนธนาคาร', // Payment method is scan
            receivedAmount: totalPrice.toFixed(2) // Set cashReceived to totalPrice
        })
    })
    .then(response => {
        if (response.ok) {
            // Order saved successfully
        } else {
            // Failed to save order, show error message
            alert('Failed to save order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save order');
    });
}





// Function to show option to print receipt using SweetAlert2
function showPrintReceiptOption(orderNumber) {
Swal.fire({
    title: 'การชำระเงินเสร็จสมบูรณ์',
    text: 'ท่านต้องการพิมพ์ใบเสร็จหรือไม่?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'พิมพ์ใบเสร็จ',
    cancelButtonText: 'ไม่ต้องการใบเสร็จ'
}).then((result) => {
    if (result.isConfirmed) {
        printReceipt(orderNumber);
    }
    // Reload the page regardless of the choice
    location.reload();
});
}

// Function to print receipt
function printReceipt(orderNumber) {
// Redirect to receipt_gen.php
window.open('receipt_gen.php?order_number=' + orderNumber, '_blank');
}



document.querySelector('.btn-change').addEventListener('click', function() {
    // ส่วนของการปรับราคาสินค้าจะให้คุณเขียนโค้ดเพิ่มเองตามต้องการ

    // เมื่อมีการปรับราคาสินค้าเสร็จสิ้น ให้เรียกฟังก์ชัน updateCartTotal() เพื่ออัปเดตราคารวมในตะกร้า
    updateCartTotal();
});

// Function to adjust product price
function adjustPrice() {
    // Assuming you have a form to input new prices
    var newPriceInput = document.getElementById('newPrice');
    var newPrice = parseFloat(newPriceInput.value);

    // Assuming you have validation for the new price
    if (isNaN(newPrice) || newPrice <= 0) {
        alert("โปรดป้อนราคาสินค้าใหม่ที่ถูกต้อง");
        return;
    }

    // Your code to adjust product price goes here
    // For example, you might want to update the price displayed on the page
    var productPriceElement = document.getElementById('productPrice');
    productPriceElement.innerText = newPrice.toFixed(2); // Assuming you want to display the price with two decimal places
}

// Function to check cart items
function checkCartItems() {
    // Assuming you have cart items displayed in a table
    var cartItems = document.getElementsByClassName('cart-item');
    var errorMessage = '';

    // Assuming each cart item has an input field for quantity and a data attribute for available quantity
    for (var i = 0; i < cartItems.length; i++) {
        var quantityInput = cartItems[i].getElementsByClassName('cart-quantity-input')[0];
        var productName = cartItems[i].getElementsByClassName('cart-item-title')[0].innerText;
        var availableQuantity = parseInt(quantityInput.getAttribute('data-available-quantity'));
        var selectedQuantity = parseInt(quantityInput.value);

        if (isNaN(selectedQuantity) || selectedQuantity <= 0 || selectedQuantity > availableQuantity) {
            errorMessage += "จำนวนสินค้าไม่เพียงพอในรายการ: " + productName + "\n";
        }
    }

    if (errorMessage !== '') {
        alert(errorMessage);
    } else {
        // Proceed with checking cart items
        // Your code to process cart items when quantities are valid goes here
    }
    
}

// จำนวนรายการสินค้าต่อหน้า
var currentPage = 0;
var productsPerPage = 4; // จำนวนสินค้าต่อหน้า

// ฟังก์ชันแสดงผลสินค้า
function updatePage() {
    var filter = document.getElementById('searchInput').value.toUpperCase(); // ค่าที่ใช้ในการค้นหา
    var productsContainer = document.getElementById('products-container');
    productsContainer.innerHTML = ''; // ล้างข้อมูลเดิมทั้งหมด

    var row = document.createElement('div');
    row.classList.add('row');

    // ตัวแปรที่ใช้ในการนับจำนวนรายการสินค้าที่แสดงผล
    var displayedProducts = 0;

    // แสดงผลสินค้าที่ตรงกับการค้นหา
    for (var i = 0; i < products.length; i++) {
        var product = products[i];
        var productName = product.name.toUpperCase();

        // ตรวจสอบว่าชื่อสินค้าตรงกับการค้นหาหรือไม่
        if (productName.indexOf(filter) > -1) {
            // ตรวจสอบว่าเป็นสินค้าในหน้าที่ถูกกำหนดหรือไม่
            if (displayedProducts >= currentPage * productsPerPage && displayedProducts < (currentPage + 1) * productsPerPage) {
                var productCard = `
                    <div class="col-md-6 mb-4">
                        <div class="card">
                        <img src="product/${product.image}" class="card-img-top image-resize" alt="${product.name}" style="width: 150px; height: 150px; display: block; margin: auto;">
                        <div class="card-body">

                            <div class="card-body">
                                <h5 class="card-title">รหัสสินค้า: ${product.id}</h5>
                                <h5 class="card-title">ชื่อ: ${product.name}</h5>
                                <p class="card-text">รายละเอียด: ${product.description}</p>
                                <p class="card-text">จำนวน: ${product.quantity}</p>
                                <p class="card-text">ราคา: ${product.price} บาท</p>
                                <button class="btn btn-primary" onclick="addToCart('${product.name}', ${product.price}, ${product.quantity})" data-quantity="${product.quantity}">เพิ่มลงในตะกร้า</button>
                            </div>
                        </div>
                    </div>
                `;
                row.insertAdjacentHTML('beforeend', productCard);
            }

            // เพิ่มจำนวนรายการสินค้าที่แสดงผล
            displayedProducts++;
        }
    }

    // Add the row to the products container
    productsContainer.appendChild(row);

    // อัปเดตปุ่มเลื่อนหน้า
    updatePaginationButtons();
}

// ฟังก์ชันเลื่อนหน้าไปหน้าก่อนหน้า
function previousPage() {
    if (currentPage > 0) {
        currentPage--;
        updatePage();
    }
}

// ฟังก์ชันเลื่อนหน้าไปหน้าถัดไป
function nextPage() {
    currentPage++;
    updatePage();
}

// ฟังก์ชันอัปเดตปุ่มเลื่อนหน้า
function updatePaginationButtons() {
    var totalPages = Math.ceil(getFilteredProducts().length / productsPerPage);
    var prevButton = document.getElementById('prev-button');
    var nextButton = document.getElementById('next-button');
    if (currentPage === 0) {
        prevButton.disabled = true;
    } else {
        prevButton.disabled = false;
    }
    if (currentPage === totalPages - 1) {
        nextButton.disabled = true;
    } else {
        nextButton.disabled = false;
    }
}

// ฟังก์ชันค้นหาสินค้า
function searchProduct() {
    currentPage = 0; // เมื่อค้นหาใหม่ให้เริ่มที่หน้าแรก
    updatePage();
}

// ฟังก์ชันดึงรายการสินค้าที่ผ่านการค้นหา
function getFilteredProducts() {
    var filter = document.getElementById('searchInput').value.toUpperCase();
    return products.filter(function(product) {
        return product.name.toUpperCase().indexOf(filter) > -1;
    });
}

// เพิ่ม event listener เพื่อเรียกใช้งานฟังก์ชัน searchProduct() เมื่อมีการเปลี่ยนแปลงในช่อง input
document.getElementById('searchInput').addEventListener('input', searchProduct);

// เริ่มต้นแสดงผลที่หน้าแรก
updatePage();
``