<?php
include 'db_connection.php';  // Connect to MySQL

session_start();
$student_email = $_SESSION['email'];  // Get logged-in student's email

$sql = "SELECT certificate_hash FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$stmt->bind_result($certificate_hash);
$stmt->fetch();

if ($certificate_hash) {
    echo json_encode(["hash" => $certificate_hash]);
} else {
    echo json_encode(["error" => "No certificate found"]);
}

$stmt->close();
$conn->close();
?>
