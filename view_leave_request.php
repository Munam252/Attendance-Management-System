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

// Initialize variables for leave request history
$leave_history = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch leave request history from the database
    $student_id = $student['student_id'];
    $sql = "SELECT leave_date, reason, status FROM LeaveRequest WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $leave_history[] = $row;
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
    <title>View Leave Request</title>
    <style>

        .leave-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .leave-table th, .leave-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .leave-table th {
            background-color: #f2f2f2;
        }
        .leave-table td.pending {
            color: orange;
        }
        .leave-table td.approved {
            color: green;
        }
        .leave-table td.rejected {
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
            <h2>View Leave Request</h2>
            <?php if ($student): ?>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                <p><strong>Roll No:</strong> <?php echo htmlspecialchars($student['Roll_no']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>

                <!-- Button to view leave status -->
                <form method="POST" action="view_leave_request.php">
                    <button type="submit">View Leave Status</button>
                </form>

                <!-- Display leave request history -->
                <?php if (!empty($leave_history)): ?>
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>Leave Date</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leave_history as $leave): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($leave['leave_date']); ?></td>
                                    <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                                    <td class="<?php echo strtolower(htmlspecialchars($leave['status'])); ?>">
                                        <?php echo htmlspecialchars($leave['status']); ?>
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

