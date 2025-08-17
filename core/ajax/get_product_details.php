<?php
/**
 * Fetches and displays detailed information for a single product.
 * This script is called via an AJAX request from the frontend to populate a modal.
 */
require_once '../db/db.php';

// Get the product ID from the URL query parameter.
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId > 0) {
    // Prepare a secure SQL query to prevent SQL injection.
    // It joins the 'users' and 'categories' tables to get related names.
    $query = "SELECT products.*, users.nickname AS submitter, categories.name AS category_name 
              FROM products 
              LEFT JOIN users ON users.id = products.user_id 
              LEFT JOIN categories ON categories.id = products.category_id 
              WHERE products.id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
    
    // Check if a product was found.
    if ($product) {
        // Generate the HTML to display the product details in a formatted way.
        // Using htmlspecialchars to prevent XSS attacks.
        $html = "<div class='container-fluid p-0'>
            <div class='row'>
                <div class='col-sm-6 mb-2'><strong>Name:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['name']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Part Number:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['part_number']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Manufacturer:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['mfg']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Quantity:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['qty']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Location:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['location']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Status:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['status']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Submitter:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['submitter']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Category:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['category_name']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Submit Date:</strong></div>
                <div class='col-sm-6 mb-2'>" . date("Y/m/d H:i:s", strtotime($product['created_at'])) . "</div>
                <div class='col-sm-6 mb-2'><strong>Tag:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['tag']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Date Code:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['date_code']) . "</div>
                <div class='col-sm-6 mb-2'><strong>Recieve Code:</strong></div>
                <div class='col-sm-6 mb-2'>" . htmlspecialchars($product['recieve_code']) . "</div>
            </div>
        </div>";
        echo $html;
    } else {
        echo "<p class='text-center text-danger'>Product not found.</p>";
    }
} else {
    echo "<p class='text-center text-danger'>Invalid product ID.</p>";
}

// Close the database connection.
$conn->close();
?>