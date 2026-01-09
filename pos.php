<?php
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Billing - Grocery Billing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .pos-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .products-section {
            flex: 3;
            padding: 15px;
            overflow-y: auto;
        }
        .cart-section {
            flex: 2;
            background: #f8f9fa;
            padding: 15px;
            border-left: 1px solid #dee2e6;
        }
        .product-card {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .cart-item {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-cash-register"></i> Point of Sale
            </a>
            <div class="d-flex align-items-center">
                <a href="../index.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="text-white me-3">Cashier: <?php echo $_SESSION['full_name']; ?></span>
            </div>
        </div>
    </nav>

    <div class="pos-container">
        <!-- Products Section -->
        <div class="products-section">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>Products</h3>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="searchProduct" class="form-control" placeholder="Search products...">
                        <button class="btn btn-outline-success" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="row" id="productsGrid">
                <?php
                require_once '../includes/functions.php';
                $products = getProducts();
                foreach($products as $product):
                ?>
                <div class="col-md-3 mb-3">
                    <div class="card product-card" onclick="addToCart(<?php echo $product['product_id']; ?>)">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo $product['product_name']; ?></h6>
                            <p class="card-text mb-1">
                                <small>Price: ₹<?php echo number_format($product['price'], 2); ?></small>
                            </p>
                            <p class="card-text">
                                <small class="text-success">
                                    Stock: <?php echo $product['stock_quantity']; ?> <?php echo $product['unit']; ?>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
            <h3>Bill</h3>
            <hr>
            
            <!-- Customer Info -->
            <div class="mb-3">
                <label class="form-label">Customer</label>
                <select id="customerSelect" class="form-select">
                    <option value="">Walk-in Customer</option>
                    <?php
                    $result = $conn->query("SELECT * FROM customers ORDER BY customer_name");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['customer_id']; ?>">
                        <?php echo $row['customer_name']; ?> (<?php echo $row['phone']; ?>)
                    </option>
                    <?php endwhile; ?>
                </select>
                <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus"></i> Add New Customer
                </button>
            </div>
            
            <!-- Cart Items -->
            <div id="cartItems" style="max-height: 300px; overflow-y: auto;">
                <!-- Cart items will be displayed here -->
                <div class="text-center text-muted mt-3">Cart is empty</div>
            </div>
            
            <!-- Bill Summary -->
            <div class="mt-4 p-3 bg-white rounded border">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span id="subtotal">₹0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount:</span>
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="number" id="discount" class="form-control" value="0" min="0" style="width: 100px;">
                        <span class="input-group-text">₹</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (5%):</span>
                    <span id="tax">₹0.00</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Grand Total:</strong>
                    <strong id="grandTotal">₹0.00</strong>
                </div>
                
                <!-- Payment Method -->
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select id="paymentMethod" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="upi">UPI</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-lg" onclick="processSale()">
                        <i class="fas fa-check-circle"></i> Process Sale
                    </button>
                    <button class="btn btn-danger" onclick="clearCart()">
                        <i class="fas fa-trash"></i> Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCustomerForm">
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveCustomer()">Save Customer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="invoiceContent">
                    <!-- Invoice will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="printInvoice()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = [];
        
        // Add product to cart
        function addToCart(productId) {
            // Check if product already in cart
            let existingItem = cart.find(item => item.product_id == productId);
            if (existingItem) {
                existingItem.quantity += 1;
                existingItem.total = existingItem.quantity * existingItem.price;
            } else {
                // Fetch product details
                fetch(`../includes/get_product.php?id=${productId}`)
                    .then(response => response.json())
                    .then(product => {
                        cart.push({
                            product_id: product.product_id,
                            product_name: product.product_name,
                            price: parseFloat(product.price),
                            quantity: 1,
                            total: parseFloat(product.price),
                            stock: parseInt(product.stock_quantity)
                        });
                        updateCartDisplay();
                    });
            }
            updateCartDisplay();
        }
        
        // Update cart display
        function updateCartDisplay() {
            let cartItemsDiv = document.getElementById('cartItems');
            let subtotal = 0;
            
            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '<div class="text-center text-muted mt-3">Cart is empty</div>';
                updateTotals(0, 0, 0);
                return;
            }
            
            let html = '';
            cart.forEach((item, index) => {
                subtotal += item.total;
                html += `
                    <div class="cart-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>${item.product_name}</strong><br>
                                <small>₹${item.price.toFixed(2)} x ${item.quantity}</small>
                            </div>
                            <div class="text-end">
                                <div>₹${item.total.toFixed(2)}</div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, -1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="removeFromCart(${index})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            cartItemsDiv.innerHTML = html;
            updateTotals(subtotal);
        }
        
        // Update item quantity
        function updateQuantity(index, change) {
            let item = cart[index];
            let newQuantity = item.quantity + change;
            
            if (newQuantity < 1) {
                cart.splice(index, 1);
            } else if (newQuantity > item.stock) {
                alert('Not enough stock available!');
                return;
            } else {
                item.quantity = newQuantity;
                item.total = item.quantity * item.price;
            }
            
            updateCartDisplay();
        }
        
        // Remove item from cart
        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }
        
        // Clear cart
        function clearCart() {
            if (confirm('Are you sure you want to clear the cart?')) {
                cart = [];
                updateCartDisplay();
                document.getElementById('discount').value = 0;
            }
        }
        
        // Update totals
        function updateTotals(subtotal) {
            let discount = parseFloat(document.getElementById('discount').value) || 0;
            let tax = (subtotal - discount) * 0.05; // 5% tax
            let grandTotal = subtotal - discount + tax;
            
            document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
            document.getElementById('tax').textContent = '₹' + tax.toFixed(2);
            document.getElementById('grandTotal').textContent = '₹' + grandTotal.toFixed(2);
        }
        
        // Save new customer
        function saveCustomer() {
            let formData = new FormData(document.getElementById('addCustomerForm'));
            
            fetch('../includes/save_customer.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new customer to select
                    let select = document.getElementById('customerSelect');
                    let option = new Option(data.customer_name + ' (' + data.phone + ')', data.customer_id);
                    select.add(option);
                    select.value = data.customer_id;
                    
                    // Close modal
                    let modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                    modal.hide();
                    
                    // Reset form
                    document.getElementById('addCustomerForm').reset();
                } else {
                    alert('Error saving customer: ' + data.error);
                }
            });
        }
        
        // Process sale
        function processSale() {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }
            
            let customer_id = document.getElementById('customerSelect').value || null;
            let payment_method = document.getElementById('paymentMethod').value;
            let discount = parseFloat(document.getElementById('discount').value) || 0;
            
            let subtotal = cart.reduce((sum, item) => sum + item.total, 0);
            let tax = (subtotal - discount) * 0.05;
            let grand_total = subtotal - discount + tax;
            
            let saleData = {
                customer_id: customer_id,
                payment_method: payment_method,
                discount: discount,
                tax: tax,
                grand_total: grand_total,
                items: cart
            };
            
            fetch('../includes/process_sale.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(saleData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show invoice
                    showInvoice(data.invoice);
                    // Clear cart
                    cart = [];
                    updateCartDisplay();
                } else {
                    alert('Error processing sale: ' + data.error);
                }
            });
        }
        
        // Show invoice
        function showInvoice(invoiceData) {
            let content = `
                <div class="text-center mb-4">
                    <h4>Grocery Store</h4>
                    <p>123 Main Street, City</p>
                    <p>Phone: 9876543210 | GST: 27AAAAA0000A1Z5</p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Invoice No:</strong> ${invoiceData.invoice_no}<br>
                        <strong>Date:</strong> ${invoiceData.date}
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Customer:</strong> ${invoiceData.customer_name || 'Walk-in Customer'}<br>
                        <strong>Cashier:</strong> ${invoiceData.cashier_name}
                    </div>
                </div>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            invoiceData.items.forEach(item => {
                content += `
                    <tr>
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>₹${parseFloat(item.unit_price).toFixed(2)}</td>
                        <td>₹${parseFloat(item.total_price).toFixed(2)}</td>
                    </tr>
                `;
            });
            
            content += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                            <td>₹${parseFloat(invoiceData.subtotal).toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                            <td>₹${parseFloat(invoiceData.discount).toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tax (5%):</strong></td>
                            <td>₹${parseFloat(invoiceData.tax).toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                            <td><strong>₹${parseFloat(invoiceData.grand_total).toFixed(2)}</strong></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="text-center mt-4">
                    <p>Payment Method: ${invoiceData.payment_method.toUpperCase()}</p>
                    <p>Thank you for shopping with us!</p>
                </div>
            `;
            
            document.getElementById('invoiceContent').innerHTML = content;
            
            let invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            invoiceModal.show();
        }
        
        // Print invoice
        function printInvoice() {
            let printContent = document.getElementById('invoiceContent').innerHTML;
            let originalContent = document.body.innerHTML;
            
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            
            // Reload to refresh page state
            location.reload();
        }
        
        // Event listeners
        document.getElementById('discount').addEventListener('input', function() {
            let subtotal = cart.reduce((sum, item) => sum + item.total, 0);
            updateTotals(subtotal);
        });
        
        // Initialize cart display
        updateCartDisplay();
    </script>
</body>
</html>