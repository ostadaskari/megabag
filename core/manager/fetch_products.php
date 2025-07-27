<?php

require_once '../db/db.php';

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT products.*, users.nickname AS submitter, categories.name AS category_name 
          FROM products 
          LEFT JOIN users ON users.id = products.user_id 
          LEFT JOIN categories ON categories.id = products.category_id 
          WHERE 1";

$params = [];
$types = "";

// Filter by search
if (!empty($search)) {
    $query .= " AND (products.name LIKE ? OR products.part_number LIKE ? OR products.tag LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = &$searchTerm;
    $params[] = &$searchTerm;
    $params[] = &$searchTerm;
    $types .= "sss";
}

// Filter by status
if (!empty($status)) {
    $query .= " AND products.status = ?";
    $params[] = &$status;
    $types .= "s";
}

$query .= " ORDER BY products.created_at DESC ";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Return HTML
?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>P/N</th>
            <th>MFG</th>
            <th>Qty</th>
            <th>Submitter</th>
            <th>Category</th>
            <th>Submit Date</th>
            <th>Location</th>
            <th>Status</th>
            <th>Tag</th>
            <th>Date Code</th>
            <th>Recieve Code</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($products)): ?>
            <?php $i = 1; foreach ($products as $p): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['part_number']) ?></td>
                    <td><?= htmlspecialchars($p['mfg']) ?></td>
                    <td><?= htmlspecialchars($p['qty']) ?></td>
                    <td><?= htmlspecialchars($p['submitter']) ?></td>
                    <td><?= htmlspecialchars($p['category_name']) ?></td>
                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                    <td><?= htmlspecialchars($p['location']) ?></td>
                    <td><?= htmlspecialchars($p['status']) ?></td>
                    <td><?= htmlspecialchars($p['tag']) ?></td>
                    <td><?= htmlspecialchars($p['date_code']) ?></td>
                    <td><?= htmlspecialchars($p['recieve_code']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $p['id'] ?>">✏️</a>
                        <a href="#" onclick="deleteProduct(<?= $p['id'] ?>)">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="14">No results found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
