<?php
require_once '../db/db.php';
require_once '../auth/check_manager.php';

$keyword = $_GET['keyword'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10; //per page
$offset = ($page - 1) * $limit;

$conditions = [];
$params = [];
$types = '';

if (!empty($keyword)) {
    $conditions[] = "(products.name LIKE ? OR products.tag LIKE ? OR products.part_number LIKE ? OR users.name LIKE ? OR users.family LIKE ? OR users.nickname LIKE ?)";
    for ($i = 0; $i < 6; $i++) {
        $params[] = "%$keyword%";
        $types .= 's';
    }
}

if (!empty($from)) {
    $conditions[] = "stock_receipts.created_at >= ?";
    $params[] = $from . " 00:00:00";
    $types .= 's';
}

if (!empty($to)) {
    $conditions[] = "stock_receipts.created_at <= ?";
    $params[] = $to . " 23:59:59";
    $types .= 's';
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$countStmt = $conn->prepare("SELECT COUNT(*) FROM stock_receipts 
    JOIN products ON stock_receipts.product_id = products.id
    JOIN users ON stock_receipts.user_id = users.id $where");

if (!empty($params)) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$count = $countStmt->get_result()->fetch_row()[0];
$totalPages = ceil($count / $limit);

$query = "SELECT stock_receipts.*, products.name AS product_name, products.part_number, products.tag, users.nickname 
          FROM stock_receipts 
          JOIN products ON stock_receipts.product_id = products.id
          JOIN users ON stock_receipts.user_id = users.id 
          $where 
          ORDER BY stock_receipts.created_at DESC 
          LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
// Append limit and offset types and parameters
$types .= 'ii';
$params[] = $limit;
$params[] = $offset;

if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$html = '';
$i = $offset + 1;
while ($row = $result->fetch_assoc()) {
    $remarks = htmlspecialchars($row['remarks']);
    $shortRemarks = mb_strlen($remarks) > 35 ? htmlspecialchars(mb_substr($remarks, 0, 35)) . '...' : $remarks;
    $tooltip = $remarks ? "title=\"$remarks\"" : '';

    $html .= "<tr>
        <td>{$i}</td>
        <td>" . htmlspecialchars($row['product_name']) . "</td>
        <td>" . htmlspecialchars($row['tag']) . "</td>
        <td>" . htmlspecialchars($row['part_number']) . "</td>
        <td>{$row['qty_received']}</td>
        <td>" . htmlspecialchars($row['nickname']) . "</td>
        <td>". date('Y/n/d ,G:i',strtotime($row['created_at'])) . "</td> 
        <td><span {$tooltip}>" . $shortRemarks . "</span></td>
    </tr>";
    $i++;
}

// Frontend Pagination HTML Generation
$paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml .= '<div class="row my-2"><div class="col-12 d-flex justify-content-center">';
    $paginationHtml .= '<div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">';

    // First button
    $firstDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $firstDisabled . '" onclick="fetchReceipts(1)" id="firstBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"></path></svg>';
    $paginationHtml .= 'First</a>';

    // Prev button
    $prevDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $prevDisabled . '" onclick="fetchReceipts(' . max(1, $page - 1) . ')" id="prevBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16"><path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"></path></svg>';
    $paginationHtml .= 'Prev</a>';

    // Page numbers
    $paginationHtml .= '<div class="px-4 px-custom">';
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);

    for ($p = $startPage; $p <= $endPage; $p++) {
        $activeClass = ($p == $page) ? 'text-danger' : '';
        $paginationHtml .= "<span class='px-1 fw-bold " . $activeClass . "'><a href='#' onclick='fetchReceipts($p)' style='text-decoration: none; color: inherit;'>" . $p . "</a></span>";
    }
    $paginationHtml .= '</div>';

    // Next button
    $nextDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $nextDisabled . '" onclick="fetchReceipts(' . min($totalPages, $page + 1) . ')" id="nextBtn">';
    $paginationHtml .= 'Next';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>';
    $paginationHtml .= '</a>';

    // Last button
    $lastDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $lastDisabled . '" onclick="fetchReceipts(' . $totalPages . ')" id="lastBtn">';
    $paginationHtml .= 'Last';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"></path></svg>';
    $paginationHtml .= '</a>';

    $paginationHtml .= '</div></div></div>';
}


echo json_encode([
    'html' => $html ?: '<tr><td colspan="8" class="text-center">No receipts found.</td></tr>', // Adjusted colspan for 8 columns
    'pagination' => $paginationHtml,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);

$stmt->close();
$conn->close();
?>