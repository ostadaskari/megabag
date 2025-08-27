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
    $status = trim($_POST['status'] ?? 'available');
    $tag = trim($_POST['tag']);
    $date_code = trim($_POST['date_code']);
    $receive_code = trim($_POST['recieve_code']);
    $category_id = intval($_POST['category_id']);
    // Check if the 'rf' checkbox is set and assign 1 or 0
    $rf = isset($_POST['rf']) ? 1 : 0; 
    
    // Get the features data from the form
    $features = $_POST['features'] ?? [];


    if (empty($name)) {
        $errors[] = "Product name is required.";
    }

    if (empty($errors)) {
        // Update product details, including the new 'rf' column
        $stmt = $conn->prepare("UPDATE products SET name=?, part_number=?, mfg=?, qty=?, company_cmt=?, location=?, status=?, tag=?, date_code=?, recieve_code=?, category_id=?, rf=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("sssissssssiii", $name, $p_n, $MFG, $qty, $company_cmt, $location, $status, $tag, $date_code, $receive_code, $category_id, $rf, $id);
        if ($stmt->execute()) {
            $stmt->close();
            
            // --- UPDATED LOGIC FOR SAVING FEATURES ---
            // First, delete all existing features for this product
            $stmtDelFeatures = $conn->prepare("DELETE FROM product_feature_values WHERE product_id = ?");
            $stmtDelFeatures->bind_param("i", $id);
            $stmtDelFeatures->execute();
            $stmtDelFeatures->close();

            // Then, re-insert the features submitted with the form
            if (!empty($features)) {
                // Fetch feature data types for validation
                $feature_ids_in = implode(',', array_map('intval', array_keys($features)));
                $feature_types_query = $conn->query("SELECT id, data_type FROM features WHERE id IN ($feature_ids_in)");
                $feature_types = [];
                if ($feature_types_query) {
                    while ($row = $feature_types_query->fetch_assoc()) {
                        $feature_types[$row['id']] = $row['data_type'];
                    }
                }
                
                // Prepare statement once outside the loop
                $stmtInsertFeatures = $conn->prepare("INSERT INTO product_feature_values (product_id, feature_id, value, unit) VALUES (?, ?, ?, ?)");
                
                if ($stmtInsertFeatures === false) {
                    $errors[] = "Failed to prepare feature insertion statement: " . $conn->error;
                } else {
                    foreach ($features as $feature_id => $feature_data) {
                        $value = trim($feature_data['value'] ?? '');
                        $unit = trim($feature_data['unit'] ?? '');
                        $data_type = $feature_types[$feature_id] ?? null;

                        if (!empty($value)) {
                            $value_to_save = $value;
                            switch ($data_type) {
                                case 'decimal(12,3)':
                                    $value_to_save = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    if ($value_to_save === false) {
                                        $errors[] = "Invalid decimal value for feature ID {$feature_id}.";
                                    }
                                    break;
                                case 'boolean':
                                    $value_to_save = ($value == '1') ? '1' : '0';
                                    break;
                                case 'TEXT':
                                default:
                                    // For TEXT, varchar, and other types, sanitize the input
                                    $value_to_save = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                                    break;
                            }
                            
                            $unit_to_save = htmlspecialchars($unit, ENT_QUOTES, 'UTF-8');
                            
                            if (empty($errors)) {
                                $stmtInsertFeatures->bind_param("iiss", $id, $feature_id, $value_to_save, $unit_to_save);
                                if (!$stmtInsertFeatures->execute()) {
                                    $errors[] = "Failed to save feature with ID {$feature_id}. Database error: " . $stmtInsertFeatures->error;
                                }
                            }
                        }
                    }
                    $stmtInsertFeatures->close();
                }
            }
            // --- END OF UPDATED LOGIC ---


            // --- File Upload Logic ---
            $imageDir = '../../uploads/images/';
            $pdfDir = '../../uploads/pdfs/';
            if (!file_exists($imageDir)) mkdir($imageDir, 0777, true);
            if (!file_exists($pdfDir)) mkdir($pdfDir, 0777, true);

            // Define allowed file types and max size
            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
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
                    $newName = $p_n . "-cover." . $fileExt;
                    $target = $imageDir . $newName;

                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $stmtCover = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension, is_cover) VALUES (?, ?, ?, ?, ?, 1)");
                        $stmtCover->bind_param("issis", $id, $newName, $target, $file['size'], $fileExt);
                        $stmtCover->execute();
                        $stmtCover->close();
                    } else {
                        $errors[] = "Failed to upload cover image.";
                    }
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
                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                        if (!in_array($ext, $allowedImageExtensions)) {
                            $errors[] = "Image file '$filename' has an invalid file format.";
                        } elseif ($size > $maxFileSize) {
                            $errors[] = "Image file '$filename' size exceeds the 20MB limit.";
                        } else {
                            $newName = $p_n . "-img-" . ($currentImgCount + $index + 1) . "." . $ext;
                            $target = $imageDir . $newName;

                            if (move_uploaded_file($tmpName, $target)) {
                                $stmtImg = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                                $stmtImg->bind_param("issis", $id, $newName, $target, $size, $ext);
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
                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        
                        if (!in_array($ext, $allowedDocExtensions)) {
                            $errors[] = "Document file '$filename' has an invalid file format.";
                        } elseif ($size > $maxFileSize) {
                            $errors[] = "Document file '$filename' size exceeds the 20MB limit.";
                        } else {
                            $newName = $p_n . "-pdf-" . ($currentPdfCount + $index + 1) . "." . $ext;
                            $target = $pdfDir . $newName;

                            if (move_uploaded_file($tmpName, $target)) {
                                $stmtPdf = $conn->prepare("INSERT INTO pdfs (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?,?, ?, ?)");
                                $stmtPdf->bind_param("issis", $id, $newName, $target, $size, $ext);
                                $stmtPdf->execute();
                                $stmtPdf->close();
                            } else {
                                $errors[] = "Failed to upload document '$filename'.";
                            }
                        }
                    }
                }
            }

            // Redirect with success or error messages after all file handling
            if (empty($errors)) {
                header("Location: ../auth/dashboard.php?page=edit_product&id=$id&success=" . urlencode("Product updated successfully."));
            } else {
                header("Location: ../auth/dashboard.php?page=edit_product&id=$id&error=" . urlencode(implode(' | ', $errors)));
            }
            exit;
        } else {
            $errors[] = "Failed to update product details.";
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
