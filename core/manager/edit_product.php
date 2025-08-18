<?php
require_once '../db/db.php';
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// (ACL) Restrict access to admins/managers only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';
$product = null;
$images = [];
$pdfs = [];
$cover_image = null; // New variable to store the cover image

// Read success/error messages from URL (for SweetAlert)
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $errors = explode(' | ', $_GET['error']);
}

// Load product data on GET
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product) {
        $errors[] = 'Product not found.';
    } else {
        // Fetch all images, but separate the cover image
        $stmt = $conn->prepare("SELECT * FROM images WHERE product_id = ? ORDER BY is_cover DESC"); // Order by is_cover to get it first
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $all_images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Assign cover and other images
        foreach ($all_images as $img) {
            if ($img['is_cover'] == 1) {
                $cover_image = $img;
            } else {
                $images[] = $img;
            }
        }

        $stmt = $conn->prepare("SELECT * FROM pdfs WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $pdfs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['product_id']);
    $name = trim($_POST['name']);
    $p_n = trim($_POST['p_n']);
    $MFG = trim($_POST['MFG']);
    $qty = intval($_POST['qty']);
    $company_cmt = trim($_POST['company_cmt']);
    $location = trim($_POST['location']);
    $status = trim($_POST['status']);
    $tag = trim($_POST['tag']);
    $date_code = trim($_POST['date_code']);
    $receive_code = trim($_POST['recieve_code']);
    $category_id = intval($_POST['category_id']);

    if (empty($name)) {
        $errors[] = "Product name is required.";
    }

    if (empty($errors)) {
        // Update product details
        $stmt = $conn->prepare("UPDATE products SET name=?, part_number=?, MFG=?, qty=?, company_cmt=?, location=?, status=?, tag=?, date_code=?, recieve_code=?, category_id=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("sssissssssii", $name, $p_n, $MFG, $qty, $company_cmt, $location, $status, $tag, $date_code, $receive_code, $category_id, $id);
        if ($stmt->execute()) {
            $stmt->close();
            
            // --- File Upload Logic ---
            $imageDir = '../../uploads/images/';
            $pdfDir = '../../uploads/pdfs/';
            if (!file_exists($imageDir)) mkdir($imageDir, 0777, true);
            if (!file_exists($pdfDir)) mkdir($pdfDir, 0777, true);

            // Handle Cover Image Upload
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                // Delete old cover image if it exists
                $stmtDelCover = $conn->prepare("SELECT file_path FROM images WHERE product_id = ? AND is_cover = 1");
                $stmtDelCover->bind_param("i", $id);
                $stmtDelCover->execute();
                $oldCover = $stmtDelCover->get_result()->fetch_assoc();
                $stmtDelCover->close();

                if ($oldCover && file_exists($oldCover['file_path'])) {
                    unlink($oldCover['file_path']);
                }

                // Delete the record from the database
                $stmtDelRecord = $conn->prepare("DELETE FROM images WHERE product_id = ? AND is_cover = 1");
                $stmtDelRecord->bind_param("i", $id);
                $stmtDelRecord->execute();
                $stmtDelRecord->close();

                // Upload new cover image
                $file = $_FILES['cover_image'];
                $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = $p_n . "-cover." . $fileExt;
                $target = $imageDir . $newName;

                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $stmtCover = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension, is_cover) VALUES (?, ?, ?, ?, ?, 1)");
                    $stmtCover->bind_param("issis", $id, $newName, $target, $file['size'], $fileExt);
                    $stmtCover->execute();
                    $stmtCover->close();
                }
            }

            // Handle Additional Images Upload
            if (!empty($_FILES['images']['tmp_name'][0])) {
                // Get the current number of images to continue the count
                $stmtCount = $conn->prepare("SELECT COUNT(*) FROM images WHERE product_id = ? AND is_cover = 0");
                $stmtCount->bind_param("i", $id);
                $stmtCount->execute();
                $result = $stmtCount->get_result();
                $currentImgCount = $result->fetch_row()[0];
                $stmtCount->close();
                
                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['images']['error'][$index] === UPLOAD_ERR_OK) {
                        $size = $_FILES['images']['size'][$index];
                        $filename = $_FILES['images']['name'][$index];
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);

                        if ($size > 20 * 1024 * 1024) continue;

                        $newName = $p_n . "-img-" . ($currentImgCount + $index + 1) . "." . $ext;
                        $target = $imageDir . $newName;

                        if (move_uploaded_file($tmpName, $target)) {
                            $stmtImg = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                            $stmtImg->bind_param("issis", $id, $newName, $target, $size, $ext);
                            $stmtImg->execute();
                            $stmtImg->close();
                        }
                    }
                }
            }

            // Handle PDFs Upload
            if (!empty($_FILES['pdfs']['tmp_name'][0])) {
                // Get the current number of PDFs to continue the count
                $stmtCount = $conn->prepare("SELECT COUNT(*) FROM pdfs WHERE product_id = ?");
                $stmtCount->bind_param("i", $id);
                $stmtCount->execute();
                $result = $stmtCount->get_result();
                $currentPdfCount = $result->fetch_row()[0];
                $stmtCount->close();

                foreach ($_FILES['pdfs']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['pdfs']['error'][$index] === UPLOAD_ERR_OK) {
                        $size = $_FILES['pdfs']['size'][$index];
                        $filename = $_FILES['pdfs']['name'][$index];
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);

                        if ($size > 20 * 1024 * 1024) continue;

                        $newName = $p_n . "-pdf-" . ($currentPdfCount + $index + 1) . "." . $ext;
                        $target = $pdfDir . $newName;

                        if (move_uploaded_file($tmpName, $target)) {
                            $stmtPdf = $conn->prepare("INSERT INTO pdfs (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?,?, ?, ?)");
                            $stmtPdf->bind_param("issis", $id, $newName, $target, $size, $ext);
                            $stmtPdf->execute();
                            $stmtPdf->close();
                        }
                    }
                }
            }

            header("Location: ../auth/dashboard.php?page=edit_product&id=$id&success=" . urlencode("Product updated successfully."));
            exit;
        } else {
            $errors[] = "Failed to update product.";
            $stmt->close();
            header("Location: ../auth/dashboard.php?page=edit_product&id=$id&error=" . urlencode(implode(' | ', $errors)));
            exit;
        }
    } else {
        header("Location: ../auth/dashboard.php?page=edit_product&id=$id&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }
}

require_once '../../design/views/manager/edit_product_view.php';