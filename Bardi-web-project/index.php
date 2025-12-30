
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bardi - Beautiful Notebooks & Journals</title>
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

   
    <div id="homepage">
       
        <section class="hero">
            <div class="container">
                <h1>Organize Your Thoughts, Express Your Style</h1>
                <p>Beautiful, reusable notebooks and covers that care about your head and your thoughts.</p>
                <a href="shop.php" class="btn">Shop Now</a>
            </div>
        </section>

        
        <section class="features">
            <div class="container">
                <h2 class="section-title">Why Choose Bardi?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <h3>Beautiful Designs</h3>
                        <p>Our notebooks feature unique, artistic covers that inspire creativity.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Reusable Covers</h3>
                        <p>Swap out covers to match your mood or style. Reduce waste with our reusable system.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Perfect Gifts</h3>
                        <p>Thoughtful presents for friends, colleagues, or yourself. We offer gift wrapping.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Covers Only</h3>
                        <p>Love a design? Buy just the cover to refresh your existing notebook.</p>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="products">
            <div class="container">
                <h2 class="section-title">Featured Products</h2>
                <div class="products-grid" id="featured-products">
                    <?php
                    
                    $productsFile = 'admin/products.json';
                    if (file_exists($productsFile)) {
                        $productsData = file_get_contents($productsFile);
                        $products = json_decode($productsData, true);
                        
                        
                        $count = 0;
                        foreach ($products as $product) {
                            if ($count >= 4) break;
                            echo '
                            <div class="product-card">
                                <img src="images/' . htmlspecialchars($product['image'] ?? 'default.png') . '" alt="' . htmlspecialchars($product['name']) . '" class="product-image">
                                <div class="product-info">
                                    <h3 class="product-title">' . htmlspecialchars($product['name']) . '</h3>
                                    <p class="product-category">' . htmlspecialchars($product['category']) . '</p>
                                    <p class="product-price">$' . number_format($product['price'], 2) . '</p>
                                    <button class="btn add-to-cart" data-id="' . $product['id'] . '">Add to Cart</button>
                                </div>
                            </div>';
                            $count++;
                        }
                    } else {
                        echo '<p>No products available. Check back soon!</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>

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