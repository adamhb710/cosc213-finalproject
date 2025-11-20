<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'ecommerce_user');
define('DB_PASS', 'ecommerce123');
define('DB_NAME', 'ecommerce_db');

// Start session
session_start();

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Helper function to sanitize input
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// Redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// Redirect if not admin
function require_admin() {
    if (!is_admin()) {
        header("Location: index.php");
        exit();
    }
}
?>
