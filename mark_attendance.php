<?php

$servername = "localhost";
$username = "root";
$password = "munam253@";
$dbname = "attendance";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//  function to get student details
include 'fetch_student_details.php';
$student = getStudentDetails($conn);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $student_id = $student['student_id'];
    $date = date("Y-m-d");

    // Check if the student has already marked attendance today
    $checkSql = "SELECT * FROM Attendance WHERE student_id = ? AND date = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $student_id, $date);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "You have already marked your attendance today.";
    } else {
        // Insert the attendance record in database
        $sql = "INSERT INTO Attendance (student_id, date, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $student_id, $date, $status);

        if ($stmt->execute()) {
            echo "Attendance marked successfully as " . htmlspecialchars($status) . ".";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
    $checkStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <style>

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
            <h2>Mark Attendance</h2>
            <?php if ($student): ?>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                <p><strong>Roll No:</strong> <?php echo htmlspecialchars($student['Roll_no']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>

                <!-- Attendance Form -->
                <form method="POST" action="mark_attendance.php">
                    <label for="status">Mark Attendance:</label><br>
                    <input type="radio" id="present" name="status" value="Present" required>
                    <label for="present">Present</label><br>
                    <input type="radio" id="absent" name="status" value="Absent" required>
                    <label for="absent">Absent</label><br><br>
                    <button type="submit">Submit</button>
                </form>
            <?php else: ?>
                <p>Error retrieving student details.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

