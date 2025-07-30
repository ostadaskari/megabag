<?php
// core/stock/parse_csv.php
require_once('../db/db.php');
$file = '../../uploads/csv/' . basename($_GET['file'] ?? '');
if (!file_exists($file)) exit(json_encode(['error' => 'File not found']));

$rows = array_map('str_getcsv', file($file));
$header = array_map('trim', array_shift($rows));

$data = [];
foreach ($rows as $line) {
    $assoc = array_combine($header, $line);
    $part = trim($assoc['part_number']);
    
    $stmt = $conn->prepare("SELECT category_id FROM products WHERE part_number = ?");
    $stmt->bind_param("s", $part);
    $stmt->execute();
    $stmt->bind_result($catId);
    $exists = $stmt->fetch();
    $stmt->close();

    $assoc['exists'] = $exists;
    $assoc['category_id'] = $catId;
    $data[] = $assoc;
}
echo json_encode(['rows' => $data]);
