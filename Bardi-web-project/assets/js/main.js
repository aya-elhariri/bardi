// Main e-commerce JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Cart functionality
    let cart = JSON.parse(localStorage.getItem('bardiCart')) || [];
    let productsCache = [];
    
    // DOM Elements
    const cartIcon = document.getElementById('cart-icon');
    const cartModal = document.getElementById('cart-modal');
    const closeCart = document.getElementById('close-cart');
    const overlay = document.getElementById('overlay');
    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');
    
    // Load products from server and cache them
    async function loadProducts() {
        try {
            const response = await fetch('admin/process.php?action=get');
            productsCache = await response.json();
            return productsCache;
        } catch (error) {
            console.error('Error loading products:', error);
            return [];
        }
    }
    
    // Get product from cache by ID
    function getProductById(id) {
        return productsCache.find(product => product.id === id);
    }
    
    // Update cart count
    function updateCartCount() {
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        if (cartCount) {
            cartCount.textContent = totalItems;
        }
    }
    
    // Update cart display in modal
    async function updateCartDisplay() {
        // Make sure products are loaded
        if (productsCache.length === 0) {
            await loadProducts();
        }
        
        // Clear current display
        if (cartItems) {
            cartItems.innerHTML = '';
        }
        
        if (cart.length === 0) {
            if (cartItems) {
                cartItems.innerHTML = '<p class="empty-cart-message">Your cart is empty</p>';
            }
            if (cartTotal) {
                cartTotal.textContent = '$0.00';
            }
            return;
        }
        
        let total = 0;
        
        // Display each item in the cart
        for (const item of cart) {
            const product = getProductById(item.id);
            
            if (product) {
                const itemTotal = parseFloat(product.price) * item.quantity;
                total += itemTotal;
                
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <img src="images/${product.image || 'default.png'}" alt="${product.name}" class="cart-item-image" onerror="this.src='images/default.png'">
                    <div class="cart-item-details">
                        <h4 class="cart-item-title">${product.name}</h4>
                        <p class="cart-item-price">$${parseFloat(product.price).toFixed(2)}</p>
                        <div class="cart-item-quantity">
                            <button class="quantity-btn decrease-quantity" data-id="${product.id}">-</button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="quantity-btn increase-quantity" data-id="${product.id}">+</button>
                        </div>
                    </div>
                `;
                
                if (cartItems) {
                    cartItems.appendChild(cartItem);
                }
            }
        }
        
        // Update total
        if (cartTotal) {
            cartTotal.textContent = `$${total.toFixed(2)}`;
        }
    }
    
    // Add to cart function
    function addToCart(productId) {
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: productId,
                quantity: 1
            });
        }
        
        // Save to localStorage
        localStorage.setItem('bardiCart', JSON.stringify(cart));
        
        // Update UI
        updateCartCount();
        
        // If cart modal is open, update it too
        if (cartModal && cartModal.style.display === 'block') {
            updateCartDisplay();
        }
        
        // Show confirmation message
        showCartConfirmation('Product added to cart!');
    }
    
    // Show cart confirmation message
    function showCartConfirmation(message) {
        // Remove existing confirmation if any
        const existingConfirmation = document.querySelector('.cart-confirmation');
        if (existingConfirmation) {
            existingConfirmation.remove();
        }
        
        // Create and show new confirmation
        const confirmation = document.createElement('div');
        confirmation.className = 'cart-confirmation';
        confirmation.textContent = message;
        document.body.appendChild(confirmation);
        
        // Remove after 2 seconds
        setTimeout(() => {
            if (confirmation.parentNode) {
                confirmation.remove();
            }
        }, 2000);
    }
    
    // Update quantity function
    function updateQuantity(productId, change) {
        const item = cart.find(item => item.id === productId);
        
        if (item) {
            item.quantity += change;
            
            if (item.quantity <= 0) {
                cart = cart.filter(item => item.id !== productId);
            }
            
            localStorage.setItem('bardiCart', JSON.stringify(cart));
            updateCartCount();
            updateCartDisplay();
        }
    }
    
    // Open cart modal
    function openCartModal() {
        if (cartModal) {
            cartModal.style.display = 'block';
        }
        if (overlay) {
            overlay.style.display = 'block';
        }
        updateCartDisplay();
    }
    
    // Close cart modal
    function closeCartModal() {
        if (cartModal) {
            cartModal.style.display = 'none';
        }
        if (overlay) {
            overlay.style.display = 'none';
        }
    }
    
    // Event Listeners
    if (cartIcon) {
        cartIcon.addEventListener('click', openCartModal);
    }
    
    if (closeCart) {
        closeCart.addEventListener('click', closeCartModal);
    }
    
    if (overlay) {
        overlay.addEventListener('click', closeCartModal);
    }
    
    // Add to cart buttons
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('add-to-cart')) {
            const productId = e.target.getAttribute('data-id');
            addToCart(productId);
        }
        
        // Quantity buttons in cart modal
        if (e.target.classList.contains('increase-quantity')) {
            const productId = e.target.getAttribute('data-id');
            updateQuantity(productId, 1);
        }
        
        if (e.target.classList.contains('decrease-quantity')) {
            const productId = e.target.getAttribute('data-id');
            updateQuantity(productId, -1);
        }
    });
    
    // Category filter for shop page
    const categoryFilter = document.getElementById('category-filter');
    if (categoryFilter) {
        categoryFilter.addEventListener('click', (e) => {
            if (e.target.classList.contains('category-btn')) {
                // Remove active class from all buttons
                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                e.target.classList.add('active');
                
                // Filter products
                const category = e.target.getAttribute('data-category');
                const productCards = document.querySelectorAll('.product-card');
                
                productCards.forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        });
    }
    
    // Close cart modal when clicking outside
    document.addEventListener('click', (e) => {
        if (cartModal && cartModal.style.display === 'block' && 
            !cartModal.contains(e.target) && 
            !cartIcon.contains(e.target)) {
            closeCartModal();
        }
    });
    
    // Escape key closes cart modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && cartModal && cartModal.style.display === 'block') {
            closeCartModal();
        }
    });
    
    // Preload products for better performance
    loadProducts().then(() => {
        console.log('Products loaded for cart functionality');
    });
    
    // Initial setup
    updateCartCount();
});