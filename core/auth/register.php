<?php
require_once('../db/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if ($username && $password && $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            header("Location: login.php?msg=Registered successfully&type=success");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    } else {
        $error = "All fields are required.";
    }
}

// Load form if not POST
include("../../design/views/auth/register_form.php");
