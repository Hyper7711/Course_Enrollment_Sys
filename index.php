<?php
include 'db.php';
session_start();

// Enable error reporting for debugging
$message = '';
$error = '';

// ----- REGISTER -----
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($name && $email && $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO student (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "Registered Successfully. Please login.";
        } else {
            if ($conn->errno == 1062) { // duplicate email
                $error = "This email is already registered!";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
        $stmt->close();
    } else {
        $error = "Please fill all fields!";
    }
}

// ----- LOGIN -----
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT id, password FROM student WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['student_id'] = $id;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid Password!";
            }
        } else {
            $error = "No account found with this email!";
        }
        $stmt->close();
    } else {
        $error = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Enrollment System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Extra centering styles only for landing page */
        .landing-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 75vh;
            gap: 60px;
            flex-wrap: wrap;
        }
        .landing-box {
            flex: 1 1 300px;
            max-width: 350px;
        }
    </style>
</head>
<body>
    <h1>Course Enrollment System</h1>

    <?php if ($message) echo "<p style='color:green; text-align:center;'>$message</p>"; ?>
    <?php if ($error) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

    <div class="landing-wrapper">
        <!-- REGISTER FORM -->
        <div class="landing-box">
            <h2>Register</h2>
            <form method="post">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Register</button>
            </form>
        </div>

        <!-- LOGIN FORM -->
        <div class="landing-box">
            <h2>Login</h2>
            <form method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
