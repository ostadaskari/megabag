<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Inventory Receipts</h2>
        <h3><a href="../auth/dashboard.php">dashboard</a></h3>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, tag, part number or user">
        </div>
        <div class="col-md-2">
            <input type="date" id="fromDate" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" id="toDate" class="form-control">
        </div>
        <div class="col-md-1">
            <button class="btn btn-primary w-100" onclick="fetchReceipts()">Search</button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100" onclick="resetFilters()">Clear Filters</button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100 mb-2" onclick="exportReceipts('excel')">Export to Excel</button>
            <button class="btn btn-danger w-100" onclick="exportReceipts('pdf')">Export to PDF</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Tag</th>
                    <th>P/N</th>
                    <th>Qty</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody id="receiptsTableBody"></tbody>
        </table>
    </div>

    <div id="pagination" class="mt-3"></div>
</div>
<!-- SweetAlert + Tooltips -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function fetchReceipts(page = 1) {
    const keyword = document.getElementById('searchInput').value;
    const from = document.getElementById('fromDate').value;
    const to = document.getElementById('toDate').value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `../ajax/search_receipts.php?keyword=${encodeURIComponent(keyword)}&from=${from}&to=${to}&page=${page}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const result = JSON.parse(xhr.responseText);
            document.getElementById("receiptsTableBody").innerHTML = result.html;
            document.getElementById("pagination").innerHTML = result.pagination;
        }
    };
    xhr.send();
}

document.addEventListener("DOMContentLoaded", () => fetchReceipts());

//excel and pdf export
function exportReceipts(format) {
    const keyword = document.getElementById("searchInput").value;
    const fromDate = document.getElementById("fromDate").value;
    const toDate = document.getElementById("toDate").value;

    const url = `../ajax/export_receipts.php?format=${format}&keyword=${encodeURIComponent(keyword)}&from_date=${fromDate}&to_date=${toDate}`;

    window.open(url, '_blank');
}


//reset filter and search
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('fromDate').value = '';
    document.getElementById('toDate').value = '';
    fetchReceipts(1); // Reload full list
}
// refresh page for full loading
window.onload = function() {
    fetchReceipts(1); // Load full list on initial load
}

</script>
</body>
</html>

