<?php
require_once('../db/db.php');
session_start();

$csv_id = intval($_POST['csv_id'] ?? 0);
if (!$csv_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSV ID']);
    exit;
}

// Read the uploaded CSV file path
$stmt = $conn->prepare("SELECT file_name FROM uploaded_csvs WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $csv_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($fileName);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'CSV file not found']);
    exit;
}
$stmt->close();

$csvPath = "../../uploads/csv/" . $fileName;
if (!file_exists($csvPath)) {
    echo json_encode(['success' => false, 'message' => 'File not found on server']);
    exit;
}

// Fetch all leaf categories
$catRes = $conn->query("SELECT id, name FROM categories WHERE id NOT IN (SELECT DISTINCT parent_id FROM categories WHERE parent_id IS NOT NULL)");
$leafCategories = [];
while ($row = $catRes->fetch_assoc()) {
    $leafCategories[] = $row;
}

// Parse CSV rows
$rows = [];
if (($handle = fopen($csvPath, "r")) !== false) {
    $headers = fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== false) {
        $row = array_combine($headers, $data);
        $part = trim($row['part_number']);

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
    fclose($handle);
}

echo json_encode(['success' => true, 'rows' => $rows, 'leaf_categories' => $leafCategories]);





