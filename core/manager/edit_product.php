<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../db/db.php';
require_once '../auth/csrf.php'; // This file contains the validate_csrf_token function

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
$cover_image = null;
$product_features = [];

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
        // Fetch existing images and separate the cover image
        $stmt = $conn->prepare("SELECT * FROM images WHERE product_id = ? ORDER BY is_cover DESC");
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

        // Fetch PDFs
        $stmt = $conn->prepare("SELECT * FROM pdfs WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $pdfs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Fetch existing feature values and their types for display
        $stmt = $conn->prepare("SELECT pfv.feature_id, pfv.value, f.data_type, f.unit, f.name FROM product_feature_values pfv JOIN features f ON pfv.feature_id = f.id WHERE pfv.product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $product_features[$row['feature_id']] = [
                'value' => json_decode($row['value'], true),
                'data_type' => $row['data_type'],
                'unit' => $row['unit'],
                'name' => $row['name']
            ];
        }
        $stmt->close();
    }
    
    // Define the base path for files, which is two directories up from the PHP script
    $base_path = '../../';
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate the CSRF token before processing any form data.
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

        $id = intval($_POST['product_id']);
        $pn = trim($_POST['p_n']);
        $mfg = trim($_POST['MFG']);
        $company_cmt = trim($_POST['company_cmt']);
        $location = trim($_POST['location']);
        $status = trim($_POST['status'] ?? 'available');
        $tag = trim($_POST['tag']);

        $category_id = intval($_POST['category_id']);
        $submitted_features = $_POST['features'] ?? [];
        
        // Sanitize the part number for use in filenames
        $safe_pn = str_replace('/', '-', $pn);

        // Validation
        if (empty($pn)) {
            $errors[] = "Product part number is required.";
        }

        if (empty($errors)) {
            // Update product details
            $stmt = $conn->prepare("UPDATE products SET part_number=?, mfg=?, company_cmt=?, location=?, status=?, tag=?, category_id=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param("ssssssii", $pn, $mfg, $company_cmt, $location, $status, $tag, $category_id, $id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update product details: " . $stmt->error);
            }
            $stmt->close();

            // --- INLINE LOGIC FOR SAVING JSON-BASED FEATURES ---
            $conn->begin_transaction();

            // First, delete all existing features for this product
            $stmtDelFeatures = $conn->prepare("DELETE FROM product_feature_values WHERE product_id = ?");
            $stmtDelFeatures->bind_param("i", $id);
            if (!$stmtDelFeatures->execute()) {
                throw new Exception("Failed to delete existing features: " . $stmtDelFeatures->error);
            }
            $stmtDelFeatures->close();

            // Then, re-insert the features submitted with the form
            if (!empty($submitted_features)) {
                // Fetch feature data types to correctly format the JSON value
                $feature_ids_in = implode(',', array_map('intval', array_keys($submitted_features)));
                $feature_info_query = $conn->query("SELECT id, data_type, metadata FROM features WHERE id IN ($feature_ids_in)");

                if (!$feature_info_query) {
                    throw new Exception("Failed to fetch feature info: " . $conn->error);
                }

                $feature_info = [];
                while ($row = $feature_info_query->fetch_assoc()) {
                    $feature_info[$row['id']] = $row;
                }

                // Prepare statement once outside the loop
                $stmtInsertFeatures = $conn->prepare("INSERT INTO product_feature_values (product_id, feature_id, value) VALUES (?, ?, ?)");

                if ($stmtInsertFeatures === false) {
                    throw new Exception("Failed to prepare feature insertion statement: " . $conn->error);
                }

                foreach ($submitted_features as $feature_id => $feature_data) {
                    $data_type = $feature_info[$feature_id]['data_type'] ?? null;
                    $metadata = json_decode($feature_info[$feature_id]['metadata'] ?? '{}', true);
                    $value_to_save = [];

                    if (is_array($feature_data)) {
                        switch ($data_type) {
                            case 'range':
                                $value_to_save['min'] = isset($feature_data['min']) ? filter_var($feature_data['min'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : null;
                                $value_to_save['max'] = isset($feature_data['max']) ? filter_var($feature_data['max'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : null;
                                if (isset($feature_data['unit'])) {
                                    $value_to_save['unit'] = htmlspecialchars($feature_data['unit'], ENT_QUOTES, 'UTF-8');
                                }
                                break;
                            case 'multiselect':
                                $value_to_save['values'] = array_map('htmlspecialchars', $feature_data);
                                break;
                            case 'decimal(15,7)':
                                $value_to_save['value'] = isset($feature_data['value']) ? filter_var($feature_data['value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : null;
                                if (isset($feature_data['unit'])) {
                                    $value_to_save['unit'] = htmlspecialchars($feature_data['unit'], ENT_QUOTES, 'UTF-8');
                                }
                                break;
                            case 'boolean':
                                $value_to_save['value'] = (isset($feature_data['value']) && $feature_data['value'] === '1');
                                break;
                            default:
                                $value_to_save['value'] = 'Error: Invalid data format.';
                                break;
                        }
                    } else {
                        switch ($data_type) {
                            case 'varchar(50)':
                            case 'TEXT':
                            default:
                                $value_to_save['value'] = htmlspecialchars($feature_data, ENT_QUOTES, 'UTF-8');
                                break;
                        }
                    }
                    $json_value_to_save = json_encode($value_to_save);
                    $stmtInsertFeatures->bind_param("iis", $id, $feature_id, $json_value_to_save);
                    if (!$stmtInsertFeatures->execute()) {
                        throw new Exception("Failed to save feature with ID {$feature_id}. Database error: " . $stmtInsertFeatures->error);
                    }
                }
                $stmtInsertFeatures->close();
            }
            $conn->commit();
        }

        // --- File Upload Logic ---
        $base_upload_dir = '../../uploads/';
        $imageDir = $base_upload_dir . 'images/';
        $pdfDir = $base_upload_dir . 'pdfs/';

        if (!file_exists($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }

        // Define allowed file types and max size
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedDocExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
        $maxFileSize = 20 * 1024 * 1024; // 20 MB

        // Handle Cover Image Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cover_image'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedImageExtensions)) {
                $errors[] = "Cover image has an invalid file format.";
            } elseif ($file['size'] > $maxFileSize) {
                $errors[] = "Cover image size exceeds the 20MB limit.";
            } else {
                // Get old cover image path for deletion
                $stmtDelCover = $conn->prepare("SELECT file_path FROM images WHERE product_id = ? AND is_cover = 1");
                $stmtDelCover->bind_param("i", $id);
                $stmtDelCover->execute();
                $oldCover = $stmtDelCover->get_result()->fetch_assoc();
                $stmtDelCover->close();

                if ($oldCover && file_exists($base_upload_dir . $oldCover['file_path'])) {
                    unlink($base_upload_dir . $oldCover['file_path']);
                }

                // Delete the record from the database
                $stmtDelRecord = $conn->prepare("DELETE FROM images WHERE product_id = ? AND is_cover = 1");
                $stmtDelRecord->bind_param("i", $id);
                $stmtDelRecord->execute();
                $stmtDelRecord->close();

                // Upload new cover image
                $newName = $safe_pn . "-cover." . $fileExt;
                $target_path = 'uploads/images/' . $newName; // Path to save in DB
                $full_target = $imageDir . $newName; // Full physical path

                if (move_uploaded_file($file['tmp_name'], $full_target)) {
                    $stmtCover = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension, is_cover) VALUES (?, ?, ?, ?, ?, 1)");
                    $stmtCover->bind_param("issis", $id, $newName, $target_path, $file['size'], $fileExt);
                    $stmtCover->execute();
                    $stmtCover->close();
                } else {
                    $errors[] = "Failed to upload cover image.";
                }
            }
        }

        // Handle Additional Images Upload
        if (!empty($_FILES['images']['tmp_name'][0])) {
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
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if (!in_array($ext, $allowedImageExtensions)) {
                        $errors[] = "Image file '$filename' has an invalid file format.";
                    } elseif ($size > $maxFileSize) {
                        $errors[] = "Image file '$filename' size exceeds the 20MB limit.";
                    } else {
                        $newName = $safe_pn . "-img-" . ($currentImgCount + $index + 1) . "." . $ext;
                        $target_path = 'uploads/images/' . $newName; // Path to save in DB
                        $full_target = $imageDir . $newName; // Full physical path

                        if (move_uploaded_file($tmpName, $full_target)) {
                            $stmtImg = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                            $stmtImg->bind_param("issis", $id, $newName, $target_path, $size, $ext);
                            $stmtImg->execute();
                            $stmtImg->close();
                        } else {
                            $errors[] = "Failed to upload image '$filename'.";
                        }
                    }
                }
            }
        }

        // Handle PDFs Upload
        if (!empty($_FILES['pdfs']['tmp_name'][0])) {
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
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if (!in_array($ext, $allowedDocExtensions)) {
                        $errors[] = "Document file '$filename' has an invalid file format.";
                    } elseif ($size > $maxFileSize) {
                        $errors[] = "Document file '$filename' size exceeds the 20MB limit.";
                    } else {
                        $newName = $safe_pn . "-pdf-" . ($currentPdfCount + $index + 1) . "." . $ext;
                        $target_path = 'uploads/pdfs/' . $newName; // Path to save in DB
                        $full_target = $pdfDir . $newName; // Full physical path

                        if (move_uploaded_file($tmpName, $full_target)) {
                            $stmtPdf = $conn->prepare("INSERT INTO pdfs (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                            $stmtPdf->bind_param("issis", $id, $newName, $target_path, $size, $ext);
                            $stmtPdf->execute();
                            $stmtPdf->close();
                        } else {
                            $errors[] = "Failed to upload document '$filename'.";
                        }
                    }
                }
            }
        }

        if (empty($errors)) {
            header("Location: ../auth/dashboard.php?page=edit_product&id=$id&success=" . urlencode("Product updated successfully."));
        } else {
            header("Location: ../auth/dashboard.php?page=edit_product&id=$id&error=" . urlencode(implode(' | ', $errors)));
        }
        exit;

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        $conn->rollback();
        header("Location: ../auth/dashboard.php?page=edit_product&id=$id&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }
}

// Fetch categories for the view
$catsRes = $conn->query("SELECT * FROM categories");
$categories = $catsRes->fetch_all(MYSQLI_ASSOC);

// Load the view
require_once '../../design/views/manager/edit_product_view.php';