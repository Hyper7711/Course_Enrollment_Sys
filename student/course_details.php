<?php
include '../db.php';
session_start();

// ✅ Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

// ✅ Validate course_id parameter
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    echo "❌ Course ID not provided!";
    exit();
}

$course_id = intval($_GET['course_id']);

// ✅ Fetch course details + faculty info (safe query)
$query = "
    SELECT 
        c.id AS course_id,
        c.course_name, 
        c.description, 
        f.faculty_name
    FROM courses c
    LEFT JOIN faculty f ON c.faculty_id = f.faculty_id
    WHERE c.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    echo "❌ Course not found!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Details</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body { font-family: Arial; background: #f5f5f5; }
        .course-box {
            max-width: 600px; 
            margin: 60px auto; 
            padding: 25px; 
            background: white;
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        button {
            background-color: #007bff; 
            color: white; 
            padding: 10px 20px;
            border: none; border-radius: 5px; 
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <div class="course-box">
        <h2>📘 <?php echo htmlspecialchars($course['course_name']); ?></h2>
        <p><b>Description:</b><br> <?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
        <p><b>Faculty:</b> 
            <?php echo $course['faculty_name'] ? htmlspecialchars($course['faculty_name']) : "Not assigned yet"; ?>
        </p>

        <!-- ✅ Use GET method since enroll.php reads $_GET['course_id'] -->
        <form action="enroll.php" method="GET">
            <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
            <button type="submit">Enroll Now</button>
        </form>

        <br><br>
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>
