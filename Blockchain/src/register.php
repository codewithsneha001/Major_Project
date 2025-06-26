<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "certificate";
$port = 3307; // If using XAMPP with MySQL running on port 3307

$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $message = "❌ Passwords do not match.";
        $message_type = "error";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $message = "✅ Registration successful. <a href='role.html'>Login here</a>";
            $message_type = "success";
        } else {
            $message = "❌ Error: " . $stmt->error;
            $message_type = "error";
        }

        // Close connections
        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <link rel="stylesheet" href="register_style.css"> <!-- Linking CSS -->
</head>
<body>
<div class="nav-container">
            <div class="nav-box"><a href="home.html">Home</a></div>
            <div class="nav-box"><a href="about.html">About Us</a></div>
            <div class="nav-box"><a href="contact.html">Contact Us</a></div>
        </div>
    
    <div class="message-box  <?php echo $message_type; ?>">
    <h1>Blockchain Enabled E-Certificate Verification with Steganographic Security</h1>
        <p><?php echo $message; ?></p>
    </div>
</body>
</html>
