<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
    <form method="post">
        <input type="radio" name="payment_method" value="esewa" >Esewa
        <img src="esewa.jpg" alt="" width="250px" height="250px">
        <input type="radio" name="payment_method" value="counter">Counter
        <br><br>
        <input type="submit" name="Submit">
    </form>
    <?php
    include 'dbconnect.php';
    if (isset($_POST['Submit'])) {
        session_start();
        $user_id = $_SESSION['user_id'];
        $payment_method = $_POST['payment_method'] ?? '';
        if ($payment_method == 'counter') {
            $sql ="INSERT INTO payment (payment_method, amount, payment_status, user_id) VALUES ('counter', 1000, 'pending', '$user_id')";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                echo "<script>alert('Error in booking. Please try again.');</script>";
                exit();
            }
            $sql_payment = "Select payment_id from payment where user_id='$user_id' order by payment_id desc limit 1";
            $result_payment = mysqli_query($conn, $sql_payment);
            $row_payment = mysqli_fetch_assoc($result_payment);
            $payment_id = $row_payment['payment_id'];
            $sql_booking = "UPDATE booking SET payment_id='$payment_id' WHERE user_id='$user_id' ORDER BY booking_id DESC LIMIT 1";
            mysqli_query($conn, $sql_booking);
            if (!mysqli_query($conn, $sql_booking)) {
                echo "<script>alert('Error in booking. Please try again.');</script>";
                exit();
            }
            echo "<script>alert('Booked Successfully Pay later at the counter');window.location.href='http://localhost/WT&DBMSproject/homepage.php'</script>";
            exit();
        } else if ($payment_method == 'esewa') {
            $sql ="INSERT INTO payment (payment_method, amount, payment_status, user_id) VALUES ('esewa', 1000, 'pending', '$user_id')";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                echo "<script>alert('Error in booking. Please try again.');</script>";
                exit();
            }
            $sql_payment = "Select payment_id from payment where user_id='$user_id' order by payment_id desc limit 1";
            $result_payment = mysqli_query($conn, $sql_payment);
            $row_payment = mysqli_fetch_assoc($result_payment);
            $payment_id = $row_payment['payment_id'];
            $sql_booking = "UPDATE booking SET payment_id='$payment_id' WHERE user_id='$user_id' ORDER BY booking_id DESC LIMIT 1";
            mysqli_query($conn, $sql_booking);
            if (!mysqli_query($conn, $sql_booking)) {
                echo "<script>alert('Error in booking. Please try again.');</script>";
                exit();
            }
            echo "<script>alert('Booked Successfully Paid via esewa');window.location.href='http://localhost/WT&DBMSproject/submit-booking.php'</script>";
            exit();
        } else {
            echo "Please select a payment method.";
        }
    }
    ?>
</body>
</html>