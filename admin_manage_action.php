<?php
// admin_manage_bookings.php - Admin page to manage bookings (delete)

require_once 'admin_auth_check.php';
require_once 'dbconnect.php';

$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

$bookings = [];
// Join with user and slots to get more meaningful data
$sql = "SELECT b.booking_id, u.user_name, u.email, s.slot_time, s.slot_date, s.availability, p.payment_status, p.amount
        FROM booking b
        LEFT JOIN user u ON b.user_id = u.user_id
        LEFT JOIN slots s ON b.booking_id = s.booking_id -- Assuming booking_id in slots links to the booking
        LEFT JOIN payment p ON b.payment_id = p.payment_id
        ORDER BY b.booking_date DESC, s.slot_time DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e7f0fd; margin: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
        .container { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); width: 100%; max-width: 1000px; margin-top: 20px; }
        h1 { color: #2a2a2a; text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .delete-btn { background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; }
        .delete-btn:hover { background-color: #c82333; }
        .back-btn { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px; transition: background-color 0.3s; }
        .back-btn:hover { background-color: #5a6268; }
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
        <h1>Manage Bookings</h1>
        <?php if (!empty($bookings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>User Email</th>
                        <th>Booking Date</th>
                        <th>Slot Time</th>
                        <th>Slot Availability</th>
                        <th>Payment Status</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['user_name'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['email'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['slot_time'] ? substr($booking['slot_time'], 0, 5) : 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['availability'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['payment_status'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['amount'] ? 'Rs.' . number_format($booking['amount'], 2) : 'N/A'); ?></td>
                            <td>
                                <form action="process_admin_action.php" method="post" onsubmit="return confirm('Are you sure you want to delete this booking? This will also free up the slot.');">
                                    <input type="hidden" name="action" value="delete_booking">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                    <input type="hidden" name="slot_id" value="<?php echo htmlspecialchars($booking['slot_id']); ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
