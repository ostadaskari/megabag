<?php
require_once '../db/db.php';
session_start();
// (ACL) Restrict access to admins/managers only  (access level )
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $productId = (int) $_POST['product_id'];

    // First: Delete images from disk and DB
    $imageStmt = $conn->prepare("SELECT file_path FROM images WHERE product_id = ?");
    $imageStmt->bind_param("i", $productId);
    $imageStmt->execute();
    $imageResult = $imageStmt->get_result();
    while ($row = $imageResult->fetch_assoc()) {
        $file = "../../uploads/images/" . $row['file_name'];
        if (file_exists($file)) {
            unlink($file);  // Delete the file
        }
    }
        // Delete image records
    $stmtDelImages = $conn->prepare("DELETE FROM images WHERE product_id = ?");
    if ($stmtDelImages) {
        $stmtDelImages->bind_param("i", $productId);
        $stmtDelImages->execute();
        $stmtDelImages->close();
    }

    // Then: Delete PDFs from disk and DB
    $pdfStmt = $conn->prepare("SELECT file_path FROM pdfs WHERE product_id = ?");
    $pdfStmt->bind_param("i", $productId);
    $pdfStmt->execute();
    $pdfResult = $pdfStmt->get_result();
    while ($row = $pdfResult->fetch_assoc()) {
        $file = "../../uploads/pdfs/" . $row['file_name'];
        if (file_exists($file)) {
            unlink($file);
        }
    }
     // Delete PDF records
    $stmtDelPDFs = $conn->prepare("DELETE FROM pdfs WHERE product_id = ?");
    if ($stmtDelPDFs) {
        $stmtDelPDFs->bind_param("i", $productId);
        $stmtDelPDFs->execute();
        $stmtDelPDFs->close();
    }

    // Finally: Delete product
 // Delete the product itself
    $stmtProduct = $conn->prepare("DELETE FROM products WHERE id = ?");
    if ($stmtProduct) {
        $stmtProduct->bind_param("i", $productId);
        $stmtProduct->execute();
        $stmtProduct->close();
    }

    // Optionally show a success message
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Product deleted successfully.',
        }).then(() => window.location.reload());
    </script>";
    // Redirect to avoid re-submission
    header("Location: products_list.php");
    exit;
}


try {
    $stmt = $conn->prepare("
        SELECT 
            p.*, 
            c.name AS category_name,
            u.nickname AS submitted_by
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    // Handle delete action
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
        $deleteStmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $deleteStmt->bind_param("i", $_POST['product_id']);
        $deleteStmt->execute();
        header("Location: products_list.php");
        exit;
    }
} catch (Exception $e) {
    $products = [];
}
require_once '../../design/views/manager/products_list_view.php';
