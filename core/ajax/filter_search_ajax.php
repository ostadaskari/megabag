<?php
// This script now fetches products, their feature values, and associated lot details.
// It uses a more efficient query pattern to avoid N+1 issues.

require_once '../db/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header('Content-Type: application/json');

try {
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
    $filters = $_GET;

    $response = ['features' => [], 'products' => []];

    // Get features for the selected category (if a category is provided)
    $features_result = [];
    if ($category_id > 0) {
        $stmt = $conn->prepare("SELECT * FROM features WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $features_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        // Fetch all features if no category is specified (for general search)
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

    // --- CHANGED SECTION: Use a recursive query to find all descendant categories ---
    $descendantCategories = [];
    if ($category_id > 0) {
        // This SQL query recursively finds the selected category and all its children, grandchildren, etc.
        $sql_descendants = "
            WITH RECURSIVE CategoryHierarchy AS (
                -- Start with the selected category itself
                SELECT id FROM categories WHERE id = ?
                UNION ALL
                -- Recursively find all children
                SELECT c.id FROM categories c
                INNER JOIN CategoryHierarchy ch ON c.parent_id = ch.id
            )
            SELECT id FROM CategoryHierarchy
        ";
        $stmt_descendants = $conn->prepare($sql_descendants);
        $stmt_descendants->bind_param("i", $category_id);
        $stmt_descendants->execute();
        $result_descendants = $stmt_descendants->get_result();
        while ($row = $result_descendants->fetch_assoc()) {
            $descendantCategories[] = $row['id'];
        }
        $stmt_descendants->close();
    }

    // Add the category filter to the query conditions using all descendant IDs
    if (!empty($descendantCategories)) {
        $in_placeholders = implode(',', array_fill(0, count($descendantCategories), '?'));
        $conds[] = "p.category_id IN ($in_placeholders)";
        
        foreach ($descendantCategories as $descendantId) {
            $params[] = $descendantId;
        }
        
        $types .= str_repeat("i", count($descendantCategories));
    } elseif ($category_id > 0) {
        // Fallback for a category that might not have been found or has no descendants
        $conds[] = "p.category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }
    // --- END OF CHANGED SECTION ---

    foreach ($features_result as $f) {
        $fname_val = "feature_{$f['id']}";
        $fname_unit = "feature_{$f['id']}_unit";

        if (isset($filters[$fname_val]) && !empty($filters[$fname_val])) {
            $alias = "pfv{$f['id']}";
            $joins[] = "JOIN product_feature_values $alias ON p.id = $alias.product_id AND $alias.feature_id = {$f['id']}";

            switch ($f['data_type']) {
                case 'multiselect':
                    $filter_value = $filters[$fname_val];
                    $conds[] = "JSON_CONTAINS($alias.value, ?, '$.values')";
                    $params[] = json_encode($filter_value);
                    $types .= "s";
                    break;
                case 'decimal(15,7)':
                    $conds[] = "CAST(JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.value')) AS DECIMAL(15,7)) = ?";
                    $params[] = $filters[$fname_val];
                    $types .= "d";
                    
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
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // --- Now, fetch features and lots for all found products in a single go ---
    $product_ids = array_column($products, 'id');
    $products_with_details = [];

    if (!empty($product_ids)) {
        $id_placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        $id_types = str_repeat('i', count($product_ids));

        // Fetch all product features
        $sql_features = "SELECT pfv.product_id, pfv.value, f.name, f.unit 
                         FROM product_feature_values pfv 
                         JOIN features f ON pfv.feature_id = f.id 
                         WHERE pfv.product_id IN ($id_placeholders)";
        $stmt_features = $conn->prepare($sql_features);
        $stmt_features->bind_param($id_types, ...$product_ids);
        $stmt_features->execute();
        $features_data = $stmt_features->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_features->close();

        $features_by_product = [];
        foreach ($features_data as $feature) {
            $decoded_value = json_decode($feature['value'], true);
            $features_by_product[$feature['product_id']][] = [
                'name' => $feature['name'],
                'value' => $decoded_value['value'] ?? 'N/A',
                'unit' => $decoded_value['unit'] ?? $feature['unit']
            ];
        }

        // Fetch all product lots
        $sql_lots = "SELECT * FROM product_lots WHERE product_id IN ($id_placeholders)";
        $stmt_lots = $conn->prepare($sql_lots);
        $stmt_lots->bind_param($id_types, ...$product_ids);
        $stmt_lots->execute();
        $lots_data = $stmt_lots->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_lots->close();

        $lots_by_product = [];
        foreach ($lots_data as $lot) {
            $lots_by_product[$lot['product_id']][] = $lot;
        }

        // Merge features and lots back into the main products array
        foreach ($products as $product) {
            $product['features'] = $features_by_product[$product['id']] ?? [];
            $product['lots'] = $lots_by_product[$product['id']] ?? [];
            $products_with_details[] = $product;
        }
    }
    
    $response['products'] = $products_with_details;

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>