<?php
$file = basename($_POST['file'] ?? '');
$path = '../../uploads/csv/' . $file;
if (file_exists($path)) unlink($path);
echo json_encode(['success' => true]);