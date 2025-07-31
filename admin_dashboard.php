<?php
// admin_dashboard.php - Admin Dashboard for a very basic college project

// Include the authentication check
require_once 'admin_auth.php';
// Include db_connect to get messages from session
require_once 'dbconnect.php';

$message = '';
$message_type = '';
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
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align items to the top */
            min-height: 100vh;
            margin: 0;
            padding-top: 50px; /* Space for messages */
            flex-direction: column;
        }
        .dashboard-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            margin: 20px auto; /* Center with margin */
            text-align: center;
        }
        h1 {
            margin-bottom: 30px;
            color: #333;
        }
        .admin-menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .admin-menu a {
            display: block;
            width: 200px;
            padding: 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .admin-menu a:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }
        .logout-link {
            margin-top: 30px;
            display: block;
            text-align: center;
        }
        .logout-link a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            border: 1px solid #dc3545;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .logout-link a:hover {
            background-color: #dc3545;
            color: white;
        }
        .flash-messages {
            list-style: none;
            padding: 10px;
            margin: 10px auto;
            max-width: 800px;
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

    <div class="dashboard-container">
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
        <div class="admin-menu">
            <a href="admin_manage_bookings.php">Manage Bookings & Payments</a>
            <a href="admin_manage_slots.php">Manage Slots</a>
            <!-- Add more admin links here as needed -->
        </div>
        <div class="logout-link">
            <a href="admin_logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
