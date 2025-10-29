<?php
include 'db.php';
session_start();

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
            if ($conn->errno == 1062) {
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
        $stmt = $conn->prepare("SELECT id, password, name FROM student WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $name);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['student_id'] = $id;
                $_SESSION['student_name'] = $name;
                header("Location: student/dashboard.php");
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Enrollment System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #5e8ef7, #1e3c72);
            color: #333;
        }

        h1 {
            color: white;
            text-align: center;
            margin-top: 40px;
            font-weight: 600;
        }

        .landing-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 50px;
            margin: 60px auto;
            max-width: 1000px;
        }

        .landing-box {
            background: white;
            border-radius: 14px;
            padding: 30px;
            width: 350px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .landing-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
        }

        .landing-box h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 12px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .admin-box {
            text-align: center;
            margin-top: 20px;
        }

        .admin-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.3s;
        }

        .admin-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .message, .error {
            text-align: center;
            margin-top: 10px;
        }

        .message {
            color: green;
        }

        .error {
            color: red;
        }

        footer {
            margin-top: 60px;
            text-align: center;
            color: #f0f0f0;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <h1>🎓 Course Enrollment System</h1>

    <?php if ($message) echo "<p class='message'>$message</p>"; ?>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <div class="landing-wrapper">
        <!-- REGISTER FORM -->
        <div class="landing-box">
            <h2>Student Registration</h2>
            <form method="post">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Register</button>
            </form>
        </div>

        <!-- LOGIN FORM -->
        <div class="landing-box">
            <h2>Student Login</h2>
            <form method="post">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>

            <div class="admin-box">
                <p>👨‍💼 Admin Access</p>
                <button class="admin-btn" onclick="window.location.href='admin/admin_login.php'">
                    Go to Admin Login
                </button>
            </div>
        </div>
    </div>

    <footer>© <?php echo date("Y"); ?> Course Enrollment System | Designed by Rohit Badgujar</footer>
</body>
</html>
