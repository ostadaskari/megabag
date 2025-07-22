<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if ($msg): ?>
<script>
    Swal.fire({
        icon: '<?php echo htmlspecialchars($type); ?>',
        title: '<?php echo ucfirst($type); ?>',
        text: '<?php echo htmlspecialchars($msg); ?>',
        timer: 3000,
        showConfirmButton: false
    });
</script>
<?php endif; ?>

<h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
<p>Your role: <strong><?php echo htmlspecialchars($role); ?></strong></p>

<?php if ($role === 'admin'): ?>
    <h3>Admin Panel</h3>
    <ul>
        <li><a href="#">Manage Users</a></li>
        <li><a href="#">System Settings</a></li>
    </ul>

<?php elseif ($role === 'manager'): ?>
    <h3>Manager Panel</h3>
    <ul>
        <li><a href="#">Inventory Management</a></li>
    </ul>

<?php elseif ($role === 'user'): ?>
    <h3>User Panel</h3>
    <ul>
        <li><a href="#">View Stock</a></li>
    </ul>
<?php endif; ?>

<a href="logout.php">Logout</a>
</body>
</html>
