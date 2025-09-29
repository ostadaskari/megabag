<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
    <svg width="22" height="22" fill="currentColor" class="bi bi-card-list mx-1 me-2" viewBox="0 0 16 16">
        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
        <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
    </svg>
    Parts List</h2>

    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<div id="List-Product" class="tab-content" >
<div class="container px-0">
    <div class="row d-flex align-items-center mb-1">
        <div class="col-12 col-md-11 px-1">
            <div class="d-flex flex-row align-items-center justify-content-between p-1">
                <div class="input-box w-75 position-relative" style="margin: 0!important;">
                    <div class="svgSearch">
                        <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search by P/N ..." autocomplete="off" />
                </div>
                <div class="w-25 ms-2">
                    <select id="statusFilter" class="form-select py-2">
                        <option value="">All Statuses</option>
                        <option value="available">Available</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-1">
            <div class="d-flex flex-column justify-content-md-center justify-content-between filesBtn border rounded bg-light shadow-sm p-1">
                <div class="d-flex flex-row align-items-center justify-content-between my-1">
                    <p class="py-1 fontS mb-0">Excel:</p>
                    <button type="button" class="btnSvg" class="btn p-1 mx-1" title="Excel File" onclick="exportProducts('xlsx')">
                        <svg width="24" height="24" fill="#217346" class="bi bi-filetype-exe" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM2.575 15.202H.785v-1.073H2.47v-.606H.785v-1.025h1.79v-.648H0v3.999h2.575zM6.31 11.85h-.893l-.823 1.439h-.036l-.832-1.439h-.931l1.227 1.983-1.239 2.016h.861l.853-1.415h.035l.85 1.415h.908l-1.254-1.992zm1.025 3.352h1.79v.647H6.548V11.85h2.576v.648h-1.79v1.025h1.684v.606H7.334v1.073Z"/>
                        </svg>
                    </button>
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between my-1">
                    <p class="py-1 fontS mb-0">PDF:</p>
                    <button type="button" class="btnSvg" class="btn p-1 mx-1" title="PDF File" onclick="exportProducts('pdf')">
                        <svg width="24" height="24" fill="#FF0000" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                        </svg>
                    </button>
                </div> 
            </div>
        </div>
    </div>
    <div class="row mt-2">
    <div class="col-12">
        <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="height: 65vh;">
            <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
            <thead class="table-invitionLink sticky-top" style="top:-6px; z-index: 1;">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">P/N</th>
                    <th scope="col">MFG</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Submitter</th>
                    <th scope="col">Category</th>
                    <th scope="col">Location</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
        
            <tbody id="productsTableBody">
                </tbody>
        
            </table>
        </div>
    </div>
    </div>

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

