<?php
$servername = "localhost";
$username = "root";
$password = "munam253@"; 
$dbname = "attendance";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$roll_no = "";
$student = null;
$loginRecords = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roll_no = $_POST['roll_no'];

    // Fetching student details using roll_no
    $sql = "SELECT student_id, name FROM student WHERE roll_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $roll_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Fetching login records for the corresponding student_id
        $sql = "SELECT login_date, login_time FROM LoginRecord WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student['student_id']);
        $stmt->execute();
        $loginRecords = $stmt->get_result();
    } else {
        echo "No student found with the provided roll number.";
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
    <title>View Login Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
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
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Login Records</h2>
        <form method="POST" action="">
            <label for="roll_no">Enter Roll No:</label>
            <input type="text" id="roll_no" name="roll_no" required>
            <input type="submit" value="View Records">
        </form>

        <?php if ($student): ?>
            <h3>Student Details</h3>
            <p><strong>Roll No:</strong> <?php echo htmlspecialchars($roll_no); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>

            <?php if ($loginRecords->num_rows > 0): ?>
                <h3>Login Records</h3>
                <table>
                    <tr>
                        <th>Login Date</th>
                        <th>Login Time</th>
                    </tr>
                    <?php while ($row = $loginRecords->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['login_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['login_time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No login records found for this student.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>

