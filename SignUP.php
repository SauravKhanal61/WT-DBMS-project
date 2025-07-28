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
    a{
      text-decoration: none;
    }
  </style>
</head>

<body>

  <!-- Sign Up Form -->
  <div class="container" id="signUpForm">
    <h1 class="form-title">Sign Up</h1>
    <!-- to be changed to post after backend -->
    <form method="get" action="/WT&DBMSproject/homepage.html">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="fName" id="fName" placeholder="First Name" required />
        <label for="fName">First Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="lName" id="lName" placeholder="Last Name" required />
        <label for="lName">Last Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required />
        <label for="email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <input type="submit" class="btn" value="Sign Up" />
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
    <form method="get" action="/WT&DBMSproject/homepage.html">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required />
        <label for="email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <p class="recover">
        <a href="#">Recover Password</a>
      </p>
      <input type="submit" class="btn" value="Sign In" />
    </form>

    <p class="or">----------or----------</p>
    <div class="links">
      <p class="alternate">Don't have an account yet?</p>
      <button onclick="toggleForm()">Sign Up</button>
    </div>
  </div>

</body>

</html>