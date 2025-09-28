<?php
require_once '../db/db.php';
require_once '../auth/csrf.php'; // This file contains the validate_csrf_token function
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';

// Read messages from query string for SweetAlert
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $errors = explode(' | ', $_GET['error']);
}

// Function to generate a truly unique x_code
function generateUniqueXCode($conn) {
    while (true) {
        $x_code = 'M' . strtoupper(bin2hex(random_bytes(4)));

        $check = $conn->prepare("SELECT id FROM product_lots WHERE x_code = ?");
        $check->bind_param("s", $x_code);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $check->close();
            return $x_code; // unique found
        }
        $check->close();
    }
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products']) && is_array($_POST['products'])) {
    $conn->begin_transaction();

    try {
        // Validate the CSRF token before processing any form data.
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

        $products = $_POST['products'];
        $user_id = $_SESSION['user_id'];

        foreach ($products as $index => $p) {
            $product_id    = isset($p['product_id']) ? (int)$p['product_id'] : 0;
            $qty_received  = isset($p['qty_received']) ? (int)$p['qty_received'] : 0;
            $purchase_code = !empty($p['purchase_code']) ? $p['purchase_code'] : null;
            $vrm_x_code    = !empty($p['vrm_x_code']) ? $p['vrm_x_code'] : null;
            $date_code     = !empty($p['date_code']) ? (int)$p['date_code'] : (int)date('Y');
            $lot_location  = !empty($p['lot_location']) ? $p['lot_location'] : null;
            $project_name  = !empty($p['project_name']) ? $p['project_name'] : null;
            $lock          = isset($p['lock']) ? 1 : 0;
            $remarks       = trim($p['remarks'] ?? '');

            // Validate inputs
            if ($product_id <= 0) {
                throw new Exception("Row " . ($index + 1) . ": Invalid product selection.");
            }
            if ($qty_received <= 0) {
                throw new Exception("Row " . ($index + 1) . ": Quantity must be greater than 0.");
            }

            // Generate guaranteed unique x_code
            $x_code = generateUniqueXCode($conn);

            // Insert into product_lots
            $stmt = $conn->prepare("
                INSERT INTO product_lots 
                (product_id, user_id, purchase_code, x_code, vrm_x_code, qty_received, qty_available, date_code, lot_location, project_name, `lock`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            if ($stmt === false) {
                throw new Exception("Failed to prepare product_lots statement: " . $conn->error);
            }
            $stmt->bind_param(
                "iisssiiissi",
                $product_id,
                $user_id,
                $purchase_code,
                $x_code,
                $vrm_x_code,
                $qty_received,
                $qty_received,
                $date_code,
                $lot_location,
                $project_name,
                $lock
            );
            if (!$stmt->execute()) {
                throw new Exception("Row " . ($index + 1) . ": Failed to insert into product_lots. (" . $stmt->error . ")");
            }
            $stmt->close();

            $lot_id = $conn->insert_id;

            // Insert into stock_receipts
            $stmt2 = $conn->prepare("
                INSERT INTO stock_receipts 
                (product_lot_id, user_id, qty_received, remarks) 
                VALUES (?, ?, ?, ?)
            ");
            if ($stmt2 === false) {
                throw new Exception("Failed to prepare stock_receipts statement: " . $conn->error);
            }
            $stmt2->bind_param("iiis", $lot_id, $user_id, $qty_received, $remarks);
            if (!$stmt2->execute()) {
                throw new Exception("Row " . ($index + 1) . ": Failed to insert into stock_receipts.");
            }
            $stmt2->close();

            // Update product quantity
            $update = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
            if ($update === false) {
                throw new Exception("Failed to prepare product update statement: " . $conn->error);
            }
            $update->bind_param("ii", $qty_received, $product_id);
            if (!$update->execute()) {
                throw new Exception("Row " . ($index + 1) . ": Failed to update product stock. (" . $update->error . ")");
            }
            $update->close();
        }

        $conn->commit();
        header("Location: ../auth/dashboard.php?page=receive_stock&success=" . urlencode("Stock received successfully."));
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $errors[] = $e->getMessage();
        header("Location: ../auth/dashboard.php?page=receive_stock&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }
}

// Load view
include '../../design/views/manager/receive_stock_view.php';