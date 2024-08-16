<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "munam253@";
$dbname = "attendance";


$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$roll_no = '';
$attendanceRecords = [];

// Handle form submission to fetch attendance data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['roll_no'])) {
    $roll_no = $_POST['roll_no'];

    // Fetch student ID for the entered roll number
    $stmt = $conn->prepare("SELECT student_id, name FROM student WHERE roll_no = ?");
    $stmt->bind_param("s", $roll_no);
    $stmt->execute();
    $stmt->bind_result($student_id, $student_name);
    $stmt->fetch();
    $stmt->close();

    if ($student_id) {
        // Fetch attendance records for the student ID
        $stmt = $conn->prepare("SELECT date, status, attendance_id FROM Attendance WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $attendanceRecords[] = $row;
        }

        $stmt->close();
    } else {
        echo "No student found with the entered Roll No.";
    }
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance_id'])) {
    $attendance_id = $_POST['attendance_id'];
    $new_status = $_POST['status'];

    // Update the attendance status in the database
    $stmt = $conn->prepare("UPDATE Attendance SET status = ? WHERE attendance_id = ?");
    $stmt->bind_param("si", $new_status, $attendance_id);
    $stmt->execute();
    $stmt->close();

    // Refresh the page to show updated data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .present {
            background-color: #d4edda;
        }
        .absent {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>
    <h2>Manage Attendance</h2>
    <form method="POST" action="">
        <label for="roll_no">Enter Roll No:</label>
        <input type="text" id="roll_no" name="roll_no" required>
        <button type="submit">View Attendance</button>
    </form>

    <?php if (!empty($attendanceRecords)): ?>
        <h3>Attendance for Roll No: <?php echo htmlspecialchars($roll_no); ?> (<?php echo htmlspecialchars($student_name); ?>)</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($attendanceRecords as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['date']); ?></td>
                    <td class="<?php echo $record['status'] == 'Present' ? 'present' : 'absent'; ?>">
                        <?php echo htmlspecialchars($record['status']); ?>
                    </td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="attendance_id" value="<?php echo $record['attendance_id']; ?>">
                            <button type="submit" name="status" value="Present">Mark Present</button>
                            <button type="submit" name="status" value="Absent">Mark Absent</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>

