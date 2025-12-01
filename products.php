<?php
//Centralized PHP logic here
require_once 'php/config.php';

//Checking user status
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = !empty($_SESSION['is_admin']);

//Success Messages if they exist
$success_message = '';
if (isset($_SESSION['message'])) {
    $success_message = $_SESSION['message'];
    unset($_SESSION['message']); // Deleted from php session (still shows in HTML unless user refreshes)
}


// Fetch product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//If there is no valid ID, we redirect back to index
if($product_id <= 0){
    header('Location: index.php');
    exit();
}

//Otherwise, we get the product from the database with a prepared statement which prevents SQL injection.
$query = "SELECT * FROM products WHERE id = ? AND active = 1"; // here is the prepared statement
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

//If the product does NOT exist, we then redirect to home
if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

//Otherwise, we retrieve the product data as an array
$product = $result->fetch_assoc();

// Extract product details into variables for cleaner HTML
$id = $product['id'];
$name = $product['name'];
$description = $product['description'];
$price = $product['price'];
$stock = $product['stock'];
$image = !empty($product['image_url']) ? $product['image_url'] : 'images/placeholder.jpg';

// Checking to see if product is in stock
$in_stock = ($stock > 0);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?> - Shop-A-Lot</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Shop-a-Lot</h1>
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
            <!-- Back button to go back to products list -->
            <a href="index.php" class="btn" style="margin-bottom: 1rem;">← Back to Products</a>

            <!-- Success Message if it exists -->
            <?php if ($success_message): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Product Detail Section -->
            <div class="product-detail">
                <div class="product-image">
                    <img src="<?php echo $image; ?>"
                         alt="<?php echo $name; ?>"
                         onerror="this.src='images/placeholder.jpg'">
                </div>

                <!-- Product Information -->
                <div class="product-info">
                    <h1><?php echo $name; ?></h1>
                    
                    <div class="price">$<?php echo number_format($price, 2); ?></div>

                    <!-- Stock Status -->
                    <?php if ($in_stock): ?>
                        <p style="color: green; font-weight: bold;">
                            In Stock: <?php echo $stock; ?> available
                        </p>
                    <?php else: ?>
                        <p style="color: red; font-weight: bold;">Out of Stock</p>
                    <?php endif; ?>

                    <!-- Product Description -->
                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?php echo nl2br($description); ?></p> <!-- New line to break, allows for line breaks to be preserved. -->
                    </div>

                    <!-- Only add to cart if in stock -->
                    <?php if ($in_stock): ?>
                        <form action="php/add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            
                            <div class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="<?php echo $stock; ?>">
                            </div>

                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>

                    <!-- Disable the button if out of stock -->
                    <?php else: ?>
                        <button class="btn" disabled>Out of Stock</button>
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

