<?php
// Start the session to destroy it
session_start();

// Clear all session data
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session completely
session_destroy();

// Redirect back to the home page
header("Location: ../index.php");
exit();
?>
