  <?php
  session_start();
  require_once 'dbconnect.php'; // Make sure DB connection is available

  if (!isset($_SESSION['user_id'])) {
    header("Location: /WT&DBMSproject/SignUP.php");
    exit();
  }

  $user_id = $_SESSION['user_id'];

  // Fetch user name
  $user_name = '';
  $sql = "SELECT user_name FROM user WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $user_name = $row['user_name'];
  }
  $stmt->close();
  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Arena Futsal</title>
    <link rel="stylesheet" href="homepage.css">
    <style>

    </style>
  </head>

  <body>

    <!-- Header -->
    <div class="navbar">
      <h1>Arena Futsal</h1>
      <div class="nav-buttons">
        <a href="/WT&DBMSproject/bookings.php">Booking</a>
        <a href="/WT&DBMSproject/logout.php">Logout</a>
        <a href="/WT&DBMSproject/dashboard.php">Dashboard</a>
        <a href="/WT&DBMSproject/admin_login.php">Admin</a>
      </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h2>Welcome to Arena Futsal<?php echo $user_name ? ', ' . htmlspecialchars($user_name) : ''; ?>!</h2>

        <p>
          Experience premium futsal in a state-of-the-art indoor facility. <br />
          Clean turf, night lighting, and a friendly atmosphere – ideal for both casual and serious players.
        </p>
      </div>
    </section>

    <!-- About Section -->
    <section class="section">
      <h3>Why Choose Us?</h3>
      <p>
        Arena Futsal offers a private, high-quality indoor court for 1-hour sessions. Perfect for small groups or team
        practice.
        Reserve your slot, show up with your friends, and enjoy a hassle-free game in a secure, weatherproof environment.
      </p>
    </section>

    <!-- Gallery Section -->
    <section class="section">
      <h3>Gallery</h3>
      <div class="gallery">
        <img src="https://www.eye.co.jp/projects/examples/img/commerce/cood07/6_l.jpg" alt="Indoor Futsal Court">
        <img
          src="https://media.istockphoto.com/id/1776811840/photo/white-arena-soccer-artificial-turf-field-line-sport-and-recreation-concept.jpg?s=612x612&w=0&k=20&c=7RUVVWhoh7uR_mGZKm1Cc8nFmMp1srLPEoBbw8zLluA="
          alt="Futsal Turf Closeup">
        <img
          src="https://images.stockcake.com/public/9/b/1/9b1fb076-7b4c-4a4d-a16a-c6b3e8fe8d47_large/indoor-soccer-match-stockcake.jpg"
          alt="Team Playing Futsal">
      </div>
    </section>

    <footer>
      &copy; 2025 Arena Futsal. All rights reserved.
    </footer>



  </body>

  </html>