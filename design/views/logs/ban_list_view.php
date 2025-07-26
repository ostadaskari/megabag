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
                    <th>#</th>
                    <th>Username</th>
                    <th>Is Active</th>
                    <th>Created At</th>
                    <th>Expires At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($ban_list) > 0): ?>
                    <?php $i = 1; foreach ($ban_list as $ban): ?>
                        <tr>
                            <td><?= ($limit * ($page - 1)) + $i++ ?></td>
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
                  
                  <!-- pagination section -->
        <?php if ($totalPages > 1): ?>
            <div style="margin-top: 15px;">
                <?php
                    $baseUrl = strtok($_SERVER["REQUEST_URI"], '?'); // get path without query string
                    $queryParams = $_GET;
                ?>

                <!-- First -->
                <?php if ($page > 1): ?>
                    <a href="<?= $baseUrl ?>?page=1">First</a>
                <?php else: ?>
                    <span style="color:gray;">First</span>
                <?php endif; ?>

                <!-- Prev -->
                <?php if ($page > 1): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $page - 1 ?>">Prev</a>
                <?php else: ?>
                    <span style="color:gray;">Prev</span>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php
                    $range = 2; // how many pages to show around current page
                    $start = max(1, $page - $range);
                    $end = min($totalPages, $page + $range);
                    for ($i = $start; $i <= $end; $i++): ?>
                        <?php if ($i == $page): ?>
                            <strong><?= $i ?></strong>
                        <?php else: ?>
                            <a href="<?= $baseUrl ?>?page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                <?php endfor; ?>

                <!-- Next -->
                <?php if ($page < $totalPages): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $page + 1 ?>">Next</a>
                <?php else: ?>
                    <span style="color:gray;">Next</span>
                <?php endif; ?>

                <!-- Last -->
                <?php if ($page < $totalPages): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $totalPages ?>">Last</a>
                <?php else: ?>
                    <span style="color:gray;">Last</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

            <!-- end pagination section -->

    </div>
</body>
</html>
