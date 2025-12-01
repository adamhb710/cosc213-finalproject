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

//add code to edit user information

//add code to delete user

//add code to change currency (if we wanna)
?>

<!-- Here is the actual webpage -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop-a-Lot - Profile</title>
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
                <li><a href="php/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <h2>Hello, <?php echo $_SESSION('user_id'); ?> </h2>
        <?php if ($success_message): ?>
            <div class="message success"> <?php echo $success_message; ?> </div>
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
