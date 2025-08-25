<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
    <svg width="22" height="22" fill="currentColor" class="bi bi-clipboard-data mx-1 me-2" viewBox="0 0 16 16">
        <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zm-2-1a.5.5 0 0 0-.5.5v5.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L10.5 10.293V6.5a.5.5 0 0 0-.5-.5"/>
        <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 3 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5zM3.5 1A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1zM3 2.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
    </svg>
    Project List</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<!-- List Project -->
<div id="List-Project" class="tab-content" >
<div class="container px-0">
    <!-- searchbar -->
    <div class="d-flex flex-row align-items-center justify-content-between" >
        <div class="input-box w-50 position-relative" style="margin: 0!important;">
            <div class="svgSearch">
                <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </div>
            <input type="text" id="searchInput" placeholder="Search by project name, employer, or purchase code..." />
        </div>

        <div class="w-25">
            <select id="statusFilter" class="form-select py-2">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="finished">Finished</option>
            </select>
        </div>
    </div>

    <!-- table list project -->
    <div class="row mt-2">
    <div class="col-12">
        <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="height: 65vh;">
            <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
            <thead class="table-invitionLink sticky-top" style="top:-6px; z-index: 1;">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Project Name</th>
                    <th scope="col">Date Code</th>
                    <th scope="col">Employer</th>
                    <th scope="col">Purchase Code</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
        
            <tbody id="projectsTableBody">
                <!-- Filled dynamically via AJAX -->
            </tbody>
        
            </table>
        </div>
    </div>
    </div>

    <!-- Pagination -->
    <div class="row my-2">
    <div class="col-12 d-flex justify-content-center">
        <div id="pagination" class="pagination-container"></div>
    </div>
    </div>
</div>
</div>
<!-- end List Project -->

<!-- modal for show details -->
<div id="modalOverlay" class="modal-overlay"></div>

<div id="projectDetailsModal" class="container bg-light border rounded shadow-sm mb-4 modalDetails" style="display: none;">
    <div class="mb-2 modal-header d-flex justify-content-between align-items-center" style="padding: 8px 16px;">
        <div class="d-flex align-items-center ">
            <svg width="20" height="20" fill="currentColor" class="bi bi-ticket-detailed" viewBox="0 0 16 16">
            <path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M5 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2z"/>
            <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zM1.5 4a.5.5 0 0 0-.5.5v1.05a2.5 2.5 0 0 1 0 4.9v1.05a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-1.05a2.5 2.5 0 0 1 0-4.9V4.5a.5.5 0 0 0-.5-.5z"/>
            </svg>
            <h3 class="mx-2 pb-1">Project Details</h3>
        </div>
        <svg width="20" height="20" fill="#CCC" class="bi bi-x-lg btn-close" id="closeModal" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
        </svg>
    </div>

    <div id="projectDetailsContent">
        <!-- Dynamic content will be loaded here -->
    </div>
