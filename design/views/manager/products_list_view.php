<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        th {
            background-color: #f0f0f0;
        }
        .action-btn {
            cursor: pointer;
            margin: 0 5px;
        }
        .actions svg {
            width: 18px;
            height: 18px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<h2>Product List</h2>
<h3><a href="../auth/dashboard.php">dashboard</a></h3>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Tag</th>
            <th>P/N</th>
            <th>MFG</th>
            <th>Qty</th>
            <th>Location</th>
            <th>Status</th>
            <th>Category</th>
            <th>Submitter</th>
            <th>Submit Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($products)): ?>
            <?php $i = 1; foreach ($products as $p): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['tag']) ?></td>
                    <td><?= htmlspecialchars($p['part_number']) ?></td>
                    <td><?= htmlspecialchars($p['mfg']) ?></td>
                    <td><?= htmlspecialchars($p['qty']) ?></td>
                    <td><?= htmlspecialchars($p['location']) ?></td>
                    <td><?= htmlspecialchars($p['status']) ?></td>
                    <td><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['submitted_by'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                    <td class="actions">
                        <span class="action-btn" onclick="editProduct(<?= $p['id'] ?>)" title="Edit">
                            ✏️
                        </span>
                        <span class="action-btn" onclick="deleteProduct(<?= $p['id'] ?>)" title="Delete">
                            🗑️
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="11">No products found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
function deleteProduct(productId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the product.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'product_id';
            idInput.value = productId;

            form.appendChild(actionInput);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}


function editProduct(productId) {
    window.location.href = "edit_product.php?id=" + productId;
}
</script>

</body>
</html>
