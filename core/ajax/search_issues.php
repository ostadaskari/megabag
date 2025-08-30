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
    SELECT si.*, p.name AS prod_name, p.part_number, p.tag, p.mfg,
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

        <td>" . htmlspecialchars($row['part_number']) . "</td>
        <td>" . htmlspecialchars($row['mfg']) . "</td>
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


// send data to front 
echo json_encode([
    'html' => $html ?: '<tr><td colspan="9" class="text-center">No records found.</td></tr>', // Colspan for 9 columns

    'totalPages' => $totalPages,
    'currentPage' => $page
]);

$stmt->close();
$conn->close();
