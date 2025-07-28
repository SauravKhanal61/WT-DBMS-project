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

    button {
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

    button:hover {
      background-color: #219150;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Book a Futsal Session</h1>
    <form action="/WT&DBMSproject/submit-booking.php" method="get">


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
      <button type="submit">Book Now</button>
    </form>
  </div>
</body>

</html>