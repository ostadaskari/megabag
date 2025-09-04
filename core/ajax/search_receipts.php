<?php
require_once '../db/db.php';
require_once '../auth/check_manager.php';

$keyword = $_GET['keyword'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 13; //per page
$offset = ($page - 1) * $limit;

$conditions = [];
$params = [];
$types = '';

if (!empty($keyword)) {
    // Added product_lots.x_code to the search conditions
    $conditions[] = "(products.part_number LIKE ? OR users.name LIKE ? OR users.family LIKE ? OR users.nickname LIKE ? OR product_lots.x_code LIKE ?)";
    // We now have 5 parameters for the keyword
    for ($i = 0; $i < 5; $i++) {
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
    JOIN product_lots ON stock_receipts.product_lot_id = product_lots.id
    JOIN products ON product_lots.product_id = products.id
    JOIN users ON stock_receipts.user_id = users.id $where"); // Note: You'll want to adjust this COUNT query if you are joining tables with multiple matches. For now, it's fine.

if (!empty($params)) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$count = $countStmt->get_result()->fetch_row()[0];
$totalPages = ceil($count / $limit);

$query = "SELECT stock_receipts.*,
        products.part_number,
        products.mfg,
        users.nickname,
        product_lots.qty_received,
        product_lots.qty_available,
        product_lots.x_code,
        product_lots.vrm_x_code,
        product_lots.date_code
FROM stock_receipts
JOIN product_lots ON stock_receipts.product_lot_id = product_lots.id
JOIN products ON product_lots.product_id = products.id
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
    $shortRemarks = mb_strlen($remarks) > 10 ? htmlspecialchars(mb_substr($remarks, 0, 10)) . '...' : $remarks;
    $tooltip = $remarks ? "title=\"$remarks\"" : '';

    $html .= "<tr>
        <td>{$i}</td>
        <td>" . htmlspecialchars($row['x_code']) . "</td>
        <td>" . htmlspecialchars($row['part_number']) . "</td>
        <td>" . htmlspecialchars($row['mfg']) . "</td>
        <td>" . htmlspecialchars($row['date_code']) . "</td>
        <td>" . htmlspecialchars($row['vrm_x_code']) . "</td>

        <td>{$row['qty_received']}</td>
        <td>{$row['qty_available']}</td>
        <td>" . htmlspecialchars($row['nickname']) . "</td>
        <td><span {$tooltip}>" . $shortRemarks . "</span></td>
        <td>
            <div title=\"" . date('Y/n/d ,G:i', strtotime($row['created_at'])) . "\">
                <svg width=\"24\" height=\"24\" fill=\"mediumblue\" class=\"bi bi-clock hoverSvg\" viewBox=\"0 0 16 16\">
                    <path d=\"M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z\"></path>
                    <path d=\"M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0\"></path>
                </svg>
            </div>
        </td>
        <td class=\"flex justify-center space-x-2\">
            <button class=\"btnSvg hoverSvg\" style=\"font-size:15px;\" onclick=\"editReceipt({$row['id']})\" title=\"Edit\">
              <svg width=\"20\" height=\"20\" fill=\"var(--main-bg1-color)\" class=\"bi bi-pencil-square\" viewBox=\"0 0 16 16\">
                        <path d=\"M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z\"></path>
                        <path fill-rule=\"evenodd\" d=\"M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z\"></path>
                    </svg>
            </button>
            <button class=\"btnSvg hoverSvg\" style=\"font-size:15px;\" onclick=\"deleteReceipt({$row['id']})\" title=\"Delete\">
                <svg width=\"20\" height=\"20\" fill=\"#8b000d\" class=\"bi bi-trash hoverSvg\" viewBox=\"0 0 16 16\">
                        <path d=\"M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z\"></path>
                        <path d=\"M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z\"></path>
                    </svg>
            </button>
        </td>
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
    'html' => $html ?: '<tr><td colspan="12" class="text-center">No receipts found.</td></tr>', // Adjusted colspan for 12 columns
    'pagination' => $paginationHtml,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);

$stmt->close();
$conn->close();
?>
