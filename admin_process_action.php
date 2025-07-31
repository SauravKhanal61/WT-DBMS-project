<?php
// admin_process_action.php - Central script to process admin actions

require_once 'admin_auth.php'; // Check admin login
require_once 'dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        switch ($action) {
            case 'update_payment_status':
                $payment_id = $_POST['payment_id'];
                $new_status = $_POST['new_status'];

                $stmt = $conn->prepare("UPDATE payment SET payment_status = ? WHERE payment_id = ?");
                $stmt->bind_param("si", $new_status, $payment_id);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Payment status updated successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    throw new Exception("Error updating payment status: " . $stmt->error);
                }
                $stmt->close();
                header("Location: admin_manage_bookings.php");
                exit();

            case 'remove_booking':
                $booking_id = $_POST['booking_id'];
                $slot_id = $_POST['slot_id']; // Get slot_id to free it up

                // Start a transaction for atomicity
                $conn->begin_transaction();

                // 1. Delete associated payment (if any)
                $stmt = $conn->prepare("DELETE FROM payment WHERE booking_id = ?");
                $stmt->bind_param("i", $booking_id);
                $stmt->execute(); // Execute even if no payment exists
                $stmt->close();

                // 2. Free up the slot by setting availability to 'available' and booking_id to NULL
                $stmt = $conn->prepare("UPDATE slots SET availability = 'available', booking_id = NULL WHERE slot_id = ?");
                $stmt->bind_param("i", $slot_id);
                if (!$stmt->execute()) {
                    throw new Exception("Error freeing up slot: " . $stmt->error);
                }
                $stmt->close();

                // 3. Delete the booking itself
                $stmt = $conn->prepare("DELETE FROM booking WHERE booking_id = ?");
                $stmt->bind_param("i", $booking_id);
                if ($stmt->execute()) {
                    $conn->commit(); // Commit transaction if all successful
                    $_SESSION['message'] = "Booking and associated payment/slot freed successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    throw new Exception("Error deleting booking: " . $stmt->error);
                }
                $stmt->close();
                header("Location: admin_manage_bookings.php");
                exit();

            case 'update_slot_availability':
                $slot_id = $_POST['slot_id'];
                $new_availability = $_POST['new_availability'];

                // If setting to 'available', ensure booking_id is NULL
                $booking_id_null = NULL; // PHP's NULL maps to SQL NULL
                $stmt = $conn->prepare("UPDATE slots SET availability = ?, booking_id = ? WHERE slot_id = ?");
                $stmt->bind_param("sii", $new_availability, $booking_id_null, $slot_id);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Slot availability updated successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    throw new Exception("Error updating slot availability: " . $stmt->error);
                }
                $stmt->close();
                header("Location: admin_manage_slots.php");
                exit();

            default:
                $_SESSION['message'] = "Unknown admin action.";
                $_SESSION['message_type'] = "error";
                header("Location: admin_dashboard.php");
                exit();
        }
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        $_SESSION['message'] = "Action failed: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        // Redirect back to the page that initiated the action
        if ($action == 'update_payment_status' || $action == 'remove_booking') {
            header("Location: admin_manage_bookings.php");
        } elseif ($action == 'update_slot_availability') {
            header("Location: admin_manage_slots.php");
        } else {
            header("Location: admin_dashboard.php");
        }
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "error";
    header("Location: admin_dashboard.php");
    exit();
}

$conn->close();
?>
