<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "munam253@"; // Replace with your MySQL password
$dbname = "mysql";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

$conn->close();
?>

