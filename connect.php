<?php
$servername = "localhost";
$username = "root";  // Default user for XAMPP MySQL
$password = "";      // Leave blank if there's no password for root
$dbname = "faite";   // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

