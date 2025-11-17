<?php
require_once 'php/config.php';

// Fetch all active products
$query = "SELECT * FROM products WHERE active = 1 ORDER BY created_at DESC";
$result = $conn->query($query);
?>
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
            <h1>🛒 My Shop</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <?php if (is_logged_in()): ?>
                        <li><a href="profile.php">Profile</a></li>
                        <?php if (is_admin()): ?>
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
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="products-grid">
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 onerror="this.src='images/products/placeholder.jpg'">
                            
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            
                            <p><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                            
                            <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                            
                            <p style="color: <?php echo $product['stock'] > 0 ? 'green' : 'red'; ?>;">
                                <?php echo $product['stock'] > 0 ? "In Stock: {$product['stock']}" : "Out of Stock"; ?>
                            </p>
                            
                            <a href="products.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                            
                            <?php if ($product['stock'] > 0): ?>
                                <a href="php/add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn btn-success">Add to Cart</a>
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
