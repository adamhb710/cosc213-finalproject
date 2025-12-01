<?php
require_once 'php/config.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header("Location: index.php");
    exit();
}

// Fetch product details
$query = "SELECT * FROM products WHERE id = ? AND active = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Shop-A-Lot</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        .product-detail img {
            width: 100%;
            border-radius: 8px;
        }
        .product-info h1 {
            margin-bottom: 1rem;
        }
        .product-info .price {
            font-size: 2rem;
            color: #27ae60;
            font-weight: bold;
            margin: 1rem 0;
        }
        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Shop-a-Lot</h1>
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
            <a href="index.php" class="btn" style="margin-bottom: 1rem;">← Back to Products</a>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="product-detail">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         onerror="this.src='images/products/placeholder.jpg'">
                </div>
                
                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                    
                    <p style="color: <?php echo $product['stock'] > 0 ? 'green' : 'red'; ?>; font-weight: bold;">
                        <?php echo $product['stock'] > 0 ? "In Stock: {$product['stock']} available" : "Out of Stock"; ?>
                    </p>
                    
                    <div style="margin: 2rem 0;">
                        <h3>Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>
                    
                    <?php if ($product['stock'] > 0): ?>
                        <form action="php/add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            
                            <div class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="<?php echo $product['stock']; ?>"
                                       style="width: 100px;">
                            </div>
                            
                            <button type="submit" class="btn btn-success" style="font-size: 1.2rem; padding: 1rem 2rem;">
                                Add to Cart
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn" disabled style="background: #ccc; cursor: not-allowed;">
                            Out of Stock
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Shop-a-Lot. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
