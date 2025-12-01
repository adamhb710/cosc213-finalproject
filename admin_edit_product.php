<?php
//Centralized PHP logic here
require_once 'php/config.php';

// Check if user is logged in and admin
require_login();
require_admin();

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//If invalid ID returns to admin panel
if ($product_id <= 0) {
    header("Location: admin.php");
    exit();
}

// Fetch product details from database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// If the product doesn't exist, we redirect to admin panel
if ($result->num_rows == 0) {
    $_SESSION['message'] = "Product not found.";
    header("Location: admin.php");
    exit();
}

$product = $result->fetch_assoc();

// Error array for validation messages
$errors = array();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitized Input
    $name = clean_input($_POST['name']);
    $description = clean_input($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_url = clean_input($_POST['image_url']);
    $active = isset($_POST['active']) ? 1 : 0;

    // Validation - checks if fields are valid
    if (empty($name)) {
        $errors[] = "Product name is required";
    }

    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }

    if ($stock < 0) {
        $errors[] = "Stock cannot be negative";
    }

    // If no errors, update product in database
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image_url = ?, active = ? WHERE id = ?");
        $stmt->bind_param("ssdisii", $name, $description, $price, $stock, $image_url, $active, $product_id);

        if ($stmt->execute()) {
            // Success - then redirect to admin panel
            $_SESSION['message'] = "Product updated successfully!";
            header("Location: admin.php");
            exit();
        } else {
            $errors[] = "Failed to update product. Please try again.";
        }
    }
}

// Preserve form values - use POST data if exists, otherwise use existing product data
$name_value = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($product['name']);
$description_value = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($product['description']);
$price_value = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : $product['price'];
$stock_value = isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : $product['stock'];
$image_url_value = isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : htmlspecialchars($product['image_url']);
$active_checked = (isset($_POST['active']) ? $_POST['active'] : $product['active']) ? 'checked' : '';

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
        <h1>Shop-a-Lot - Admin</h1>
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
        <form method="POST" action="admin_edit_product.php?id=<?php echo $product_id; ?>" class="form-container"
              style="max-width: 700px;">
            <h2>Edit Product</h2>

            <!-- Display error messages if any -->
            <?php if (!empty($errors)): ?>
                <div class="message error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Product Name Input -->
            <div class="form-group">
                <label for="name">Product Name *</label>
                <input type="text" id="name" name="name"
                       value="<?php echo $name_value; ?>"
                       required>
            </div>

            <!-- Description Input -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?php echo $description_value; ?></textarea>
            </div>

            <!-- Price Input -->
            <div class="form-group">
                <label for="price">Price ($) *</label>
                <input type="number" id="price" name="price" step="0.01" min="0"
                       value="<?php echo $price_value; ?>"
                       required>
            </div>

            <!-- Stock Quantity Input -->
            <div class="form-group">
                <label for="stock">Stock Quantity *</label>
                <input type="number" id="stock" name="stock" min="0"
                       value="<?php echo $stock_value; ?>"
                       required>
            </div>

            <!-- Image URL Input -->
            <div class="form-group">
                <label for="image_url">Image URL</label>
                <input type="text" id="image_url" name="image_url"
                       value="<?php echo $image_url_value; ?>">
                <small>Leave empty for default placeholder image</small>
            </div>

            <!-- Active Checkbox -->
            <div class="form-group">
                <label>
                    <input type="checkbox" name="active" value="1" <?php echo $active_checked; ?>>
                    Active (visible in store)
                </label>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="admin.php" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Shop-a-Lot. All rights reserved.</p>
    </div>
</footer>
</body>
</html>

