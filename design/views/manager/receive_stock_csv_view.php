<!DOCTYPE html>
<html>
<head>
    <title>Excel Stock Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .highlight-new { background-color: #d4edda !important; }
        .category-suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            background: white;
            position: absolute;
            z-index: 1000;
            width: 100%;
        }
        .category-suggestion-item {
            padding: 5px;
        }
        .category-suggestion-item:hover {
            background-color: #f1f1f1;
        }
        .position-relative {
            position: relative;
        }
    </style>
</head>
<body class="container mt-4">

<a href="download_sample_xlsx.php" class="btn btn-secondary mb-3" download>📄 Download Sample Excel</a>
<h3>Upload Excel (.xlsx) for Stock Receiving</h3>

<div class="mb-3">
    <form id="csvUploadForm" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".xlsx" required>
        <button class="btn btn-primary btn-sm">Upload</button>
    </form>
</div>

<h5>Uploaded Excel Files This Session</h5>
<table class="table table-bordered" id="csvTable">
    <thead>
        <tr>
            <th>Filename</th>
            <th>Size (KB)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div id="checkedData" class="mt-4"></div>

<script>
function fetchCSVList() {
    fetch('list_csvs.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const rows = data.csvs.map(csv => `
                    <tr>
                        <td>${csv.original_name}</td>
                        <td>${(csv.file_size / 1024).toFixed(2)}</td>
                        <td>${csv.status}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="deleteCSV(${csv.id})">Delete</button>
                            <button class="btn btn-sm btn-info" onclick="checkCSV(${csv.id})">Check</button>
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
            fetch('delete_csv.php', {
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
    fetch('parse_csv.php', {
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
                    <div class="position-relative">
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
            <h5>File Content</h5>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Name</th><th>Part #</th><th>Tag</th><th>Qty</th><th>Remark</th><th>Category</th>
                    </tr>
                </thead>
                <tbody>${rowsHtml}</tbody>
            </table>
            <button class="btn btn-success" onclick="submitStock(${csvId})">Insert Stock</button>
        `;
    });
}

function submitStock(csvId) {
    const tableRows = document.querySelectorAll('#checkedData table tbody tr');
    const rows = [];

    for (const tr of tableRows) {
        const tds = tr.querySelectorAll('td');
        const isNew = tr.classList.contains('highlight-new');

        const name = tds[0].textContent.trim();
        const part_number = tds[1].textContent.trim();
        const tag = tds[2].textContent.trim();
        const qty = parseInt(tds[3].textContent.trim());
        const remark = tds[4].textContent.trim();

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

    fetch('insert_csv_stock.php', {
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

    fetch('upload_csv.php', {
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

fetchCSVList();
</script>

<script>
// Category search & auto-complete
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('category-autocomplete')) {
        const input = e.target;
        const index = input.dataset.index;
        const term = input.value.trim();
        const suggestionsDiv = document.querySelector(`.category-suggestions[data-index="${index}"]`);

        if (term.length < 2) {
            suggestionsDiv.innerHTML = '';
            suggestionsDiv.classList.add('d-none');
            input.dataset.categoryId = '';
            return;
        }

        fetch(`search_leaf_categories.php?term=${encodeURIComponent(term)}`)
            .then(res => res.json())
            .then(data => {
                const cats = data.categories || [];

                if (cats.length === 1) {
                    input.value = cats[0].name;
                    input.dataset.categoryId = cats[0].id;
                    suggestionsDiv.innerHTML = '';
                    suggestionsDiv.classList.add('d-none');
                    return;
                }

                const suggestions = cats.map(cat => `
                    <div class="category-suggestion-item" data-id="${cat.id}" data-name="${cat.name}">
                        ${cat.name}
                    </div>
                `).join('');
                suggestionsDiv.innerHTML = suggestions;
                suggestionsDiv.classList.remove('d-none');
            });
    }
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
</script>

</body>
</html>
