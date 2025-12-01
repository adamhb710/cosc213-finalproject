<?php
//Centralized PHP logic here
require_once 'php/config.php';

// If already logged in, redirect to home
if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

// Error message variable
$error = "";

// Success message if it exists
$success_message = "";
if (isset($_SESSION['message'])) {
    $success_message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];

    // Check to see if both fields are filled
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } else {
        // Query database for user with a prepared statement
        $stmt = $conn->prepare("SELECT id, email, password, first_name, last_name, is_admin FROM users WHERE email = ?");
        $stmt->bind_param("s", $email); // Type String is being bound
        $stmt->execute();
        $result = $stmt->get_result();

        // Checks to see if the user exists
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password hash
            if (password_verify($password, $user['password'])) {
                // Login successful - set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['is_admin'] = $user['is_admin'];

                // Redirect to home or admin panel based on user
                if ($user['is_admin']) {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    }
}

//Preserves an email input if the form was submitted
$email_value = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shop-a-Lot</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Shop-a-Lot</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <form method="POST" action="login.php" class="form-container">
            <h2>Login</h2>

            <!-- Display success message if it exists -->
            <?php if ($success_message): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Display error message if it exists -->
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Email Input Field -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?php echo $email_value; ?>"
                       required autofocus>
            </div>

            <!-- Password Input Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">Login</button>

            <!-- Sign Up Link -->
            <p class="text-center" style="margin-top: 1rem;">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </p>

            <!-- These are test accounts for the purpose of development -->
            <div style="margin-top: 2rem; padding: 1rem; background: #f0f0f0; border-radius: 4px;">
                <p style="margin: 0; font-size: 0.9rem;"><strong>Test Accounts:</strong></p>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.85rem;">
                    Admin: admin@shop.com / admin123 <br>
                    User: user@shop.com / user123
                </p>
            </div>
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
