<?php
$host = "localhost";
$user = "saeed";   // in host : dohteksc_megabag_u
$pass = "saeed_pass";  // in host : !G0baU%vZMak;rFr
$dbname = "mega_db";  //  in host : dohteksc_megabag

// Create database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>