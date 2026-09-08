<?php
// این اسکریپت محصولات، مقادیر ویژگی‌ها و جزئیات پارت‌ها (Lots) را پردازش می‌کند.
// استفاده از Prepared Statements و ساختار کامل MySQLi

require_once '../db/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header('Content-Type: application/json');

try {
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
    $filters = $_GET;

    $response = ['features' => [], 'products' => []];

    // ۱. دریافت لیست ویژگی‌ها بر اساس دسته‌بندی انتخاب شده
    $features_result = [];
    if ($category_id > 0) {
        $stmt = $conn->prepare("SELECT * FROM features WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $features_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $stmt = $conn->prepare("SELECT * FROM features");
        $stmt->execute();
        $features_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
    $response['features'] = $features_result;

    // ۲. ساخت Dynamic SQL برای فیلتر محصولات
    $sql = "SELECT p.* FROM products p";
    $joins = [];
    $conds = [];
    $params = [];
    $types = "";

    // دریافت دسته‌بندی‌های زیرمجموعه با Recursive CTE
    $descendantCategories = [];
    if ($category_id > 0) {
        try {
            $sql_descendants = "
                WITH RECURSIVE CategoryHierarchy AS (
                    SELECT id FROM categories WHERE id = ?
                    UNION ALL
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
                $descendantCategories[] = (int)$row['id'];
            }
            $stmt_descendants->close();
        } catch (Exception $e) {
            // در صورت عدم پشتیبانی ماریا‌دی‌بی/مای‌اس‌کیوال از CTE، fallback به خود category_id
            $descendantCategories = [$category_id];
        }
    }

    // اعمال فیلتر دسته‌بندی
    if (!empty($descendantCategories)) {
        $in_placeholders = implode(',', array_fill(0, count($descendantCategories), '?'));
        $conds[] = "p.category_id IN ($in_placeholders)";
        
        foreach ($descendantCategories as $descendantId) {
            $params[] = $descendantId;
        }
        $types .= str_repeat("i", count($descendantCategories));
    }

    // ۳. اعمال فیلترهای dynamic ویژگی‌ها (Features)
    foreach ($features_result as $f) {
        $fname_val = "feature_{$f['id']}";
        $fname_unit = "feature_{$f['id']}_unit";

        if (isset($filters[$fname_val]) && $filters[$fname_val] !== '') {
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
                    $params[] = (float)$filters[$fname_val];
                    $types .= "d";
                    
                    if (isset($filters[$fname_unit]) && !empty($filters[$fname_unit])) {
                       $conds[] = "LOWER(TRIM(JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.unit')))) = LOWER(?)";
                       $params[] = trim($filters[$fname_unit]);
                       $types .= "s";
                    }
                    break;

                case 'boolean':
                    $filter_val_from_db = "JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.value'))";
                    $expected_json_literal = ($filters[$fname_val] === '1' || $filters[$fname_val] === 'true') ? 'true' : 'false';
                    $conds[] = "{$filter_val_from_db} = ?";
                    $params[] = $expected_json_literal;
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

                default: // 'varchar(50)', 'TEXT'
                    $conds[] = "TRIM(JSON_UNQUOTE(JSON_EXTRACT($alias.value, '$.value'))) = TRIM(?)";
                    $params[] = $filters[$fname_val];
                    $types .= "s";
                    break;
            }
        }
    }

    // ۴. سرهم کردن کوئری نهایی
    if ($joins) $sql .= " " . implode(" ", array_unique($joins));
    if ($conds) $sql .= " WHERE " . implode(" AND ", $conds);
    
    $sql .= " LIMIT 10";

    // اجرای کوئری اصلی دریافت محصولات
    $stmt = $conn->prepare($sql);
    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // ۵. دریافت همزمان Features و Lots برای محصولات پیدا شده (جلوگیری از N+1)
    $product_ids = array_column($products, 'id');
    $products_with_details = [];

    if (!empty($product_ids)) {
        $id_placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        $id_types = str_repeat('i', count($product_ids));

        // دریافت Feature Values
        $sql_features = "SELECT pfv.product_id, pfv.value, f.name, f.data_type 
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
            $feature_value = null;
            $unit = $decoded_value['unit'] ?? null;

            if ($feature['data_type'] === 'multiselect' && isset($decoded_value['values']) && is_array($decoded_value['values'])) {
                $feature_value = $decoded_value['values']; 
            } else if (isset($decoded_value['value'])) {
                $feature_value = $decoded_value['value'];
            } else if (is_string($decoded_value) || is_int($decoded_value) || is_bool($decoded_value) || is_float($decoded_value)) {
                $feature_value = $decoded_value;
            }
            
            $features_by_product[$feature['product_id']][] = [
                'name' => $feature['name'],
                'value' => $feature_value,
                'unit' => $unit
            ];
        }

        // دریافت Product Lots
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

        // مپ کردن داده‌ها روی محصولات اصلی
        foreach ($products as $product) {
            $product['features'] = $features_by_product[$product['id']] ?? [];
            $product['lots'] = $lots_by_product[$product['id']] ?? [];
            $products_with_details[] = $product;
        }
    }
    
    $response['products'] = $products_with_details;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_UNESCAPED_UNICODE);
}