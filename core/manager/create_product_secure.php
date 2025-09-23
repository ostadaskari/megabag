<?php
// Start session if not already started
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
    // Check if we are creating or updating
    $product_id = (int) ($_POST['product_id'] ?? 0);
    $is_update = $product_id > 0;


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


    
    $features_values = $_POST['feature'] ?? [];
    $features_units = $_POST['feature_unit'] ?? [];
    
    if (empty($errors)) {
        if ($is_update) {
            // It's an update. Prepare the UPDATE statement.
            $stmt = $conn->prepare("UPDATE products SET  part_number = ?, mfg = ?, qty = ?, company_cmt = ?, user_id = ?, category_id = ?, location = ?, status = ?, tag = ?, date_code = ? WHERE id = ?");
            $stmt->bind_param("ssisiissssi",  $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag, $date_code, $product_id);
        } else {
            // It's a new product. Prepare the INSERT statement.
            $stmt = $conn->prepare("INSERT INTO products ( part_number, mfg, qty, company_cmt, user_id, category_id, location, status, tag, date_code  ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisiissss",  $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag, $date_code);
        }

        if ($stmt->execute()) {
            // Get the product_id for feature and file insertions
            if (!$is_update) {
                $product_id = $stmt->insert_id;
            }

            // Define allowed file types and max size
            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif' , 'webp'];
            $allowedImageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $allowedDocExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
            $allowedDocMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain'];
            $maxFileSize = 20 * 1024 * 1024; // 20 MB

            // Handle Cover Image
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $file = $_FILES['cover_image'];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                    // Use finfo to get the real MIME type
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    if (!in_array($fileExt, $allowedImageExtensions) || !in_array($mimeType, $allowedImageMimeTypes)) {
                        $errors[] = "Cover image has an invalid file format or MIME type.";
                    } elseif ($file['size'] > $maxFileSize) {
                        $errors[] = "Cover image size exceeds the 20MB limit.";
                    } else {
                        // Sanitize the part number and combine with a unique ID for a secure filename
                        $safePn = preg_replace('/[^a-zA-Z0-9\._-]/', '', $pn);
                        $newName = $safePn . '_' . uniqid() . '.' . $fileExt;
                        $target = "../../uploads/images/" . $newName;

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
                            $stmtCover = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension, is_cover) VALUES (?, ?, ?, ?, ?, 1)");
                            $stmtCover->bind_param("issis", $product_id, $newName, $target, $file['size'], $fileExt);
                            $stmtCover->execute();
                            $stmtCover->close();
                        } else {
                            $errors[] = "Failed to upload cover image.";
                        }
                    }
                } catch (Exception $e) {
                    error_log("Cover image upload failed: " . $e->getMessage());
                    $errors[] = "An unexpected error occurred during cover image upload.";
                }
            }

            // Handle Additional Images
            if (!empty($_FILES['images']['tmp_name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['images']['error'][$index] !== UPLOAD_ERR_OK) continue;

                    try {
                        $fileName = $_FILES['images']['name'][$index];
                        $fileSize = $_FILES['images']['size'][$index];
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        // Use finfo to get the real MIME type
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $tmpName);
                        finfo_close($finfo);

                        if (!in_array($fileExt, $allowedImageExtensions) || !in_array($mimeType, $allowedImageMimeTypes)) {
                            $errors[] = "Image file '$fileName' has an invalid file format or MIME type.";
                        } elseif ($fileSize > $maxFileSize) {
                            $errors[] = "Image file '$fileName' size exceeds the 20MB limit.";
                        } else {
                            // Sanitize the part number and combine with a unique ID for a secure filename
                            $safePn = preg_replace('/[^a-zA-Z0-9\._-]/', '', $pn);
                            $newName = $safePn . '_' . uniqid() . '.' . $fileExt;
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
                    } catch (Exception $e) {
                        error_log("Additional image upload failed: " . $e->getMessage());
                        $errors[] = "An unexpected error occurred during an image upload.";
                    }
                }
            }

            // Handle PDFs
            if (!empty($_FILES['pdfs']['tmp_name'][0])) {
                foreach ($_FILES['pdfs']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['pdfs']['error'][$index] !== UPLOAD_ERR_OK) continue;

                    try {
                        $fileName = $_FILES['pdfs']['name'][$index];
                        $fileSize = $_FILES['pdfs']['size'][$index];
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        // Use finfo to get the real MIME type
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $tmpName);
                        finfo_close($finfo);

                        if (!in_array($fileExt, $allowedDocExtensions) || !in_array($mimeType, $allowedDocMimeTypes)) {
                            $errors[] = "Document file '$fileName' has an invalid file format or MIME type.";
                        } elseif ($fileSize > $maxFileSize) {
                            $errors[] = "Document file '$fileName' size exceeds the 20MB limit.";
                        } else {
                            // Sanitize the part number and combine with a unique ID for a secure filename
                            $safePn = preg_replace('/[^a-zA-Z0-9\._-]/', '', $pn);
                            $newName = $safePn . '_' . uniqid() . '.' . $fileExt;
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
                    } catch (Exception $e) {
                        error_log("PDF upload failed: " . $e->getMessage());
                        $errors[] = "An unexpected error occurred during a document upload.";
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
    // We are now only inserting into product_id, feature_id, and value
    $stmt_features = $conn->prepare("INSERT INTO product_feature_values (product_id, feature_id, value) VALUES (?, ?, ?)");
    
    if ($stmt_features === false) {
        $errors[] = "Failed to prepare feature insertion statement: " . $conn->error;
    } else {
        foreach ($features_values as $feature_id => $value) {
            $unit = $features_units[$feature_id] ?? null;
            $data_type = $feature_types[$feature_id] ?? null;
            
            // This is the new logic to create a single JSON object for the value
            $feature_data = [];

            switch ($data_type) {
                case 'range':
                    // Handle 'range' type which has min, max, and an optional unit
                    $feature_data = [
                        'min' => filter_var($value['min'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                        'max' => filter_var($value['max'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                        'unit' => htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?? ''
                    ];
                    break;

                case 'multiselect':
                    // Handle 'multiselect' which has multiple values
                    $feature_data['values'] = array_map(function($val) {
                        return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                    }, $value);
                    break;
                
                case 'boolean':
                    // Handle 'boolean' which has a single value (1 or 0)
                    $feature_data['value'] = ($value === '1') ? 1 : 0;
                    break;

                case 'decimal(15,7)':
                    // Handle 'decimal' with an optional unit
                    $feature_data['value'] = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $feature_data['unit'] = htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?? '';
                    break;
                
                case 'varchar(50)':
                case 'TEXT':
                default:
                    // Handle simple text inputs
                    $feature_data['value'] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                    $feature_data['unit'] = htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?? '';
                    break;
            }

            // Encode the PHP array into a JSON string to save in the 'value' column
            $value_to_save = json_encode($feature_data);

            if (empty($errors)) {
                // Bind parameters for the prepared statement: product_id, feature_id, and the JSON string value
                $stmt_features->bind_param("iis", $product_id, $feature_id, $value_to_save);
                if (!$stmt_features->execute()) {
                    $errors[] = "Failed to save feature with ID {$feature_id}. Database error: " . $stmt_features->error;
                }
            }
        }
        $stmt_features->close();
    }
}
            // --- END OF UPDATED CODE ---

            $message = $is_update ? "Product updated successfully!" : "Product created successfully!";
            if (empty($errors)) {
                header("Location: ../auth/dashboard.php?page=create_product&success=" . urlencode($message));
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
