<?php
session_start();
require_once 'config.php';

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
  
$quantity = $item['quantity'];

  // put the quantity back when removed from cart
  $stmt = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id =?");
  $stmt->bind_param("ii", $quantity, $product_id);
  $stmt->execute();

  // remove the item from the cart
  unset($_SESSION['cart'][$index]);

  // re-index the array
  $_SESSION['cart'] = array_values($_SESSION['cart']);


  
  break;

}
}

header("Location: ../cart.php");
exit();

