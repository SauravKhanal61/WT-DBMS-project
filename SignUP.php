<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Authentication</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <script>
    function toggleForm() {
      document.getElementById("signUpForm").classList.toggle("hidden");
      document.getElementById("signInForm").classList.toggle("hidden");
    }
  </script>
  <style>
    .hidden {
      display: none;
    }

    a {
      text-decoration: none;
    }
  </style>
</head>

<body>

  <!-- Sign Up Form -->
  <div class="container" id="signUpForm">
    <h1 class="form-title">Sign Up</h1>
    <!-- to be changed to post after backend -->
    <form method="POST">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="Fname" id="fName" placeholder="First Name" required />
        <label for="fName">First Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="Lname" id="lName" placeholder="Last Name" required />
        <label for="lName">Last Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-phone-alt"></i>
        <input type="tel" name="Phone" id="phone" placeholder="Phone" required />
        <label for="phone">Phone</label>
      </div>

      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="Email" id="email" placeholder="Email" required />
        <label for="email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="Password" id="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="Cpassword" id="password" placeholder="Confirm" required />
        <label for="password">Confirm</label>
      </div>
      <input type="submit" class="btn" value="Sign Up" Name="Submit" />
    </form>
    <p class="or">----------or----------</p>
    <div class="links">
      <p class="alternate">Already have an account?</p>
      <button onclick="toggleForm()">Sign In</button>
    </div>
  </div>

  <!-- Sign In Form -->
  <div class="container hidden" id="signInForm">
    <h1 class="form-title">Sign In</h1>
    <!-- to be changed to post after backend -->
    <form method="post">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="Email" id="email" placeholder="Email" required />
        <label for="email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="Password" id="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <p class="recover">
        <a href="#">Recover Password</a>
      </p>
      <input type="submit" class="btn" value="Sign In" name="Login" />
    </form>

    <p class="or">----------or----------</p>
    <div class="links">
      <p class="alternate">Don't have an account yet?</p>
      <button onclick="toggleForm()">Sign Up</button>
    </div>
  </div>
  <?php
  include 'dbconnect.php';
  if (isset($_POST['Submit'])) {
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $Phone = $_POST['Phone'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $Cpassword = $_POST['Cpassword'];
    $Name = $Fname . " " . $Lname;
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
      echo "<script>alert('Invalid Email');</script>";
    } else if ($Password != $Cpassword) {
      echo "<script>alert('Passwords do not match');</script>";
    } else if (!preg_match("/^(98|97)[0-9]{8}$/", $Phone)) {
      echo "<script>alert('Invalid NTC or Ncell phone number');</script>";
    } else {
      $sql = "select * from user where email='$Email'";
      $result = mysqli_query($conn, $sql);
      $num = mysqli_num_rows($result);
      if ($num > 0) {
        echo "<script>alert('Email already taken');</script>";
        exit();
      } else {
        $Pass = md5($Password);
        // Insert into database
        $sql = "INSERT INTO user (user_name,phone_no, email, Password) VALUES ('$Name', '$Phone', '$Email', '$Pass')";
        $result = mysqli_query($conn, $sql);
        // Check if the query was successful
        if ($result) {
          echo "<script>alert('Registration Successful Please login');</script>";
        } else {
          echo "<script>alert('Registration Failed');</script>";
        }
      }
    }
  }
  if (isset($_POST['Login'])) {
    $Email = $_POST['Email'];
    $Password = md5($_POST['Password']);
    $sql = "Select * from user Where email='$Email' and Password = '$Password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
      $sql = "SELECT user_id FROM user WHERE email='$Email'";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $user_id = $row['user_id'];
      session_start();
      $_SESSION['user_id'] = $user_id;
      echo "<script>alert('Login Successful');window.location.href='http://localhost/WT&DBMSproject/homepage.php';</script>";
    } else {
      echo "<script>alert('Invalid Email or Password');</script>";
    }
  }
  ?>
</body>

</html>