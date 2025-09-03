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
        // Add new data types for structured features
        $valid_data_types = ['varchar(50)', 'decimal(15,7)', 'TEXT', 'boolean', 'range', 'multiselect'];

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
            $metadata = null; // Initialize metadata as null

            // Construct the metadata JSON based on the selected data_type
            if ($data_type === 'range') {
                $min = $feature['min'] ?? null;
                $max = $feature['max'] ?? null;
                $units = $feature['units'] ?? null;

                // Create array of units from a comma-separated string
                $units_array = !empty($units) ? array_map('trim', explode(',', $units)) : [];
                $metadata = json_encode(['min' => $min, 'max' => $max, 'units' => $units_array]);

            } else if ($data_type === 'multiselect') {
                $options = $feature['options'] ?? null;

                // Create array of options from a comma-separated string
                $options_array = !empty($options) ? array_map('trim', explode(',', $options)) : [];
                $metadata = json_encode(['options' => $options_array]);

            }

            if (!$name) {
                continue;
            }
            if (!in_array($data_type, $valid_data_types)) {
                $all_success = false;
                break;
            }

            // The SQL query is updated to include the `metadata` column
            $stmt = $conn->prepare("INSERT INTO features (category_id, name, data_type, unit, is_required, metadata) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Database prepare failed: ' . $conn->error);
            }

            $stmt->bind_param("isssis", $category_id, $name, $data_type, $unit, $is_required, $metadata);

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