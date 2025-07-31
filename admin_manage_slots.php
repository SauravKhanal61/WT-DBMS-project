<?php
// admin_manage_slots.php - Manage Futsal Slots

require_once 'admin_auth.php'; // Check admin login
require_once 'dbconnect.php'; // Database connection

$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Fetch all slots
$slots = [];
$sql = "SELECT slot_id, slot_date, slot_time, availability, booking_id FROM slots ORDER BY slot_id asc";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slots[] = $row;
    }
}

$conn->close(); // Close connection after all operations
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Slots</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons button,
        .action-buttons select {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            margin-right: 5px;
        }

        .action-buttons .available {
            background-color: #28a745;
            color: white;
        }

        .action-buttons .booked {
            background-color: #ffc107;
            color: #333;
        }

        .action-buttons select {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
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

    <div class="container">
        <h1>Manage Futsal Slots</h1>

        <?php if (empty($slots)): ?>
            <p style="text-align: center;">No slots found. Please add some manually in phpMyAdmin or through a future "Add Slot" feature.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Slot ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slots as $slot): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($slot['slot_id']); ?></td>
                            <td><?php echo htmlspecialchars($slot['slot_date']); ?></td>
                            <td><?php echo htmlspecialchars(substr($slot['slot_time'], 0, 5)); ?></td>
                            <td>
                                <span class="<?php echo htmlspecialchars($slot['availability']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($slot['availability'])); ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <form action="admin_process_action.php" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="update_slot_availability">
                                    <input type="hidden" name="slot_id" value="<?php echo htmlspecialchars($slot['slot_id']); ?>">
                                    <select name="new_availability" onchange="this.form.submit()"
                                        class="<?php echo htmlspecialchars($slot['availability']); ?>">
                                        <option value="available" <?php echo ($slot['availability'] == 'available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="booked" <?php echo ($slot['availability'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
                                    </select>
                                </form>
                                <?php if ($slot['availability'] == 'booked' && $slot['booking_id']): ?>
                                    <span style="font-size: 0.8em; color: #666;"> (Booked by Booking ID: <?php echo htmlspecialchars($slot['booking_id']); ?>)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>