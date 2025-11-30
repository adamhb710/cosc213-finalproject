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

// Get selected category from URL (?category=1)
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : null;

// Fetch all categories for filter buttons
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = $conn->query($categories_query);
$categories = [];
while ($cat = $categories_result->fetch_assoc()) {
    $categories[] = $cat;
}

// Build query based on selected category
if ($selected_category) {
    $query = "SELECT * FROM products WHERE active = 1 AND category_id = $selected_category ORDER BY created_at DESC";
} else {
    $query = "SELECT * FROM products WHERE active = 1 ORDER BY created_at DESC";
}
/** @var mysqli $conn */
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

<!-- Here is the actual webpage -->
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
        <h1>Shop-a-Lot</h1>
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

        <!-- Category filter buttons -->
        <div class="category-filters">
            <a href="index.php" class="filter-btn <?php echo !$selected_category ? 'active' : ''; ?>">
                All Products
            </a>
            <?php foreach ($categories as $category): ?>
                <a href="index.php?category=<?php echo $category['id']; ?>"
                   class="filter-btn <?php echo $selected_category == $category['id'] ? 'active' : ''; ?>">
                    <?php echo $category['name']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (count($products) > 0): ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>

                    <?php
                    // Splitting product details into variables
                    $id = $product['id'];
                    $name = $product['name'];
                    $description = $product['description'];
                    $price = $product['price'];
                    $stock = $product['stock'];

                    //If image URL is empty
                    $image = !empty($product['image_url']) ? $product['image_url'] : 'images/placeholder.jpg';
                    ?>

                    <div class="product-card">
                        <img src="<?php echo $image ?>"
                             alt="<?php echo $name; ?>"
                             onerror="this.src='images/placeholder.jpg'">

                        <h3><?php echo $name; ?></h3>
                        <p><?php echo substr($description, 0, 100); ?>...</p>

                        <div class="price">$<?php echo number_format($price, 2); ?></div>

                        <?php if ($stock > 0): ?>
                            <p style="color: green;">In Stock: <?php echo $stock ?></p>
                            <a href="products.php?id=<?php echo $id; ?>" class="btn">View Details</a>

                        <form action="php/add_to_cart.php" method="POST">
                           <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                           <input type="hidden" name="quantity" value="1">
                           <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>

                                
                        <?php else: ?>
                            <p style="color: red">Out of Stock :(</p>
                            <a href="products.php?id=<?php echo $id; ?>" class="btn">View Details</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No products available at the moment.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Shop-a-Lot. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
