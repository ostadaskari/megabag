
 <div class="d-flex flex-row align-items-center justify-content-between mb-3 titleTop">       
    <h2 class="d-flex align-items-center">
    <svg width="22" height="22" fill="currentColor" class="bi bi-card-list mx-1 me-2" viewBox="0 0 16 16">
        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
        <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
    </svg>
    Product List</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<!-- List Product -->
<div id="List-Product" class="tab-content" >
<div class="container px-0">
    <!-- searchbar -->
    <div class="d-flex flex-row align-items-start justify-content-between" >
            <div class="input-box w-50" style="margin: 0!important;">
                <svg width="24" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search by name, tag or P/N..." />
            </div>

            <div class="w-25">
                <select id="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                </select>
            </div>
    </div>

    <!-- table list product -->
    <div class="row mt-2">
    <div class="col-12">
        <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-3" style="max-height: 65vh;">
            <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
            <thead class="table-invitionLink sticky-top" style="top:-3px; z-index: 1;">
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">P/N</th>
                <th scope="col">MFG</th>
                <th scope="col">Qty</th>
                <th scope="col">Submitter</th>
                <th scope="col">Category</th>
                <th scope="col">Submit Date</th>
                <th scope="col">Location</th>
                <th scope="col">Status</th>
                <th scope="col">Tag</th>
                <th scope="col">Date Code</th>
                <th scope="col">Recieve Code</th>
                <th scope="col">Actions</th>

                </tr>
            </thead>
        
            <tbody id="productsTableBody">
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
<!-- end List Product -->




            <!-- fetchProducts -->
<script>
let currentPage = 1;

function fetchProducts(page = 1) {
    currentPage = page;
    const keyword = document.getElementById("searchInput").value;
    const status = document.getElementById("statusFilter").value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `../ajax/search_products.php?keyword=${encodeURIComponent(keyword)}&status=${status}&page=${page}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const result = JSON.parse(xhr.responseText);
            document.getElementById("productsTableBody").innerHTML = result.html;
            document.getElementById("pagination").innerHTML = result.pagination;
        }
    };
    xhr.send();
}

// Handle typing and filter change
document.getElementById("searchInput").addEventListener("input", () => fetchProducts(1));
document.getElementById("statusFilter").addEventListener("change", () => fetchProducts(1));

// Initial load
fetchProducts();
//pagination
document.addEventListener('click', function (e) {
    if (e.target.matches('.pagination a')) {
        e.preventDefault();
        const page = parseInt(e.target.getAttribute('data-page'));
        if (!isNaN(page)) fetchProducts(page);
    }
});

</script>

            <!-- end fetchProducts -->

<script>
function deleteProduct(productId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the product.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
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
            idInput.name = 'product_id';
            idInput.value = productId;

            form.appendChild(actionInput);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}


function editProduct(productId) {
    window.location.href = "../auth/dashboard.php?page=edit_product&id=" + productId;
}
</script>



