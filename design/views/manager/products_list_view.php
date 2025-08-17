
 <div class="d-flex flex-row align-items-center justify-content-between titleTop">       
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
    <div class="d-flex flex-row align-items-center justify-content-between" >
            <div class="input-box w-50 position-relative" style="margin: 0!important;">
                <div class="svgSearch">
                    <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Search by name, tag or P/N..." />
            </div>

            <div class="w-25">
                <select id="statusFilter" class="form-select py-2">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                </select>
            </div>
    </div>

    <!-- table list product -->
    <div class="row mt-2">
    <div class="col-12">
        <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="max-height: 60vh;">
            <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
            <thead class="table-invitionLink sticky-top" style="top:-6px; z-index: 1;">
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

<!-- modal for show details -->
<div id="modalOverlay" class="modal-overlay"></div>

<div class="container bg-light border rounded shadow-sm mb-4 modalDetails">
  <div class="mb-3 modal-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center ">
        <svg width="20" height="20" fill="currentColor" class="bi bi-ticket-detailed" viewBox="0 0 16 16">
        <path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M5 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2z"/>
        <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zM1.5 4a.5.5 0 0 0-.5.5v1.05a2.5 2.5 0 0 1 0 4.9v1.05a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-1.05a2.5 2.5 0 0 1 0-4.9V4.5a.5.5 0 0 0-.5-.5z"/>
        </svg>
        <h3 class="mx-2 pb-1">Part Details</h3>
    </div> 
 
      <svg width="20" height="20" fill="#CCC" class="bi bi-x-lg btn-close" id="closeModal" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
       </svg>
   
  </div>

  <div class="row">
    <div class="col-12 col-md-6">
     <div class="container">
        <div class="row">
        <div class="col-12 my-2">
            <strong>Name:</strong> Example Part
            </div>
            <div class="col-6 col-md-4 my-3">
            <strong>P/N:</strong> PN-12345
            </div>
            <div class="col-12 my-2">
            <strong>MFG:</strong> Samsung
            </div>
            <div class="col-12 my-2">
            <strong>Qty:</strong> 50
            </div>
            <div class="col-12 my-2">
            <strong>Category:</strong> Electronics
            </div>
            <div class="col-12 my-2">
            <strong>Location:</strong> Warehouse A
            </div>
            <div class="col-12 my-2">
            <strong>Status:</strong> In Stock
            </div>
            <div class="col-12 my-2">
            <strong>Tag:</strong> New
            </div>
            <div class="col-12 my-2 d-flex flex-row">
                <div class="d-flex align-items-center">
                    <svg width="18" height="18" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"></path>
                    </svg>
                    <label >Datasheet:</label>
                </div> 
                <ul class="mt-1 list-group small  d-flex justify-content-between align-items-center" style="max-height: 180px;overflow-y:auto;">
                    <li class="d-flex flex-row align-items-center justify-content-between itemfile border shadow-sm p-2">
                        <a style="color:#101010;" href="../../uploads/pdfs/6893379f2eb20_Test-pdf_4.pdf" target="_blank">
                            <span>Test-pdf_5.pdf</span>  
                        </a>
                    </li>
                </ul>  
        </div> 
        </div>
    </div>
    </div> 

<div class="col-12 col-md-6">
    <div>
        <label class="form-label">Images:</label>
        <div class="imgCover mb-2">
        <img src="../../design/assets/img/img14.jpg" class="img-fluid w-100">
    </div>   
    <ul class="mt-1 list-group small d-flex justify-content-between align-items-center" style="max-height: 180px; overflow-y:auto;">
        <li class="d-flex flex-row align-items-center justify-content-between itemfile itemImg border shadow-sm p-2">
            <span><img class="img-fluid" src="../../design/assets/img/img11.jpg"></span>                             
        </li>
        <li class="d-flex flex-row align-items-center justify-content-between itemfile itemImg border shadow-sm p-2">
            <span><img class="img-fluid" src="../../design/assets/img/img12.jpg"></span>                             
        </li>
        <li class="d-flex flex-row align-items-center justify-content-between itemfile itemImg border shadow-sm p-2">
            <span><img class="img-fluid" src="../../design/assets/img/img13.jpg"></span>                             
        </li>
        <li class="d-flex flex-row align-items-center justify-content-between itemfile itemImg border shadow-sm p-2">
            <span><img class="img-fluid" src="../../design/assets/img/img14.jpg"></span>                             
        </li>
        <li class="d-flex flex-row align-items-center justify-content-between itemfile itemImg border shadow-sm p-2">
            <span><img class="img-fluid" src="../../design/assets/img/img11.jpg"></span>                             
        </li>
    </ul>
  </div>      
</div>

    <script>
    const imgCover = document.querySelector('.imgCover'); 
    const items = document.querySelectorAll('.itemfile span img'); 

    items.forEach(img => {
        img.addEventListener('click', () => {
        imgCover.innerHTML = `<img src="${img.src}" class="img-fluid w-100">`;
        });
    });
    </script>

   
    
  </div>   

</div>

<!-- Example button to open modal -->
<button onclick="openModal()" class="btn btn-primary">Show Part Details</button>
<!-- js for modal -->
<script>
function openModal() {
  document.getElementById('modalOverlay').style.display = 'block';
  document.querySelector('.modalDetails').style.display = 'block';
}

function closeModal() {
  document.getElementById('modalOverlay').style.display = 'none';
  document.querySelector('.modalDetails').style.display = 'none';
}

// Close button
document.getElementById('closeModal').addEventListener('click', closeModal);

// Click outside modal
document.getElementById('modalOverlay').addEventListener('click', closeModal);
</script>
<!-- end modal for show details -->




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



