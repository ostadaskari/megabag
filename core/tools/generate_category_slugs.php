<?php
// Fix: Use __DIR__ to ensure the path is always relative to the current file's directory.
require_once(__DIR__ . "/../db/db.php");

// Fetch all categories without slugs
$result = $conn->query("SELECT id, name FROM categories WHERE slug IS NULL OR slug = ''");

/**
 * Generates a unique, SEO-friendly slug from a string (name or part number).
 *
 * @param string $sourceString The input string (e.g., category name, part number).
 * @param array $existingSlugs Array of currently existing slugs to check for uniqueness.
 * @return string The unique slug.
 */
function makeSlug($sourceString, $existingSlugs = []) {
    // 1. Convert to lowercase, replace slashes with hyphens, and remove invalid characters.
    // This is a simplified regex for basic Latin/Numeric slugs.
    // Note: For non-Latin languages like Farsi, a more complex transliteration/normalization
    // function might be needed, but this basic approach works well for English names.
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', str_replace('/', '-', $sourceString))));
    $originalSlug = $slug;
    $i = 1;

    // 2. Ensure uniqueness (avoid duplicates)
    // We check against the array of existing slugs from the database AND slugs created
    // in the current script run to prevent collisions.
    while (in_array($slug, $existingSlugs)) {
        $slug = $originalSlug . '-' . $i++;
    }

    return $slug;
}

// Fetch existing slugs from the 'categories' table to avoid runtime duplicates
$existing = [];
$res = $conn->query("SELECT slug FROM categories WHERE slug IS NOT NULL AND slug != ''");
while ($row = $res->fetch_assoc()) {
    $existing[] = $row['slug'];
}

$updated = 0;
while ($row = $result->fetch_assoc()) {
    // Use 'name' column for slug generation
    $slug = makeSlug($row['name'], $existing);
    
    // Update the category record
    $stmt = $conn->prepare("UPDATE categories SET slug = ? WHERE id = ?");
    $stmt->bind_param("si", $slug, $row['id']);
    $stmt->execute();
    
    // Add the newly created slug to the existing array to prevent duplicates in this batch
    $existing[] = $slug;
    $updated++;
}

echo "✅ Slugs generated for $updated categories.\n";

// Close the database connection
if (isset($conn)) {
    $conn->close();
}