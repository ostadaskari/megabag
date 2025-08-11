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

header('Content-Type: application/json');

try {
    if (!isset($conn)) {
        throw new Exception('Database connection failed to load.');
    }

    // Handle GET request to fetch existing features
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category_id'])) {
        $category_id = (int) $_GET['category_id'];
        $stmt = $conn->prepare("SELECT id, name, data_type, unit, is_required FROM features WHERE category_id = ?");
        if ($stmt === false) {
            throw new Exception('Database prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $features = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        echo json_encode(['status' => 'success', 'features' => $features]);
        exit;
    }

    // Handle POST request to update a feature
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_feature_id'])) {
        $feature_id = (int) $_POST['update_feature_id'];
        $name = trim($_POST['name'] ?? '');
        $data_type = $_POST['data_type'] ?? 'varchar(50)';
        $unit = trim($_POST['unit'] ?? '');
        
        // This is the key change: Directly use the value sent from the client.
        // It will be 1 or 0, so we just cast it to an integer.
        $is_required = (int)$_POST['is_required'];
        
        if (!$name) {
            throw new Exception('Feature name is required.');
        }

        $stmt = $conn->prepare("UPDATE features SET name=?, data_type=?, unit=?, is_required=? WHERE id=?");
        if ($stmt === false) {
            throw new Exception('Database prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("sssii", $name, $data_type, $unit, $is_required, $feature_id);
        if ($stmt->execute()) {
             // Check if any rows were affected to give a more accurate message
            if ($stmt->affected_rows > 0) {
                 echo json_encode(['status' => 'success', 'message' => 'Feature updated successfully.']);
            } else {
                 echo json_encode(['status' => 'success', 'message' => 'No changes were made to the feature.']);
            }
        } else {
            throw new Exception('Database error: ' . $stmt->error);
        }
        $stmt->close();
        exit;
    }

    // Handle POST request to delete a feature
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_feature_id'])) {
        $feature_id = (int) $_POST['delete_feature_id'];
        
        $stmt = $conn->prepare("DELETE FROM features WHERE id=?");
        if ($stmt === false) {
            throw new Exception('Database prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("i", $feature_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                 echo json_encode(['status' => 'success', 'message' => 'Feature deleted successfully.']);
            } else {
                 echo json_encode(['status' => 'error', 'message' => 'Feature not found.']);
            }
        } else {
            throw new Exception('Database error: ' . $stmt->error);
        }
        $stmt->close();
        exit;
    }

    // Fallback for unrecognized requests
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'An internal server error occurred: ' . $e->getMessage()]);
    if (isset($conn) && is_object($conn)) {
        $conn->close();
    }
}
exit;