<?php
session_start();
require_once 'config.php';

// if cart doesnt exist go back
if (!isset($_SESSION['cart'])) {
header("Location: ../checkout.php");
exit();
}

//Clears the cart
unset($_SESSION['cart']);

//Sends a message and redirects back to the main store page
$_SESSION['message'] = "Checkout complete. Thanks for shopping!";
header("Location: ../index.php");

exit();

