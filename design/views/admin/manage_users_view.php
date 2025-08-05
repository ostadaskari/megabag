<div class="container px-0">
    <input type="text" id="searchInput" class="form-control mb-3 border shadow-sm" placeholder="Search by name, family, or nickname">
    <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" id="userTableContainer">
        <!-- AJAX content will be loaded here -->
    </div>
</div>


<script>
    function fetchUsers(page = 1) {
        const search = document.getElementById('searchInput').value;
        fetch(`../admin/manage_users.php?ajax=1&page=${page}&search=${encodeURIComponent(search)}`)
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

                    // Generate the new pagination HTML
                    let paginationNumbers = '';
                    const maxPagesToShow = 3;
                    let startPage = Math.max(1, data.currentPage - Math.floor(maxPagesToShow / 2));
                    let endPage = Math.min(data.totalPages, startPage + maxPagesToShow - 1);
                    if (endPage - startPage < maxPagesToShow - 1) {
                        startPage = Math.max(1, endPage - maxPagesToShow + 1);
                    }
                    
                    for (let i = startPage; i <= endPage; i++) {
                        const activeClass = i === data.currentPage ? 'fw-bold text-danger' : 'fw-bold';
                        paginationNumbers += `<span class="px-1 ${activeClass}" onclick="fetchUsers(${i})">${i}</span>`;
                    }
                    
                    const firstBtn = `<a href="#" onclick="fetchUsers(1)" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ${data.currentPage === 1 ? 'disabled' : ''}">
                                        <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"></path></svg>
                                        First
                                      </a>`;
                    const prevBtn = `<a href="#" onclick="fetchUsers(${data.currentPage - 1})" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ${data.currentPage === 1 ? 'disabled' : ''}" id="prevBtn">
                                        <svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16"><path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"></path></svg>
                                        Prev
                                     </a>`;
                    const nextBtn = `<a href="#" onclick="fetchUsers(${data.currentPage + 1})" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ${data.currentPage === data.totalPages ? 'disabled' : ''}" id="nextBtn">
                                       Next
                                       <svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>
                                     </a>`;
                    const lastBtn = `<a href="#" onclick="fetchUsers(${data.totalPages})" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ${data.currentPage === data.totalPages ? 'disabled' : ''}">
                                       Last
                                       <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"></path></svg>
                                     </a>`;

                    const paginationHtml = `
                        <div class="row my-2">
                            <div class="col-12 d-flex justify-content-center">
                                <div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">
                                    ${firstBtn}
                                    ${prevBtn}
                                    <div class="px-4 px-custom">
                                        ${paginationNumbers}
                                    </div>
                                    ${nextBtn}
                                    ${lastBtn}
                                </div>
                            </div>
                        </div>
                    `;

                    document.getElementById('userTableContainer').innerHTML = `
                        <table class="table table-bordered table-striped table-hover mb-0 text-center">
                            <thead><tr><th>Name</th><th>Family</th><th>Nickname</th><th>Email</th><th>Role</th><th>Action</th></tr></thead>
                            <tbody>${rows}</tbody>
                        </table>
                        ${paginationHtml}
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                Swal.fire('Error', 'Failed to load user data.', 'error');
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
                fetch('../../core/admin/update_user_role.php', {
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
                    })
                    .catch(error => {
                        console.error('Error updating role:', error);
                        Swal.fire('Error', 'An error occurred while updating the role.', 'error');
                        fetchUsers(); // Reset dropdown visually
                    });
            } else {
                fetchUsers(); // Reset dropdown visually if canceled
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
                fetch('../../core/admin/toggle_block_user.php', {
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
                    })
                    .catch(error => {
                        console.error('Error toggling block status:', error);
                        Swal.fire('Error', 'An error occurred while updating the user status.', 'error');
                    });
            }
        });
    }

    document.getElementById('searchInput').addEventListener('input', () => fetchUsers(1));
    fetchUsers();
</script>
