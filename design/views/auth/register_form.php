<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<h2>Register</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form action="register.php" method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="role" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="manager">Manager</option>
        <option value="user">User</option>
    </select><br>
    <button type="submit">Register</button>
</form>
<a href="login.php">Login</a>
</body>
</html>

