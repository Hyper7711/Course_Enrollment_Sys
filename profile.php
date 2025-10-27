<?php
include 'db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$message = '';
$error = '';

// Fetch current student info
$stmt = $conn->prepare("SELECT name, email FROM student WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Update profile
if (isset($_POST['update'])) {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    $new_password = trim($_POST['password']);

    if ($new_name && $new_email) {
        if ($new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE student SET name=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $new_name, $new_email, $hashed_password, $student_id);
        } else {
            $stmt = $conn->prepare("UPDATE student SET name=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $new_name, $new_email, $student_id);
        }

        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
            $name = $new_name;
            $email = $new_email;
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "Name and Email cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>My Profile</h1>

    <nav>
        <a href="dashboard.php" class="btn">Dashboard</a>
        <a href="logout.php" class="btn">Logout</a>
    </nav>

    <?php if ($message) echo "<p style='color:green; text-align:center;'>$message</p>"; ?>
    <?php if ($error) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

    <form method="post">
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
        <button type="submit" name="update">Update Profile</button>
    </form>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Course Enrollment System. All rights reserved.</p>
</footer>
</body>
</html>
