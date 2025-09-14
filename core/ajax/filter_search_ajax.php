<?php

require_once '../db/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header('Content-Type: application/json');

try {
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
    $filters = $_GET;

    $response = ['features' => [], 'products' => []];

    // Get features for the selected category (if a category is provided)
    if ($category_id > 0) {
        $stmt = $conn->prepare("SELECT * FROM features WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $features_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        // Fetch all features if no category is specified
        $stmt = $conn->prepare("SELECT * FROM features");
        $stmt->execute();
        $features_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
    $response['features'] = $features_result;

    // --- Build the dynamic SQL query for product filtering ---
    $sql = "SELECT p.* FROM products p";
    $joins = [];
    $conds = [];
    $params = [];
    $types = "";

    // --- NEW LOGIC: Determine if the category has children and adjust the query ---
    $childCategories = [];
    if ($category_id > 0) {
        $stmt_children = $conn->prepare("SELECT id FROM categories WHERE parent_id = ?");
        $stmt_children->bind_param("i", $category_id);
        $stmt_children->execute();
        $result_children = $stmt_children->get_result();
        while ($row = $result_children->fetch_assoc()) {
            $childCategories[] = $row['id'];
        }
        $stmt_children->close();
    }

    // Add the category filter to the query conditions
    if ($category_id > 0) {
        if (empty($childCategories)) {
            // No children, filter by the single category ID
            $conds[] = "p.category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        } else {
            // Children exist, filter by parent OR any of the children
            $in_placeholders = implode(',', array_fill(0, count($childCategories), '?'));
            $conds[] = "(p.category_id = ? OR p.category_id IN ($in_placeholders))";
            
            // Add parent category ID first, then all child IDs
            $params[] = $category_id;
            foreach ($childCategories as $childId) {
                $params[] = $childId;
            }
            
            $types .= "i" . str_repeat("i", count($childCategories));
        }
    }
    // --- END NEW LOGIC ---

    foreach ($features_result as $f) {
        $fname_val = "feature_{$f['id']}";
        $fname_unit = "feature_{$f['id']}_unit";

        // Only add joins and conditions if a filter value is provided in the URL
        if (isset($filters[$fname_val]) && !empty($filters[$fname_val])) {
            $alias = "pfv{$f['id']}";
            $joins[] = "JOIN product_feature_values $alias ON p.id = $alias.product_id AND $alias.feature_id = {$f['id']}";

            // Handle different data types based on the feature
            switch ($f['data_type']) {
                case 'multiselect':
                    $filter_value = $filters[$fname_val];
                    $conds[] = "JSON_CONTAINS($alias.value, ?, '$.values')";
                    // The value needs to be a JSON string for JSON_CONTAINS
                    $params[] = json_encode($filter_value);
                    $types .= "s";
                    break;

                case 'decimal(15,7)':
                    $conds[] = "JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.value')) = ?";
                    $params[] = $filters[$fname_val];
                    $types .= "s";
                    
                    if (isset($filters[$fname_unit]) && !empty($filters[$fname_unit])) {
                       $conds[] = "LOWER(TRIM(JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.unit')))) = LOWER(?)";
                       $params[] = trim($filters[$fname_unit]);
                       $types .= "s";
                    }
                    break;
                
                case 'boolean':
                    $conds[] = "JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.value')) = ?";
                    $params[] = $filters[$fname_val];
                    $types .= "s";
                    break;

                case 'range':
                    $range_parts = explode('-', $filters[$fname_val]);
                    if (count($range_parts) === 2) {
                        $min = (float)trim($range_parts[0]);
                        $max = (float)trim($range_parts[1]);
                        $conds[] = "CAST(JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.value')) AS DECIMAL(15,7)) BETWEEN ? AND ?";
                        $params[] = $min;
                        $params[] = $max;
                        $types .= "dd";
                    }
                    break;

                default: // Handles 'varchar(50)' and 'TEXT'
                    $conds[] = "TRIM(JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.value'))) = TRIM(?)";
                    $params[] = $filters[$fname_val];
                    $types .= "s";
                    break;
            }
        }
    }

    // Build the final SQL query
    if ($joins) $sql .= " " . implode(" ", array_unique($joins));
    if ($conds) $sql .= " WHERE " . implode(" AND ", $conds);
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $response['products'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
