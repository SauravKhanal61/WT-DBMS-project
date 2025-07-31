<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Futsal Session Booking</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #e7f0fd;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #2a2a2a;
    }

    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input,
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    input,button {
      width: 100%;
      margin-top: 25px;
      padding: 12px;
      font-size: 16px;
      color: white;
      background-color: #27ae60;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    input:hover {
      background-color: #1e7d46ff;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Book a Futsal Session</h1>
    <form method="post">


      <label for="date">Select Date</label>
      <input type="date" id="date" name="date" required />

      <label for="time">Time Slot</label>
      <select id="time" name="time" required>
        <option value="">-- Choose a time slot --</option>
        <option value="08:00-09:00">08:00 - 09:00</option>
        <option value="09:00-10:00">09:00 - 10:00</option>
        <option value="17:00-18:00">17:00 - 18:00</option>
        <option value="18:00-19:00">18:00 - 19:00</option>
        <option value="19:00-20:00">19:00 - 20:00</option>
        <option value="20:00-21:00">20:00 - 21:00</option>
      </select>
      <input type="submit" name="Submit" value="Book">
    </form>
    </div>
    <?php
    include 'dbconnect.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
      echo "<script>alert('Please log in to book a session.');window.location.href='http://localhost/WT&DBMSproject/SignUP.php';</script>";
      exit();
    }
    $user_id = $_SESSION['user_id'];
    // Handle form submission
    if (isset($_POST['Submit'])) {
      $date = $_POST['date'];
      $time = $_POST['time'];
      $sql = "Select * from slots where slot_date='$date' and slot_time='$time'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This time slot is already booked. Please choose another time.');</script>";
      } else {
        // Insert booking into booking table
        $sql = "INSERT INTO booking (user_id) VALUES ('$user_id')";
        if (!mysqli_query($conn, $sql)) {
          echo "<script>alert('Error in booking. Please try again.');</script>";
          exit();
        }
        // Insert booking into slots table
        $sql_booking = "Select booking_id from booking where user_id='$user_id' order by booking_id desc limit 1";
        $result_booking = mysqli_query($conn, $sql_booking);
        $row_booking = mysqli_fetch_assoc($result_booking);
        $booking_id = $row_booking['booking_id'];
        $insert_sql = "INSERT INTO slots (slot_date,slot_time,booking_id,availability) VALUES ('$date', '$time','$booking_id','booked')";
        if (mysqli_query($conn, $insert_sql)) {
          echo "<script>alert('Payment for booking');window.location.href='http://localhost/WT&DBMSproject/payment.php';</script>";
        } else {
          echo "<script>alert('Error in booking. Please try again.');</script>";
        }
      }
    }
    ?>
</body>

</html>