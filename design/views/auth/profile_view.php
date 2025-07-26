<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        form { margin-bottom: 30px; border: 1px solid #ddd; padding: 20px; width: 400px; }
        input { width: 100%; margin: 5px 0; padding: 8px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>My Profile</h2>
    <h3><a href="dashboard.php">Back to Dashboard</a></h3>

    <!-- Personal Info Update -->
    <form method="POST">
        
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Family</label>
        <input type="text" name="family" value="<?= htmlspecialchars($user['family']) ?>" required>

        <label>Nickname</label>
        <input type="text" name="nickname" value="<?= htmlspecialchars($user['nickname']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <button type="submit" name="form" value="info" >Update Info</button>
    </form>

    <!-- Password Change -->
    <form method="POST">
        <input type="hidden" >

        <label>Current Password</label>
        <input type="password" name="current_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" name="form" value="password">Change Password</button>
    </form>


        <!-- SweetAlert for success -->
    <?php if (!empty($success)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= addslashes($success) ?>',
            });
        </script>
    <?php endif; ?>

    <!-- SweetAlert for errors -->
    <?php foreach ($errors as $e): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= addslashes($e) ?>',
            });
        </script>
    <?php endforeach; ?>

    
</body>
</html>
