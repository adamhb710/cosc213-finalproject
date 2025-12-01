<?php
// opens session and loads database connection
session_start();
require_once 'config.php';

// checking the incoming POST data making sure form sent product ID and quantity
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    $_SESSION['message'] = "Missing product information.";
    header("Location: ../cart.php");
    exit();
}

// convert input to integers to avoid any problems
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// check if product ID is valid
if ($product_id <= 0) {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: ../cart.php");
    exit();
}

//check if quantity is valid and within range
if ($quantity <= 0) {
    $_SESSION['message'] = "Invalid quantity.";
    header("Location: ../cart.php");
    exit();
}

// get product info from database
$query = "SELECT id, name, price, stock FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);

// convert sql result into an array
$product = mysqli_fetch_assoc($result);

// if product doesnt exist then product not found
if (!$product) {
    $_SESSION['message'] = "Product not found.";
    header("Location: ../cart.php");
    exit();
}

// check if in stock

if ($quantity > $product['stock']) {
    $_SESSION['message'] = "This product is out of stock.";
    header("Location: ../cart.php");
    exit();
}
// create cart array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// build an array for the item in the cart
$cart_item = [
        'id' => $product['id'],
    'name' => $product['name'],
    'price' => $product['price'],
    'quantity' => $quantity

];

// Add item to the cart
$_SESSION['cart'][] = $cart_item;

//reduce stock in database
$new_stock = $product['stock'] - $quantity;
$update_query = "UPDATE products SET stock = $new_stock WHERE id = $product_id";
mysqli_query($conn, $update_query);


$_SESSION['message'] = "Item has been added to your cart.";
header("Location: ../index.php");
exit();
?>

