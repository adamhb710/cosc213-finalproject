<?php
// opens session and loads database connection
session_start();
require_once 'config.php';

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    $_SESSION['message'] = "Missing product information.";
    header("Location: add_to_cart.php");
    exit();
}
?>

