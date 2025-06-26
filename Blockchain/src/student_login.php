<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['student_id'] = $id;
            header("Location: view_certificate.html"); // Redirect to View Certificate Page
            exit();
        } else {
            echo "Invalid email or password!";
        }
    } else {
        echo "Student not found!";
    }

    $stmt->close();
}

$conn->close();
?>
