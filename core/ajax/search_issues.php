<?php
require_once '../db/db.php';
require_once '../auth/check_manager.php';

header('Content-Type: application/json');

$keyword = $_GET['keyword'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 17;
$offset = ($page - 1) * $limit;

$conditions = [];
$params = [];
$types = '';

if ($keyword) {
    $conditions[] = "(p.name LIKE ? OR p.tag LIKE ? OR p.part_number LIKE ? OR u1.name LIKE ? OR u1.family LIKE ? OR u1.nickname LIKE ? OR u2.name LIKE ? OR u2.family LIKE ? OR u2.nickname LIKE ?)";
    for ($i = 0; $i < 9; $i++) {
        $params[] = "%$keyword%";
        $types .= 's';
    }
}

if ($from) {
    $conditions[] = "si.created_at >= ?";
    $params[] = $from . " 00:00:00";
    $types .= 's';
}
if ($to) {
    $conditions[] = "si.created_at <= ?";
    $params[] = $to . " 23:59:59";
    $types .= 's';
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$countStmt = $conn->prepare("
    SELECT COUNT(*) FROM stock_issues si
    JOIN products p ON si.product_id = p.id
    JOIN users u1 ON si.user_id = u1.id
    JOIN users u2 ON si.issued_to = u2.id
    $where
");

if ($types) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_row()[0];
$totalPages = ceil($total / $limit);

$stmt = $conn->prepare("
    SELECT si.*, p.name AS prod_name, p.part_number, p.tag,
           u1.nickname AS issued_by, u2.nickname AS issued_to
    FROM stock_issues si
    JOIN products p ON si.product_id = p.id
    JOIN users u1 ON si.user_id = u1.id
    JOIN users u2 ON si.issued_to = u2.id
    $where
    ORDER BY si.created_at DESC
    LIMIT ? OFFSET ?
");

// Append limit and offset types and parameters
$types .= 'ii';
$params[] = $limit;
$params[] = $offset;

if (!empty($params)) { // Ensure params array is not empty before binding
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

$html = '';
$i = $offset + 1;
while ($row = $res->fetch_assoc()) {
    $remarks = htmlspecialchars($row['remarks']);
    $short = mb_strlen($remarks) > 35 ? htmlspecialchars(mb_substr($remarks, 0, 35)) . '...' : $remarks;
    $tooltip = $remarks ? " title=\"$remarks\"" : '';

    $html .= "<tr>
        <td>{$i}</td>
        <td>" . htmlspecialchars($row['prod_name']) . "</td>
        <td>" . htmlspecialchars($row['part_number']) . "</td>
        <td>" . htmlspecialchars($row['tag']) . "</td>
        <td>{$row['qty_issued']}</td>
        <td>" . htmlspecialchars($row['issued_by']) . "</td>
        <td>" . htmlspecialchars($row['issued_to']) . "</td>
        <td>". date('Y/n/d ,G:i',strtotime($row['created_at'])) ."</td>
        <td><span{$tooltip}>{$short}</span></td>
    </tr>";
    $i++;
}

// Build frontend pagination HTML
$paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml .= '<div class="row my-2"><div class="col-12 d-flex justify-content-center">';
    $paginationHtml .= '<div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">';

    // First button
    $firstDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $firstDisabled . '" onclick="fetchIssues(1)" id="firstBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"></path></svg>';
    $paginationHtml .= 'First</a>';

    // Prev button
    $prevDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $prevDisabled . '" onclick="fetchIssues(' . max(1, $page - 1) . ')" id="prevBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16"><path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"></path></svg>';
    $paginationHtml .= 'Prev</a>';

    // Page numbers
    $paginationHtml .= '<div class="px-4 px-custom">';
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);

    for ($p = $startPage; $p <= $endPage; $p++) {
        $activeClass = ($p == $page) ? 'text-danger' : '';
        $paginationHtml .= "<span class='px-1 fw-bold " . $activeClass . "'><a href='#' onclick='fetchIssues($p)' style='text-decoration: none; color: inherit;'>" . $p . "</a></span>";
    }
    $paginationHtml .= '</div>';

    // Next button
    $nextDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $nextDisabled . '" onclick="fetchIssues(' . min($totalPages, $page + 1) . ')" id="nextBtn">';
    $paginationHtml .= 'Next';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>';
    $paginationHtml .= '</a>';

    // Last button
    $lastDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $lastDisabled . '" onclick="fetchIssues(' . $totalPages . ')" id="lastBtn">';
    $paginationHtml .= 'Last';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"></path></svg>';
    $paginationHtml .= '</a>';

    $paginationHtml .= '</div></div></div>';
}

// send data to front 
echo json_encode([
    'html' => $html ?: '<tr><td colspan="9" class="text-center">No records found.</td></tr>', // Colspan for 9 columns
    'pagination' => $paginationHtml,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);

$stmt->close();
$conn->close();
?>