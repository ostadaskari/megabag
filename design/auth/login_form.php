
<!DOCTYPE html>
<html>
<head><title>Login</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form action="login.php" method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
<a href="register.php">Register</a>
</body>
</html>

<?php
$message = $_GET['msg'] ?? null;
$type = $_GET['type'] ?? 'info'; // 'success', 'error', etc.
?>
<?php if ($message): ?>
<script>
    Swal.fire({
        icon: '<?php echo htmlspecialchars($type); ?>',
        title: '<?php echo ucfirst($type); ?>',
        text: '<?php echo htmlspecialchars($message); ?>',
        timer: 3000,
        showConfirmButton: false
    });
</script>
<?php endif; ?>

