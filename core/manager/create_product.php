<?php
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    $status = trim($_POST['status'] ?? 'available');
    $tag = trim($_POST['tag'] ?? '');
    $date_code = trim($_POST['date_code'] ?? '');
    $recieve_code = trim($_POST['recieve_code'] ?? '');
    $rf = trim($_POST['rf'] ?? '');


    if (empty($errors)) {
        // Prepare the product insertion statement
        $stmt = $conn->prepare("INSERT INTO products (name, part_number, mfg, qty, company_cmt, user_id, category_id, location, status, tag, date_code, recieve_code, rf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisiissssss", $name, $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag, $date_code, $recieve_code, $rf);

        if ($stmt->execute()) {
            $product_id = $stmt->insert_id;

            // Define allowed file types and max size
            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedDocExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
            $maxFileSize = 20 * 1024 * 1024; // 20 MB

            // Handle Cover Image
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['cover_image'];
                $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (!in_array($fileExt, $allowedImageExtensions)) {
                    $errors[] = "Cover image has an invalid file format.";
                } elseif ($file['size'] > $maxFileSize) {
                    $errors[] = "Cover image size exceeds the 20MB limit.";
                } else {
                    $newName = $pn . "-cover." . $fileExt;
                    $target = "../../uploads/images/" . $newName;

                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $stmtCover = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension, is_cover) VALUES (?, ?, ?, ?, ?, 1)");
                        $stmtCover->bind_param("issis", $product_id, $newName, $target, $file['size'], $fileExt);
                        $stmtCover->execute();
                        $stmtCover->close();
                    } else {
                        $errors[] = "Failed to upload cover image.";
                    }
                }
            }

            // Handle Additional Images
            if (!empty($_FILES['images']['tmp_name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['images']['error'][$index] !== UPLOAD_ERR_OK) continue;

                    $fileName = $_FILES['images']['name'][$index];
                    $fileSize = $_FILES['images']['size'][$index];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (!in_array($fileExt, $allowedImageExtensions)) {
                        $errors[] = "Image file '$fileName' has an invalid file format.";
                    } elseif ($fileSize > $maxFileSize) {
                        $errors[] = "Image file '$fileName' size exceeds the 20MB limit.";
                    } else {
                        $newName = $pn . "-img-" . ($index + 1) . "." . $fileExt;
                        $target = "../../uploads/images/" . $newName;

                        if (move_uploaded_file($tmpName, $target)) {
                            $stmtImg = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                            $stmtImg->bind_param("issis", $product_id, $newName, $target, $fileSize, $fileExt);
                            $stmtImg->execute();
                            $stmtImg->close();
                        } else {
                            $errors[] = "Failed to upload image '$fileName'.";
                        }
                    }
                }
            }

            // Handle PDFs
            if (!empty($_FILES['pdfs']['tmp_name'][0])) {
                foreach ($_FILES['pdfs']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['pdfs']['error'][$index] !== UPLOAD_ERR_OK) continue;

                    $fileName = $_FILES['pdfs']['name'][$index];
                    $fileSize = $_FILES['pdfs']['size'][$index];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (!in_array($fileExt, $allowedDocExtensions)) {
                        $errors[] = "Document file '$fileName' has an invalid file format.";
                    } elseif ($fileSize > $maxFileSize) {
                        $errors[] = "Document file '$fileName' size exceeds the 20MB limit.";
                    } else {
                        $newName = $pn . "-pdf-" . ($index + 1) . "." . $fileExt;
                        $target = "../../uploads/pdfs/" . $newName;

                        if (move_uploaded_file($tmpName, $target)) {
                            $stmtPdf = $conn->prepare("INSERT INTO pdfs (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                            $stmtPdf->bind_param("issis", $product_id, $newName, $target, $fileSize, $fileExt);
                            $stmtPdf->execute();
                            $stmtPdf->close();
                        } else {
                            $errors[] = "Failed to upload document '$fileName'.";
                        }
                    }
                }
            }

            // After all uploads, check for any accumulated errors and redirect
            if (empty($errors)) {
                header("Location: ../auth/dashboard.php?page=create_product&success=" . urlencode("Product created successfully!"));
            } else {
                header("Location: ../auth/dashboard.php?page=create_product&error=" . urlencode(implode(' | ', $errors)));
            }
            exit;

        } else {
            $errors[] = "Failed to save product.";
            header("Location: ../auth/dashboard.php?page=create_product&error=" . urlencode(implode(' | ', $errors)));
            exit;
        }
    }

    // If initial validation fails (e.g., no user ID), redirect with errors
    if (!empty($errors)) {
        header("Location: ../auth/dashboard.php?page=create_product&error=" . urlencode(implode(' | ', $errors)));
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
