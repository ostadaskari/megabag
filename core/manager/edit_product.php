<?php
session_start();
require_once '../db/db.php';
// (ACL) Restrict access to admins/managers only  (access level )
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';
$product = null;
$images = [];
$pdfs = [];

// Load product data on GET

    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product) {
        $errors[] = 'Product not found.';
    } else {
        // Fetch images
        $stmt = $conn->prepare("SELECT * FROM images WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Fetch PDFs
        $stmt = $conn->prepare("SELECT * FROM pdfs WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $pdfs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
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
        $stmt = $conn->prepare("UPDATE products SET name=?, part_number=?, MFG=?, qty=?, company_cmt=?, location=?, status=?, tag=?, date_code=?, recieve_code=?, category_id=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("sssissssssii", $name, $p_n, $MFG, $qty, $company_cmt, $location, $status, $tag, $date_code, $receive_code, $category_id, $id);
        if ($stmt->execute()) {
            $success = "Product updated successfully.";
        } else {
            $errors[] = "Failed to update product.";
        }
        $stmt->close();

        // Upload images
        $imageDir = '../../uploads/images/';
        if (!file_exists($imageDir)) mkdir($imageDir, 0777, true);

        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $key => $filename) {
                $tmp_name = $_FILES['images']['tmp_name'][$key];
                $size = $_FILES['images']['size'][$key];
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    if ($size > 20 * 1024 * 1024) {
                        $errors[] = "$filename exceeds the 20MB limit.";
                        continue;
                    }

                    $target = "../../uploads/images/" . uniqid() . "_" . basename($filename);

                    if (move_uploaded_file($tmp_name, $target)) {
                        $stmt = $conn->prepare("INSERT INTO images (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $stmt->bind_param("issis", $id,  $filename, $target, $size, $ext);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }

        // Upload PDFs
        $pdfDir = '../../uploads/pdfs/';
        if (!file_exists($pdfDir)) mkdir($pdfDir, 0777, true);

        if (!empty($_FILES['pdfs']['name'][0])) {
            foreach ($_FILES['pdfs']['name'] as $key => $filename) {
                $tmp_name = $_FILES['pdfs']['tmp_name'][$key];
                $size = $_FILES['pdfs']['size'][$key];
                if ($_FILES['pdfs']['error'][$key] === UPLOAD_ERR_OK) {
                    if ($size > 20 * 1024 * 1024) {
                        $errors[] = "$filename exceeds the 20MB limit.";
                        continue;
                    }

                    $target = "../../uploads/pdfs/" . uniqid() . "_" . basename($filename);

                    if (move_uploaded_file($tmp_name, $target)) {
                        $stmt = $conn->prepare("INSERT INTO pdfs (product_id, file_name, file_path, file_size, file_extension) VALUES (?, ?,?, ?, ?)");
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $stmt->bind_param("issis", $id, $filename, $target, $size, $ext);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }

        // Reload updated data
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM images WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM pdfs WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $pdfs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

require_once '../../design/views//manager/edit_product_view.php';

