<?php
// admin_login.php - Admin Login Page for a very basic college project

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hardcoded admin credentials (FOR BASIC PROJECT ONLY - DO NOT USE IN REAL APPS!)
$admin_username = "admin";
$admin_password = "123"; // In a real app, this would be hashed!

$message = '';
$message_type = '';

// Check if the login form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Very basic credential check
    if ($input_username === $admin_username && $input_password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin_username;
        $_SESSION['message'] = "Admin login successful!";
        $_SESSION['message_type'] = "success";
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        $message = "Invalid admin username or password.";
        $message_type = "error";
    }
}

// Get and clear messages from session (e.g., if redirected from admin_auth.php)
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }
        h1 {
            margin-bottom: 25px;
            color: #333;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .input-group input[type="text"],
        .input-group input[type="password"] {
            width: calc(100% - 22px); /* Account for padding and border */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .flash-messages {
            list-style: none;
            padding: 10px;
            margin: 10px auto;
            max-width: 350px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .flash-messages li {
            margin-bottom: 5px;
        }
        .flash-messages .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .flash-messages .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .flash-messages .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>

    <?php if (!empty($message)): ?>
        <ul class="flash-messages">
            <li class="<?php echo htmlspecialchars($message_type); ?>"><?php echo htmlspecialchars($message); ?></li>
        </ul>
    <?php endif; ?>

    <div class="login-container">
        <h1>Admin Login</h1>
        <form action="admin_login.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="admin_login">Login</button>
        </form>
    </div>
</body>
</html>
