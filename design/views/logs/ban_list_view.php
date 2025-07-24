<!DOCTYPE html>
<html>
<head>
    <title>Ban List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Ban List</h2>
        <h3><a href="../auth/dashboard.php">dashboard</a></h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Is Active</th>
                    <th>Created At</th>
                    <th>Expires At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($ban_list) > 0): ?>
                    <?php foreach ($ban_list as $ban): ?>
                        <tr>
                            <td><?= htmlspecialchars($ban['username']) ?></td> 
                            <td>
                                <?= $ban['is_active'] ? '<span class="badge bg-danger">Active</span>' : '<span class="badge bg-secondary">Expired</span>' ?>
                            </td>
                            <td><?= $ban['created_at'] ?></td>
                            <td><?= $ban['expires_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No bans found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
