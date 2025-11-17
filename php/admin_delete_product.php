<?php
require_once 'config.php';

// Check if user is admin
require_login();
require_admin();

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: ../admin.php");
    exit();
}

// Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Product deleted successfully!";
} else {
    $_SESSION['message'] = "Failed to delete product.";
}

header("Location: ../admin.php");
exit();
?>
