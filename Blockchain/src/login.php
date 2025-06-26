<?php
session_start();
include 'db_config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]); // Getting the pre-selected role

    if (empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('All fields are required!'); window.location.href='login.html';</script>";
        exit();
    }

    // Prepare SQL query to check user credentials
    $sql = "SELECT id, full_name, password FROM users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION["user_id"] = $id;
            $_SESSION["full_name"] = $full_name;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;

            // Redirect to respective dashboards based on role
            if ($role === "student") {
                header("Location: student_operation.html");
            } elseif ($role === "admin") {
                header("Location: index.html");
            } elseif ($role === "company") {
                header("Location: company_operation.html");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials or role!'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
