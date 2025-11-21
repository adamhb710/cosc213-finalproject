<?php
//Centralized PHP logic here
require_once 'php/config.php';

//Checking user status
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = !empty($_SESSION['is_admin']);

//Success Messages if they exist
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['message'];
    unset($_SESSION['message']); // Deleted from php session (still shows in HTML unless user refreshes)
}

//Getting all products from our database, $conn is the connection to the sql
$query = "SELECT * FROM products WHERE active = 1 ORDER BY created_at DESC";
$result = $conn->query($query);

//Creating product array
$products = [];
if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) { // fetch_assoc gets one row at a time and adds each row to the product array.
        $products[] = $product;
    }
}

$conn->close();
?>

//Here is the actual webpage
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1></h1>Shop-a-Lot</h1>
        <!-- The contents of this navbar changes depending if anyone, including admin, is logged in -->
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="profile.php">Profile</a></li>
                    <?php if ($is_admin): ?>
                        <li><a href="admin.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="php/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <h2>Our Products</h2>

        <?php if ($success_message): ?>
            <div class="message success"> <?php echo $success_message; ?> </div>
        <?php endif; ?>

        <?php if (count($products) > 0): ?>
            <div class="products-grid">
                <?php foreach ($product as $products): ?>
                    <div class="product-card">
                        <img src="<?php echo $product['image_url']; ?>"
                             alt="<?php echo $product['name']; ?>"
                             onerror="this.src='images/products/placeholder.jpg'">

                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>

                        <p><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>

                        <div class="price">$<?php echo number_format($product['price'], 2); ?></div>

                        <p style="color: <?php echo $product['stock'] > 0 ? 'green' : 'red'; ?>;">
                            <?php echo $product['stock'] > 0 ? "In Stock: {$product['stock']}" : "Out of Stock"; ?>
                        </p>

                        <a href="products.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>

                        <?php if ($product['stock'] > 0): ?>
                            <a href="php/add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn btn-success">Add
                                to Cart</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No products available at the moment.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> My Shop. All rights reserved.</p>
    </div>
</footer>
</body>
</html>

<?php
$conn->close();
?>
