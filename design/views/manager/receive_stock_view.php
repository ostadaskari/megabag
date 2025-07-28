<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 <div class="container">
    <h2 class="mb-4">Insert Inventory</h2>

    <?php if (!empty($success)): ?>
        <script>
        Swal.fire('Success', '<?= $success ?>', 'success');
        </script>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <script>
        Swal.fire('Error', '<?= implode("<br>", array_map('htmlspecialchars', $errors)) ?>', 'error');
        </script>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="product_id">Product</label>
            <select class="form-control" name="product_id" required>
                <option value="">Select Product</option>
                <?php
                $res = $conn->query("SELECT id, name, part_number FROM products ORDER BY name");
                while ($row = $res->fetch_assoc()):
                ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['part_number']) ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group mt-2">
            <label for="qty">Quantity to Add</label>
            <input type="number" class="form-control" name="qty" min="1" required>
        </div>

        <div class="form-group mt-2">
            <label for="remarks">Remarks (optional)</label>
            <textarea class="form-control" name="remarks" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Insert Inventory</button>
    </form>
</div>
</body>
</html>



