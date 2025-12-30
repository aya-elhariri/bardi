<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Bardi</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
   
    <header>
        <div class="container header-container">
            <a href="index.php" class="logo">Bardi</a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li class="cart-icon" id="cart-icon">
                        <span>Cart</span>
                        <span class="cart-count" id="cart-count">0</span>
                    </li>
                </ul>
            </nav>
        </div>
    </header>


    <section class="shop-hero">
        <div class="container">
            <h1>Shop Our Collection</h1>
            <p>Find the perfect notebook, journal, or cover to organize your thoughts in style.</p>
        </div>
    </section>

   
    <section>
        <div class="container">
            <div class="category-filter" id="category-filter">
                <button class="category-btn active" data-category="all">All Products</button>
                <button class="category-btn" data-category="notebooks">Notebooks</button>
                <button class="category-btn" data-category="journals">Journals</button>
                <button class="category-btn" data-category="covers">Covers Only</button>
            </div>
            
            <div class="products-grid" id="shop-products">
                <?php
                // Get category from URL
                $category = $_GET['category'] ?? 'all';
                
                // Load products from JSON file
                $productsFile = 'admin/products.json';
                if (file_exists($productsFile)) {
                    $productsData = file_get_contents($productsFile);
                    $products = json_decode($productsData, true);
                    
                    foreach ($products as $product) {
                        // Filter by category if specified
                        if ($category !== 'all' && $product['category'] !== $category) {
                            continue;
                        }
                        
                        echo '
                        <div class="product-card" data-category="' . htmlspecialchars($product['category']) . '">
                            <img src="images/' . htmlspecialchars($product['image'] ?? 'default.png') . '" alt="' . htmlspecialchars($product['name']) . '" class="product-image">
                            <div class="product-info">
                                <h3 class="product-title">' . htmlspecialchars($product['name']) . '</h3>
                                <p class="product-category">' . htmlspecialchars($product['category']) . '</p>
                                <p class="product-price">$' . number_format($product['price'], 2) . '</p>
                                <button class="btn add-to-cart" data-id="' . $product['id'] . '">Add to Cart</button>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>No products available. Check back soon!</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Cart Modal -->
    <div class="overlay" id="overlay"></div>
    <div class="cart-modal" id="cart-modal">
        <div class="cart-header">
            <h2>Your Cart</h2>
            <button class="close-cart" id="close-cart">&times;</button>
        </div>
        <div class="cart-items" id="cart-items">
            <!-- Cart items will be dynamically added here -->
        </div>
        <div class="cart-total">
            <span>Total:</span>
            <span id="cart-total">$0.00</span>
        </div>
        <button class="btn checkout-btn" onclick="window.location.href='cart.php'">Checkout</button>
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

    <script src="assets/js/main.js"></script>
</body>
</html>