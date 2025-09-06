<?php
require_once '../db/db.php';

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
        $x_code = 'X' . strtoupper(bin2hex(random_bytes(5)));

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
    $products = $_POST['products'];
    $user_id = $_SESSION['user_id'];

    foreach ($products as $index => $p) {
        $product_id    = isset($p['product_id']) ? (int)$p['product_id'] : 0;
        $qty_received  = isset($p['qty_received']) ? (int)$p['qty_received'] : 0;
        $purchase_code = $p['purchase_code'] ?? null;
        $vrm_x_code    = $p['vrm_x_code'] ?? null;
        $date_code     = isset($p['date_code']) ? (int)$p['date_code'] : (int)date('Y');
        $lot_location  = $p['lot_location'] ?? null;
        $project_name  = $p['project_name'] ?? null;
        $lock          = isset($p['lock']) ? 1 : 0;
        $remarks       = trim($p['remarks'] ?? '');

        // Validate inputs
        if ($product_id <= 0) {
            $errors[] = "Row " . ($index + 1) . ": Invalid product selection.";
            continue;
        }
        if ($qty_received <= 0) {
            $errors[] = "Row " . ($index + 1) . ": Quantity must be greater than 0.";
            continue;
        }

        // Generate guaranteed unique x_code
        try {
            $x_code = generateUniqueXCode($conn);
        } catch (Exception $e) {
            $errors[] = "Row " . ($index + 1) . ": Failed to generate x_code.";
            continue;
        }

        // Insert into product_lots
        $stmt = $conn->prepare("
            INSERT INTO product_lots 
            (product_id, user_id, purchase_code, x_code, vrm_x_code, qty_received, qty_available, date_code, lot_location, project_name, `lock`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
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
            $errors[] = "Row " . ($index + 1) . ": Failed to insert into product_lots.";
            continue;
        }

        $lot_id = $conn->insert_id;

        // Insert into stock_receipts
        $stmt2 = $conn->prepare("
            INSERT INTO stock_receipts 
            (product_lot_id, user_id, qty_received, remarks) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt2->bind_param("iiis", $lot_id, $user_id, $qty_received, $remarks);
        if (!$stmt2->execute()) {
            $errors[] = "Row " . ($index + 1) . ": Failed to insert into stock_receipts.";
            continue;
        }

        // Update product quantity
        $update = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
        $update->bind_param("ii", $qty_received, $product_id);
        if (!$update->execute()) {
            $errors[] = "Row " . ($index + 1) . ": Failed to update product stock.";
            continue;
        }
    }

    if (empty($errors)) {
        header("Location: ../auth/dashboard.php?page=receive_stock&success=" . urlencode("Stock received successfully."));
        exit;
    } else {
        header("Location: ../auth/dashboard.php?page=receive_stock&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }
}

// Load view
include '../../design/views/manager/receive_stock_view.php';
