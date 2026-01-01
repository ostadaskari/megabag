<?php
require_once("../db/db.php");

// Fetch all products without slugs
$result = $conn->query("SELECT id, part_number FROM products WHERE slug IS NULL OR slug = ''");

function makeSlug($partNumber, $existingSlugs = []) {
    // Replace slashes and invalid characters
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', str_replace('/', '-', $partNumber))));
    $originalSlug = $slug;
    $i = 1;

    // Ensure uniqueness (avoid duplicates)
    while (in_array($slug, $existingSlugs)) {
        $slug = $originalSlug . '-' . $i++;
    }

    return $slug;
}

// Fetch existing slugs to avoid duplicates
$existing = [];
$res = $conn->query("SELECT slug FROM products WHERE slug IS NOT NULL AND slug != ''");
while ($row = $res->fetch_assoc()) {
    $existing[] = $row['slug'];
}

$updated = 0;
while ($row = $result->fetch_assoc()) {
    $slug = makeSlug($row['part_number'], $existing);
    $stmt = $conn->prepare("UPDATE products SET slug = ? WHERE id = ?");
    $stmt->bind_param("si", $slug, $row['id']);
    $stmt->execute();
    $existing[] = $slug;
    $updated++;
}

echo "✅ Slugs generated for $updated products.\n";
