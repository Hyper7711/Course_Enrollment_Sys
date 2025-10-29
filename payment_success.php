<?php
session_start();
include 'db.php';

if (!isset($_SESSION['student_id']) || !isset($_POST['course_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$course_id = $_POST['course_id'];

// Enroll student if not already enrolled
$check = $conn->query("SELECT * FROM enrollment_table WHERE student_id=$student_id AND course_id=$course_id");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO enrollment_table (student_id, course_id, status) VALUES ($student_id, $course_id, 'Enrolled')");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #00b09b, #96c93d);
            color: #fff;
            text-align: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        a {
            background-color: #fff;
            color: #00b09b;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        a:hover {
            background-color: #e7ffe7;
        }
    </style>
</head>
<body>
    <h1>✅ Payment Successful!</h1>
    <p>Congratulations! You’ve successfully enrolled in the course.</p>
    <a href="dashboard.php">Go to Dashboard</a>
</body>
</html>
