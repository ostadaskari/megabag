<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<h2>Login</h2>

<form method="POST" action="login.php">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <label>Captcha:</label><br>
    <img src="captcha.php" alt="CAPTCHA" onclick="this.src='captcha.php?'+Math.random()" title="Click to refresh"><br><br>

    <input type="text" name="captcha" required placeholder="Enter CAPTCHA"><br><br>


    <button type="submit">Login</button>
</form>

<?php if (isset($_GET['registered'])): ?>
<script>
    Swal.fire('Success', 'Registration successful. You can now login.', 'success');
</script>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $err): ?>
        <script>
            Swal.fire('Error', '<?php echo addslashes($err); ?>', 'error');
        </script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
