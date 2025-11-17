<?php
require_once 'php/config.php';

// Check if user is admin
require_login();
require_admin();

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header("Location: admin.php");
    exit();
}

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['message'] = "Product not found.";
    header("Location: admin.php");
    exit();
}

$product = $result->fetch_assoc();
$errors = array();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean_input($_POST['name']);
    $description = clean_input($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_url = clean_input($_POST['image_url']);
    $active = isset($_POST['active']) ? 1 : 0;
    
    // Validation
    if (empty($name)) {
        $errors[] = "Product name is required";
    }
    
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    
    if ($stock < 0) {
        $errors[] = "Stock cannot be negative";
    }
    
    // If no errors, update product
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image_url = ?, active = ? WHERE id = ?");
        $stmt->bind_param("ssdisii", $name, $description, $price, $stock, $image_url, $active, $product_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Product updated successfully!";
            header("Location: admin.php");
            exit();
        } else {
            $errors[] = "Failed to update product. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🛒 My Shop - Admin</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Shop</a></li>
                    <li><a href="admin.php">Admin Panel</a></li>
                    <li><a href="php/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <form method="POST" action="admin_edit_product.php?id=<?php echo $product_id; ?>" class="form-container" style="max-width: 700px;">
                <h2>Edit Product</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="message error">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($product['name']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Price ($) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0"
                           value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : $product['price']; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock Quantity *</label>
                    <input type="number" id="stock" name="stock" min="0"
                           value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : $product['stock']; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="image_url">Image URL</label>
                    <input type="text" id="image_url" name="image_url" 
                           value="<?php echo isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : htmlspecialchars($product['image_url']); ?>"
                           placeholder="https://via.placeholder.com/400x400">
                    <small>Leave empty for default placeholder image</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="active" value="1" 
                               <?php echo (isset($_POST['active']) ? $_POST['active'] : $product['active']) ? 'checked' : ''; ?>>
                        Active (visible in store)
                    </label>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="admin.php" class="btn">Cancel

