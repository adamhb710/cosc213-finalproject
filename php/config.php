<?php
// DATABASE SETUP
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce_db');

// Start session
session_start();

// Create database connection
/** @var mysqli $conn */
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");

// SECURITY FUNCTIONS
function clean_input($data) {
    global $conn;
    $data = trim($data);                    // removes whitespace
    $data = stripslashes($data);            // removes backslashes
    $data = htmlspecialchars($data);        // prevents XSS
    return $conn->real_escape_string($data);// prevents SQL injection
}


// USER CHECK FUNCTIONS

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
