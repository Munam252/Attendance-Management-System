<?php
$servername = "localhost";
$username = "root";
$password = "munam253@";
$dbname = "attendance";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Approve or Reject action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $leave_id = $_POST['leave_id'];
    $action = $_POST['action'];

    // Update the status based on the action
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    $sql = "UPDATE LeaveRequest SET status = ? WHERE leave_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $leave_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch pending leave requests
$sql = "SELECT student.name, student.roll_no, LeaveRequest.leave_id, LeaveRequest.leave_date, LeaveRequest.reason, LeaveRequest.status
        FROM LeaveRequest
        JOIN student ON LeaveRequest.student_id = student.student_id
        WHERE LeaveRequest.status = 'pending'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            cursor: pointer;
            color: #fff;
        }
        .approve-btn {
            background-color: #28a745;
        }
        .reject-btn {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <h2>Pending Leave Requests</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Roll Number</th>
                <th>Leave Date</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['leave_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                    <td class="actions">
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="leave_id" value="<?php echo $row['leave_id']; ?>">
                            <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="leave_id" value="<?php echo $row['leave_id']; ?>">
                            <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No pending leave requests found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>



