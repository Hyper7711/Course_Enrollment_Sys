<?php
include '../db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$message = "";

// Assign faculty
if (isset($_POST['assign_faculty'])) {
    $course_id = $_POST['course_id'];
    $faculty_name = trim($_POST['faculty_name']);

    if (!empty($faculty_name)) {
        $conn->query("UPDATE courses SET faculty_name='$faculty_name' WHERE id=$course_id");
        $message = "✅ Faculty assigned successfully!";
    } else {
        $message = "⚠️ Faculty name cannot be empty.";
    }
}

// Remove faculty
if (isset($_GET['remove_faculty'])) {
    $course_id = $_GET['remove_faculty'];
    $conn->query("UPDATE courses SET faculty_name=NULL WHERE id=$course_id");
    $message = "❌ Faculty removed successfully.";
}

$courses = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Faculty</title>
    <style>
        body { font-family: Arial; background: #f5f6fa; margin: 20px; }
        h1 { text-align: center; }
        form, table { background: #fff; margin: 20px auto; width: 70%; padding: 20px; border-radius: 10px; box-shadow: 0 0 8px #ccc; }
        select, input { padding: 8px; margin: 8px; width: 60%; }
        button { padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        table { border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }
        a { color: red; text-decoration: none; }
        p { text-align: center; }
    </style>
</head>
<body>
    <h1>Assign / Remove Faculty</h1>

    <?php if ($message) echo "<p><b>$message</b></p>"; ?>

    <form method="post">
        <select name="course_id" required>
            <option value="">Select Course</option>
            <?php while ($c = $courses->fetch_assoc()) {
                echo "<option value='{$c['id']}'>{$c['course_name']}</option>";
            } ?>
        </select><br>
        <input type="text" name="faculty_name" placeholder="Faculty Name" required><br>
        <button type="submit" name="assign_faculty">Assign Faculty</button>
    </form>

    <h2 style="text-align:center;">Current Assignments</h2>
    <table>
        <tr><th>ID</th><th>Course Name</th><th>Faculty Name</th><th>Action</th></tr>
        <?php
        $courses2 = $conn->query("SELECT * FROM courses");
        while ($row = $courses2->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['course_name']}</td>
                <td>" . ($row['faculty_name'] ?: '—') . "</td>
                <td><a href='?remove_faculty={$row['id']}'>Remove</a></td>
            </tr>";
        }
        ?>
    </table>

    <p><a href='admin_dashboard.php'>⬅ Back to Dashboard</a></p>
</body>
</html>
