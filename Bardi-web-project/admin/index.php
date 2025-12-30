<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Product Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
            <a href="../shop.php">go to shop</a>
        </header>
        
        <section class="form-section">
            <h2>Add New Product</h2>
            
            <?php
            // Display success/error messages from form submission
            if (isset($_GET['status']) && isset($_GET['message'])) {
                $statusClass = $_GET['status'] === 'success' ? 'success' : 'error';
                echo "<div class='message {$statusClass}'>{$_GET['message']}</div>";
            }
            ?>
            
            <form id="addProductForm" method="POST" action="process.php">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Enter product name">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <input type="text" id="category" name="category" required 
                               placeholder="Enter category (e.g., Electronics, Clothing)">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (USD) *</label>
                        <input type="number" id="price" name="price" required 
                               min="0.01" step="0.01" placeholder="0.00">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="image">Image Filename *</label>
                    <input type="text" id="image" name="image" required 
                        placeholder="e.g., n1 (1).png">
                </div>
                
                <button type="submit" class="btn">Add Product</button>
            </form>
        </section>
        
        <section class="products-section">
            <div class="controls">
                <h2>Product List</h2>
                <button type="button" id="toggleProductsBtn" class="btn btn-secondary">
                    Display Products
                </button>
            </div>
            
            <div id="productsTableContainer">
                <table id="productsTable" class="products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>image</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <!-- Products will be loaded here via JavaScript -->
                    </tbody>
                </table>
                
                <div id="emptyState" class="empty-state" style="display: none;">
                    No products found. Add your first product above!
                </div>
            </div>
        </section>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productsTable = document.getElementById('productsTable');
            const productsTableBody = document.getElementById('productsTableBody');
            const toggleBtn = document.getElementById('toggleProductsBtn');
            const emptyState = document.getElementById('emptyState');
            const addProductForm = document.getElementById('addProductForm');
            
            let isTableVisible = false;
            
            // Toggle products table visibility
            toggleBtn.addEventListener('click', function() {
                isTableVisible = !isTableVisible;
                
                if (isTableVisible) {
                    productsTable.classList.add('show');
                    toggleBtn.textContent = 'Hide Products';
                    loadProducts();
                } else {
                    productsTable.classList.remove('show');
                    toggleBtn.textContent = 'Display Products';
                }
            });
            
            // Load products from server
            function loadProducts() {
                fetch('process.php?action=get')
                    .then(response => response.json())
                    .then(products => {
                        productsTableBody.innerHTML = '';
                        
                        if (products.length === 0) {
                            emptyState.style.display = 'block';
                            productsTable.style.display = 'none';
                            return;
                        }
                        
                        emptyState.style.display = 'none';
                        productsTable.style.display = 'table';
                        
                        products.forEach(product => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${product.id.substring(0, 8)}...</td>
                                <td>${product.name}</td>
                                <td>${product.category}</td>
                                <td>
                                    <img src="../images/${product.image}" alt="${product.name}" class="product-image-thumb">
                                </td>
                                <td class="price">$${parseFloat(product.price).toFixed(2)}</td>
                                <td class="actions">
                                    <button class="delete-btn" onclick="deleteProduct('${product.id}')">
                                        Delete
                                    </button>
                                </td>
                            `;
                            productsTableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading products:', error);
                    });
            }
            
            // Handle form submission with AJAX
            addProductForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('process.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert(data.message);
                        
                        // Reset form
                        addProductForm.reset();
                        
                        // Reload products if table is visible
                        if (isTableVisible) {
                            loadProducts();
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the product.');
                });
            });
        });
        
        // Delete product function
        function deleteProduct(productId) {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', productId);
            
            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    
                    // Reload products
                    const event = new Event('DOMContentLoaded');
                    document.dispatchEvent(event);
                    
                    // Trigger click on toggle button to refresh
                    document.getElementById('toggleProductsBtn').click();
                    setTimeout(() => {
                        document.getElementById('toggleProductsBtn').click();
                    }, 100);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the product.');
            });
        }
    </script>
</body>
</html>