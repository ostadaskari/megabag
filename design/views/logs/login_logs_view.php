<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Logs</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f4f4f4; }
        .status-ok { color: green; }
        .status-wrong { color: red; }
        .status-unknown { color: orange; }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: blue;
        }
        .pagination strong {
            margin: 0 5px;
            color: black;
            font-weight: bold;
        }
        .pagination span {
            margin: 0 5px;
            color: gray;
        }
    </style>
</head>
<body>
    <h2>Login Logs</h2>
    <h3><a href="../auth/dashboard.php">dashboard</a></h3>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>IP Address</th>
                <th>Username</th>
                <th>Status</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($logs) && count($logs) > 0): ?>
                <?php $i = 1; foreach ($logs as $log): ?>
                    <tr>
                        <td><?= ($limit * ($page - 1)) + $i++ ?></td>
                        <td><?= htmlspecialchars($log['ip']) ?></td>
                        <td><?= htmlspecialchars($log['username']) ?></td>
                        <td class="<?php
                            switch ($log['status']) {
                                case 'ok': echo 'status-ok'; break;
                                case 'wrong pass':
                                case 'wrong captcha': echo 'status-wrong'; break;
                                default: echo 'status-unknown'; break;
                            }
                        ?>">
                            <?= ucfirst($log['status']) ?>
                        </td>
                        <td><?= $log['created_at'] ?></td>
                    </tr>
                <?php  endforeach;  ?>
            <?php else: ?>
                <tr><td colspan="5">No login logs found.</td></tr>
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

            <!-- showing errors section -->
    <?php if (isset($error)): ?>
        <script>
            Swal.fire('Error', '<?= $error ?>', 'error');
        </script>
    <?php endif; ?>
</body>
</html>
