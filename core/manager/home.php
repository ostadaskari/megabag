<?php
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db/db.php';


// Query to get the total number of products
$productCountQuery = "SELECT COUNT(*) as total FROM products";
$productCountResult = $conn->query($productCountQuery);
$totalProducts = $productCountResult->fetch_assoc()['total'];

// Query to get the total number of categories
$categoryCountQuery = "SELECT COUNT(*) as total FROM categories";
$categoryCountResult = $conn->query($categoryCountQuery);
$totalCategories = $categoryCountResult->fetch_assoc()['total'];


// --- Render view ---
include('../../design/views/manager/home_view.php');

