<?php
include '../db.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Delete student
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM student WHERE id=$id");
    header("Location: manage_students.php");
    exit();
}

// Unenroll student from a course
if (isset($_GET['unenroll_id'])) {
    $enroll_id = $_GET['unenroll_id'];
    $conn->query("DELETE FROM enrollment_table WHERE id=$enroll_id");
    header("Location: manage_students.php");
    exit();
}

// Fetch all students with enrollments
$students = $conn->query("
    SELECT s.id AS student_id, s.name, s.email, e.id AS enroll_id, c.course_name
    FROM student s
    LEFT JOIN enrollment_table e ON s.id = e.student_id
    LEFT JOIN courses c ON e.course_id = c.id
    ORDER BY s.id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Manage Students</h1>

    <nav>
        <a href="admin_dashboard.php" class="btn">Dashboard</a>
        <a href="logout.php" class="btn btn-delete">Logout</a>
    </nav>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr style="background:#34495e;color:white;">
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Enrolled Course</th>
            <th>Actions</th>
        </tr>

        <?php
        $current = -1;
        while ($row = $students->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>" . ($row['course_name'] ?: '—') . "</td>";
            echo "<td>";
            echo "<a href='?delete_id={$row['student_id']}' class='btn btn-delete' onclick='return confirm(\"Delete student?\")'>Delete</a> ";
            if ($row['enroll_id']) {
                echo "<a href='?unenroll_id={$row['enroll_id']}' class='btn btn-profile' onclick='return confirm(\"Unenroll this student?\")'>Unenroll</a>";
            }
            echo "</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
