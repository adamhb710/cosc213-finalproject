<?php
session_start();
require_once 'config.php';

$user = $_SESSION['user_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$phone = $_POST['phone'];

if ($password != $confirm_password) {
    $_SESSION['message'] = "Passwords do not match.";
    header("Location: ../profile.php");
    exit();
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, password = ? "
    . "WHERE id = ?;");
$stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $password_hash, $user);
$stmt->execute();

$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['email'] = $email;
$_SESSION['password'] = $password;
$_SESSION['phone'] = $phone;

$_SESSION['message'] = "Information successfully changed.";
header("Location: ../profile.php");
exit();
?>
