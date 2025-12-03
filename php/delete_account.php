<?php
session_start();
require_once 'config.php';

$user = $_POST['user_id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = " . $user . ";");
$stmt->execute();

$_SESSION['message'] = "Account successfully deleted.";
header("Location: logout.php");
exit();
?>
