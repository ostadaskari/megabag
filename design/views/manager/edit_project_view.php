<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
        <svg width="22" height="22" fill="currentColor" class="bi bi-database-add" viewBox="0 0 16 16">
        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0"/>
        <path d="M12.096 6.223A5 5 0 0 0 13 5.698V7c0 .289-.213.654-.753 1.007a4.5 4.5 0 0 1 1.753.25V4c0-1.007-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1s-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4v9c0 1.007.875 1.755 1.904 2.223C4.978 15.71 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-.813-.927Q8.378 15 8 15c-1.464 0-2.766-.27-3.682-.687C3.356 13.875 3 13.373 3 13v-1.302c.271.202.58.378.904.525C4.978 12.71 6.427 13 8 13h.027a4.6 4.6 0 0 1 0-1H8c-1.464 0-2.766-.27-3.682-.687C3.356 10.875 3 10.373 3 10V8.698c.271.202.58.378.904.525C4.978 9.71 6.427 10 8 10q.393 0 .774-.024a4.5 4.5 0 0 1 1.102-1.132C9.298 8.944 8.666 9 8 9c-1.464 0-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777M3 4c0-.374.356-.875 1.318-1.313C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4"/>
        </svg>
        Edit Project
    </h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
        <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
        </svg>
        <span>Back</span>
    </a>
</div>

