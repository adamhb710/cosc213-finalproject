<?php
require_once 'config.php';

// Check if user is logged in AND is an admin
require_login();
require_admin();

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If the ID is invalid, then you redirect to admin.php
if ($product_id <= 0) {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: ../admin.php");
    exit();
}

// Delete product from the database
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);

// Executes and sets a success/error message
if ($stmt->execute()) {
    $_SESSION['message'] = "Product deleted successfully!";
} else {
    $_SESSION['message'] = "Failed to delete product.";
}

// Redirects back to admin panel
header("Location: ../admin.php");
exit();
?>
