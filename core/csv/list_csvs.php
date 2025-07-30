<?php
$csvDir = '../../uploads/csv/';
$files = array_diff(scandir($csvDir), ['.', '..']);

$result = [];
foreach ($files as $file) {
    $path = $csvDir . $file;
    $result[] = [
        'name' => $file,
        'size_kb' => round(filesize($path) / 1024, 2)
    ];
}
echo json_encode(['files' => $result]);