<div id="editProject" class="tab-content">
    <?php if (!$project_data): ?>
        <div class="alert alert-danger" role="alert">
            Project not found or invalid ID.
        </div>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="project_id" value="<?= htmlspecialchars($project_data['id']) ?>">
            
            <div class="container bg-light border rounded shadow-sm p-2">    
                <!-- project details -->
                <div class="d-flex flex-row align-items-center mb-2">
                    <svg width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    <h3 class="pl-1">Project Details:</h3>
                </div>
                
                <div class="row">
                    <!-- Project Name -->
                    <div class="col-12 col-md-3 my-2">
                        <label for="projectName" class="form-label">Project Name:</label>
                        <input type="text" name="project_name" id="projectName" class="form-control" autocomplete="off" value="<?= htmlspecialchars($project_data['project_name']) ?>" required>
                    </div>
                    <!-- Project owner -->
                    <div class="col-12 col-md-3 px-2 my-2">
                        <label for="owner" class="form-label">owner:</label>
                        <input type="text" name="owner" id="owner" class="form-control" autocomplete="off" value="<?= htmlspecialchars($project_data['owner']) ?>">
                    </div>
                    <!-- Project status -->
                    <div class="col-12 col-md-3 px-2 my-2">
                       <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="<?= htmlspecialchars($project_data['status']) ?>"><?= htmlspecialchars($project_data['status']) ?></option>
                            <option value="Pending">Pending</option>
                            <option value="Finished">Finished</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="designators" class="form-label">Designators:</label>
                        <textarea name="designators" id="designators" class="form-control" autocomplete="off"><?= htmlspecialchars($project_data['designators']) ?></textarea>
                    </div>
                </div>
            </div> 
    
            <div class="container bg-light border rounded shadow-sm p-2 my-2">
                <!-- Products Used Section -->
                <div class="d-flex flex-row align-items-center justify-content-between my-2">
                    <div class="d-flex align-items-center">
                        <svg width="20" height="20" fill="currentColor" class="bi bi-app-indicator" viewBox="0 0 16 16">
                            <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1z"/>
                            <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        </svg>
                        <h3 class="pl-1">Parts Used in Project:</h3>
                    </div>
                </div>

                <!-- Add New Parts Section -->
                <h4>Add New Parts</h4>
                <div class="d-flex align-items-center mb-2">
                    <div title="Add Row">
                        <button type="button" id="addRowBtn" class="btn btn-link btnSvg p-0">
                            <svg width="28" height="28" fill="green" class="bi bi-plus-circle-dotted hoverSvg" viewBox="0 0 16 16">
                                <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                            </svg>
                        </button>
                    </div>
                    <h5 class="pl-1 my-0">Click to add a new part</h5>
                </div>
                
                <div id="newPartsContainer" class="my-3">
                    <!-- New rows will be appended here via JavaScript -->
                </div>
                
                <!-- Existing Parts Used Section -->
                <h4>Existing Parts</h4>
                <div id="existingPartsContainer" class="my-3">
                    <?php if (empty($project_products)): ?>
                        <div class="alert alert-info" role="alert">
                            No parts have been added to this project yet.
                        </div>
                    <?php else: ?>
                        <!-- Dynamically generate rows for existing products -->
                        <?php foreach ($project_products as $index => $product): ?>
                            <div class="stock-row border p-2 rounded mb-2 bg-light position-relative">
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-12 col-md-4 px-1 position-relative mb-2">
                                        <label for="productInput" class="form-label">Part Lot Code:</label>
                                        <input type="text" name="products[<?= $index ?>][product_search]" class="form-control product-search" placeholder="Search by part number, x-code, or tag" autocomplete="off" value="<?= htmlspecialchars($product['x_code'] . " pn:" . $product['part_number']) ?>" required>
                                        <input type="hidden" name="products[<?= $index ?>][product_lot_id]" class="product-lot-id" value="<?= htmlspecialchars($product['product_lot_id']) ?>">
                                        <div class="autocomplete-box category-suggestions" style="display: none;"></div>
                                    </div>
                                    <div class="col-4 col-md-3 px-1 mb-2">
                                        <label for="quantityInput" class="form-label">QTY Used: <span class="available-qty text-muted fw-normal"></span></label>
                                        <input type="number" name="products[<?= $index ?>][used_qty]" class="form-control" min="1" value="<?= htmlspecialchars($product['used_qty']) ?>" required>
                                    </div>
                                    <div class="col-8 col-md-4 px-1 mb-2">
                                        <label for="commentInput" class="form-label">Remarks:</label>
                                        <textarea class="form-control" name="products[<?= $index ?>][remarks]" rows="1"><?= htmlspecialchars($product['remarks']) ?></textarea>
                                    </div>
                                    <div class="col d-flex justify-content-end align-items-end mb-2">
                                        <button type="button" class="btn btn-link btn-remove-row btnSvg p-0" title="Remove">
                                            <svg width="24" height="24" fill="#8b000d" class="bi bi-trash hoverSvg" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div> 

            <div style="text-align: end">
                <button type="submit" class="btn btn-primary" title="Submit">
                    Update Project
                </button>
            </div>
        </form>
        
        <!-- Hidden template for new rows -->
        <div id="newRowTemplate" class="stock-row border p-2 rounded mb-2 bg-light position-relative" style="display: none;">
            <div class="row d-flex align-items-end justify-content-between">
                <div class="col-12 col-md-4 px-1 position-relative mb-2">
                    <label for="productInput" class="form-label">Part Lot Code:</label>
                    <input type="text" name="products[0][product_search]" class="form-control product-search" placeholder="Search by x-code, part number " autocomplete="off" >
                    <input type="hidden" name="products[0][product_lot_id]" class="product-lot-id">
                    <div class="autocomplete-box category-suggestions" style="display: none;"></div>
                </div>
                <div class="col-4 col-md-3 px-1 mb-2">
                    <label for="quantityInput" class="form-label">QTY Used: <span class="available-qty text-muted fw-normal"></span></label>
                    <input type="number" name="products[0][used_qty]" class="form-control" min="1" >
                </div>
                <div class="col-8 col-md-4 px-1 mb-2">
                    <label for="commentInput" class="form-label">Remarks:</label>
                    <textarea class="form-control" name="products[0][remarks]" rows="1"></textarea>
                </div>
                <div class="col d-flex justify-content-end align-items-end mb-2">
                    <button type="button" class="btn btn-link btn-remove-row btnSvg p-0" title="Remove">
                        <svg width="24" height="24" fill="#8b000d" class="bi bi-trash hoverSvg" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($success) || !empty($errors)): ?>
