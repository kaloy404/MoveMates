<?php
$host = "localhost";      // your MySQL host
$user = "root";           // default XAMPP user
$pass = "";               // default password is empty
$dbname = "moveMates";    // your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: uncomment for debugging
// echo "Database connected successfully!";
?>
