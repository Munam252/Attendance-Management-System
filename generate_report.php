<?php

$servername = "localhost";
$username = "root";
$password = "munam253@";
$dbname = "attendance";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalPresents = 0;
$totalAbsents = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roll_no = $_POST['roll_no'];

    // Fetching student details from database
    $sql = "SELECT student_id, name, roll_no FROM student WHERE roll_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $roll_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Fetching attendance data for the student from database
        $student_id = $student['student_id'];
        $sql_attendance = "SELECT date, status FROM Attendance WHERE student_id = ?";
        $stmt_attendance = $conn->prepare($sql_attendance);
        $stmt_attendance->bind_param("i", $student_id);
        $stmt_attendance->execute();
        $attendance_result = $stmt_attendance->get_result();
    } else {
        echo "No student found with the given roll number.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .report-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-present {
            color: green;
        }
        .status-absent {
            color: red;
        }
        .summary {
            margin-top: 20px;
        }
        .buttons {
            margin-top: 20px;
            text-align: center;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin: 5px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="report-container" id="report">
        <h2>Generate Attendance Report</h2>
        <form method="post" action="generate_report.php">
            <label for="roll_no">Enter Roll Number:</label>
            <input type="text" id="roll_no" name="roll_no" required>
            <button type="submit">Generate Report</button>
        </form>

        <?php if (isset($student) && isset($attendance_result)): ?>
            <h3>Report for <?php echo htmlspecialchars($student['name']); ?> (Roll No: <?php echo htmlspecialchars($student['roll_no']); ?>)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $attendance_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td class="<?php echo $row['status'] == 'present' ? 'status-present' : 'status-absent'; ?>">
                                <?php 
                                    echo htmlspecialchars($row['status']); 
                                    if ($row['status'] == 'present') {
                                        $totalPresents++;
                                    } else {
                                        $totalAbsents++;
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="summary">
                <p>Total Presents: <?php echo $totalPresents; ?></p>
                <p>Total Absents: <?php echo $totalAbsents; ?></p>
            </div>
            <div class="buttons">
                <button id="downloadBtn">Download Report</button>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.getElementById('downloadBtn').addEventListener('click', function() {
            html2canvas(document.getElementById('report')).then(function(canvas) {
                var link = document.createElement('a');
                link.download = 'attendance_report.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        });
    </script>
</body>
</html>

