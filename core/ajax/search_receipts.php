<?php
require_once '../db/db.php';

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
          LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($query);
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
        <td>{$row['created_at']}</td>
        <td><span $tooltip>" . $shortRemarks . "</span></td>
    </tr>";
    $i++;
}

// Pagination
$pagination = '';
if ($totalPages > 1) {
    $pagination .= '<nav><ul class="pagination justify-content-center">';
    $pagination .= ($page > 1) ? '<li class="page-item"><a class="page-link" href="#" onclick="fetchReceipts(1)">First</a></li>' : '';
    $pagination .= ($page > 1) ? '<li class="page-item"><a class="page-link" href="#" onclick="fetchReceipts(' . ($page - 1) . ')">Prev</a></li>' : '';

    for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++) {
        $active = ($p == $page) ? 'active' : '';
        $pagination .= "<li class='page-item $active'><a class='page-link' href='#' onclick='fetchReceipts($p)'>$p</a></li>";
    }

    $pagination .= ($page < $totalPages) ? '<li class="page-item"><a class="page-link" href="#" onclick="fetchReceipts(' . ($page + 1) . ')">Next</a></li>' : '';
    $pagination .= ($page < $totalPages) ? '<li class="page-item"><a class="page-link" href="#" onclick="fetchReceipts(' . $totalPages . ')">Last</a></li>' : '';
    $pagination .= '</ul></nav>';
}

echo json_encode([
    'html' => $html ?: '<tr><td colspan="7" class="text-center">No receipts found.</td></tr>',
    'pagination' => $pagination
]);
