<?php
include 'db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$stmt = $conn->prepare("SELECT name FROM student WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

$enrolled = $conn->query("
    SELECT c.id AS course_id, c.course_name, c.course_code, c.description
    FROM enrollment_table e
    JOIN courses c ON e.course_id = c.id
    WHERE e.student_id = $student_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Courses</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #5563DE, #74ABE2);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            background: #fff;
            margin: 50px auto;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 40px;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 10px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        nav {
            text-align: center;
            margin: 20px 0;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            background: #007bff;
            color: white;
            margin: 0 8px;
            transition: 0.3s;
        }
        .btn:hover { background: #0056b3; }
        .btn.logout { background: #dc3545; }
        .btn.logout:hover { background: #b02a37; }

        .course-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 30px;
        }
        .course-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            width: 280px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
        .course-card h3 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .no-course {
            text-align: center;
            color: #777;
            margin-top: 30px;
        }
        footer {
            text-align: center;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($student_name); ?> 👋</h1>

    <nav>
        <a href="profile.php" class="btn">My Profile</a>
        <a href="logout.php" class="btn logout">Logout</a>
    </nav>

    <h2>🎓 My Enrolled Courses</h2>
    <div class="course-list">
        <?php if ($enrolled->num_rows > 0): ?>
            <?php while($row = $enrolled->fetch_assoc()): ?>
                <div class="course-card">
                    <h3><?= htmlspecialchars($row['course_name']); ?></h3>
                    <p><strong>Code:</strong> <?= htmlspecialchars($row['course_code']); ?></p>
                    <p><?= htmlspecialchars($row['description']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-course">You have not enrolled in any courses yet.</p>
        <?php endif; ?>
    </div>
</div>

<footer>© <?php echo date("Y"); ?> Course Enrollment System | Student Dashboard</footer>
</body>
</html>
