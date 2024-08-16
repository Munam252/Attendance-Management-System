<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$servername = "localhost";
$username = "root"; // MySQL username
$password = "munam253@"; //MySQL password
$dbname = "attendance";

//  connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize user input
$roll_no = $_POST['roll_no'];
$password = $_POST['password'];

// SQL query
$sql = "SELECT * FROM student WHERE roll_no = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $roll_no, $password);
$stmt->execute();
$result = $stmt->get_result();

// Checking if user exists
if ($result->num_rows > 0) {
    // Successful login
    $student = $result->fetch_assoc();

    // store student data
    session_start();
    $_SESSION['student_id'] = $student['student_id'];
    $_SESSION['roll_no'] = $student['roll_no'];
    $_SESSION['email'] = $student['email'];
    $_SESSION['username'] = $student['username'];
    $_SESSION['name'] = $student['name'];

    // current date and time
    $login_date = date('Y-m-d');
    $login_time = date('H:i:s');

    // Insert into LoginRecord table
    $insert_sql = "INSERT INTO LoginRecord (student_id, login_date, login_time) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iss", $student['student_id'], $login_date, $login_time);
    $insert_stmt->execute();


    header("Location: student_dashboard.php");
    exit();
} else {
    // Failed login
    echo "Invalid Roll No or Password.";
}

// Closing statements and connection
$stmt->close();
$conn->close();
?>

