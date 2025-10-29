<?php 
include '../db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// ✅ Same logic preserved
if (isset($_POST['add_course'])) {
    $name = $_POST['course_name'];
    $code = $_POST['course_code'];
    $desc = $_POST['description'];
    $conn->query("INSERT INTO courses (course_name, course_code, description) VALUES ('$name', '$code', '$desc')");
}
if (isset($_GET['delete_course'])) {
    $id = $_GET['delete_course'];
    $conn->query("DELETE FROM courses WHERE id=$id");
}
if (isset($_GET['delete_student'])) {
    $id = $_GET['delete_student'];
    $conn->query("DELETE FROM student WHERE id=$id");
}
if (isset($_GET['unenroll'])) {
    $eid = $_GET['unenroll'];
    $conn->query("DELETE FROM enrollment_table WHERE id=$eid");
}
if (isset($_POST['assign_faculty'])) {
    $course_id = $_POST['course_id'];
    $faculty_name = $_POST['faculty_name'];
    $conn->query("UPDATE courses SET faculty_name='$faculty_name' WHERE id=$course_id");
}

$courses = $conn->query("SELECT * FROM courses");
$students = $conn->query("SELECT * FROM student");
$enrollments = $conn->query("SELECT * FROM enrollment_table");

$total_students = $conn->query("SELECT COUNT(*) AS total FROM student")->fetch_assoc()['total'];
$total_enrollments = $conn->query("SELECT COUNT(*) AS total FROM enrollment_table")->fetch_assoc()['total'];
$total_courses = $conn->query("SELECT COUNT(*) AS total FROM courses")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #141E30, #243B55);
            margin: 0;
            padding: 0;
            color: #f5f5f5;
        }
        header {
            background: #0b132b;
            text-align: center;
            padding: 30px 0;
            box-shadow: 0 3px 15px rgba(0,0,0,0.3);
        }
        header h1 {
            margin: 0;
            font-size: 36px;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px #00e0ff, 0 0 20px #00e0ff, 0 0 30px #00b4d8;
        }
        .stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin: 50px 0;
        }
        .stat-box {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            width: 220px;
            text-align: center;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }
        .stat-box:hover { transform: translateY(-5px); }
        .stat-box h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }
        .stat-box p {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
        }

        h2 {
            text-align: center;
            color: #00e0ff;
            margin-top: 40px;
        }

        form {
            background: rgba(255, 255, 255, 0.1);
            width: 80%;
            margin: 25px auto;
            border-radius: 12px;
            padding: 25px;
            backdrop-filter: blur(6px);
            box-shadow: 0 3px 15px rgba(0,0,0,0.3);
        }
        input, select {
            padding: 10px;
            margin: 8px;
            border-radius: 8px;
            border: none;
            outline: none;
            width: 250px;
        }
        button {
            background: #00b894;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background: #009874; }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
            color: #333;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background: #007bff;
            color: white;
            text-align: center;
        }
        tr:nth-child(even) { background: #f2f2f2; }
        tr:hover { background: #e0ecff; }

        .upload-btn {
            display: inline-block;
            background: #00a8ff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 10px;
            transition: 0.3s;
        }
        .upload-btn:hover { background: #007adf; }

        .logout {
            text-align: center;
            margin: 40px 0;
        }
        .logout a {
            color: white;
            background: #e84118;
            padding: 12px 22px;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .logout a:hover {
            background: #c23616;
        }
    </style>
</head>
<body>

<header>
    <h1>🛠️ ADMIN DASHBOARD</h1>
</header>

<div class="stats">
    <div class="stat-box"><h3>Total Students</h3><p><?= $total_students; ?></p></div>
    <div class="stat-box"><h3>Total Enrollments</h3><p><?= $total_enrollments; ?></p></div>
    <div class="stat-box"><h3>Total Courses</h3><p><?= $total_courses; ?></p></div>
</div>

<h2>Manage Courses</h2>
<form method="post">
    <input type="text" name="course_name" placeholder="Course Name" required>
    <input type="text" name="course_code" placeholder="Course Code" required>
    <input type="text" name="description" placeholder="Description" required>
    <button type="submit" name="add_course">Add Course</button>
</form>

<table>
    <tr><th>ID</th><th>Course</th><th>Code</th><th>Description</th><th>Faculty</th><th>Action</th></tr>
    <?php while ($row = $courses->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= htmlspecialchars($row['course_name']); ?></td>
        <td><?= htmlspecialchars($row['course_code']); ?></td>
        <td><?= htmlspecialchars($row['description']); ?></td>
        <td><?= htmlspecialchars($row['faculty_name'] ?? 'Unassigned'); ?></td>
        <td><a href="?delete_course=<?= $row['id']; ?>" style="color:red;">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<h2>Assign Faculty</h2>
<form method="post">
    <select name="course_id">
        <?php
        $course_data = $conn->query("SELECT id, course_name FROM courses");
        while ($c = $course_data->fetch_assoc()) {
            echo "<option value='{$c['id']}'>{$c['course_name']}</option>";
        }
        ?>
    </select>
    <input type="text" name="faculty_name" placeholder="Faculty Name" required>
    <button type="submit" name="assign_faculty">Assign</button>
</form>

<h2>Upload Lectures</h2>
<div style="text-align:center;">
    <a href="upload_lectures.php" class="upload-btn">📤 Go to Upload Page</a>
</div>

<h2>Students</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php while ($s = $students->fetch_assoc()): ?>
    <tr>
        <td><?= $s['id']; ?></td>
        <td><?= htmlspecialchars($s['name']); ?></td>
        <td><?= htmlspecialchars($s['email']); ?></td>
        <td><a href="?delete_student=<?= $s['id']; ?>" style="color:red;">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<h2>Enrollments</h2>
<table>
    <tr><th>ID</th><th>Student ID</th><th>Course ID</th><th>Action</th></tr>
    <?php while ($e = $enrollments->fetch_assoc()): ?>
    <tr>
        <td><?= $e['id']; ?></td>
        <td><?= $e['student_id']; ?></td>
        <td><?= $e['course_id']; ?></td>
        <td><a href="?unenroll=<?= $e['id']; ?>" style="color:red;">Unenroll</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<div class="logout">
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
