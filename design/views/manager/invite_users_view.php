<h2>Invite New User</h2>
<h3><a href="../auth/dashboard.php">dashboard</a></h3>
<?php foreach ($errors as $e): ?>
    <script>Swal.fire('Error', '<?php echo $e; ?>', 'error');</script>
<?php endforeach; ?>

<?php if ($success): ?>
    <script>Swal.fire('Success', '<?php echo $success; ?>', 'success');</script>
<?php endif; ?>

<form method="POST">
    <input type="text" name="nickname" placeholder="Nickname for Invite" required>
    
    <select name="role" required>
        <option value="">Select role</option>
        <option value="user" selected>User</option>
        <option value="manager">Manager</option>
        <option value="admin">Admin</option>
    </select>
    
    <button type="submit">Generate Invite Code</button>

    <?php if ($inviteLink): ?>
        <input type="text" value="<?php echo $inviteLink; ?>" id="inviteCode" readonly style="width:300px;">
        <button type="button" onclick="copyCode()">Copy</button>
    <?php endif; ?>
</form>

<script>
function copyCode() {
    let copyText = document.getElementById("inviteCode");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    Swal.fire("Copied!", "", "success");
}
</script>

<hr>

<h3>Generated Invite Codes</h3>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Invite Code</th>
            <th>Nickname</th>
            <th>Role</th>
            <th>Invite Link</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Used By</th>
            <th>Generated At</th>
            <th>User Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invites as $invite): ?>
        <tr>
            <td>
                <span 
                    onclick="copyInviteLink('<?php echo $invite['code']; ?>')" 
                    style="cursor:pointer; color:blue;" 
                    title="Click to copy invite link"
                >
                    <?php echo htmlspecialchars($invite['code']); ?>
                </span>
            </td>

            <td><?php echo htmlspecialchars($invite['nickname']); ?></td>
            <td><?php echo htmlspecialchars($invite['role']); ?></td>
            <td>
                <input type="text" readonly value="http://localhost/megabag/core/auth/user_register.php?code=<?php echo $invite['code']; ?>" style="width: 300px;">
            </td>
            <td><?php echo $invite['is_used'] ? 'Used' : 'Active'; ?></td>
            <td><?php echo $invite['created_by_nickname'] ?: '-'; ?></td>
            <td><?php echo $invite['used_by_nickname'] ?: '-'; ?></td>
            <td><?php echo $invite['generated_at']; ?></td>
            <td><?php echo $invite['used_at'] ?: '-'; ?></td>
            <td>
                <form method="POST" id="delete-form-<?php echo $invite['id']; ?>" style="display:inline;">
                    <input type="hidden" name="delete_code_id" value="<?php echo $invite['id']; ?>">
                    <button type="button" onclick="confirmDelete(<?php echo $invite['id']; ?>, <?php echo $invite['is_used']; ?>)" style="background:none;border:none;cursor:pointer;">
                        🔥
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
function confirmDelete(id, isUsed) {
    if (isUsed) {
        Swal.fire('Cannot Delete', 'This invite code has already been used.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "This invite code will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete-form-" + id).submit();
        }
    });
}
</script>

<!-- for copy each invitation code -->
<script>
function copyInviteLink(code) {
    const fullLink = `http://localhost/megabag/core/auth/user_register.php?code=${code}`;
    navigator.clipboard.writeText(fullLink).then(() => {
        Swal.fire('Copied!', 'Invite link copied to clipboard.', 'success');
    }).catch(err => {
        Swal.fire('Error', 'Failed to copy invite link.', 'error');
    });
}
</script>



