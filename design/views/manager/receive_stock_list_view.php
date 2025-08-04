<div class="tab-content" id="List-Of-Receives">
    <div class="container px-0 mt-3">
        <h3 class="card-title mb-2 d-flex align-items-center">
            <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-box-arrow-in-left mx-1" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z"></path>
                <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708z"></path>
            </svg>
            Received Stock Log
        </h3>
        
        <div class="row mb-3 mt-3 d-flex align-items-center bg-light rounded border p-3 shadow-sm">
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
            </div>
            <div class="col-6 col-md-2 mb-2 mb-md-0">
                <input type="date" id="fromDate" class="form-control">
            </div>
            <div class="col-6 col-md-2 mb-2 mb-md-0">
                <input type="date" id="toDate" class="form-control">
            </div>
            <div class="col-6 col-md-1 mb-2 mb-md-0">
                <button class="btn btn-primary w-100" onclick="fetchReceipts()">Search</button>
            </div>
            <div class="col-6 col-md-2 mb-2 mb-md-0">
                <button class="btn btn-secondary w-100" onclick="resetFilters()">Clear</button>
            </div>
            <div class="col-12 col-md-2 d-flex flex-column mb-2 mb-md-0">
                <button class="btn btn-success w-100 mb-2" onclick="exportReceipts('excel')">Export to Excel</button>
                <button class="btn btn-danger w-100" onclick="exportReceipts('pdf')">Export to PDF</button>
            </div>
        </div>
        
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12">
                <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-3" style="max-height: 85vh;">
                    <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
                        <thead class="table-invitionLink sticky-top" style="top:-3px; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Tag</th>
                                <th>P/N</th>
                                <th>QTY</th>
                                <th>User</th>
                                <th>Date</th>
                                <th style="width: 10%;">Comment</th>
                            </tr>
                        </thead>
                        <tbody id="receiptsTableBody">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="pagination" class="mt-3 d-flex justify-content-center"></div>
    </div>
</div>

<div class="custom-modal" id="customModal">
    <div class="modal-backdrop" id="modalBackdrop"></div>
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title d-flex align-items-center">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-text-paragraph" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2 12.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m4-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5"/>
                </svg>
                Comment Detail
            </h3>
            <span class="modal-close" id="modalClose">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            </div>
    </div>
</div>

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
                setupCommentModals(); // Call the function to set up the modals after content is loaded
            }
        };
        xhr.send();
    }

    function exportReceipts(format) {
        const keyword = document.getElementById("searchInput").value;
        const fromDate = document.getElementById("fromDate").value;
        const toDate = document.getElementById("toDate").value;
        const url = `../ajax/export_receipts.php?format=${format}&keyword=${encodeURIComponent(keyword)}&from_date=${fromDate}&to_date=${toDate}`;
        window.open(url, '_blank');
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('fromDate').value = '';
        document.getElementById('toDate').value = '';
        fetchReceipts(1); // Reload full list
    }

    // Modal functionality for comments
    function setupCommentModals() {
        const openModalButtons = document.querySelectorAll('.open-modal');
        const modal = document.getElementById('customModal');
        const modalBody = document.getElementById('modalBody');
        const modalClose = document.getElementById('modalClose');
        const modalBackdrop = document.getElementById('modalBackdrop');

        openModalButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const comment = this.getAttribute('data-comment');
                modalBody.textContent = comment;
                modal.style.display = 'block';
            });
        });

        modalClose.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        modalBackdrop.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    // Initial load
    document.addEventListener("DOMContentLoaded", () => fetchReceipts());

    // You can remove the onload function as the DOMContentLoaded event listener handles the initial fetch.
    // window.onload = function() {
    //     fetchReceipts(1);
    // }
</script>