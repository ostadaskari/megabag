<?php
session_start();
require_once("../db/db.php");

// (ACL) Restrict access to admins/managers only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $pn = trim($_POST['pn'] ?? '');
    $mfg = trim($_POST['mfg'] ?? '');
    $qty = (int) ($_POST['qty'] ?? 0);
    $company_cmt = trim($_POST['company_cmt'] ?? '');
    $user_id = $_SESSION['user_id'] ?? null;
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $location = trim($_POST['location'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    $date_code = trim($_POST['date_code'] ?? '');
    $recieve_code = trim($_POST['recieve_code'] ?? '');

    if (!$user_id) {
        $errors[] = "User not logged in.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (name, part_number, mfg, qty, company_cmt, user_id, category_id, location, status, tag, date_code, recieve_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisiisssss", $name, $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag, $date_code, $recieve_code);

        if ($stmt->execute()) {
            $product_id = $stmt->insert_id;

            // Upload Images
            if (!empty($_FILES['images']['tmp_name'])) {
                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                    if (empty($tmpName)) continue;
                    $fileName = $_FILES['images']['name'][$index];
                    $fileSize = $_FILES['images']['size'][$index];
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

                    if ($fileSize > 20 * 1024 * 1024) continue; // Skip files > 20MB

                    $target = "../../uploads/images/" . uniqid() . "_" . basename($fileName);
                    if (move_uploaded_file($tmpName, $target)) {
                        $stmtImg = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                        $stmtImg->bind_param("issis", $product_id, $fileName, $target, $fileSize, $fileExt);
                        $stmtImg->execute();
                        $stmtImg->close();
                    }
                }
            }

            // Upload PDFs
            if (!empty($_FILES['pdfs']['tmp_name'])) {
                foreach ($_FILES['pdfs']['tmp_name'] as $index => $tmpName) {
                    if (empty($tmpName)) continue;
                    $fileName = $_FILES['pdfs']['name'][$index];
                    $fileSize = $_FILES['pdfs']['size'][$index];
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

                    if ($fileSize > 20 * 1024 * 1024) continue; // Skip files > 20MB

                    $target = "../../uploads/pdfs/" . uniqid() . "_" . basename($fileName);
                    if (move_uploaded_file($tmpName, $target)) {
                        $stmtPdf = $conn->prepare("INSERT INTO pdfs (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                        $stmtPdf->bind_param("issis", $product_id, $fileName, $target, $fileSize, $fileExt);
                        $stmtPdf->execute();
                        $stmtPdf->close();
                    }
                }
            }

            // Redirect with success message
            header("Location: create_product.php?success=" . urlencode("Product created successfully!"));
            exit;
        } else {
            $errors[] = "Failed to save product.";
        }
    }

    // If errors, redirect with error messages
    if (!empty($errors)) {
        header("Location: create_product.php?error=" . urlencode(implode(' | ', $errors)));
        exit;
    }
}

// --- Handle PRG success/error messages ---
$success = $_GET['success'] ?? '';
$errors = isset($_GET['error']) ? explode(' | ', $_GET['error']) : [];

// Fetch categories
$catsRes = $conn->query("SELECT * FROM categories");
$categories = $catsRes->fetch_all(MYSQLI_ASSOC);

include("../../design/views/manager/create_product_view.php");
