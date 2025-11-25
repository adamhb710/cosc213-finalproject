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
?>

