
  <div class="d-flex flex-row align-items-center justify-content-between titleTop">
        <h2 class="d-flex align-items-center">
        <svg width="26" height="26" fill="#fff" class="bi bi-people me-2 mx-1" viewBox="0 0 16 16">
        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
        </svg>  
        Manage Users</h2>
        <a href="../auth/dashboard.php?page=home" class="backBtn">
        <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
        </svg>
        <span>Back</span>
        </a>
    </div>
<div class="container px-0">
    <div class="row">
      <div class="col-12 col-md-6" style="margin: auto;">
             <div class="input-box my-0" style="width:100%;">
                <div class="svgSearch">
                    <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </div>
                <input type="text" id="searchInput" class="form-control mb-2 border shadow-sm" placeholder="Search by name, family, or nickname">
            </div>
        </div>
    </div>   
    
    <div class="row mt-2">
        <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" id="userTableContainer" style="height: 58vh;">
            <!-- AJAX content will be loaded here -->
        </div>
    </div>   

    <div id="pagination" class="mt-3 d-flex justify-content-center"></div>

</div>


<script>
    function fetchUsers(page = 1) {
        const search = document.getElementById('searchInput').value;
        fetch(`../admin/manage_users.php?ajax=1&page=${page}&search=${encodeURIComponent(search)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let i = (data.currentPage - 1) * data.itemsPerPage + 1;
                    const rows = data.users.map(user => `
                        <tr>
                            <td>${i++}</td>
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
                            <thead><tr><th>#</th><th>Name</th><th>Family</th><th>Nickname</th><th style="width: 200px;">Email</th><th style="width: 120px;">Role</th><th style="width: 120px;">Action</th></tr></thead>
                            <tbody>${rows}</tbody>
                        </table>
                    `;
                    document.getElementById('pagination').innerHTML = ` ${paginationHtml}`;

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