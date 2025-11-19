-- Create Database
CREATE DATABASE IF NOT EXISTS course_enrollment;
USE course_enrollment;

-- ----------------------------
-- Table: student
-- ----------------------------
CREATE TABLE IF NOT EXISTS student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- ----------------------------
-- Table: admin
-- ----------------------------
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- ----------------------------
-- Table: courses
-- ----------------------------
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(50) NOT NULL UNIQUE,
    faculty_name VARCHAR(100),
    duration VARCHAR(50),
    price DECIMAL(10,2) DEFAULT 0.00,
    description TEXT
);

-- ----------------------------
-- Table: enrollment_table
-- ----------------------------
CREATE TABLE IF NOT EXISTS enrollment_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES student(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- ----------------------------
-- Table: payments
-- ----------------------------
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- ----------------------------
-- Insert Sample Data
-- ----------------------------

-- Sample Student
INSERT INTO student (name, email, password)
VALUES 
('Test User', 'test@example.com', '$2y$10$YjWikI7f9ccBBJr3K58PMejx.AQhXc5F5ApqHLL2pnbslSPvdYyWe'); 
-- Password = 12345

-- Sample Admin
INSERT INTO admin (username, password)
VALUES
('admin', '$2y$10$CqY2mGBfo6FqLQbk3p5rleKf5v08.H.WvEavtzrKNZrJmUOkn7l0S'); 
-- Password = admin123

-- Sample Courses
INSERT INTO courses (course_name, course_code, faculty_name, duration, price, description)
VALUES
('Introduction to Programming', 'CS101', 'Prof. Neha Patil', '6 Weeks', 1999.00, 'Learn basic programming concepts in C and Python.'),
('Database Management Systems', 'CS202', 'Dr. Suresh Kulkarni', '8 Weeks', 2499.00, 'Understand relational databases and SQL queries.'),
('Data Structures', 'CS203', 'Prof. Rohan Mehta', '10 Weeks', 2999.00, 'Explore arrays, linked lists, stacks, and queues.'),
('Web Development', 'CS301', 'Prof. Anjali Deshmukh', '12 Weeks', 3499.00, 'Build modern web applications using HTML, CSS, JS, and PHP.');