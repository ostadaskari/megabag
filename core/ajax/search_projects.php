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

// Correctly search only for project_name and employer, as per the projects table schema
if (!empty($keyword)) {
    $whereClause .= " AND (project_name LIKE ? OR employer LIKE ?)";
    $keywordParam = "%{$keyword}%";
    array_push($params, $keywordParam, $keywordParam);
    $types .= 'ss';
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
    // We need to bind the parameters for the count query as well
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$stmtCount->bind_result($totalRecords);
$stmtCount->fetch();
$stmtCount->close();
$totalPages = ceil($totalRecords / $recordsPerPage);

// Fetch projects
// Note: Removed date_code and purchase_code from the select statement as they are not in the projects table
$sql = "SELECT id, project_name, employer, status, created_at FROM projects " . $whereClause . " LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$types .= 'ii';
array_push($params, $offset, $recordsPerPage);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$html = '';
$count = $offset + 1; // Initialize the counter
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr class="tdColor" data-id="' . htmlspecialchars($row['id']) . '">';
        $html .= '<td scope="row">' . $count . '</td>'; // Use the counter here
        $html .= '<td>' . htmlspecialchars($row['project_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['employer']) . '</td>';
        $html .= '<td><span class="badge ' . ($row['status'] === 'finished' ? 'bg-success' : 'bg-secondary') . '">' . htmlspecialchars($row['status']) . '</span></td>';
        $html .= '<td>
                        <div title="' . htmlspecialchars($row['created_at']) . '">
                            <svg width="24" height="24" fill="mediumblue" class="bi bi-clock hoverSvg" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"></path>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"></path>
                            </svg>
                        </div>
                    </td>';

        $html .= '<td class="actions-cell">
                    <div class="d-flex justify-content-center">
                        <button onclick="editProject(' . htmlspecialchars($row['id']) . ')" class="btn btn-sm btn-primary btnSvg mx-1">
                            <svg width="20" height="20" fill="var(--main-bg1-color)" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </button>
                        <button onclick="deleteProject(' . htmlspecialchars($row['id']) . ')" class="btn btn-sm btn-danger btnSvg mx-1">
                            <svg width="20" height="20" fill="#8b000d" class="bi bi-trash hoverSvg" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                            </svg>
                        </button>
                    </div>
                </td>';
        $html .= '</tr>';
        $count++; // Increment the counter
    }
} else {
    $html .= '<tr><td colspan="8" class="text-center">No projects found.</td></tr>';
}

// Frontend Pagination HTML Generation
$paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml .= '<div class="row my-2"><div class="col-12 d-flex justify-content-center">';
    $paginationHtml .= '<div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">';

    // First button
    $firstDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $firstDisabled . '" onclick="searchProjects(1)" id="firstBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"></path></svg>';
    $paginationHtml .= 'First</a>';

    // Prev button
    $prevDisabled = ($page <= 1) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ' . $prevDisabled . '" onclick="searchProjects(' . max(1, $page - 1) . ')" id="prevBtn">';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16"><path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"></path></svg>';
    $paginationHtml .= 'Prev</a>';

    // Page numbers
    $paginationHtml .= '<div class="px-4 px-custom">';
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);

    for ($p = $startPage; $p <= $endPage; $p++) {
        $activeClass = ($p == $page) ? 'text-danger' : '';
        $paginationHtml .= "<span class='px-1 fw-bold " . $activeClass . "'><a href='#' onclick='searchProjects($p)' style='text-decoration: none; color: inherit;'>" . $p . "</a></span>";
    }
    $paginationHtml .= '</div>';

    // Next button
    $nextDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $nextDisabled . '" onclick="searchProjects(' . min($totalPages, $page + 1) . ')" id="nextBtn">';
    $paginationHtml .= 'Next';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>';
    $paginationHtml .= '</a>';

    // Last button
    $lastDisabled = ($page >= $totalPages) ? 'disabled' : '';
    $paginationHtml .= '<a href="#" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ' . $lastDisabled . '" onclick="searchProjects(' . $totalPages . ')" id="lastBtn">';
    $paginationHtml .= 'Last';
    $paginationHtml .= '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"></path></svg>';
    $paginationHtml .= '</a>';

    $paginationHtml .= '</div></div></div>';
}

echo json_encode(['html' => $html, 'pagination' => $paginationHtml]);

$stmt->close();
$conn->close();
