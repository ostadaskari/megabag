<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-3">Manage Users</h2>
    <h3><a href="../auth/dashboard.php">dashboard</a></h3>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by name, family, or nickname">
    <div id="userTableContainer">
        <!-- AJAX content will be loaded here -->
    </div>
</div>

<script>
function fetchUsers(page = 1) {
    const search = document.getElementById('searchInput').value;
    fetch(`manage_users.php?ajax=1&page=${page}&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const rows = data.users.map(user => `
                    <tr>
                        <td>${user.name}</td>
                        <td>${user.family}</td>
                        <td>${user.nickname}</td>
                        <td>${user.email}</td>
                        <td>
                            <select onchange="changeRole(${user.id}, this.value, '${user.role}')" class="form-select form-select-sm">
                                <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                                <option value="manager" ${user.role === 'manager' ? 'selected' : ''}>Manager</option>
                                <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                        </td>
                        <td>
                            <button onclick="toggleBlock(${user.id}, ${user.is_blocked})" class="btn btn-sm ${user.is_blocked ? 'btn-danger' : 'btn-success'}">
                                ${user.is_blocked ? 'Unblock' : 'Block'}
                            </button>
                        </td>
                    </tr>
                `).join('');

                let pagination = '';
                for (let i = 1; i <= data.totalPages; i++) {
                    pagination += `<button onclick="fetchUsers(${i})" class="btn btn-sm ${i === data.currentPage ? 'btn-primary' : 'btn-light'}">${i}</button> `;
                }

                document.getElementById('userTableContainer').innerHTML = `
                    <table class="table table-bordered">
                        <thead><tr><th>Name</th><th>Family</th><th>Nickname</th><th>Email</th><th>Role</th><th>Action</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                    <div class="mt-2">${pagination}</div>
                `;
            }
        });
}

function changeRole(userId, newRole, oldRole) {
    if (newRole === oldRole) return;

    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to change the role to "${newRole}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it',
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('update_user_role.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, role: newRole })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', 'Role updated successfully.', 'success');
                        fetchUsers();
                    } else {
                        Swal.fire('Error', 'Failed to update role.', 'error');
                    }
                });
        } else {
            fetchUsers(); // Reset dropdown visually
        }
    });
}

function toggleBlock(userId, isBlocked) {
    const action = isBlocked ? 'unblock' : 'block';

    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to ${action} this user?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Yes, ${action}`,
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('toggle_block_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', `User ${action}ed.`, 'success');
                        fetchUsers();
                    } else {
                        Swal.fire('Error', 'Failed to update user status.', 'error');
                    }
                });
        }
    });
}

document.getElementById('searchInput').addEventListener('input', () => fetchUsers(1));
fetchUsers();


</script>

</body>
</html>