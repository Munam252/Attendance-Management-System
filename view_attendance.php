<?php

$servername = "localhost";
$username = "root";
$password = "munam253@";
$dbname = "attendance";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include the function to get student details
include 'fetch_student_details.php';
$student = getStudentDetails($conn);

// Initialize variables for attendance history
$attendance_history = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch attendance history from the database
    $student_id = $student['student_id'];
    $sql = "SELECT date, status FROM Attendance WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $attendance_history[] = $row;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <style>
        /* Include your styling here */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .attendance-table th, .attendance-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .attendance-table th {
            background-color: #f2f2f2;
        }
        .attendance-table td.present {
            color: green;
        }
        .attendance-table td.absent {
            color: red;
        }
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
            <h2>View Attendance</h2>
            <?php if ($student): ?>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                <p><strong>Roll No:</strong> <?php echo htmlspecialchars($student['Roll_no']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>

                <!-- Button to view attendance history -->
                <form method="POST" action="view_attendance.php">
                    <button type="submit">View Attendance History</button>
                </form>

                <!-- Display attendance history -->
                <?php if (!empty($attendance_history)): ?>
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance_history as $attendance): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($attendance['date']); ?></td>
                                    <td class="<?php echo strtolower(htmlspecialchars($attendance['status'])); ?>">
                                        <?php echo htmlspecialchars($attendance['status']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php else: ?>
                <p>Error retrieving student details.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function openSidebar() {
            document.querySelector('.sidebar').style.width = '250px';
        }

        function closeSidebar() {
            document.querySelector('.sidebar').style.width = '0';
        }
    </script>
</body>
</html>

