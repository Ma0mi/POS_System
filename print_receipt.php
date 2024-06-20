<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receipt</title>
<style>
    /* CSS for printing */
    @media print {
        /* Hide buttons and unnecessary elements */
        .print-btn {
            display: none;
        }
    }
</style>
</head>
<body>
    <div id="receipt">
        <!-- Content of the receipt -->
        <h1>Receipt</h1>
        <p>Date: <span id="receiptDate"></span></p>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="receiptItems">
                <!-- Receipt items will be dynamically added here -->
            </tbody>
        </table>
        <p>Total: $<span id="receiptTotal"></span></p>
    </div>

    <button class="print-btn" onclick="printReceipt()">Print Receipt</button>

    <script>
        function printReceipt() {
            // Set receipt data
            var receiptDate = new Date().toLocaleDateString();
            var receiptItems = [
                { name: 'Product 1', price: 10, quantity: 2 },
                { name: 'Product 2', price: 15, quantity: 1 }
            ];
            var receiptTotal = receiptItems.reduce((total, item) => total + (item.price * item.quantity), 0);

            // Update HTML with receipt data
            document.getElementById('receiptDate').innerText = receiptDate;
            var receiptItemsHTML = '';
            receiptItems.forEach(item => {
                receiptItemsHTML += `<tr>
                                        <td>${item.name}</td>
                                        <td>$${item.price.toFixed(2)}</td>
                                        <td>${item.quantity}</td>
                                        <td>$${(item.price * item.quantity).toFixed(2)}</td>
                                    </tr>`;
            });
            document.getElementById('receiptItems').innerHTML = receiptItemsHTML;
            document.getElementById('receiptTotal').innerText = receiptTotal.toFixed(2);

            // Print the receipt
            window.print();
        }
    </script>
</body>
</html>