<script>
    // Get the URL and its parameters
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    
    // Check if the success message should be shown
    const successMessage = params.get('success');
    if (successMessage) {
        Swal.fire({ 
            icon: 'success', 
            title: 'Success', 
            text: successMessage
        });
        // Remove the 'success' parameter from the URL
        params.delete('success');
    }
    
    // Check if the error message should be shown
    const errorMessage = params.get('error');
    if (errorMessage) {
        const errorsArray = errorMessage.split(' | ');
        const htmlContent = '<ul>' + errorsArray.map(error => '<li>' + error + '</li>').join('') + '</ul>';
        Swal.fire({
            icon: 'error',
            title: 'Errors',
            html: htmlContent
        });
        // Remove the 'error' parameter from the URL
        params.delete('error');
    }

    // Replace the current URL with the cleaned-up version
    const newUrl = `${url.pathname}?${params.toString()}`;
    history.replaceState(null, '', newUrl);
</script>
<?php endif; ?>


<script>
    const newPartsContainer = document.getElementById('newPartsContainer');
    const newRowTemplate = document.getElementById('newRowTemplate');

    // Function to re-index rows and manage remove button visibility
    const updateNewRows = () => {
        const rows = newPartsContainer.querySelectorAll('.stock-row');
        rows.forEach((row, index) => {
            row.querySelectorAll('input, textarea').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    el.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
            const removeButton = row.querySelector('.btn-remove-row');
            if (removeButton) {
                removeButton.style.display = rows.length > 0 ? 'block' : 'none';
            }
        });
    };

    // Add a new row
    document.getElementById('addRowBtn').addEventListener('click', () => {
        const newRow = newRowTemplate.cloneNode(true);
        newRow.style.display = 'block';
        newPartsContainer.appendChild(newRow);
        updateNewRows();
    });

    // Remove a row
    document.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-row')) {
            const rowToRemove = e.target.closest('.stock-row');
            if (rowToRemove) {
                rowToRemove.remove();
                updateNewRows();
            }
        }
    });

    // Product lot search using AJAX (same as create_project_view.php)
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('product-search')) {
            const input = e.target;
            const keyword = input.value;
            const wrapper = input.closest('.position-relative');
            const resultBox = wrapper.querySelector('.autocomplete-box');
            
            const productRow = input.closest('.stock-row');
            const availableQtySpan = productRow.querySelector('.available-qty');
            if (availableQtySpan) {
                availableQtySpan.textContent = '';
            }

            if (keyword.length >= 2) {
                fetch(`../ajax/search_product_lots.php?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultBox.innerHTML = '';
                        resultBox.style.display = 'block';
                        if (data.length === 0) {
                            resultBox.innerHTML = '<div class="p-2 text-muted">No product lots found</div>';
                        } else {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('p-2', 'autocomplete-item');
                                
                                // Check if the item is locked
                                if (item.lock == 1) { // Assuming 'lock' is 1 for true
                                    div.classList.add('locked-item');
                                    div.innerHTML = `${item.part_number} - x-code: ${item.x_code} <span class="text-danger fw-bold">(Locked for: ${item.project_name})</span>`;
                                } else {
                                    div.textContent = `${item.part_number} - x-code: ${item.x_code} (Available: ${item.qty_available})`;
                                }

                                div.style.cursor = 'pointer';
                                div.addEventListener('click', () => {
                                    // Prevent selection if the item is locked
                                    if (item.lock == 1) {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Item Locked',
                                            text: `This item is locked for the project: ${item.project_name}`
                                        });
                                        // Do not select the item, just clear the dropdown
                                        resultBox.innerHTML = '';
                                        resultBox.style.display = 'none';
                                    } else {
                                        // Set the input value to the selected lot details
                                        input.value = `${item.part_number} - x-code: ${item.x_code}`;
                                        const productLotIdInput = productRow.querySelector('.product-lot-id');
                                        productLotIdInput.value = item.lot_id;
                                        availableQtySpan.textContent = `(Available: ${item.qty_available})`;
                                        resultBox.innerHTML = '';
                                        resultBox.style.display = 'none';
                                    }
                                });
                                resultBox.appendChild(div);
                            });
                        }
                    })
                    .catch(error => console.error('Error fetching product lots:', error));
            } else {
                resultBox.innerHTML = '';
                resultBox.style.display = 'none';
            }
        }
    });

    // Hide autocomplete on click outside
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('product-search') && !e.target.closest('.autocomplete-box')) {
            document.querySelectorAll('.autocomplete-box').forEach(box => {
                box.style.display = 'none';
            });
        }
    });
</script>
