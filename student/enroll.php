<?php
include '../db.php';
session_start();

// ✅ Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit();
}

// ✅ Check if course ID is provided
if (isset($_GET['course_id'])) {
    $student_id = $_SESSION['student_id'];
    $course_id = $_GET['course_id'];

    // ✅ Check if already enrolled
    $check = $conn->query("SELECT * FROM enrollment_table WHERE student_id='$student_id' AND course_id='$course_id'");
    if ($check->num_rows > 0) {
        echo "<script>alert('You are already enrolled in this course!'); window.location.href='dashboard.php';</script>";
        exit();
    }

    // ✅ Insert enrollment
    $query = "INSERT INTO enrollment_table (student_id, course_id) VALUES ('$student_id', '$course_id')";
    if ($conn->query($query)) {
        // If you want to redirect to payment gateway:
        // header("Location: ../payment_gateway.php?course_id=$course_id");
        
        echo "<script>alert('Enrollment successful!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error during enrollment. Please try again.'); window.location.href='dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid course ID!'); window.location.href='dashboard.php';</script>";
}
?>
