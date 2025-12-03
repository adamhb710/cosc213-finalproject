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
                <?php if($is_admin): ?>
                   <li><a href="admin.php">Admin</a><li>
                <?php endif; ?>
                <li><a href="php/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <?php if ($success_message): ?>
            <div class="message success"> <?php echo $success_message; ?> </div>
        <?php endif; ?>

        <h2>Hello, <?php echo $_SESSION['first_name']; ?></h2>

        <form action="php/edit_account.php" method="POST" class="form-container">
            <h2>Edit Account Information</h2>
            <p> Leave field blank if you are not changing it</p>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name"
                    placeholder="<?php echo $_SESSION['first_name']; ?>">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name"
                    placeholder="<?php echo $_SESSION['last_name']; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    placeholder="<?php echo $_SESSION['email']; ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone (optional)</label>
                <input type="text" id="phone" name="phone"
                    placeholder="<?php if(isset($_SESSION['phone']) {echo $_SESSION['phone'];} ?>">
            </div>

            <div class="form-group">
                <label for="password"> New Password</label>
                <input type="password" id="password" name="password"
                    placeholder="<?php echo $_SESSION['email']; ?>">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    placeholder="<?php echo $_SESSION['email']; ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Confirm Changes</button>
        </form>

        <form action="php/delete_account.php" method="POST">
            <h2>Delete Account</h2>
            <p class="text-danger">Warning! Deleting your account is permanent and can't be undone.</p>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Shop-a-Lot. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
