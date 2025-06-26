<?php
include 'db_config.php';
if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Database connection failed!";
}
?>
