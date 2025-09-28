<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
    <svg width="22" height="22" fill="currentColor" class="bi bi-inboxes mx-1 me-2" viewBox="0 0 16 16">
        <path d="M4.98 1a.5.5 0 0 0-.39.188L1.54 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h4.46l-3.05-3.812A.5.5 0 0 0 11.02 1zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562A.5.5 0 0 0 1.884 9h12.234a.5.5 0 0 0 .496-.438zM3.809.563A1.5 1.5 0 0 1 4.981 0h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .106-.374zM.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393zm.941.83.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438l.32-2.562H10.45a2.5 2.5 0 0 1-4.9 0z"/>
    </svg> 
    List Receives</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
        <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
        </svg>
        <span>Back</span>
    </a>
</div>
<div class="tab-content" id="List-Of-Receives">
    <div class="container px-0">
        <div class="row align-items-center">
            <div class="col-12 col-md-11 px-1">
                <form class="row d-flex align-items-end justify-content-between mb-1 border rounded bg-light shadow-sm p-1">

                <div class="col-12 col-md-4">
                    <label for="" class="form-label">Product:</label>
                    <div class="input-box m-0" style="width: 100%;">
                    <div class="svgSearch">
                        <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                        </svg>
                    </div>
                    <input class="px-5" id="searchInput" type="text" autocomplete="off" placeholder="Search here...">
                    </div>
                </div>

                <div class="col-5 col-md-2 mt-md-0 mt-2">
                    <div class="mx-0" style="width: 100%;">
                    <label for="fromDate" class="form-label">From:</label>
                        <input type="date" id="fromDate" class="form-control">
                    </div>
                </div>

                <div class="col-5 col-md-2 mt-md-0 mt-2">
                    <div class="mx-0" style="width: 100%;">
                    <label for="toDate" class="form-label">To:</label>
                    <input type="date" id="toDate" class="form-control">
                    </div>
                </div>

                <div class="col-12 col-md-3 mt-md-0 mt-2">
                    <div class="d-flex justify-content-end">
                    <button type="button" class="btn p-2 px-3 mx-1 d-flex flex-row align-items-center" onclick="fetchReceipts()" title="Search">
                        <svg width="16" height="16" fill="currentColor" class="bi bi-search mx-1" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                        Search
                    </button>

                    <button type="button" class="btn p-2 px-3 mx-1 d-flex flex-row align-items-center" onclick="resetFilters()" title="Clear">
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
                <div class="d-flex flex-column justify-content-md-center justify-content-between filesBtn mb-1 border rounded bg-light shadow-sm p-1">
                    <div class="d-flex flex-row align-items-center justify-content-between my-1">
                        <p class="py-1 fontS">EXE:</p>
                        <button type="button" class="btnSvg" class="btn p-1 mx-1" title="Excel File" onclick="exportReceipts('xlsx')">
                        <svg width="24" height="24" fill="#217346" class="bi bi-filetype-exe" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM2.575 15.202H.785v-1.073H2.47v-.606H.785v-1.025h1.79v-.648H0v3.999h2.575zM6.31 11.85h-.893l-.823 1.439h-.036l-.832-1.439h-.931l1.227 1.983-1.239 2.016h.861l.853-1.415h.035l.85 1.415h.908l-1.254-1.992zm1.025 3.352h1.79v.647H6.548V11.85h2.576v.648h-1.79v1.025h1.684v.606H7.334v1.073Z"/>
                        </svg>
                        </button>
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-between my-1">
                        <p class="py-1 fontS">PDF:</p>
                        <button type="button" class="btnSvg" class="btn p-1 mx-1" title="PDF File" onclick="exportReceipts('pdf')">
                        <svg width="24" height="24" fill="#FF0000" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                        </svg>
                        </button>
                    </div> 
                </div>
            </div>
        </div>
        
        <div class="row d-flex justify-content-center align-items-center mt-1">
            <div class="col-12">
                <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="height: 61vh;">
                    <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
                        <thead class="table-invitionLink sticky-top" style="top:-6px; z-index: 1;">
                            <tr>
                                <th style="width: 3%;">#</th>
                                <th style="color:coral;">X-Code</th>
                                <th>P/N</th>      
                                <th>MFG</th>      
                                <th>Date Code</th>
                                <th>Lot Location</th>
                                <th>Project Name</th>
                                <th>VRM X-Code</th>
                                <th>Initial QTY</th>
                                <th style="color:aqua;">Available QTY</th>
                                <th style="width: 10%;">User</th>
                                <th style="width: 15%;">Comment</th>
                                <th>
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                                    </svg>
                                </th>
                                <th style="width: 6%;" >Date</th>
                                <th style="width: 8%;" >Action</th>
                            </tr>
                        </thead>
                        <tbody id="receiptsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="pagination" class="mt-1 d-flex justify-content-center"></div>
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
        // New function to toggle the lock status of a product lot
    window.toggleLock = function(lotId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will toggle the lock status for this receipt.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", `../ajax/toggle_receipt_lock.php`, true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            fetchReceipts(); // Refresh the table to show the new status
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    } else {
                        Swal.fire('Error!', 'An error occurred while communicating with the server.', 'error');
                    }
                };
                xhr.send(`product_lot_id=${lotId}`);
            }
        });
    };

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


    window.editReceipt = function(receiptId) {
        window.location.href = `../auth/dashboard.php?page=edit_receipt&id=${receiptId}`;
    };

    window.deleteReceipt = function(receiptId) {
        Swal.fire({
            title: 'Are you sure to delete this receipt?',
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
                actionInput.value = 'delete_receipt';
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'receipt_id';
                idInput.value = receiptId;
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    };


            function printXcode(receiptxc) {
                const w = window.open(`../auth/dashboard.php?page=print_receipt&xc=${encodeURIComponent(receiptxc)}`, "_blank");
                w.onload = () => {
                    w.print();
                    w.onafterprint = () => w.close();
                };
            }
        
    // Initial load
    document.addEventListener("DOMContentLoaded", () => {
        fetchReceipts();

        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'deleted') {
            Swal.fire({
                title: 'Deleted!',
                text: 'The receipt has been deleted.',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        } else if (status === 'error') {
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred during deletion.',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        } else if (status === 'updated') {
            Swal.fire({
                title: 'Updated!',
                text: 'The receipt has been updated.',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
        
        // Clean up the URL after displaying the alert
        if (status) {
            urlParams.delete('status');
            history.replaceState(null, '', `?${urlParams.toString()}`);
        }
    });
</script>
