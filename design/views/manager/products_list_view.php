
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
        <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="height: 65vh;">
            <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
            <thead class="table-invitionLink sticky-top" style="top:-6px; z-index: 1;">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">P/N</th>
                    <th scope="col">MFG</th>
                    <th scope="col">Tag</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Submitter</th>
                    <th scope="col">Category</th>

                    <th scope="col">Location</th>
                    <th scope="col">Status</th>


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

<div id="productDetailsModal" class="container bg-light border rounded shadow-sm mb-4 modalDetails" style="display: none;">
    <div class="mb-2 modal-header d-flex justify-content-between align-items-center" style="padding: 8px 16px;">
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

    <div id="productDetailsContent">
        <!-- Dynamic content will be loaded here -->
    </div>
</div>
<!-- end modal for show details -->



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productsTableBody = document.getElementById('productsTableBody');
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const modalOverlay = document.getElementById('modalOverlay');
        const productDetailsModal = document.getElementById('productDetailsModal');
        const productDetailsContent = document.getElementById('productDetailsContent');
        const closeModalButton = document.getElementById('closeModal');

        // Close modal
        window.closeModal = function() {
            modalOverlay.style.display = 'none';
            productDetailsModal.style.display = 'none';
        }

        closeModalButton.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);

        // Function to show a modal with detailed information for a specific product.
        window.showProductDetails = function(productId) {
            // Show a loading spinner while the content is being fetched
            productDetailsContent.innerHTML = `
                <div style="text-align: center; padding: 20px;">
                    <div class="spinner"></div>
                    <p style="margin-top: 10px; color: #666;">Loading...</p>
                </div>
            `;
            
            // Show the modal and overlay
            modalOverlay.style.display = 'block';
            productDetailsModal.style.display = 'block';

            // Fetch the product details via AJAX using the Fetch API
            fetch(`../ajax/get_product_details.php?id=${productId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const product = data.product;
                        const images = data.images;
                        const pdfs = data.pdfs;
                        
                        let pdfListHtml = '';
                        if (pdfs && pdfs.length > 0) {
                            pdfs.forEach(pdf => {
                                pdfListHtml += ` <a class="mx-2 bg-pdf p-2" style="color:rgb(8, 55, 126);" href="${pdf.file_path}" target="_blank"><span>${pdf.file_name}</span></a> `;
                            });
                        }

                        let imageListHtml = '';
                        let mainImageSrc = 'https://placehold.co/600x400/E0E0E0/505050?text=No+Image';
                        if (images && images.length > 0) {
                            mainImageSrc = images[0].file_path;
                            images.forEach(image => {
                                imageListHtml += `<li class="d-flex flex-row align-items-center justify-content-between itemfile itemImg border shadow-sm"><span><img class="img-fluid" src="${image.file_path}"></span></li>`;
                            });
                        }
                        
                        productDetailsContent.innerHTML = `
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6 my-2">
                                                <strong>Name:</strong> ${product.name}
                                            </div>
                                            <div class="col-6 my-1">
                                                <strong>P/N:</strong> ${product.part_number}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>MFG:</strong> ${product.mfg}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>Tag:</strong> ${product.tag}
                                            </div>
                                            <div class="col-6 my-2">
                                                <strong>Qty:</strong> ${product.qty}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Category:</strong> ${product.category_name}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Location:</strong> ${product.location}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Status:</strong> ${product.status}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Date Code:</strong> ${product.date_code}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Recieve Code:</strong> ${product.recieve_code}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Created_At:</strong> ${product.created_at}
                                            </div>
                                            <div class="col-12 my-2">
                                                <strong>Updated_At:</strong> ${product.updated_at}
                                            </div>
                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="imgCover mb-2">
                                            <img src="${mainImageSrc}" class="img-fluid w-100">
                                        </div>
                                        <ul class="mt-1 list-group small d-flex justify-content-between align-items-center" style="max-height: 180px; overflow-y:auto;width: 100%;">
                                            ${imageListHtml}
                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row modal-header">
                                <div class="col-12 my-2 d-flex flex-row">
                                    <svg width="20" height="20" fill="#d42222" class="bi bi-filetype-pdf" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/></svg>
                                    <p class="mx-2"><strong>Datasheet:</strong> ${pdfListHtml} </p>
                                </div>
                            </div>
                        `;

                        // Add event listener for image thumbnails
                        document.querySelectorAll('.itemfile span img').forEach(img => {
                            img.addEventListener('click', () => {
                                document.querySelector('.imgCover img').src = img.src;
                            });
                        });
                        
                    } else {
                        // Display the server-side error message to the user
                        productDetailsContent.innerHTML = `<p style="color: red;">Error: ${data.message}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching product details:', error);
                    // This will catch network errors or parsing errors if the server still returns invalid data
                    productDetailsContent.innerHTML = `<p style="color: red;">Network error or invalid response from server: ${error.message}. Check your server logs.</p>`;
                });
        };

        // Event delegation for table row clicks
        productsTableBody.addEventListener('click', function (event) {
            const clickedRow = event.target.closest('tr');
            const clickedCell = event.target.closest('td');

            if (clickedRow && clickedCell && !clickedCell.classList.contains('actions-cell')) {
                const productId = clickedRow.getAttribute('data-id');
                if (productId) {
                    showProductDetails(productId);
                }
            }
        });

        // Global function to fetch products
        window.fetchProducts = function (page = 1) {
            const keyword = searchInput.value;
            const status = statusFilter.value;
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `../ajax/search_products.php?keyword=${encodeURIComponent(keyword)}&status=${status}&page=${page}`, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const result = JSON.parse(xhr.responseText);
                        productsTableBody.innerHTML = result.html;
                        document.getElementById("pagination").innerHTML = result.pagination;
                    } catch (e) {
                        console.error("Failed to parse JSON response:", e);
                        productsTableBody.innerHTML = '<tr><td colspan="11" class="text-center">Error: Invalid response from server.</td></tr>';
                    }
                } else {
                    productsTableBody.innerHTML = '<tr><td colspan="11" class="text-center">Failed to fetch products.</td></tr>';
                }
            };
            xhr.send();
        };

        // Handle typing and filter change
        searchInput.addEventListener("input", () => fetchProducts(1));
        statusFilter.addEventListener("change", () => fetchProducts(1));
        
        // Initial load
        fetchProducts();

        // Handle pagination link clicks
        document.addEventListener('click', function (e) {
            if (e.target.matches('.pagination a')) {
                e.preventDefault();
                const page = parseInt(e.target.getAttribute('data-page'));
                if (!isNaN(page)) fetchProducts(page);
            }
        });
        
        // Function to delete a product using SweetAlert2
        window.deleteProduct = function(productId) {
            Swal.fire({
                title: 'Are you sure to delete this product?',
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
            })
        };

        window.editProduct = function(productId) {
            window.location.href = "../auth/dashboard.php?page=edit_product&id=" + productId;
        };
    });
</script>
