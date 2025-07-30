<!DOCTYPE html>
<html>
<head>
    <title>CSV Stock Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .highlight-new {
            background-color: #d4edda !important;
        }
    </style>
</head>
<body class="container mt-4">

<a href="download_sample_csv.php" class="btn btn-secondary mb-3" download>📄 Download Sample CSV</a>

<h3>Upload CSV for Stock Receiving</h3>

<div class="mb-3">
    <form id="csvUploadForm" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <button class="btn btn-primary btn-sm">Upload</button>
    </form>
</div>

<h5>CSV Files Uploaded This Session</h5>
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
        text: "This CSV will be deleted!",
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
            document.getElementById('checkedData').innerHTML = '<p class="text-muted">No data found in CSV.</p>';
            return;
        }

        const rowsHtml = data.rows.map((row, idx) => {
            const isNew = row.is_new;
            const rowClass = isNew ? 'highlight-new' : '';
            let catCell;

            if (isNew) {
                catCell = `
                    <input type="text" class="form-control form-control-sm mb-1 category-search" 
                        placeholder="Search category..." 
                        data-select="cat-select-${idx}" 
                        onkeyup="searchCategory(this)">
                    <select class="form-select form-select-sm" id="cat-select-${idx}" name="category_id">
                        <option value="">Select category</option>
                    </select>
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
            <h5>CSV Content</h5>
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
    })
    .catch(() => {
        Swal.fire('Error', 'Could not parse CSV.', 'error');
    });
}

function searchCategory(input) {
    const keyword = input.value.trim();
    const selectId = input.getAttribute('data-select');
    const select = document.getElementById(selectId);

    if (!keyword || keyword.length < 2) {
        select.innerHTML = '<option value="">Type at least 2 letters</option>';
        return;
    }

    fetch(`search_leaf_categories.php?term=${encodeURIComponent(keyword)}`)
        .then(res => res.json())
        .then(data => {
            const options = data.categories.map(c => 
                `<option value="${c.id}">${c.name}</option>`
            ).join('');
            select.innerHTML = options || '<option>No matches</option>';
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
            const select = tr.querySelector('select[name="category_id"]');
            if (select && select.value) {
                category_id = parseInt(select.value);
            } else {
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
</body>
</html>
