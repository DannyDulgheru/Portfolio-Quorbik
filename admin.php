<?php 
include 'config.php'; 
session_start(); 

// Check if the user is already logged in
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    // If logged in, redirect to the dashboard
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 text-white p-6">
    <h1 class="text-center text-3xl mb-6">Admin Login</h1>
    <?php if (isset($_GET['logged_out']) && $_GET['logged_out'] == 1): ?>
        <p class="text-green-500 text-center mb-4">You have been logged out successfully.</p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" class="max-w-md mx-auto bg-gray-900 p-4 rounded">
        <label class="block mb-2">Username</label>
        <input type="text" name="username" class="w-full mb-4 p-2 rounded bg-gray-700">
        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="w-full mb-4 p-2 rounded bg-gray-700">
        <button type="submit" class="w-full bg-blue-500 p-2 rounded text-white">Login</button>
    </form>
</body>
</html>
