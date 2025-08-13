<div class="d-flex flex-row align-items-center justify-content-between mb-3 titleTop">
    <h2 class="d-flex align-items-center">
    <svg width="24" height="24" fill="currentColor" class="bi bi-filetype-exe mx-1 me-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM2.575 15.202H.785v-1.073H2.47v-.606H.785v-1.025h1.79v-.648H0v3.999h2.575zM6.31 11.85h-.893l-.823 1.439h-.036l-.832-1.439h-.931l1.227 1.983-1.239 2.016h.861l.853-1.415h.035l.85 1.415h.908l-1.254-1.992zm1.025 3.352h1.79v.647H6.548V11.85h2.576v.648h-1.79v1.025h1.684v.606H7.334v1.073Z"/>
    </svg>
    Insert By Excel</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
        <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
        </svg>
        <span>Back</span>
    </a>
</div>
<div class="tab-content" id="Insert-By-CSV">
    <div class="container px-0">
        <div class="row d-flex justify-content-between border rounded shadow-sm bg-light p-2">
            <div class="col-12 col-md-6 d-flex flex-column align-items-center px-2">
                <h5 class="p-2 titleTop w-100" style="border-radius: 5px 5px 0 0;">Files:</h5>
                <div class="d-flex flex-row justify-content-between align-items-center border rounded shadow-sm px-3 py-2 w-100 bg-light" style="border-radius:0 0 5px 5px;">
                    <svg width="30" height="30" fill="rgb(43, 100, 150)" class="bi bi-cloud-download-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.5a.5.5 0 0 1 1 0V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.354 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V11h-1v3.293l-2.146-2.147a.5.5 0 0 0-.708.708z"/>
                    </svg>
                    <h3 class="">Download Sample</h3>
                    <a href="../csv/download_sample_xlsx.php" class="btn aButton secBtn" download>Download</a>
                </div>

                <div class="d-flex flex-row justify-content-between align-items-center border rounded shadow-sm mt-2 px-3 py-2 w-100 bg-light">
                    <svg width="35" height="35" fill="rgb(43, 100, 150)" class="bi bi-cloud-upload" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383"/>
                        <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708z"/>
                    </svg>
                    <h3 class="text-center">Upload Excel (.xlsx)</h3>
                    <form class="d-flex flex-column justify-content-end" id="csvUploadForm" enctype="multipart/form-data">
                        <input type="file" name="csv_file" accept=".xlsx" required class="form-control mx-auto" >
                        <button class="btn mt-2" style="align-self: end;width: 79px;" >Upload</button>
                    </form>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <h5 class="p-2 titleTop" style="border-radius: 5px 5px 0 0;">Uploaded Excel Files This Session:</h5>
                <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light" style="max-height: 16vh;">
                    <table class="table table-bordered table-striped table-hover mb-0 text-center" id="csvTable">
                        <thead class="table-invitionLink sticky-top" style="top:-3px; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>Filename</th>
                                <th>File Size</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div id="checkedData" class="mt-4">
                    </div>
            </div>
        </div>
    </div>
</div>


