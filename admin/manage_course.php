<?php
include '../db.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Add course
if (isset($_POST['add_course'])) {
    $name = trim($_POST['course_name']);
    $code = trim($_POST['course_code']);
    $desc = trim($_POST['description']);

    if ($name && $code) {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $code, $desc);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_courses.php");
    exit();
}

// Delete course
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM courses WHERE id=$id");
    header("Location: manage_courses.php");
    exit();
}

// Fetch all courses
$courses = $conn->query("SELECT * FROM courses ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Manage Courses</h1>

    <nav>
        <a href="admin_dashboard.php" class="btn">Dashboard</a>
        <a href="logout.php" class="btn btn-delete">Logout</a>
    </nav>

    <h2>Add New Course</h2>
    <form method="post">
        <input type="text" name="course_name" placeholder="Course Name" required>
        <input type="text" name="course_code" placeholder="Course Code" required>
        <textarea name="description" placeholder="Course Description"></textarea>
        <button type="submit" name="add_course">Add Course</button>
    </form>

    <h2>Existing Courses</h2>
    <div class="course-list">
        <?php while($row = $courses->fetch_assoc()) { ?>
            <div class="course-card">
                <h3><?php echo htmlspecialchars($row['course_name']); ?></h3>
                <p><?php echo htmlspecialchars($row['course_code']); ?></p>
                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-delete"
                   onclick="return confirm('Delete this course?')">Delete</a>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
