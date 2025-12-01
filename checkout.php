<!-- commenting every section for my own learning
- and clarity for team members -->
<?php
//config starts session and loads database
require_once 'php/config.php';
//check if cart exists in the session and if it
// has items or is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $cart_empty = true;
} else {
    $cart_empty = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
          <!-- quick title and linking css file -->
          <meta charset="UTF-8">
                <title>Checkout</title>
          <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header>
    <!-- navBar for cart page -->
    <div class="container">
        <h1>Checkout</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <?php if (is_logged_in()): ?>
                <li><a href="php/logout.php">Logout</a></li>
                <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
<!-- button that brings you back to the main page if your cart is empty -->
    <?php if ($cart_empty): ?>

    <h2>Your cart is empty</h2>
    <a href="index.php" class="btn">Continue Shopping</a>

    <?php else: ?>
<!-- table headers showing product, price, quantity, and subtotal -->
    <table class="cart-table">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
<!-- foreach loop to loop through items in cart
- to add up subtotal -->
        <?php
        $total = 0;

        foreach ($_SESSION['cart'] as $item):
            $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        ?>
<!-- table row for one cart item -->
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <!-- display grand total -->
    <h2>Total: $<?php echo number_format($total, 2); ?></h2>

<!-- checkout form -->
    <form action="php/checkout_process.php" type="POST">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" required><br>
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" required><br>
        <label for="email">Email:</label>
        <input type="text" id="email" required><br>
        <label for="postCode">Postal Code:</label>
        <input type="text" id="postCode" required><br>
        <label for="creditCard">Credit Card Number (no spaces or dashes):</label>
        <input type="number" id="creditCard" pattern="[0-9]{14,16}" required><br>
        <label for="expDate">Expiration Date:</label>
        <input type="number" id="expDate" pattern="[0-9]{5}" required><br>
        <label for="cvv">CVV:</label>
        <input type="number" id="cvv" pattern="[0-9]{3}" required><br>
        <button type="submit" class="btn">Submit</button>
    </form>
    <?php endif; ?>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Shop-a-Lot. All rights reserved.</p>
    </div>
</footer>
</body>

</html>
