<?php
require_once '../db/db.php';
// Check if a session has already been started before starting a new one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// (ACL) Restrict access to admins/managers only (access level)
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

header('Content-Type: application/json');

$keyword = $_GET['keyword'] ?? '';
$status = $_GET['status'] ?? '';
$page = (int) ($_GET['page'] ?? 1);
$recordsPerPage = 10;
$offset = ($page - 1) * $recordsPerPage;

$whereClause = "WHERE 1";
$params = [];
$types = '';

if (!empty($keyword)) {
    $whereClause .= " AND (project_name LIKE ? OR employer LIKE ? OR purchase_code LIKE ?)";
    $keywordParam = "%{$keyword}%";
    array_push($params, $keywordParam, $keywordParam, $keywordParam);
    $types .= 'sss';
}

if (!empty($status)) {
    $whereClause .= " AND status = ?";
    array_push($params, $status);
    $types .= 's';
}

// Count total records
$sqlCount = "SELECT COUNT(*) FROM projects " . $whereClause;
$stmtCount = $conn->prepare($sqlCount);
if ($types) {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$stmtCount->bind_result($totalRecords);
$stmtCount->fetch();
$stmtCount->close();
$totalPages = ceil($totalRecords / $recordsPerPage);

// Fetch projects
$sql = "SELECT id, project_name, date_code, employer, purchase_code, status, created_at FROM projects " . $whereClause . " LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$types .= 'ii';
array_push($params, $offset, $recordsPerPage);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$html = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr data-id="' . htmlspecialchars($row['id']) . '">';
        $html .= '<th scope="row">' . htmlspecialchars($row['id']) . '</th>';
        $html .= '<td>' . htmlspecialchars($row['project_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['date_code']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['employer']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['purchase_code']) . '</td>';
        $html .= '<td><span class="badge ' . ($row['status'] === 'finished' ? 'bg-success' : 'bg-secondary') . '">' . htmlspecialchars($row['status']) . '</span></td>';
        $html .= '<td>' . htmlspecialchars($row['created_at']) . '</td>';
        $html .= '<td class="actions-cell">
            <div class="d-flex justify-content-center">
                <a href="../auth/dashboard.php?page=edit_project&project_id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-primary mx-1">
                    <svg width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                </a>
                <button onclick="deleteProject(' . htmlspecialchars($row['id']) . ')" class="btn btn-sm btn-danger mx-1">
                    <svg width="18" height="18" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4-4-4-3.999-4.002zM6 2h4v.5a.5.5 0 0 0 .5.5h1.5a.5.5 0 0 0 .5-.5V2a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5zm1 1h4a.5.5 0 0 1 .5.5v1h-5v-1a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                </button>
            </div>
        </td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="8" class="text-center">No projects found.</td></tr>';
}

$paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml .= '<nav aria-label="Page navigation"><ul class="pagination">';
    if ($page > 1) {
        $paginationHtml .= '<li class="page-item"><a class="page-link" href="#" data-page="' . ($page - 1) . '">Previous</a></li>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = ($i == $page) ? 'active' : '';
        $paginationHtml .= '<li class="page-item ' . $activeClass . '"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
    }
    if ($page < $totalPages) {
        $paginationHtml .= '<li class="page-item"><a class="page-link" href="#" data-page="' . ($page + 1) . '">Next</a></li>';
    }
    $paginationHtml .= '</ul></nav>';
}

echo json_encode(['html' => $html, 'pagination' => $paginationHtml]);

$stmt->close();
$conn->close();
