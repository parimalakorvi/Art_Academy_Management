<?php
session_start();
include 'config/db_connect.php';

$role = $_POST['role'];
$username = trim($_POST['username']);  // email or username
$password = $_POST['password'];

switch ($role) {
    case 'admin':
        $stmt = $conn->prepare("SELECT * FROM login WHERE username = ? AND role = 'admin'");
        break;
    case 'student':
        $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
        break;
    case 'instructor':
        $stmt = $conn->prepare("SELECT * FROM instructors WHERE email = ?");
        break;
    default:
        $_SESSION['login_error'] = "Invalid role.";
        header("Location: login.php");
        exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Use password_verify to check hashed passwords
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $role;

        // Set common keys
        $_SESSION['name'] = $user['name'] ?? $user['username'];
        $_SESSION['username'] = $user['email'] ?? $user['username'];

        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid password.";
    }
} else {
    $_SESSION['login_error'] = "User not found.";
}


header("Location: login.php");
exit();
