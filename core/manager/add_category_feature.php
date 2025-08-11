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

// Handle POST request for NEW features
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        if (!isset($conn)) {
            throw new Exception('Database connection failed to load.');
        }

        $category_id = $_POST['category_id'] ?? null;
        $features = $_POST['features'] ?? [];
        $valid_data_types = ['varchar(50)', 'decimal(12,3)', 'TEXT', 'boolean'];

        if (!$category_id) {
            echo json_encode(['status' => 'error', 'message' => 'A category must be selected.']);
            exit;
        }

        if (empty($features)) {
            echo json_encode(['status' => 'error', 'message' => 'No features to add.']);
            exit;
        }

        $all_success = true;
        foreach ($features as $feature) {
            $name = trim($feature['name'] ?? '');
            $data_type = $feature['data_type'] ?? 'varchar(50)';
            $unit = trim($feature['unit'] ?? '');
            $is_required = isset($feature['is_required']) ? 1 : 0;

            if (!$name) {
                continue;
            }
            if (!in_array($data_type, $valid_data_types)) {
                $all_success = false;
                break;
            }

            $stmt = $conn->prepare("INSERT INTO features (category_id, name, data_type, unit, is_required) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Database prepare failed: ' . $conn->error);
            }

            $stmt->bind_param("isssi", $category_id, $name, $data_type, $unit, $is_required);

            if (!$stmt->execute()) {
                $all_success = false;
                break;
            }

            $stmt->close();
        }
        
        $conn->close();

        if ($all_success) {
            echo json_encode(['status' => 'success', 'message' => 'New features added successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'An internal server error occurred while adding features.']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'An internal server error occurred: ' . $e->getMessage()]);
        if (isset($conn) && is_object($conn)) {
            $conn->close();
        }
    }
    exit;
}

// Show the view only if it's a GET request
include("../../design/views/manager/add_category_feature_view.php");
