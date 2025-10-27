<?php
include 'db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student name
$stmt = $conn->prepare("SELECT name FROM student WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

// Fetch all courses
$courses = $conn->query("SELECT * FROM Courses");

// Fetch enrolled courses
$enrolled = $conn->query("
    SELECT c.id AS course_id, c.course_name, c.course_code 
    FROM Enrollment_Table e
    JOIN Courses c ON e.course_id = c.id
    WHERE e.student_id = $student_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Course Enrollment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($student_name); ?>!</h1>

    <nav>
        <a href="profile.php" class="btn btn-profile">My Profile</a>
        <a href="logout.php" class="btn">Logout</a>
    </nav>

    <h2>Available Courses</h2>
    <div class="course-list">
        <?php while($row = $courses->fetch_assoc()) { ?>
            <div class="course-card">
                <h3><?php echo htmlspecialchars($row['course_name']); ?></h3>
                <p><?php echo htmlspecialchars($row['course_code']); ?></p>
                <a href="enroll.php?course_id=<?php echo $row['id']; ?>" class="btn btn-enroll">Enroll</a>
            </div>
        <?php } ?>
    </div>

    <h2>Your Enrollments</h2>
    <div class="enrolled-list">
        <?php while($row = $enrolled->fetch_assoc()) { ?>
            <div class="course-card">
                <h3><?php echo htmlspecialchars($row['course_name']); ?></h3>
                <p><?php echo htmlspecialchars($row['course_code']); ?></p>
                <a href="unenroll.php?course_id=<?php echo $row['course_id']; ?>" class="btn btn-delete"
                   onclick="return confirm('Are you sure you want to remove this course?');">Delete</a>
            </div>
        <?php } ?>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Course Enrollment System. All rights reserved.</p>
</footer>
</body>
</html>
