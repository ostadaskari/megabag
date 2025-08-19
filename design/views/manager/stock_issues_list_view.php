<div class="d-flex flex-row align-items-center justify-content-between titleTop">      
    <h2 class="d-flex align-items-center">
    <svg width="24" height="24" fill="currentColor" class="bi bi-list-ul mx-1 me-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2m0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2m0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
    </svg>
    List of Withdraw</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<div class="container px-0">
    <div class="row align-items-center">
        <div class="col-12 col-md-11 px-1">
        <form class="row d-flex align-items-end justify-content-between mb-3 border rounded bg-light shadow-sm p-2">
                <div class="col-12 col-md-4">
                    <label for="" class="form-label">Product:</label>
                    <div class="input-box m-0" style="width: 100%;">
                    <div class="svgSearch">
                        <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" class="form-control px-5" placeholder="Search by product/user...">
                    </div>
                </div>

                <div class="col-5 col-md-2 mt-md-0 mt-2">
                    <div class="mx-0" style="width: 100%;">
                    <label for="fromDate" class="form-label">From:</label>
                        <input type="date" id="fromDate"  class="form-control">
                    </div>
                </div>

                <div class="col-5 col-md-2 mt-md-0 mt-2">
                    <div class="mx-0" style="width: 100%;">
                    <label for="toDate" class="form-label">To:</label>
                    <input type="date" id="toDate" class="form-control">
                    </div>
                </div>

                <div class="col-12 col-md-3 mb-1 mt-md-0 mt-2">
                    <div class="d-flex justify-content-end">
                    <button type="button" class="btn p-2 px-3 mx-1 d-flex flex-row align-items-center" onclick="fetchIssues(1)" title="Search">
                        <svg width="16" height="16" fill="currentColor" class="bi bi-search mx-1" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                        Search
                    </button>

                    <button type="button" class="btn p-2 px-3 mx-1 d-flex flex-row align-items-center" onclick="clearSearch()" title="Clear Search">
                        <svg width="20" height="20" fill="currentColor" class="bi bi-eraser mx-1" viewBox="0 0 16 16">
                        <path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828zm2.121.707a1 1 0 0 0-1.414 0L4.16 7.547l5.293 5.293 4.633-4.633a1 1 0 0 0 0-1.414zM8.746 13.547 3.453 8.254 1.914 9.793a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293z"/>
                        </svg>
                        Clear
                    </button>
                    </div>
                </div>
        </form> 
        </div>
        <div class="col-12 col-md-1">
        <div class="d-flex flex-column justify-content-md-center justify-content-between filesBtn mb-3 border rounded bg-light shadow-sm p-2">
            <div class="d-flex flex-row align-items-center justify-content-between my-1">
                <p class="py-1 fontS">EXE:</p>
                <button class="btn p-1 mx-1 btnSvg" title="Excel File" onclick="exportIssues('excel')">
                <svg width="24" height="24" fill="#217346" class="bi bi-filetype-exe" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM2.575 15.202H.785v-1.073H2.47v-.606H.785v-1.025h1.79v-.648H0v3.999h2.575zM6.31 11.85h-.893l-.823 1.439h-.036l-.832-1.439h-.931l1.227 1.983-1.239 2.016h.861l.853-1.415h.035l.85 1.415h.908l-1.254-1.992zm1.025 3.352h1.79v.647H6.548V11.85h2.576v.648h-1.79v1.025h1.684v.606H7.334v1.073Z"/>
                </svg>
                </button>
            </div>
            <div class="d-flex flex-row align-items-center justify-content-between my-1">
                <p class="py-1 fontS">PDF:</p>
                <button class="btn p-1 mx-1 btnSvg" title="PDF File" onclick="exportIssues('pdf')" >
                
                <svg width="24" height="24" fill="#FF0000" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"></path>
                </svg>
                </button>
            </div> 
        </div>
        </div>
    </div>

    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-12"> 
            <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="height: 58vh;">
                <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
                    <thead class="table-invitionLink sticky-top" style="top:-6px; z-index: 1;">
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
        </div>
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
            
            // Generate pagination buttons
            let paginationHtml = '';
            if (data.totalPages > 0) {
                const maxPagesToShow = 3; // Number of page buttons to show in the middle
                let startPage = Math.max(1, data.currentPage - Math.floor(maxPagesToShow / 2));
                let endPage = Math.min(data.totalPages, startPage + maxPagesToShow - 1);

                // Adjust startPage if we're at the end of the total pages
                if (endPage - startPage < maxPagesToShow - 1) {
                    startPage = Math.max(1, endPage - maxPagesToShow + 1);
                }

                let paginationNumbers = '';
                for (let i = startPage; i <= endPage; i++) {
                    const activeClass = i === data.currentPage ? 'fw-bold text-danger' : 'fw-bold';
                    paginationNumbers += `<span class="px-1 ${activeClass}" style="cursor: pointer;" onclick="fetchIssues(${i})">${i}</span>`;
                }

                const firstBtnClass = data.currentPage === 1 ? 'disabled' : '';
                const prevBtnClass = data.currentPage === 1 ? 'disabled' : '';
                const nextBtnClass = data.currentPage === data.totalPages ? 'disabled' : '';
                const lastBtnClass = data.currentPage === data.totalPages ? 'disabled' : '';

                paginationHtml = `
                    <div class="row my-2">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">
                                <a href="#" onclick="event.preventDefault(); fetchIssues(1)" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ${firstBtnClass}">
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"></path></svg>
                                    First
                                </a>
                                <a href="#" onclick="event.preventDefault(); fetchIssues(${data.currentPage - 1})" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight ${prevBtnClass}" id="prevBtn">
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16"><path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"></path></svg>
                                    Prev
                                </a>
                                <div class="px-4 px-custom">
                                    ${paginationNumbers}
                                </div>
                                <a href="#" onclick="event.preventDefault(); fetchIssues(${data.currentPage + 1})" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ${nextBtnClass}" id="nextBtn">
                                    Next
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>
                                </a>
                                <a href="#" onclick="event.preventDefault(); fetchIssues(${data.totalPages})" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft ${lastBtnClass}">
                                    Last
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                paginationHtml = ''; // No pagination if no pages
            }
            document.getElementById('pagination').innerHTML = paginationHtml;
        })
        .catch(error => {
            console.error('Error fetching issues:', error);
            // Optionally show a SweetAlert for fetch errors
            Swal.fire('Error', 'Failed to load stock issues.', 'error');
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
