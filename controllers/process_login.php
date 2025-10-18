<?php
session_start();
require_once '../includes/db_connect.php'; // adjust path if needed

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit();
}

// Get username and password from form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
if ($username === '' || $password === '') {
    $_SESSION['error'] = "Username and password are required.";
    header("Location: ../index.php");
    exit();
}

// Prepare statement safely
$stmt = $conn->prepare("SELECT id, username, password FROM majoy WHERE username = ?");
if (!$stmt) {
    $_SESSION['error'] = "Database error: " . $conn->error;
    header("Location: ../index.php");
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Check user and password
if ($user) {
    // If passwords are hashed
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: ../index.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>
