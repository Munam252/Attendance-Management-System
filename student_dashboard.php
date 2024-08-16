<?php
session_start();

// Checking if the student is logged in or not
if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            overflow-x: hidden;
        }

        /* Sidebar styles */
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
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .open-btn {
            font-size: 30px;
            cursor: pointer;
            background-color: #333;
            color: white;
            padding: 10px 15px;
            border: none;
            position: absolute;
            top: 15px;
            left: 15px;
        }

        .main-content {
            margin-left: 15px;
            padding: 20px;
            text-align: center;
        }

        .dashboard-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-top: 50px;
        }

        h2 {
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
    <script>
        function openSidebar() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeSidebar() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>
</head>
<body>

    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="leave_application.php">Leave Application</a>
        <a href="view_attendance.php">View Attendance</a>
        <a href="view_leave_request.php">View Leave Approval</a>
    </div>

    <!-- Open button -->
    <button class="open-btn" onclick="openSidebar()">&#9776;</button>

    <!-- Main content -->
    <div class="main-content">
        <div class="dashboard-container">
            <h2>Student Dashboard</h2>
            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($_SESSION['student_id']); ?></p>
            <p><strong>Roll No:</strong> <?php echo htmlspecialchars($_SESSION['roll_no']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        </div>
    </div>

</body>
</html>

