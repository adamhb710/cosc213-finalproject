<?php
require_once 'php/config.php';

// Check if user is admin
require_login();
require_admin();

// Fetch all products
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Shop-A-Lot</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .products-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #2c3e50;
            color: white;
            padding: 1rem;
            text-align: left;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .product-image-small {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Shop-A-Lot - Admin</h1>
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
            <div class="admin-header">
                <div>
                    <h2>Product Management</h2>
                    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
                </div>
                <a href="admin_add_product.php" class="btn btn-success">+ Add New Product</a>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

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
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($product = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                                             class="product-image-small"
                                             onerror="this.src='images/products/placeholder.jpg'">
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo $product['stock']; ?></td>
                                    <td>
                                        <span style="color: <?php echo $product['active'] ? 'green' : 'red'; ?>;">
                                            <?php echo $product['active'] ? 'Active' : 'Inactive'; ?>
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
                            <?php endwhile; ?>
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
            <p>&copy; <?php echo date('Y'); ?> My Shop. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
