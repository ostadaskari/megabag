<?php
$host = "localhost";
$user = "saeed";
$pass = "saeed_pass"; 
$dbname = "mega_db";

// Create database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>