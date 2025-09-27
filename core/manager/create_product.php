<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../db/db.php");
require_once("../auth/csrf.php");
// (ACL) Restrict access to admins/managers only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Wrap the entire POST logic in a try-catch block for comprehensive error handling
    try {
        // Validate the CSRF token before processing any form data.
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            // Log the error for security monitoring purposes.
            error_log('CSRF token validation failed.');
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

        // Check if we are creating or updating
        $product_id = (int) ($_POST['product_id'] ?? 0);
        $is_update = $product_id > 0;

        $pn = trim($_POST['pn'] ?? '');
        // Sanitize the part number for use in filenames
        $safe_pn = str_replace('/', '-', $pn);
        
        $mfg = trim($_POST['mfg'] ?? '');
        $qty = (int) ($_POST['qty'] ?? 0);
        $company_cmt = trim($_POST['company_cmt'] ?? '');
        $user_id = $_SESSION['user_id'] ?? null;
        $category_id = (int) ($_POST['category_id'] ?? 0);
        $location = trim($_POST['location'] ?? '');
        $status = trim($_POST['status'] ?? 'available');
        $tag = trim($_POST['tag'] ?? '');


        $features_values = $_POST['feature'] ?? [];
        $features_units = $_POST['feature_unit'] ?? [];
        
        // This is a placeholder for `db.php`
        // Make sure your database connection is configured to throw exceptions for `mysqli`
        // Example: mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        if ($is_update) {
            // It's an update. Prepare the UPDATE statement.
            $stmt = $conn->prepare("UPDATE products SET part_number = ?, mfg = ?, qty = ?, company_cmt = ?, user_id = ?, category_id = ?, location = ?, status = ?, tag = ? WHERE id = ?");
            $stmt->bind_param("ssisiisssi", $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag, $product_id);
        } else {
            // It's a new product. Prepare the INSERT statement.
            $stmt = $conn->prepare("INSERT INTO products (part_number, mfg, qty, company_cmt, user_id, category_id, location, status, tag) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisiisss", $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag);
        }

        // Execute the main database query
        if (!$stmt->execute()) {
            throw new Exception("Failed to save product. Database error: " . $stmt->error);
        }

        // Get the product_id for feature and file insertions
        if (!$is_update) {
            $product_id = $stmt->insert_id;
        }

        // Define allowed file types and max size
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedDocExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
        $maxFileSize = 20 * 1024 * 1024; // 20 MB

        // Define absolute paths for uploads
        $imageUploadDir = realpath(__DIR__ . '/../../uploads/images/') . DIRECTORY_SEPARATOR;
        $docUploadDir = realpath(__DIR__ . '/../../uploads/pdfs/') . DIRECTORY_SEPARATOR;
        
        // Check if directories exist and are writable
        if (!is_dir($imageUploadDir) || !is_writable($imageUploadDir)) {
            throw new Exception("Image upload directory is not writable or does not exist: " . $imageUploadDir);
        }
        if (!is_dir($docUploadDir) || !is_writable($docUploadDir)) {
            throw new Exception("Document upload directory is not writable or does not exist: " . $docUploadDir);
        }
        
        // Handle Cover Image
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cover_image'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedImageExtensions)) {
                $errors[] = "Cover image has an invalid file format.";
            } elseif ($file['size'] > $maxFileSize) {
                $errors[] = "Cover image size exceeds the 20MB limit.";
            } else {
                // Use the sanitized part number for the filename
                $newName = $safe_pn . "-cover." . $fileExt;
                // THIS IS THE CRITICAL CHANGE: Build a relative path for the database.
                $relative_path = 'uploads/images/' . $newName;
                $target = $imageUploadDir . $newName;

                // On update, delete old cover image if it exists
                if ($is_update) {
                    $oldCoverQuery = $conn->prepare("SELECT file_path FROM images WHERE product_id = ? AND is_cover = 1");
                    $oldCoverQuery->bind_param("i", $product_id);
                    $oldCoverQuery->execute();
                    $oldCoverResult = $oldCoverQuery->get_result();
                    if ($oldCoverResult->num_rows > 0) {
                        $oldCoverPath = $oldCoverResult->fetch_assoc()['file_path'];
                        if (file_exists($oldCoverPath)) {
                            unlink($oldCoverPath);
                        }
                        // Delete the database entry for the old cover
                        $conn->query("DELETE FROM images WHERE product_id = $product_id AND is_cover = 1");
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $target)) {
                    // Save the new relative path to the database
                    $stmtCover = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension, is_cover) VALUES (?, ?, ?, ?, ?, 1)");
                    $stmtCover->bind_param("issis", $product_id, $newName, $relative_path, $file['size'], $fileExt);
                    if (!$stmtCover->execute()) {
                        throw new Exception("Failed to save cover image to the database.");
                    }
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
                    // Use the sanitized part number for the filename
                    $newName = $safe_pn . "-img-" . ($index + 1) . "." . $fileExt;
                    // THIS IS THE CRITICAL CHANGE: Build a relative path for the database.
                    $relative_path = 'uploads/images/' . $newName;
                    $target = $imageUploadDir . $newName;

                    if (move_uploaded_file($tmpName, $target)) {
                        // Save the new relative path to the database
                        $stmtImg = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                        $stmtImg->bind_param("issis", $product_id, $newName, $relative_path, $fileSize, $fileExt);
                        if (!$stmtImg->execute()) {
                            throw new Exception("Failed to save image '$fileName' to the database.");
                        }
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
                        // Use the sanitized part number for the filename
                    $newName = $safe_pn . "-pdf-" . ($index + 1) . "." . $fileExt;
                    // THIS IS THE CRITICAL CHANGE: Build a relative path for the database.
                    $relative_path = 'uploads/pdfs/' . $newName;
                    $target = $docUploadDir . $newName;

                    if (move_uploaded_file($tmpName, $target)) {
                        // Save the new relative path to the database
                        $stmtPdf = $conn->prepare("INSERT INTO pdfs (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                        $stmtPdf->bind_param("issis", $product_id, $newName, $relative_path, $fileSize, $fileExt);
                        if (!$stmtPdf->execute()) {
                            throw new Exception("Failed to save document '$fileName' to the database.");
                        }
                        $stmtPdf->close();
                    } else {
                        $errors[] = "Failed to upload document '$fileName'.";
                    }
                }
            }
        }
        
        // --- UPDATED CODE FOR FEATURES ---
        if (!empty($features_values)) {
            // Fetch feature data types for validation
            $feature_ids_in = implode(',', array_map('intval', array_keys($features_values)));
            $feature_types_query = $conn->query("SELECT id, data_type FROM features WHERE id IN ($feature_ids_in)");
            $feature_types = [];
            while ($row = $feature_types_query->fetch_assoc()) {
                $feature_types[$row['id']] = $row['data_type'];
            }
            
            // If this is an update, delete all old feature values first
            if ($is_update) {
                $conn->query("DELETE FROM product_feature_values WHERE product_id = " . intval($product_id));
            }
            
            // Prepare the SQL statement for inserting features
            $stmt_features = $conn->prepare("INSERT INTO product_feature_values (product_id, feature_id, value) VALUES (?, ?, ?)");
            
            if ($stmt_features === false) {
                throw new Exception("Failed to prepare feature insertion statement: " . $conn->error);
            }
            
            foreach ($features_values as $feature_id => $value) {
                $unit = $features_units[$feature_id] ?? null;
                $data_type = $feature_types[$feature_id] ?? null;
                
                $feature_data = [];

                switch ($data_type) {
                    case 'range':
                        $feature_data = [
                            'min' => filter_var($value['min'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                            'max' => filter_var($value['max'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                            'unit' => htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?? ''
                        ];
                        break;
                    case 'multiselect':
                        $feature_data['values'] = array_map(function($val) {
                            return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                        }, $value);
                        break;
                    case 'boolean':
                        $feature_data['value'] = ($value === '1') ? 1 : 0;
                        break;
                    case 'decimal(15,7)':
                        $feature_data['value'] = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $feature_data['unit'] = htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?? '';
                        break;
                    case 'varchar(50)':
                    case 'TEXT':
                    default:
                        $feature_data['value'] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                        $feature_data['unit'] = htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?? '';
                        break;
                }

                $value_to_save = json_encode($feature_data);

                $stmt_features->bind_param("iis", $product_id, $feature_id, $value_to_save);
                if (!$stmt_features->execute()) {
                    throw new Exception("Failed to save feature with ID {$feature_id}. Database error: " . $stmt_features->error);
                }
            }
            $stmt_features->close();
        }
        // --- END OF UPDATED CODE ---

        $message = $is_update ? "Product updated successfully!" : "Product created successfully!";
        // Redirect to a success page only if no other errors occurred
        if (empty($errors)) {
            header("Location: ../auth/dashboard.php?page=create_product&success=" . urlencode($message));
        } else {
            header("Location: ../auth/dashboard.php?page=create_product&error=" . urlencode(implode(' | ', $errors)));
        }
        exit;

    } catch (Exception $e) {
        // Catch any exceptions and redirect with an error message
        $errors[] = $e->getMessage();
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
