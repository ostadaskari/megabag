<?php
require_once('../db/db.php');
require_once '../../vendor/autoload.php';// path to PhpSpreadsheet autoloader
session_start();

use PhpOffice\PhpSpreadsheet\IOFactory;

$csv_id = intval($_POST['csv_id'] ?? 0);
if (!$csv_id || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSV ID']);
    exit;
}

// Fetch file from DB
$stmt = $conn->prepare("SELECT file_name FROM uploaded_csvs WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $csv_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($fileName);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'File not found']);
    exit;
}
$stmt->close();

$filePath = "../../uploads/csv/" . $fileName;
if (!file_exists($filePath)) {
    echo json_encode(['success' => false, 'message' => 'File not found on server']);
    exit;
}

// Fetch all leaf categories
$catRes = $conn->query("SELECT id, name FROM categories WHERE id NOT IN (SELECT DISTINCT parent_id FROM categories WHERE parent_id IS NOT NULL)");
$leafCategories = [];
while ($row = $catRes->fetch_assoc()) {
    $leafCategories[] = $row;
}

// Load Excel file
try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray(null, true, true, true);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to read Excel file']);
    exit;
}

// Get headers from first row and map to a standardized format
$headers = array_map('strtolower', $data[1]);
$columnMapping = [
    'part nomber' => 'part_number',
    'mfg' => 'mfg',
    'tag' => 'tag',
    'qty' => 'qty',
    'p-name' => 'name',
    'comment' => 'remark'
];

$rows = [];

for ($i = 2; $i <= count($data); $i++) {
    $rowData = $data[$i];
    $row = [];

    foreach ($headers as $col => $key) {
        $standardKey = $columnMapping[$key] ?? $key;
        $row[$standardKey] = trim($rowData[$col] ?? '');
    }

    $part = $row['part_number'] ?? '';
    if (!$part) continue;

    // Check if product exists
    $stmt = $conn->prepare("SELECT p.id, p.category_id, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.part_number = ?");
    $stmt->bind_param("s", $part);
    $stmt->execute();
    $result = $stmt->get_result();
    $match = $result->fetch_assoc();
    $stmt->close();

    $row['is_new'] = $match ? false : true;
    $row['matched_category'] = $match['category_name'] ?? '';
    $row['matched_product_id'] = $match['id'] ?? null;

    $rows[] = $row;
}

echo json_encode([
    'success' => true,
    'rows' => $rows,
    'leaf_categories' => $leafCategories
]);
