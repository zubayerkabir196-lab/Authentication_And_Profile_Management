<?php
// Database configuration
$servername = "localhost";   // usually "localhost"
$username   = "root";        // your MySQL username
$password   = "";            // your MySQL password (empty by default in XAMPP)
$dbname     = "user_system"; // the database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>