<div id="productDetailsModal" class="container bg-light border rounded shadow-sm modalDetails p-2" style="display: none;">
    <div class="mb-2 modal-header d-flex justify-content-between align-items-center" style="padding: 8px 0px;">
        <div class="d-flex align-items-center">
            <svg width="24" height="24" fill="currentColor" class="bi bi-ticket-detailed" viewBox="0 0 16 16">
                <path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M5 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2z"/>
                <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zM1.5 4a.5.5 0 0 0-.5.5v1.05a2.5 2.5 0 0 1 0 4.9v1.05a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-1.05a2.5 2.5 0 0 1 0-4.9V4.5a.5.5 0 0 0-.5-.5z"/>
            </svg>
            <h3 class="mx-2 pb-1">Part Details</h3>
        </div>
        <svg width="20" height="20" fill="#CCC" class="bi bi-x-lg btn-close" id="closeModal" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
        </svg>
    </div>

    <div id="productDetailsContent" class="px-1">
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
                            const features = data.features;
                            
                            let pdfListHtml = '';
                            if (pdfs && pdfs.length > 0) {
                                pdfs.forEach(pdf => {
                                    pdfListHtml += `<a class="mx-2 bg-pdf p-2" style="color:rgb(8, 55, 126);" href="${pdf.file_path}" target="_blank"><span>${pdf.file_name}</span></a>`;
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


                            // Build the HTML for the features list
                            let featuresListHtml = 'N/A';
                            if (features && features.length > 0) {
                                featuresListHtml = ``;
                                features.forEach(feature => {
                                    let featureDisplayValue;

                                    // 1. Check if the value is a boolean (for tick/cross icons)
                                    if (typeof feature.value === 'boolean') {
                                        featureDisplayValue = feature.value 
                                            ? '<span class="text-success fw-bold">✔ Yes</span>' 
                                            : '<span class="text-danger fw-bold">✖ No</span>';
                                    } 
                                    // 2. Check if the value is an array (for multiselect)
                                    else if (Array.isArray(feature.value)) {
                                        // Option A: Simple comma-separated list
                                        // featureDisplayValue = feature.value.join(', ');

                                        
                                        // Option B: Nicer looking badges
                                        featureDisplayValue = feature.value.map(val => `<span class="badge bg-secondary mx-1">${val}</span>`).join(' ');


                                    } 
                                    // 3. Handle all other types (string, number, etc.)
                                    else {
                                        featureDisplayValue = `${feature.value ?? 'N/A'}${feature.unit ? ' ' + feature.unit : ''}`;
                                    }
                                    
                                    featuresListHtml += `<div class="col-6 my-2"><strong>${feature.name}:</strong> ${featureDisplayValue}</div>`;
                                });
                                
                            }
                            
                            productDetailsContent.innerHTML = `
                                <div class="row">
                                    <div class="col-12 col-md-8">
                                        <div class="container px-0">
                                            <div class="row">
                                                <div class="col-6 my-2">
                                                    <strong>P/N:</strong> ${product.part_number}
                                                </div>
                                                <div class="col-6 my-2">
                                                    <strong>MFG:</strong> ${product.mfg}
                                                </div>
                                                <div class="col-6 my-2">
                                                    <strong>Location:</strong> ${product.location}
                                                </div>
                                                <div class="col-6 my-2">
                                                    <strong>Qty:</strong> ${product.qty}
                                                </div>
                                                <div class="col-6 my-2">
                                                    <strong>Category:</strong> ${product.category_name}
                                                </div>

                                                <div class="col-6 my-2">
                                                    <strong>Status:</strong> ${product.status}
                                                </div>

                                                <div class="col-6 my-2">
                                                    <strong>Created_At:</strong> ${formatDate(product.created_at)}
                                                </div>
                                                <div class="col-6 my-2">
                                                    <strong>Updated_At:</strong> ${formatDate(product.updated_at)}
                                                </div>
                                                <div class="col-12 my-2">
                                                    <strong>Description:</strong> ${product.company_cmt}
                                                </div>
                                            </div>
                                            <div class="row my-2 mr-3 px-0 py-1">
                                                <div class="col-12 d-flex flex-row align-items-center mb-2">
                                                    <svg width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"></path>
                                                    </svg> 
                                                    <h3 class="pl-1 mb-0">Features & Specifications:</h3>
                                                    <div class="flex-grow-1 ms-2 border-bottom"></div>
                                                </div>
                                               
                                                    ${featuresListHtml}
                                              
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="d-flex flex-column align-items-end" id="myZoom"></div>
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
                       
                        if (images && images.length > 0) {
                            // Prepare an array in the proper format for Zoomy
                            const zoomImages = images.map(img => ({
                                image: img.file_path,  // main image
                                thumb: img.file_path   // if no separate thumbnail, use the main image
                            }));

                            // Get the target element for Zoomy
                            const el = document.getElementById('myZoom');

                            // Initialize the Zoomy plugin (pure JS version)
                            zoomy(el, zoomImages, {
                                width: 300,
                                height: 300,
                                zoomScale: 2,
                                thumbHide: false // set to true if you want to hide thumbnails
                            });
                        }



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


<!-- for zoomy -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
(function() {
    function zoomy(element, urls, options) {
        if (!urls) return;
        if (typeof urls === 'string') urls = [urls];
        options = options || {};

        // Options
        const thumbHide = options.thumbHide || urls.length < 2; // Hide thumbnails if only one image
        const width = options.width || 300;                      // Width of the zoom container
        const height = options.height || 300;                    // Height of the zoom container
        const zoomScale = options.zoomScale || 2;               // Zoom magnification on hover

        // Set element size and add class
        element.style.width = width + 'px';
        element.style.height = height + 'px';
        element.classList.add('zoom');

        // Determine if thumbnails are objects with 'image' and 'thumb'
        const thumbMode = typeof urls[0] !== 'string';
        const firstImage = thumbMode ? urls[0].image : urls[0];

        // Main zoom container
        let html = `<div class="zoom-main" style="
                        width:100%; height:100%; 
                        background-image:url('${firstImage}');
                        background-size:100%;
                        background-position:center;
                        background-repeat:no-repeat;
                        transition: background-size 0.3s, background-position 0.1s;
                        position:relative;">
                    </div>`;

        // Thumbnails container
        if (!thumbHide) {
            html += `<div class="zoom-thumb">`;
            urls.forEach((url) => {
                const imgSrc = thumbMode ? url.thumb : url;
                const mainSrc = thumbMode ? url.image : url;
                html += `<img class="zoom-click" src="${imgSrc}" data-url="${mainSrc}" style="cursor:pointer; object-fit:cover;">`;
            });
            html += `</div>`;
        }

        element.innerHTML = html;

        const zoomMain = element.querySelector('.zoom-main');

        // Zoom on mouse enter
        zoomMain.addEventListener('mouseenter', () => {
            zoomMain.style.backgroundSize = `${zoomScale * 100}%`;
        });

        // Move background on mouse move
        zoomMain.addEventListener('mousemove', (e) => {
            const rect = zoomMain.getBoundingClientRect();
            const offsetX = e.clientX - rect.left;
            const offsetY = e.clientY - rect.top;
            const xPercent = (offsetX / rect.width) * 100;
            const yPercent = (offsetY / rect.height) * 100;
            zoomMain.style.backgroundPosition = `${xPercent}% ${yPercent}%`;
        });

        // Reset zoom on mouse leave
        zoomMain.addEventListener('mouseleave', () => {
            zoomMain.style.backgroundSize = '100%';
            zoomMain.style.backgroundPosition = 'center';
        });

        // Click on thumbnails to change main image
        const zoomClicks = element.querySelectorAll('.zoom-click');
        zoomClicks.forEach(img => {
            img.addEventListener('click', (e) => {
                e.stopPropagation();
                const newUrl = img.getAttribute('data-url');
                zoomMain.style.backgroundImage = `url('${newUrl}')`;
            });
        });
    }

    // Add zoomy function to the global window object
    window.zoomy = zoomy;
})();
</script>


<script>
function formatDate(dateString) {
    const date = new Date(dateString.replace(" ", "T")); // make it ISO-friendly

    const year = date.getFullYear();
    const month = date.getMonth() + 1; // months are 0-based
    const day = date.getDate();
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");

    return `${year}/${month}/${day} ${hours}:${minutes}`;
}

    function exportProducts(format) {
        const searchInput = document.getElementById("searchInput").value;
        const statusFilter = document.getElementById("statusFilter").value;
        const url = `../ajax/export_products.php?format=${format}&search=${encodeURIComponent(searchInput)}&status=${encodeURIComponent(statusFilter)}`;
        window.open(url, '_blank');
    }


</script>

