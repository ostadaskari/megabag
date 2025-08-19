<?php
require_once '../db/db.php';
session_start();

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
//limit per page
$limit = 8;
$offset = ($page - 1) * $limit;

$where = "WHERE 1";
$params = [];
$types = "";

// Search filters
if (!empty($keyword)) {
    $where .= " AND (products.name LIKE ? OR products.part_number LIKE ? OR products.tag LIKE ?)";
    $keywordParam = "%{$keyword}%";
    $params[] = $keywordParam;
    $params[] = $keywordParam;
    $params[] = $keywordParam;
    $types .= "sss";
}

// Status filter
if ($status === 'available') {
    $where .= " AND products.status = ?";
    $params[] = "available";
    $types .= "s";
}

// Count total for pagination
$countQuery = "SELECT COUNT(*) as total FROM products $where";
$countStmt = $conn->prepare($countQuery);
if ($types) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$total = $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = ceil($total / $limit);

// Fetch filtered products
$query = "SELECT products.*, users.nickname AS submitter, categories.name AS category_name 
          FROM products 
          LEFT JOIN users ON users.id = products.user_id 
          LEFT JOIN categories ON categories.id = products.category_id 
          $where
          ORDER BY products.created_at DESC
          LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$html = "";
$count = $offset + 1;
while ($row = $result->fetch_assoc()) {
    // Add data-id to the row for the frontend JavaScript to easily identify the product
    // The previous HTML file used this attribute to find the product ID
    $html .= "<tr data-id=\"{$row['id']}\">
        <td>{$count}</td>
        <td>" . htmlspecialchars($row['name']) . "</td>
        <td>" . htmlspecialchars($row['part_number']) . "</td>
        <td>" . htmlspecialchars($row['mfg']) . "</td>
        <td>" . htmlspecialchars($row['tag']) . "</td>
        <td>{$row['qty']}</td>
        <td>" . htmlspecialchars($row['submitter']) . "</td>
        <td>" . htmlspecialchars($row['category_name']) . "</td>
        <td>" . htmlspecialchars($row['location']) . "</td>
        <td>" . htmlspecialchars($row['status']) . "</td>


        <td class=\"flex justify-center space-x-2\">
            <button class=\"btnSvg\" style=\"font-size:15px;\" onclick=\"showProductDetails({$row['id']})\" title=\"View\">
                💭
            </button>
            <button class=\"btnSvg\" style=\"font-size:15px;\" onclick=\"editProduct({$row['id']})\" title=\"Edit\">
                ✏️
            </button>
            <button class=\"btnSvg\" style=\"font-size:15px;\" onclick=\"deleteProduct({$row['id']})\" title=\"Delete\">
                🗑️
            </button>
        </td>
    </tr>";
    $count++;
}
$stmt->close();

// Frontend Pagination HTML Generation
$paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml .= '<div class="row my-2"><div class="col-12 d-flex justify-content-center">';
    $paginationHtml .= '<div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">';

    // First button
    $firstDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $firstDisabled . '" onclick="fetchProducts(1)" id="firstBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"></path></svg>';
    $paginationHtml .= 'First</a>';

    // Prev button
    $prevDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $prevDisabled . '" onclick="fetchProducts(' . max(1, $page - 1) . ')" id="prevBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16"><path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"></path></svg>';
    $paginationHtml .= 'Prev</a>';

    // Page numbers
    $paginationHtml .= '<div class="px-4 px-custom">';
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);

    for ($p = $startPage; $p <= $endPage; $p++) {
        $activeClass = ($p == $page) ? 'text-danger' : '';
        $paginationHtml .= "<span class='px-1 fw-bold " . $activeClass . "'><a href='#' onclick='fetchProducts($p)' style='text-decoration: none; color: inherit;'>" . $p . "</a></span>";
    }
    $paginationHtml .= '</div>';

    // Next button
    $nextDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $nextDisabled . '" onclick="fetchProducts(' . min($totalPages, $page + 1) . ')" id="nextBtn">';
    $paginationHtml .= 'Next';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>';
    $paginationHtml .= '</a>';

    // Last button
    $lastDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $lastDisabled . '" onclick="fetchProducts(' . $totalPages . ')" id="lastBtn">';
    $paginationHtml .= 'Last';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"></path></svg>';
    $paginationHtml .= '</a>';

    $paginationHtml .= '</div></div></div>';
}


// Final JSON response
echo json_encode([
    'html' => $html ?: '<tr><td colspan="14" class="text-center">No products found.</td></tr>',
    'pagination' => $paginationHtml,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);

$conn->close();
?>