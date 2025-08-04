<div class="container">
    <h2 class="mb-4">Stock Issues Log</h2>
    <p class="mb-4"><a href="../auth/dashboard.php" class="btn btn-secondary">← Back to Dashboard</a></p>

    <div class="row d-flex justify-content-center align-items-center mb-3">
        <div class="col-12">
            <h3 class="card-title d-flex align-items-center">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-box-arrow-left mx-1" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                    <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                </svg>
                List of Withdraw Log
            </h3>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by product/user...">
        </div>
        <div class="col-md-2">
            <input type="date" id="fromDate" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" id="toDate" class="form-control">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100 mb-2" onclick="fetchIssues(1)">Search</button>
            <button class="btn btn-warning w-100" onclick="clearSearch()">Clear Search</button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100 mb-2" onclick="exportIssues('excel')">Export Excel</button>
            <button class="btn btn-danger w-100" onclick="exportIssues('pdf')">Export PDF</button>
        </div>
    </div>

    <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-3" style="max-height: 85vh;">
        <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
            <thead class="table-invitionLink sticky-top" style="top:-3px; z-index: 1;">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>P/N</th>
                    <th>Tag</th>
                    <th>Qty</th>
                    <th>Issued By</th>
                    <th>Issued To</th>
                    <th>Date</th>
                    <th style="width: 10%;">Comment</th>
                </tr>
            </thead>
            <tbody id="issuesTableBody">
                <!-- Data will be loaded here via AJAX -->
            </tbody>
        </table>
    </div>

    <div id="pagination" class="mt-3"></div>
</div>

<!-- Modal for displaying comment -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="commentText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to fetch and display stock issues
function fetchIssues(page = 1) {
    const k = document.getElementById('searchInput').value;
    const f = document.getElementById('fromDate').value;
    const t = document.getElementById('toDate').value;
    
    fetch(`../ajax/search_issues.php?keyword=${encodeURIComponent(k)}&from=${f}&to=${t}&page=${page}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('issuesTableBody').innerHTML = data.html;
            document.getElementById('pagination').innerHTML = data.pagination;
        });
}

// Function to clear all search inputs and reload the table
function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('fromDate').value = '';
    document.getElementById('toDate').value = '';
    fetchIssues(); // Reload with default, unfiltered view
}

// Function to export stock issues to Excel or PDF
function exportIssues(format) {
    const k = document.getElementById('searchInput').value;
    const f = document.getElementById('fromDate').value;
    const t = document.getElementById('toDate').value;
    
    window.open(`../ajax/export_issues.php?format=${format}&keyword=${encodeURIComponent(k)}&from_date=${f}&to_date=${t}`, '_blank');
}

// Open modal and display comment when a user clicks the comment icon
document.addEventListener('click', function (e) {
    if (e.target.closest('.open-modal')) {
        e.preventDefault();
        const comment = e.target.closest('.open-modal').getAttribute('data-comment');
        document.getElementById('commentText').textContent = comment;
        
        const myModal = new bootstrap.Modal(document.getElementById('commentModal'));
        myModal.show();
    }
});

// Load data on page load
document.addEventListener('DOMContentLoaded', () => fetchIssues());
</script>
