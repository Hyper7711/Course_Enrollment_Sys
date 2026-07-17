# Course Enrollment System

A simple **Course Enrollment System** built with **PHP**, **MySQL**, and **CSS** for managing student course registration, enrollment, unenrollment, payments, and user profiles.

## Features

* Student login and dashboard
* Course enrollment and unenrollment
* Payment flow with success/failure pages
* Profile management
* Database connection and SQL setup
* Clean, lightweight web-based interface

## Project Structure

* `index.php` — entry point / landing page
* `dashboard.php` — main student dashboard
* `enroll.php` — enroll in a course
* `unenroll.php` — remove a course enrollment
* `payment_gateway.php` — payment processing page
* `payment_success.php` — payment confirmation
* `payment_failed.php` — payment failure page
* `profile.php` — student profile page
* `logout.php` — log out user
* `db.php` — database connection file
* `course_enrollment.sql` — SQL file for database setup
* `style.css` — custom styling

## Requirements

* PHP 7+ or higher
* MySQL or MariaDB
* A local server environment such as XAMPP, WAMP, or MAMP

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Hyper7711/Course_Enrollment_Sys.git
   ```

2. Move the project folder into your server directory:

   * `htdocs` for XAMPP
   * `www` for WAMP
   * `htdocs` or the equivalent for your setup

3. Import the database:

   * Open phpMyAdmin
   * Create a new database
   * Import `course_enrollment.sql`

4. Update database credentials in `db.php` if needed.

5. Start your local server and open the project in your browser.

## How to Use

1. Open the application in your browser.
2. Log in as a student.
3. Browse the dashboard.
4. Enroll or unenroll from courses.
5. Complete payment steps when required.
6. View profile and logout when finished.

## Notes

* Make sure the database is imported before running the app.
* If pages show errors, check your `db.php` connection settings and SQL import.

## Author

Developed by **Hyper7711**

## License

Add a license section if you want to open-source the project under specific terms.
