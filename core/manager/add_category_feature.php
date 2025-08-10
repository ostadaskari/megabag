<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../db/db.php"); // mysqli $conn

// Restrict access to admin/manager only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set content type to JSON
    header('Content-Type: application/json');

    try {
        if (!isset($conn)) {
            throw new Exception('Database connection failed to load.');
        }

        $category_id = $_POST['category_id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $data_type = $_POST['data_type'] ?? 'varchar(50)';
        $unit = trim($_POST['unit'] ?? '');
        $is_required = isset($_POST['is_required']) ? 1 : 0;

        $valid_data_types = ['varchar(50)', 'decimal(12,3)', 'TEXT', 'boolean'];

        // Validation
        if (!$category_id || !$name) {
            echo json_encode(['status' => 'error', 'message' => 'Category and Feature Name are required.']);
            exit;
        }
        if (!in_array($data_type, $valid_data_types)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data type.']);
            exit;
        }

        // Insert
        $stmt = $conn->prepare("INSERT INTO features (category_id, name, data_type, unit, is_required) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception('Database prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("isssi", $category_id, $name, $data_type, $unit, $is_required);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Feature added successfully.']);
        } else {
            throw new Exception('Database error: ' . $stmt->error);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        // Catch any unhandled exceptions and return a JSON error
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'An internal server error occurred: ' . $e->getMessage()]);
        // It's good practice to close the connection even in a catch block
        if (isset($conn) && is_object($conn)) {
            $conn->close();
        }
    }
    exit; // Exit here to prevent the view from being included in the POST request
}

// Show the view only if it's a GET request
include("../../design/views/manager/add_category_feature_view.php");
