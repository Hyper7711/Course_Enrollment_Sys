<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Validate course ID
if (!isset($_GET['course_id'])) {
    die("Invalid request");
}

$course_id = $_GET['course_id'];

// Fetch course details
$course = $conn->query("SELECT * FROM courses WHERE id = $course_id")->fetch_assoc();

if (!$course) {
    die("Course not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Gateway</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .payment-box {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }
        .payment-box:hover {
            transform: scale(1.02);
        }
        h2 {
            color: #2575fc;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        .amount {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }
        button {
            padding: 12px 25px;
            font-size: 16px;
            background-color: #28a745;
            border: none;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }
        button:hover {
            background-color: #218838;
        }
        footer {
            position: absolute;
            bottom: 20px;
            font-size: 13px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="payment-box">
        <h2>💳 Secure Payment</h2>
        <p><strong>Course:</strong> <?= htmlspecialchars($course['course_name']); ?></p>
        <p class="amount">Amount: ₹<?= htmlspecialchars($course['price'] ?? 999); ?></p>
        <form action="payment_success.php" method="POST">
            <input type="hidden" name="course_id" value="<?= $course_id; ?>">
            <button type="submit">Proceed to Pay</button>
        </form>
    </div>

    <footer>
        &copy; <?= date("Y"); ?> Course Enrollment System. All Rights Reserved.
    </footer>
</body>
</html>
