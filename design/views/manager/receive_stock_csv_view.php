<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>receive by CSV</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

</head>
<body>

<div class="container">
    
    <h2>REceive BY CSV</h2>
    <a href="../stock/download_sample_csv.php" class="btn btn-sm btn-secondary">Download Sample CSV</a>

    <!-- Upload Section -->
<input type="file" id="csvFile" class="form-control w-50">
<button onclick="uploadCSV()" class="btn btn-primary mt-2">Upload CSV</button>
<div id="csvList" class="mt-3"></div>

<!-- CSV Preview -->
<div id="csvPreview" class="mt-5"></div>

<!-- Insert Button -->
<button id="insertBtn" class="btn btn-success mt-3" style="display:none">Insert to Inventory</button>


</div>

<script>
let csvData = [];
let categories = [];

function uploadCSV() {
    const file = document.getElementById('csvFile').files[0];
    if (!file) return Swal.fire('Please select a file');
    const formData = new FormData();
    formData.append('csv', file);

    fetch('upload_csv.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Uploaded');
                loadCSVList();
            } else Swal.fire('Error', data.message, 'error');
        });
}

function loadCSVList() {
    fetch('list_csvs.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('csvList').innerHTML = data.files.map(f => `
                <div class="d-flex justify-content-between border p-2">
                    ${f.name} (${f.size_kb} KB)
                    <div>
                        <button class="btn btn-sm btn-info" onclick="checkCSV('${f.name}')">Check</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCSV('${f.name}')">Delete</button>
                    </div>
                </div>
            `).join('');
        });
}

function deleteCSV(name) {
    fetch('delete_csv.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `file=${encodeURIComponent(name)}`
    }).then(() => {
        Swal.fire('Deleted');
        loadCSVList();
    });
}

function checkCSV(name) {
    fetch('parse_csv.php?file=' + name)
        .then(res => res.json())
        .then(data => {
            csvData = data.rows;
            fetch('fetch_leaf_categories.php')
                .then(res => res.json())
                .then(cat => {
                    categories = cat.categories;
                    renderPreview();
                });
        });
}

function renderPreview() {
    const rows = csvData.map((row, i) => {
        let catColumn = row.exists ? `<td>${row.category_id || '-'}</td>` : `
            <td><select onchange="setCategory(${i}, this.value)">
                <option value="">Select Category</option>
                ${categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
            </select></td>`;

        return `
            <tr style="background:${row.exists ? '#fff' : '#d4edda'}">
                <td>${row.name}</td><td>${row.part_number}</td><td>${row.tag}</td><td>${row.qty}</td><td>${row.remark}</td>${catColumn}
            </tr>`;
    }).join('');

    document.getElementById('csvPreview').innerHTML = `
        <h5>CSV Preview</h5>
        <table class="table table-bordered">
            <thead><tr><th>Name</th><th>Part Number</th><th>Tag</th><th>Qty</th><th>Remark</th><th>Category</th></tr></thead>
            <tbody>${rows}</tbody>
        </table>`;
    document.getElementById('insertBtn').style.display = 'block';
}

function setCategory(index, id) {
    csvData[index].category_id = parseInt(id);
}

document.getElementById('insertBtn').addEventListener('click', () => {
    fetch('insert_csv_stock.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({rows: csvData})
    }).then(res => res.json()).then(data => {
        if (data.success) {
            Swal.fire('Success', 'Stock inserted', 'success');
            document.getElementById('csvPreview').innerHTML = '';
            document.getElementById('insertBtn').style.display = 'none';
            loadCSVList();
        } else Swal.fire('Error');
    });
});

loadCSVList();
</script>


</body>
</html>