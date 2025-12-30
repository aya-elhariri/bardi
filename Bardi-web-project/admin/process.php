<?php
header('Content-Type: application/json');

// File paths
$jsonFile = 'products.json';

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $response = ['success' => false, 'message' => ''];
    
    // Validate input
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $image = trim($_POST['image'] ?? '');

    if (empty($image)) {
        $response['message'] = 'Image filename is required.';
        echo json_encode($response);
        exit;
    }
    
    if (empty($name) || empty($category)) {
        $response['message'] = 'Product name and category are required.';
        echo json_encode($response);
        exit;
    }
    
    if ($price <= 0) {
        $response['message'] = 'Price must be greater than 0.';
        echo json_encode($response);
        exit;
    }
    
    // Create new product
    $newProduct = [
        'id' => uniqid(),
        'name' => htmlspecialchars($name),
        'description' => htmlspecialchars($description),
        'price' => $price,
        'category' => htmlspecialchars($category),
        'image' => htmlspecialchars($image),  // Add this line
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Read existing products
    $products = [];
    if (file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);
        $products = json_decode($jsonContent, true) ?: [];
    }
    
    // Add new product
    $products[] = $newProduct;
    
    // Save to file
    if (file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT))) {
        $response['success'] = true;
        $response['message'] = 'Product added successfully!';
        $response['product'] = $newProduct;
    } else {
        $response['message'] = 'Failed to save product.';
    }
    
    echo json_encode($response);
    exit;
}

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $response = ['success' => false, 'message' => ''];
    
    $productId = $_POST['id'] ?? '';
    
    if (empty($productId)) {
        $response['message'] = 'Product ID is required.';
        echo json_encode($response);
        exit;
    }
    
    if (!file_exists($jsonFile)) {
        $response['message'] = 'No products found.';
        echo json_encode($response);
        exit;
    }
    
    $jsonContent = file_get_contents($jsonFile);
    $products = json_decode($jsonContent, true) ?: [];
    
    // Find and remove product
    $initialCount = count($products);
    $products = array_filter($products, function($product) use ($productId) {
        return $product['id'] !== $productId;
    });
    
    $products = array_values($products); // Reindex array
    
    if (count($products) < $initialCount) {
        if (file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT))) {
            $response['success'] = true;
            $response['message'] = 'Product deleted successfully!';
        } else {
            $response['message'] = 'Failed to delete product.';
        }
    } else {
        $response['message'] = 'Product not found.';
    }
    
    echo json_encode($response);
    exit;
}

// Handle fetching products
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    $products = [];
    
    if (file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);
        $products = json_decode($jsonContent, true) ?: [];
    }
    
    echo json_encode($products);
    exit;
}

echo json_encode(['error' => 'Invalid request']);
?>