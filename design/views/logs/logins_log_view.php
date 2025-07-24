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
            <?php if (isset($logs) && $logs->num_rows > 0): ?>
                <?php $i = 1; while ($log = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
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
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No login logs found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (isset($error)): ?>
        <script>
            Swal.fire('Error', '<?= $error ?>', 'error');
        </script>
    <?php endif; ?>
</body>
</html>
