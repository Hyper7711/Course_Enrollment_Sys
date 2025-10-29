<?php
include '../db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$message = "";

// Create uploads folder if not exists
$uploadDir = "../uploads/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Step 1: Select course
$selected_course_id = isset($_POST['selected_course']) ? $_POST['selected_course'] : (isset($_GET['course_id']) ? $_GET['course_id'] : null);

// Step 2: Handle lecture upload
if (isset($_POST['upload_lecture']) && isset($_FILES['lecture_file'])) {
    $course_id = $_POST['course_id'];
    $file = $_FILES['lecture_file'];
    $fileName = basename($file["name"]);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO lectures (course_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $course_id, $fileName, $targetPath);
        $stmt->execute();
        $message = "✅ Lecture uploaded successfully for selected course!";
        $selected_course_id = $course_id;
    } else {
        $message = "⚠️ Error uploading file.";
    }
}

// Step 3: Delete lecture
if (isset($_GET['delete_lecture'])) {
    $id = $_GET['delete_lecture'];
    $file = $conn->query("SELECT file_path FROM lectures WHERE id=$id")->fetch_assoc();
    if ($file && file_exists($file['file_path'])) unlink($file['file_path']);
    $conn->query("DELETE FROM lectures WHERE id=$id");
    $message = "🗑️ Lecture deleted successfully.";
}

// Fetch all courses
$courses = $conn->query("SELECT * FROM courses");

// Fetch selected course info
$selected_course = null;
if ($selected_course_id) {
    $selected_course = $conn->query("SELECT * FROM courses WHERE id=$selected_course_id")->fetch_assoc();
    $lectures = $conn->query("SELECT * FROM lectures WHERE course_id=$selected_course_id ORDER BY upload_date DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Lectures</title>
    <style>
        body { font-family: Arial; background: #f5f6fa; margin: 20px; }
        h1, h2 { text-align: center; }
        form, table { background: #fff; margin: 20px auto; width: 80%; padding: 20px; border-radius: 10px; box-shadow: 0 0 8px #ccc; }
        select, input { padding: 8px; margin: 8px; width: 60%; }
        button { padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #28a745; color: white; }
        table { border-collapse: collapse; }
        a { color: red; text-decoration: none; }
        p { text-align: center; }
    </style>
</head>
<body>
    <h1>Lecture Upload Portal</h1>
    <?php if ($message) echo "<p><b>$message</b></p>"; ?>

    <!-- Step 1: Course Selection -->
    <form method="post" style="text-align:center;">
        <select name="selected_course" required>
            <option value="">-- Select Course --</option>
            <?php
            mysqli_data_seek($courses, 0);
            while ($c = $courses->fetch_assoc()) {
                $sel = ($selected_course_id == $c['id']) ? 'selected' : '';
                echo "<option value='{$c['id']}' $sel>{$c['course_name']}</option>";
            }
            ?>
        </select>
        <button type="submit">Select</button>
    </form>

    <?php if ($selected_course): ?>
        <h2>Course: <?= htmlspecialchars($selected_course['course_name']); ?></h2>

        <!-- Step 2: Upload form -->
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="course_id" value="<?= $selected_course['id']; ?>">
            <input type="file" name="lecture_file" required><br>
            <button type="submit" name="upload_lecture">Upload Lecture</button>
        </form>

        <!-- Step 3: Show uploaded lectures -->
        <h2>Uploaded Lectures for <?= htmlspecialchars($selected_course['course_name']); ?></h2>
        <table>
            <tr><th>ID</th><th>Lecture File</th><th>Uploaded On</th><th>Action</th></tr>
            <?php
            if ($lectures->num_rows > 0) {
                while ($row = $lectures->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td><a href='{$row['file_path']}' target='_blank'>{$row['file_name']}</a></td>
                        <td>{$row['upload_date']}</td>
                        <td><a href='?delete_lecture={$row['id']}&course_id={$selected_course_id}'>Delete</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No lectures uploaded yet.</td></tr>";
            }
            ?>
        </table>
    <?php endif; ?>

    <p><a href='admin_dashboard.php'>⬅ Back to Dashboard</a></p>
</body>
</html>
