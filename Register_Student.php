<?php

$servername = "localhost";
$username = "root";
$password = "munam253@";
$dbname = "attendance";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and initialize user input
    $roll_no = $conn->real_escape_string($_POST['roll_no']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    // Checking roll_no, email, or username already exists in database or not
    $check_sql = "SELECT * FROM student WHERE roll_no = '$roll_no' OR email = '$email' OR username = '$username'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Displaying error that credidentials already exist
        echo "Roll number, email, or username already exists.";
    } else {
        // Insert data into student table and storing the password as plain text
        $insert_sql = "INSERT INTO student (roll_no, username, password, name, email) VALUES ('$roll_no', '$username', '$password', '$name', '$email')";

        if ($conn->query($insert_sql) === TRUE) {
            // Move to index.html after successful registration
            header("Location: index.html");
            exit(); 
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .register-container input[type="text"], 
        .register-container input[type="password"], 
        .register-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register Student</h2>
        <form action="" method="post">
            <label for="roll_no">Roll No:</label>
            <input type="text" id="roll_no" name="roll_no" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

