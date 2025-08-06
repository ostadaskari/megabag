<?php
require_once '../db/db.php';
session_start();

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
//limit per page
$limit = 10;
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
    $html .= "<tr>
        <td>{$count}</td>
        <td>" . htmlspecialchars($row['name']) . "</td>
        <td>" . htmlspecialchars($row['part_number']) . "</td>
        <td>" . htmlspecialchars($row['mfg']) . "</td>
        <td>{$row['qty']}</td>
        <td>" . htmlspecialchars($row['submitter']) . "</td>
        <td>" . htmlspecialchars($row['category_name']) . "</td>
        <td>" . date("Y-m-d", strtotime($row['created_at'])) . "</td>
        <td>" . htmlspecialchars($row['location']) . "</td>
        <td>" . htmlspecialchars($row['status']) . "</td>
        <td>" . htmlspecialchars($row['tag']) . "</td>
        <td>" . htmlspecialchars($row['date_code']) . "</td>
        <td>" . htmlspecialchars($row['recieve_code']) . "</td>
        <td>
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

// Build pagination HTML
$pagination = '';
$pagination = '<div class="pagination"><ul>';

if ($page > 1) {
    $pagination .= '<li><a href="#" data-page="1">« First</a></li>';
    $pagination .= '<li><a href="#" data-page="' . ($page - 1) . '">‹ Prev</a></li>';
}

for ($i = 1; $i <= $totalPages; $i++) {
    $style = $i == $page ? ' style="font-weight:bold;"' : '';
    $pagination .= '<li><a href="#" data-page="' . $i . '"' . $style . '>' . $i . '</a></li>';
}

if ($page < $totalPages) {
    $pagination .= '<li><a href="#" data-page="' . ($page + 1) . '">Next ›</a></li>';
    $pagination .= '<li><a href="#" data-page="' . $totalPages . '">Last »</a></li>';
}

$pagination .= '</ul></div>';


// Final JSON response
echo json_encode([
    'html' => $html,
    'pagination' => $pagination
]);