<script>
    function fetchCSVList() {
        fetch('../csv/list_csvs.php')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const rows = data.csvs.map((csv, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${csv.original_name}</td>
                            <td>${csv.file_size_readable}</td>
                            <td>${csv.status}</td>
                            <td>
                                <a href="#" class="" title="Delete" onclick="event.preventDefault(); deleteCSV(${csv.id});">
                                    <svg width="20" height="20" fill="#8b000d" class="bi bi-trash hoverSvg" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                                    </svg>
                                </a>
                                <a href="#" class="" title="Check" onclick="event.preventDefault(); checkCSV(${csv.id});">
                                    <svg width="20" height="20" fill="#2b6496" class="bi bi-check2-circle hoverSvg" viewBox="0 0 16 16">
                                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                                        <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    `).join('');
                    document.querySelector('#csvTable tbody').innerHTML = rows;
                }
            });
    }

    function deleteCSV(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This file will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('../csv/delete_csv.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        fetchCSVList();
                        Swal.fire('Deleted!', '', 'success');
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    function checkCSV(csvId) {
        fetch('../csv/parse_csv.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `csv_id=${csvId}`
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success || data.rows.length === 0) {
                document.getElementById('checkedData').innerHTML = '<p class="text-muted">No data found in file.</p>';
                return;
            }

            const rowsHtml = data.rows.map((row, idx) => {
                const isNew = row.is_new;
                const rowClass = isNew ? 'highlight-new' : '';
                let catCell;

                if (isNew) {
                    catCell = `
                                <div class="category-autocomplete-container">
                                    <input type="text" class="form-control form-control-sm category-autocomplete" 
                                        placeholder="Search category..." 
                                        data-index="${idx}" 
                                        data-category-id="" />
                                    <div class="category-suggestions d-none" data-index="${idx}"></div>
                                </div>
                            `;
                } else {
                    catCell = row.matched_category;
                }

                return `
                    <tr class="${rowClass}">
                        <td>${idx + 1}</td>
                        <td>${row.name}</td>
                        <td>${row.part_number}</td>
                        <td>${row.tag}</td>
                        <td>${row.qty}</td>
                        <td>${row.remark}</td>
                        <td>${catCell}</td>
                    </tr>
                `;
            }).join('');

            document.getElementById('checkedData').innerHTML = `
                <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-3" style="max-height: 60vh;">
                    <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
                        <thead class="table-invitionLink sticky-top" style="top:-3px; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>Name</th><th>Part #</th><th>Tag</th><th>Qty</th><th>Remark</th><th>Category</th>
                            </tr>
                        </thead>
                        <tbody>${rowsHtml}</tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success mt-3" onclick="submitStock(${csvId})">Insert to Inventory</button>
                </div>
            `;

            // Re-apply event listeners for autocomplete
            setupCategoryAutocomplete();
        });
    }

    function submitStock(csvId) {
        const tableRows = document.querySelectorAll('#checkedData table tbody tr');
        const rows = [];

        for (const tr of tableRows) {
            const tds = tr.querySelectorAll('td');
            const isNew = tr.classList.contains('highlight-new');

            // Note: The index column is now tds[0], so the others shift
            const name = tds[1].textContent.trim();
            const part_number = tds[2].textContent.trim();
            const tag = tds[3].textContent.trim();
            const qty = parseInt(tds[4].textContent.trim());
            const remark = tds[5].textContent.trim();

            let category_id = null;

            if (isNew) {
                const input = tr.querySelector('input.category-autocomplete');
                category_id = input.dataset.categoryId;
                if (!category_id) {
                    Swal.fire('Error', `Please select a category for "${part_number}"`, 'error');
                    return;
                }
            }

            rows.push({ name, part_number, tag, qty, remark, category_id });
        }

        fetch('../csv/insert_csv_stock.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csv_id: csvId, rows })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', 'Stock inserted successfully', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Insertion failed', 'error');
            }
        });
    }

    document.getElementById('csvUploadForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('../csv/upload_csv.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fetchCSVList();
                Swal.fire('Uploaded!', '', 'success');
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        });
    });

    function setupCategoryAutocomplete() {
        // Category search & auto-complete
        document.querySelectorAll('.category-autocomplete').forEach(input => {
            input.addEventListener('input', function () {
                const term = this.value.trim();
                const index = this.dataset.index;
                const suggestionsDiv = document.querySelector(`.category-suggestions[data-index="${index}"]`);

                if (term.length < 2) {
                    suggestionsDiv.innerHTML = '';
                    suggestionsDiv.classList.add('d-none');
                    this.dataset.categoryId = '';
                    return;
                }

                fetch(`../csv/search_leaf_categories.php?term=${encodeURIComponent(term)}`)
                    .then(res => res.json())
                    .then(data => {
                        const cats = data.categories || [];
                        const suggestions = cats.map(cat => `
                            <div class="category-suggestion-item" data-id="${cat.id}" data-name="${cat.name}">
                                ${cat.name}
                            </div>
                        `).join('');
                        suggestionsDiv.innerHTML = suggestions;
                        suggestionsDiv.classList.remove('d-none');
                    });
            });
        });

        // Handle category selection
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('category-suggestion-item')) {
                const name = e.target.dataset.name;
                const id = e.target.dataset.id;
                const parent = e.target.closest('.category-suggestions');
                const index = parent.dataset.index;
                const input = document.querySelector(`input.category-autocomplete[data-index="${index}"]`);

                input.value = name;
                input.dataset.categoryId = id;
                parent.innerHTML = '';
                parent.classList.add('d-none');
            } else {
                // Hide all suggestions if clicked outside
                document.querySelectorAll('.category-suggestions').forEach(div => div.classList.add('d-none'));
            }
        });
    }

    // Initial load
    fetchCSVList();
</script>
