<!-- cart.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Bardi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .cart-page {
            min-height: 60vh;
            padding: 60px 0;
        }
        
        .cart-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .cart-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }
        
        .cart-items-list {
            background-color: var(--white);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .cart-item-details-page {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .cart-item-details-page:last-child {
            border-bottom: none;
        }
        
        .cart-item-image-page {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        
        .cart-item-info-page {
            flex: 1;
        }
        
        .cart-item-title-page {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .cart-item-price-page {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
        }
        
        .cart-item-quantity-page {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cart-summary {
            background-color: var(--white);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .summary-row.total {
            font-size: 1.3rem;
            font-weight: 700;
            border-bottom: none;
            color: var(--text-dark);
        }
        
        .checkout-form {
            margin-top: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .empty-cart-message {
            text-align: center;
            padding: 60px 0;
        }
        
        .empty-cart-message h2 {
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .cart-container {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="index.php" class="logo">Bardi</a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Gift</a></li>
                    <li class="cart-icon">
                        <span>Cart</span>
                        <span class="cart-count" id="cart-count">0</span>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Cart Page Content -->
    <div class="cart-page">
        <div class="container">
            <div class="cart-header">
                <h1>Your Shopping Cart</h1>
                <p>Review your items and proceed to checkout</p>
            </div>
            
            <div id="cart-content">
                <!-- Cart content will be loaded by JavaScript -->
                <div class="empty-cart-message">
                    <h2>Your cart is empty</h2>
                    <p>Add some beautiful notebooks to your cart!</p>
                    <a href="shop.php" class="btn">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Bardi</h3>
                    <p>Beautiful, reusable notebooks and journals that help you organize your thoughts and express your unique style.</p>
                </div>
                <div class="footer-column">
                    <h3>Shop</h3>
                    <ul>
                        <li><a href="shop.php?category=notebooks">Notebooks</a></li>
                        <li><a href="shop.php?category=journals">Journals</a></li>
                        <li><a href="shop.php?category=covers">Covers Only</a></li>
                        <li><a href="#">Gift Sets</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Help</h3>
                    <ul>
                        <li><a href="#">Shipping & Returns</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Connect</h3>
                    <ul>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Pinterest</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Bardi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = JSON.parse(localStorage.getItem('bardiCart')) || [];
            let products = [];
            
            // Load products from server
            async function loadProducts() {
                try {
                    const response = await fetch('admin/process.php?action=get');
                    products = await response.json();
                    return products;
                } catch (error) {
                    console.error('Error loading products:', error);
                    return [];
                }
            }
            
            // Get product by ID
            function getProductById(id) {
                return products.find(product => product.id === id);
            }
            
            // Update cart count in header
            function updateCartCount() {
                const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = totalItems;
                }
            }
            
            // Display cart on page
            async function displayCart() {
                await loadProducts();
                
                if (cart.length === 0) {
                    // Keep empty cart message
                    return;
                }
                
                let total = 0;
                let itemsHTML = '';
                
                cart.forEach(item => {
                    const product = getProductById(item.id);
                    
                    if (product) {
                        const itemTotal = parseFloat(product.price) * item.quantity;
                        total += itemTotal;
                        
                        itemsHTML += `
                            <div class="cart-item-details-page">
                                <img src="images/${product.image}" alt="${product.name}" class="cart-item-image-page">
                                <div class="cart-item-info-page">
                                    <h3 class="cart-item-title-page">${product.name}</h3>
                                    <p class="cart-item-price-page">$${parseFloat(product.price).toFixed(2)} × ${item.quantity}</p>
                                    <div class="cart-item-quantity-page">
                                        <button class="btn quantity-btn-page decrease" data-id="${product.id}" style="padding: 5px 10px; font-size: 14px;">-</button>
                                        <span class="quantity">${item.quantity}</span>
                                        <button class="btn quantity-btn-page increase" data-id="${product.id}" style="padding: 5px 10px; font-size: 14px;">+</button>
                                        <button class="btn remove-item" data-id="${product.id}" style="padding: 5px 10px; font-size: 14px; margin-left: 20px; background-color: #ff4444;">Remove</button>
                                    </div>
                                    <p style="margin-top: 10px; font-weight: 600;">Subtotal: $${itemTotal.toFixed(2)}</p>
                                </div>
                            </div>
                        `;
                    }
                });
                
                const cartHTML = `
                    <div class="cart-container">
                        <div class="cart-items-list">
                            <h2>Cart Items (${cart.reduce((total, item) => total + item.quantity, 0)})</h2>
                            ${itemsHTML}
                            <div style="margin-top: 30px;">
                                <a href="shop.php" class="btn" style="background-color: #666; margin-right: 10px;">Continue Shopping</a>
                                <button class="btn" id="clear-cart" style="background-color: #ff4444;">Clear Cart</button>
                            </div>
                        </div>
                        
                        <div class="cart-summary">
                            <h2>Order Summary</h2>
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>EGP ${total.toFixed(2)}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>$5.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax</span>
                                <span>$${(total * 0.08).toFixed(2)}</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span>$${(total + 5 + (total * 0.08)).toFixed(2)}</span>
                            </div>
                            
                            <div class="checkout-form">
                                <h3>Checkout Information</h3>
                                <form id="checkout-form">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Shipping Address</label>
                                        <textarea id="address" name="address" rows="3" required></textarea>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" id="city" name="city" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="zip">ZIP Code</label>
                                            <input type="text" id="zip" name="zip" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn" style="width: 100%;">Proceed to Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('cart-content').innerHTML = cartHTML;
                
                // Add event listeners
                document.addEventListener('click', (e) => {
                    if (e.target.classList.contains('increase')) {
                        const productId = e.target.getAttribute('data-id');
                        updateQuantity(productId, 1);
                    }
                    
                    if (e.target.classList.contains('decrease')) {
                        const productId = e.target.getAttribute('data-id');
                        updateQuantity(productId, -1);
                    }
                    
                    if (e.target.classList.contains('remove-item')) {
                        const productId = e.target.getAttribute('data-id');
                        removeItem(productId);
                    }
                    
                    if (e.target.id === 'clear-cart') {
                        if (confirm('Are you sure you want to clear your cart?')) {
                            clearCart();
                        }
                    }
                });
                
                // Handle checkout form submission
                const checkoutForm = document.getElementById('checkout-form');
                if (checkoutForm) {
                    checkoutForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        alert('Thank you for your order! This is a demo site. In a real implementation, this would process payment.');
                        clearCart();
                    });
                }
            }
            
            // Update quantity
            function updateQuantity(productId, change) {
                const item = cart.find(item => item.id === productId);
                
                if (item) {
                    item.quantity += change;
                    
                    if (item.quantity <= 0) {
                        cart = cart.filter(item => item.id !== productId);
                    }
                    
                    localStorage.setItem('bardiCart', JSON.stringify(cart));
                    updateCartCount();
                    displayCart();
                }
            }
            
            // Remove item
            function removeItem(productId) {
                cart = cart.filter(item => item.id !== productId);
                localStorage.setItem('bardiCart', JSON.stringify(cart));
                updateCartCount();
                displayCart();
            }
            
            // Clear cart
            function clearCart() {
                cart = [];
                localStorage.removeItem('bardiCart');
                updateCartCount();
                displayCart();
            }
            
            // Initial setup
            updateCartCount();
            displayCart();
        });
    </script>
</body>
</html>