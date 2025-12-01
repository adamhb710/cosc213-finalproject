<?php
//Centralized PHP logic here
require_once 'php/config.php';

// Check if user is admin
require_login();
require_admin();

// This is for the welcome message
$admin_name = htmlspecialchars($_SESSION['first_name']);

// Success message if it exists
$success_message = '';
if (isset($_SESSION['message'])) {
    $success_message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch all products
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($query);

// Create products array and extract variables
$products = [];
if ($result && $result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        // Extract product details into individual variables
        $id = $product['id'];
        $name = htmlspecialchars($product['name']);
        $price = number_format($product['price'], 2);
        $stock = $product['stock'];
        $active = $product['active'];
        $image = !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : 'images/placeholder.jpg';

        // Status display
        $status_color = $active ? 'green' : 'red';
        $status_text = $active ? 'Active' : 'Inactive';

        // Store extracted values in array
        $products[] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'image' => $image,
                'status_color' => $status_color,
                'status_text' => $status_text
        ];
    }
}

// Check if we have any products
$has_products = count($products) > 0;

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Shop-a-Lot</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Shop-a-Lot - Admin</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Shop</a></li>
                    <li><a href="admin.php">Admin Panel</a></li>
                    <li><a href="php/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="admin-container">
            <!-- Admin Header with welcome message -->
            <div class="admin-header">
                <div>
                    <h2>Product Management</h2>
                    <p>Welcome, <?php echo $admin_name; ?>!</p>
                </div>
                <a href="admin_add_product.php" class="btn btn-success">+ Add New Product</a>
            </div>

            <!-- Display message if it exists -->
            <?php if ($success_message): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Products Table -->
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($has_products): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td>
                                        <img src="<?php echo $product['image']; ?>"
                                             alt="<?php echo $product['name']; ?>"
                                             class="product-image-small">
                                    </td>
                                    <td><strong><?php echo  $product['name']; ?></strong></td>
                                    <td>$<?php echo $product['price']; ?></td>
                                    <td><?php echo $product['stock']; ?></td>
                                    <td>
                                        <span style="color: <?php echo $product['status_color']; ?>;">
                                            <?php echo $product['status_text']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="admin_edit_product.php?id=<?php echo $product['id']; ?>"
                                               class="btn btn-primary btn-small">Edit</a>
                                            <a href="php/admin_delete_product.php?id=<?php echo $product['id']; ?>"
                                               class="btn btn-danger btn-small"
                                               onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    No products found. <a href="admin_add_product.php">Add your first product</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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

