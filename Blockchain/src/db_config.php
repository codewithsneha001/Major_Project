<?php
$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "certificate";
$port = 3307;  // Specify the port

// Create a connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
