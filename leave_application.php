<?php

$servername = "localhost";
$username = "root";
$password = "munam253@";
$dbname = "attendance";

// Creating connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// function to get student details
include 'fetch_student_details.php';
$student = getStudentDetails($conn);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_date = $_POST['leave_date'];
    $reason = $_POST['reason'];
    $student_id = $student['student_id'];
    $status = 'Pending';

    // Checking if the student has already applied for leave on the same date
    $check_sql = "SELECT * FROM LeaveRequest WHERE student_id = ? AND leave_date = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $student_id, $leave_date);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "You have already applied for leave on this date.";
    } else {
        // Insert the leave request into the database
        $sql = "INSERT INTO LeaveRequest (student_id, leave_date, reason, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $student_id, $leave_date, $reason, $status);

        if ($stmt->execute()) {
            echo "Leave request submitted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Application</title>
    <style>
        /* Include your styling here */
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="leave_application.php">Leave Application</a>
        <a href="view_attendance.php">View Attendance</a>
    </div>

    <button class="open-btn" onclick="openSidebar()">&#9776;</button>

    <div class="main-content">
        <div class="dashboard-container">
            <h2>Leave Application</h2>
            <?php if ($student): ?>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                <p><strong>Roll No:</strong> <?php echo htmlspecialchars($student['Roll_no']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>

                <!-- Leave Application Form -->
                <form method="POST" action="leave_application.php">
                    <label for="leave_date">Leave Date:</label><br>
                    <input type="date" id="leave_date" name="leave_date" required><br><br>
                    <label for="reason">Reason for Leave:</label><br>
                    <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>
                    <button type="submit">Submit</button>
                </form>
            <?php else: ?>
                <p>Error retrieving student details.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

