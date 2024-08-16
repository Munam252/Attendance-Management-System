<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "munam253@";
$dbname = "attendance";

// Creating connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and initializing/storing the user input in a varibale
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to fetch admin details from DB to get login
$sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Check if admin exists or not
if ($result->num_rows > 0) {
    // if login is Successful then fetch admin details
    $admin = $result->fetch_assoc();
} else {
    // If Failed to login
    echo "Invalid Username or Password.";
    exit;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            overflow-x: hidden;
        }

        .dashboard-container {
            padding: 20px;
            margin: auto;
            text-align: center;
            max-width: 600px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 100px;
        }

        .dashboard-container h2 {
            margin-bottom: 20px;
        }

        .dashboard-container p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        /* Sidebar Styles */
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .closebtn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 36px;
            margin-left: 50px;
            color: white;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #333;
            color: white;
            padding: 10px 15px;
            border: none;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .openbtn:hover {
            background-color: #444;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
        <a href="Register_Student.php">Register Student</a>
        <a href="view_login_record.php">View Login Record</a>
        <a href="Manage_attendance.php">Manage Attendance</a>
        <a href="generate_report.php">Create Attendance Report</a>
        <a href="manage_leaves.php">Manage Leave Requests</a>
    </div>

    <!-- Main Content -->
    <button class="openbtn" onclick="openSidebar()">&#9776;</button>
    <div class="dashboard-container">
        <h2>Welcome, Admin</h2>
        <p><strong>Admin ID:</strong> <?php echo htmlspecialchars($admin['admin_id']); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
    </div>

    <script>
        function openSidebar() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeSidebar() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>

</body>
</html>

