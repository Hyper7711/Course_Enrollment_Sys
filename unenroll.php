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

    $sql = "DELETE FROM Enrollment_Table WHERE student_id=$student_id AND course_id=$course_id";
    if ($conn->query($sql)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error removing course: " . $conn->error;
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
