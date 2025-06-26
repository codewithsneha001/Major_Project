<?php
include 'db_connection.php';  // Connect to MySQL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_email = $_POST['email']; // Get student email
    $certificate_hash = $_POST['hash']; // Get generated hash

    // Store the hash in the database for the student
    $sql = "UPDATE users SET certificate_hash = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $certificate_hash, $student_email);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Certificate issued successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error issuing certificate."]);
    }

    $stmt->close();
    $conn->close();
}
?>
