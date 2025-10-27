<?php
include 'db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Check if already enrolled
    $check = $conn->query("SELECT * FROM Enrollment_Table WHERE student_id=$student_id AND course_id=$course_id");
    if ($check->num_rows > 0) {
        echo "You are already enrolled in this course!";
        echo "<br><a href='dashboard.php'>Go Back</a>";
        exit();
    }

    // Enroll
    $sql = "INSERT INTO Enrollment_Table (student_id, course_id) VALUES ($student_id, $course_id)";
    if ($conn->query($sql)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error enrolling: " . $conn->error;
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
