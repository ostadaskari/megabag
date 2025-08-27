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
    
    $features_values = $_POST['feature'] ?? [];
    $features_units = $_POST['feature_unit'] ?? [];
    
    if (empty($errors)) {
        if ($is_update) {
            // It's an update. Prepare the UPDATE statement.
            $stmt = $conn->prepare("UPDATE products SET name = ?, part_number = ?, mfg = ?, qty = ?, company_cmt = ?, user_id = ?, category_id = ?, location = ?, status = ?, tag = ?, date_code = ?, recieve_code = ?, rf = ? WHERE id = ?");
            $stmt->bind_param("sssisiissssssi", $name, $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag, $date_code, $recieve_code, $rf, $product_id);
        } else {
            // It's a new product. Prepare the INSERT statement.
            $stmt = $conn->prepare("INSERT INTO products (name, part_number, mfg, qty, company_cmt, user_id, category_id, location, status, tag, date_code, recieve_code, rf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisiissssss", $name, $pn, $mfg, $qty, $company_cmt, $user_id, $category_id, $location, $status, $tag, $date_code, $recieve_code, $rf);
        }

        if ($stmt->execute()) {
            // Get the product_id for feature and file insertions
            if (!$is_update) {
                $product_id = $stmt->insert_id;
            }

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
                    $conn->query("DELETE FROM product_feature_values WHERE product_id = $product_id");
                }
                
                // Prepare statement once outside the loop
                $stmt_features = $conn->prepare("INSERT INTO product_feature_values (product_id, feature_id, value, unit) VALUES (?, ?, ?, ?)");
                
                if ($stmt_features === false) {
                    $errors[] = "Failed to prepare feature insertion statement: " . $conn->error;
                } else {
                    foreach ($features_values as $feature_id => $value) {
                        $unit = $features_units[$feature_id] ?? null;
                        $data_type = $feature_types[$feature_id] ?? null;
                        
                        $value_to_save = $value;
                        switch ($data_type) {
                            case 'decimal(12,3)':
                                if (!empty($value)) {
                                    $value_to_save = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    if ($value_to_save === false || !is_numeric($value_to_save)) {
                                        $errors[] = "Value for a decimal field is not a valid number.";
                                    }
                                }
                                break;
                            case 'TEXT':
                                $value_to_save = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                                break;
                            case 'boolean':
                                if ($value !== '1' && $value !== '0') {
                                    $errors[] = "Value for a boolean field is not valid.";
                                }
                                break;
                            case 'varchar(50)':
                            default:
                                $value_to_save = substr(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), 0, 50);
                                break;
                        }

                        $unit_to_save = htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?? '';

                        if (empty($errors)) {
                            $stmt_features->bind_param("iiss", $product_id, $feature_id, $value_to_save, $unit_to_save);
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