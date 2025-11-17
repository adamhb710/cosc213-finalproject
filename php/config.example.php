<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Change to your MySQL username
define('DB_PASS', '');               // Change to your MySQL password
define('DB_NAME', 'ecommerce_db');

// Start session
session_start();

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Helper functions...
// (rest of your config.php code)
?>
