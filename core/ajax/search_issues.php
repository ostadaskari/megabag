<?php
require_once '../db/db.php';
require_once '../auth/check_manager.php';

header('Content-Type: application/json');

$keyword = $_GET['keyword'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 14;
$offset = ($page - 1) * $limit;

$conditions = [];
$params = [];
$types = '';

if ($keyword) {
    // Search by product part number, x-code, vrm_x_code, issued-by user, or issued-to user
    $conditions[] = "(p.part_number LIKE ? OR pl.x_code LIKE ? OR pl.vrm_x_code LIKE ? OR u1.name LIKE ? OR u1.family LIKE ? OR u1.nickname LIKE ? OR u2.name LIKE ? OR u2.family LIKE ? OR u2.nickname LIKE ?)";
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
    SELECT COUNT(*) 
    FROM stock_issues si
    JOIN product_lots pl ON si.product_lot_id = pl.id
    JOIN products p ON pl.product_id = p.id
    JOIN users u1 ON si.user_id = u1.id
    JOIN users u2 ON si.issued_to = u2.id
    $where
");

if ($types) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_row()[0];
$totalPages = ceil($total / $limit);

$stmt = $conn->prepare("
    SELECT 
        si.id, si.qty_issued, si.remarks, si.created_at,
        pl.x_code, pl.vrm_x_code, pl.date_code,
        p.part_number, p.mfg,
        u1.nickname AS issued_by, u2.nickname AS issued_to
    FROM stock_issues si
    JOIN product_lots pl ON si.product_lot_id = pl.id
    JOIN products p ON pl.product_id = p.id
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
        <td>" . htmlspecialchars($row['x_code']) . "</td>
        <td>" . htmlspecialchars($row['part_number']) . "</td>
        <td>" . htmlspecialchars($row['vrm_x_code']) . "</td>
        <td>" . htmlspecialchars($row['mfg']) . "</td>
        <td>" . htmlspecialchars($row['date_code']) . "</td>
        <td>" . htmlspecialchars($row['qty_issued']) . "</td>
        <td>" . htmlspecialchars($row['issued_by']) . "</td>
        <td>" . htmlspecialchars($row['issued_to']) . "</td>
        <td>" . date('Y/m/d H:i', strtotime($row['created_at'])) . "</td>
        <td><span{$tooltip}>{$short}</span></td>
        <td class=\"flex justify-center space-x-2\">
            <button class=\"btnSvg hoverSvg\" style=\"font-size:15px;\" onclick=\"editIssue({$row['id']})\" title=\"Edit\">
                <svg width=\"20\" height=\"20\" fill=\"var(--main-bg1-color)\" class=\"bi bi-pencil-square\" viewBox=\"0 0 16 16\">
                    <path d=\"M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z\"></path>
                    <path fill-rule=\"evenodd\" d=\"M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z\"></path>
                </svg>
            </button>
            <button class=\"btnSvg hoverSvg\" style=\"font-size:15px;\" onclick=\"deleteIssue({$row['id']})\" title=\"Delete\">
                <svg width=\"20\" height=\"20\" fill=\"#8b000d\" class=\"bi bi-trash hoverSvg\" viewBox=\"0 0 16 16\">
                    <path d=\"M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z\"></path>
                    <path d=\"M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z\"></path>
                </svg>
            </button>
        </td>
    </tr>";
    $i++;
}

// send data to front 
echo json_encode([
    'html' => $html ?: '<tr><td colspan="11" class="text-center">No records found.</td></tr>', // Colspan for 11 columns
    'totalPages' => $totalPages,
    'currentPage' => $page
]);

$stmt->close();
$conn->close();
