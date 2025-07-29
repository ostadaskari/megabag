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
        <li><a href="../manager/invite_users.php">Invite Users</a></li>
        <li><a href="../admin/manage_users.php">Manage Users</a></li>
        <li><a href="../manager/manage_categories.php">Manage categories</a></li>
        <br>
        <li><a href="../manager/receive_stock.php">Insert items</a></li>
        <li><a href="../manager/list_receipts.php">Insert in list</a></li>
        <br>
        <li><a href="../manager/stock_issue.php">withdraw items</a></li>
        <li><a href="../manager/list_issues.php">withdraw list</a></li>
        <br>
        <li><a href="../manager/create_product.php">Create Product</a></li>
        <li><a href="../manager/products_list.php">Product List</a></li>  
        <li><a href="../logs/login_logs.php">logins logs</a></li>
        <li><a href="../logs/bans.php">ban list logs</a></li>
        <li><a href="../auth/profile.php">profile</a></li>
        <br>
        <li><a href="../user/search_products.php">Search products</a></li>
        
    </ul>

<?php elseif ($role === 'manager'): ?>
    <h3>Manager Panel</h3>
    <ul>
        <li><a href="../manager/invite_users.php">Invite Users</a></li>
            
        <li><a href="../manager/manage_categories.php">Manage categories</a></li>
        <br>
        <li><a href="../manager/receive_stock.php">Insert items</a></li>
        <li><a href="../manager/list_receipts.php">Insert in list</a></li>
        <br>
        <li><a href="../manager/stock_issue.php">withdraw items</a></li>
        <li><a href="../manager/list_issues.php">withdraw list</a></li>
        <br>
        <li><a href="../manager/create_product.php">Create Product</a></li>
        <li><a href="../manager/products_list.php">Product List</a></li>  
        <li><a href="../logs/login_logs.php">logins logs</a></li>
        <li><a href="../logs/bans.php">ban list logs</a></li>
        <li><a href="../auth/profile.php">profile</a></li>
        <br>
        <li><a href="../user/search_products.php">Search products</a></li>
    </ul>

<?php elseif ($role === 'user'): ?>
    <h3>User Panel</h3>
    <ul>
        <li><a href="../user/search_products.php">Search products</a></li>
    </ul>
<?php endif; ?>

<a href="logout.php">Logout</a>
</body>
</html>
