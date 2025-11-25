<?php
// opens session and loads database connection
session_start();
require_once 'config.php';

// checking the incoming POST data
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    $_SESSION['message'] = "Missing product information.";
    header("Location: cart.php");
    exit();
}


$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

if ($product_id <= 0) {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: cart.php");
    exit();
}

if ($quantity <= 0) {
    $_SESSION['message'] = "Invalid quantity.";
    header("Location: cart.php");
    exit();
}

// look up product in database
$query = "SELECT id, name, price, stock FROM products WHERE id = '$product_id'";
$result = mysqli_query($conn, $query);

$product = mysqli_fetch_array($result);

if (!$product) {
    $_SESSION['message'] = "Product not found.";
    header("Location: cart.php");
    exit();
}

// check if in stock

if ($quantity > $product['stock']) {
    $_SESSION['message'] = "This product is out of stock.";
    header("Location: cart.php");
    exit();
}
?>

