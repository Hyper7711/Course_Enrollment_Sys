<?php
include '../db.php';
session_start();

// ✅ Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// ✅ Fetch student name
$student_query = $conn->prepare("SELECT name FROM student WHERE id = ?");
$student_query->bind_param("i", $student_id);
$student_query->execute();
$student_result = $student_query->get_result();
$student = $student_result->fetch_assoc();
$student_name = $student ? $student['name'] : 'Student';

// ✅ Fetch all courses
$courses = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ABE2, #5563DE);
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 15px 0;
            text-align: center;
        }
        header h2 {
            color: #007bff;
            margin: 0;
        }
        .container {
            max-width: 1000px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        tr:hover {
            background: #e9f3ff;
        }
        a.button {
            background: #007bff;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s;
        }
        a.button:hover {
            background: #0056b3;
        }
        footer {
            text-align: center;
            color: white;
            margin: 30px 0;
        }
    </style>
</head>
<body>

<header>
    <h2>🎓 Welcome, <?php echo htmlspecialchars($student_name); ?></h2>
</header>

<div class="container">
    <h3>Available Courses</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $courses->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['course_name']); ?></td>
            <td>
                <a href="course_details.php?course_id=<?= $row['id']; ?>" class="button">View Details</a>
                <a href="../payment_gateway.php?course_id=<?= $row['id']; ?>" class="button" style="background:#28a745;">Pay & Enroll</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<footer>© <?php echo date("Y"); ?> Course Enrollment System | Student Portal</footer>
</body>
</html>
