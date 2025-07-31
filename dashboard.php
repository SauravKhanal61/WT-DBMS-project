<?php
// Start the session to access session variables
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /WT&DBMSproject/SignUP.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Updated SQL query without payment_date and with correct JOIN
$sql = "SELECT 
            u.user_name, 
            p.payment_status, 
            s.slot_date, 
            s.slot_time 
        FROM user u
        JOIN booking b ON u.user_id = b.user_id
        JOIN payment p ON b.payment_id = p.payment_id
        JOIN slots s ON b.booking_id = s.booking_id
        WHERE u.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Dashboard - Arena Futsal</title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .navbar {
            background-color: #222;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .nav-buttons a {
            color: white;
            text-decoration: none;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-buttons a:hover {
            background-color: #555;
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: #222;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .dashboard-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
        }

        .bookings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .bookings-table th,
        .bookings-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .bookings-table th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .bookings-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .bookings-table tr:hover {
            background-color: #e9ecef;
        }

        .status-successful {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-failed {
            color: red;
            font-weight: bold;
        }

        .no-bookings {
            text-align: center;
            font-size: 1.1rem;
            color: #777;
            padding: 2rem;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>Arena Futsal</h1>
        <div class="nav-buttons">
            <a href="/WT&DBMSproject/homepage.html">Home</a>
            <a href="/WT&DBMSproject/bookings.php">Booking</a>
            <a href="/WT&DBMSproject/logout.php">Logout</a>
        </div>
    </div>

    <main class="dashboard-container">
        <h2>My Bookings</h2>
        <div class="table-responsive">
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Slot Date</th>
                        <th>Slot Time</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_class = '';
                            if ($row["payment_status"] == 'successful') {
                                $status_class = 'status-successful';
                            } elseif ($row["payment_status"] == 'pending') {
                                $status_class = 'status-pending';
                            } elseif ($row["payment_status"] == 'failed') {
                                $status_class = 'status-failed';
                            }

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["user_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars(date("F j, Y", strtotime($row["slot_date"]))) . "</td>";
                            echo "<td>" . htmlspecialchars(date("g:i A", strtotime($row["slot_time"]))) . "</td>";
                            echo "<td class='" . $status_class . "'>" . htmlspecialchars(ucfirst($row["payment_status"])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='no-bookings'>You have no bookings yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        &copy; 2025 Arena Futsal. All rights reserved.
    </footer>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
