<?php
//Centralized PHP logic here
require_once 'php/config.php';

// If already logged in, redirect to home
if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

// Error array for validation messages
$errors = array();

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize input
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation to check if the fields are filled and valid
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if email already exists in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email); // Type String
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email already registered";
        }
    }
    
    // If no errors, create account
    if (empty($errors)) {
        // Hash the password for security purposes
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        //Insert User into the database
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $password_hash, $first_name, $last_name, $phone);
        
        if ($stmt->execute()) {
            // Success - redirects user to login page
            $_SESSION['message'] = "Account created successfully! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}

//Preserving form values if the validation portion fails
$first_name_value = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
$last_name_value = isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
$email_value = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$phone_value = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Shop-a-Lot</title>
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
            <form method="POST" action="signup.php" class="form-container">
                <h2>Create Account</h2>

                <!-- Display error messages if any -->
                <?php if (!empty($errors)): ?>
                    <div class="message error">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- First Name Input -->
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" 
                           value="<?php echo $first_name_value; ?>"
                           required>
                </div>

                <!-- Last Name Input -->
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" 
                           value="<?php echo $last_name_value; ?>"
                           required>
                </div>

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo $email_value; ?>"
                           required>
                </div>

                <!-- Optional Phone input -->
                <div class="form-group">
                    <label for="phone">Phone (optional)</label>
                    <input type="text" id="phone" name="phone" 
                           value="<?php echo $phone_value; ?>">
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                    <small>Must be at least 6 characters</small>
                </div>

                <!-- Confirming Password Input -->
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>

                <!-- Login Link -->
                <p class="text-center" style="margin-top: 1rem;">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
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
