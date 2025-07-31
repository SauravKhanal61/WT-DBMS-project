<?php
// admin_manage_bookings.php - Manage Bookings and Payments

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

// Fetch all bookings with associated user, slot and payment info
$bookings = [];
$sql = "SELECT b.booking_id, s.slot_id, s.slot_date, u.user_name, s.slot_time, 
               p.amount, p.payment_method, p.payment_status, p.payment_id
        FROM booking b
        JOIN user u ON b.user_id = u.user_id
        JOIN slots s ON b.booking_id = s.booking_id -- slots holds slot_date now
        LEFT JOIN payment p ON b.payment_id = p.payment_id
        ORDER BY s.slot_date DESC, s.slot_time DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

$conn->close(); // Close connection after all operations
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings & Payments</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-buttons button, .action-buttons select {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            margin-right: 5px;
        }
        .action-buttons .status-pending { background-color: #ffc107; color: #333; }
        .action-buttons .status-successful { background-color: #28a745; color: white; }
        .action-buttons .status-failed { background-color: #dc3545; color: white; }
        .action-buttons .remove-btn { background-color: #dc3545; color: white; }
        .action-buttons .remove-btn:hover { background-color: #c82333; }
        .action-buttons select { background-color: #f8f9fa; border: 1px solid #ced4da; }
        .back-link { display: block; text-align: center; margin-top: 30px; }
        .back-link a { color: #007bff; text-decoration: none; font-weight: bold; }
        .flash-messages { list-style: none; padding: 10px; margin: 10px auto; max-width: 1000px; border-radius: 5px; text-align: center; font-weight: bold; }
        .flash-messages li { margin-bottom: 5px; }
        .flash-messages .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash-messages .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .flash-messages .info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body>

    <?php if (!empty($message)): ?>
        <ul class="flash-messages">
            <li class="<?php echo htmlspecialchars($message_type); ?>"><?php echo htmlspecialchars($message); ?></li>
        </ul>
    <?php endif; ?>

    <div class="container">
        <h1>Manage Bookings & Payments</h1>

        <?php if (empty($bookings)): ?>
            <p style="text-align: center;">No bookings found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>User</th>
                        <th>Amount (Rs.)</th>
                        <th>Method</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['slot_date']); ?></td>
                        <td><?php echo htmlspecialchars(substr($booking['slot_time'], 0, 5)); ?></td>
                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['amount'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($booking['payment_method'] ?? 'N/A'); ?></td>
                        <td>
                            <form action="admin_process_action.php" method="post" style="display:inline;">
                                <input type="hidden" name="action" value="update_payment_status">
                                <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($booking['payment_id']); ?>">
                                <select name="new_status" onchange="this.form.submit()"
                                    class="status-<?php echo htmlspecialchars($booking['payment_status'] ?? 'pending'); ?>">
                                    <option value="pending" <?php echo (isset($booking['payment_status']) && $booking['payment_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="successful" <?php echo (isset($booking['payment_status']) && $booking['payment_status'] == 'successful') ? 'selected' : ''; ?>>Successful</option>
                                    <option value="failed" <?php echo (isset($booking['payment_status']) && $booking['payment_status'] == 'failed') ? 'selected' : ''; ?>>Failed</option>
                                </select>
                            </form>
                        </td>
                        <td class="action-buttons">
                            <form action="admin_process_action.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this booking? This will also free up the slot.');">
                                <input type="hidden" name="action" value="remove_booking">
                                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                <input type="hidden" name="slot_id" value="<?php echo htmlspecialchars($booking['slot_id']); ?>">
                                <button type="submit" class="remove-btn">Remove Booking</button>
                            </form>
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