</div>
<!-- end modal for show details -->



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const projectsTableBody = document.getElementById('projectsTableBody');
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const modalOverlay = document.getElementById('modalOverlay');
        const projectDetailsModal = document.getElementById('projectDetailsModal');
        const projectDetailsContent = document.getElementById('projectDetailsContent');
        const closeModalButton = document.getElementById('closeModal');
        
        // Check for success or error messages from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage) {
            Swal.fire({
                title: 'Success!',
                text: decodeURIComponent(successMessage),
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Remove the URL parameter to prevent the alert from showing on refresh
                const newUrl = window.location.href.split('?')[0] + '?page=projects_listt';
                history.replaceState({}, document.title, newUrl);
            });
        }
        
        if (errorMessage) {
            // Split multiple errors if they exist
            const errorMessages = decodeURIComponent(errorMessage).split(' | ');
            const formattedError = errorMessages.join('<br>');
            
            Swal.fire({
                title: 'Error!',
                html: formattedError,
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                // Remove the URL parameter to prevent the alert from showing on refresh
                const newUrl = window.location.href.split('?')[0] + '?page=project_list';
                history.replaceState({}, document.title, newUrl);
            });
        }

        // Close modal
        window.closeModal = function() {
            modalOverlay.style.display = 'none';
            projectDetailsModal.style.display = 'none';
        }

        closeModalButton.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);

        // Function to show a modal with detailed information for a specific project.
        window.showProjectDetails = function(projectId) {
            // Show a loading spinner while the content is being fetched
            projectDetailsContent.innerHTML = `
                <div style="text-align: center; padding: 20px;">
                    <div class="spinner"></div>
                    <p style="margin-top: 10px; color: #666;">Loading...</p>
                </div>
            `;
            
            // Show the modal and overlay
            modalOverlay.style.display = 'block';
            projectDetailsModal.style.display = 'block';

            // Fetch the project details via AJAX using the Fetch API
            fetch(`../ajax/get_project_details.php?id=${projectId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const project = data.project;
                        
                        projectDetailsContent.innerHTML = `
                            <div class="row">
                                <div class="col-12">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6 my-2">
                                                <strong>User ID:</strong> ${project.user_id}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>Project Name:</strong> ${project.project_name}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>Date Code:</strong> ${project.date_code}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>Employer:</strong> ${project.employer}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>Purchase Code:</strong> ${project.purchase_code}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>Status:</strong> ${project.status}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Designators:</strong> ${project.designators ? project.designators : 'N/A'}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Created At:</strong> ${project.created_at}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Updated At:</strong> ${project.updated_at}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                    } else {
                        // Display the server-side error message to the user
                        projectDetailsContent.innerHTML = `<p style="color: red;">Error: ${data.message}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching project details:', error);
                    // This will catch network errors or parsing errors if the server still returns invalid data
                    projectDetailsContent.innerHTML = `<p style="color: red;">Network error or invalid response from server: ${error.message}. Check your server logs.</p>`;
                });
        };

        // Event delegation for table row clicks
        projectsTableBody.addEventListener('click', function (event) {
            const clickedRow = event.target.closest('tr');
            const clickedCell = event.target.closest('td');

            if (clickedRow && clickedCell && !clickedCell.classList.contains('actions-cell')) {
                const projectId = clickedRow.getAttribute('data-id');
                if (projectId) {
                    showProjectDetails(projectId);
                }
            }
        });

        // Global function to fetch projects
        window.fetchProjects = function (page = 1) {
            const keyword = searchInput.value;
            const status = statusFilter.value;
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `../ajax/search_projects.php?keyword=${encodeURIComponent(keyword)}&status=${status}&page=${page}`, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const result = JSON.parse(xhr.responseText);
                        projectsTableBody.innerHTML = result.html;
                        document.getElementById("pagination").innerHTML = result.pagination;
                    } catch (e) {
                        console.error("Failed to parse JSON response:", e);
                        projectsTableBody.innerHTML = '<tr><td colspan="8" class="text-center">Error: Invalid response from server.</td></tr>';
                    }
                } else {
                    projectsTableBody.innerHTML = '<tr><td colspan="8" class="text-center">Failed to fetch projects.</td></tr>';
                }
            };
            xhr.send();
        };

        // Handle typing and filter change
        searchInput.addEventListener("input", () => fetchProjects(1));
        statusFilter.addEventListener("change", () => fetchProjects(1));
        
        // Initial load
        fetchProjects();

        // Handle pagination link clicks
        document.addEventListener('click', function (e) {
            if (e.target.matches('.pagination a')) {
                e.preventDefault();
                const page = parseInt(e.target.getAttribute('data-page'));
                if (!isNaN(page)) fetchProjects(page);
            }
        });
        
        // Function to delete a product using SweetAlert2
        window.deleteProject = function(projectId) {
            Swal.fire({
                title: 'Are you sure to delete this project?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'delete';
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'project_id';
                    idInput.value = projectId;
                    form.appendChild(actionInput);
                    form.appendChild(idInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        };

        window.editProject = function(projectId) {
            window.location.href = "../auth/dashboard.php?page=edit_project&id=" + projectId;
        };
    });
</script>
