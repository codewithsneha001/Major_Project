<?php
session_start(); // Start the session
include 'db_config.php'; // Ensure this file contains database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Role selection from login form

    // Determine the table based on role
    if ($role == "student") {
        $table = "students";
        $redirect_page = "student_login.html"; // Redirect to student page
    } elseif ($role == "company") {
        $table = "companies";
        $redirect_page = "company_login.html"; // Redirect to company page
    } elseif ($role == "admin") {
        $table = "admins";
        $redirect_page = "index.html"; // Redirect to admin panel
    } else {
        echo "<script>alert('Invalid role selected!'); window.location.href='login.html';</script>";
        exit();
    }

    // Fetch user details
    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Store session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $role;

        // Redirect to respective dashboard
        header("Location: index.html");
        exit();
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='admin_login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
