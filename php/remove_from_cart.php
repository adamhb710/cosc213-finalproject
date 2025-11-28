<?php
session_start();

// check if product id exists
if (!isset($_POST['product_id'])) {
header("Location: ../cart.php");
exit();
}

$product_id = intval($_POST['product_id']);


// if cart doesnt exist go back
if (!isset($_SESSION['cart'])) {
header("Location: ../cart.php");
exit();
}

// loop through cart and remove item that matches product id
foreach($_SESSION['cart'] as $index => $item) {
if ($item['id'] == $product_id) {

// remove item from cart
unset($_SESSION['cart'][$index]);

//re-index array
$_SESSION['cart'] = array_values($_SESSION['cart']);

$_SESSION['message'] = "Item removed from cart.";
break;
}
}

header("Location: ../cart.php");
exit();

