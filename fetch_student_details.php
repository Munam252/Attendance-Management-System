<?php
function getStudentDetails($conn) {
    session_start();

    if (!isset($_SESSION['student_id'])) {
        header("Location: index.html");
        exit();
    }

    // Retrieve student details
    $student_id = $_SESSION['student_id'];
    $sql = "SELECT student_id, Roll_no, email, username, name FROM student WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        // case where student details are not found
        return null;
    }
}
?>

