    <div class="d-flex flex-row align-items-center justify-content-between titleTop">
        <h2 class="d-flex align-items-center">
            <svg width="24" height="24" fill="currentColor" class="bi bi-pencil-square mx-1 me-2" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
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
            <!-- Form action points to the controller itself -->
            <form method="POST" action="" class="bg-light border rounded shadow-sm p-2">
                <!-- project details -->
                <div class="d-flex flex-row align-items-center mb-2">
                    <svg width="18" height="18" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    <h3 class="pl-1">Project Details:</h3>
                </div>
                <!-- Hidden input to store the project ID -->
                <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['id']) ?>">

                <div class="container">  
                    <!-- Project Details Section -->
                    <div class="row">
                        <!-- Project Name -->
                        <div class="col-12 col-md-3 my-2">
                            <label for="projectName" class="form-label">Project Name:</label>
                            <input type="text" name="project_name" id="projectName" class="form-control" required value="<?= htmlspecialchars($project['project_name']) ?>">
                        </div>
                        <!-- date code -->
                        <div class="col-12 col-md-3 px-2 my-2">
                            <label for="dateCode" class="form-label">Date Code:</label>
                            <input type="text" name="date_code" id="dateCode" class="form-control" value="<?= htmlspecialchars($project['date_code']) ?>">
                        </div>
                        <!-- Project Employer -->
                        <div class="col-12 col-md-3 px-2 my-2">
                            <label for="employer" class="form-label">Employer:</label>
                            <input type="text" name="employer" id="employer" class="form-control" value="<?= htmlspecialchars($project['employer']) ?>">
                        </div>

                        <div class="col-12 col-md-3 px-2 my-2">
                            <label for="purchaseCode" class="form-label">Purchase Code(s):</label>
                            <input type="text" name="purchase_code" id="purchaseCode" class="form-control" placeholder="e.g., code1, code2" value="<?= htmlspecialchars($project['purchase_code']) ?>">
                        </div>

                        <div class="col-6 my-2">
                            <label for="designators" class="form-label">Designators:</label>
                            <textarea name="designators" id="designators" class="form-control"><?= htmlspecialchars($project['designators']) ?></textarea>
                        </div>

                        <div class="col-12 col-md-3 px-2 my-2">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="<?= htmlspecialchars($project['status']) ?>"><?= htmlspecialchars($project['status']) ?></option>
                            <option value="Pending">Pending</option>
                            <option value="Finished">Finished</option>
                        </select>
                    </div>

                    </div>
                </div>
                <!-- Part Used Section -->
                  <div class="d-flex flex-row align-items-center my-2">
                        <svg width="20" height="20" fill="currentColor" class="bi bi-app-indicator" viewBox="0 0 16 16">
                            <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1z"/>
                            <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        </svg>
                        <h3 class="pl-1">Part Used in Project:</h3>
                    </div>
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-2">
                            <div id="stockRows">
                                <?php if (empty($projectProducts)): ?>
                                    <!-- Initial product row template if no products are found -->
                                    <div class="stock-row border p-2 rounded mb-2 bg-light position-relative">
                                        <div class="row d-flex align-items-end justify-content-between">
                                            <div class="col-12 col-md-5 px-1 position-relative mb-2">
                                                <label for="productInput" class="form-label">Part:</label>
                                                <input type="text" name="products[0][product_search]" class="form-control product-search" placeholder="Search by name, tag, or part number" autocomplete="off" required>
                                                <input type="hidden" name="products[0][product_id]" class="product-id">
                                                <div class="autocomplete-box" style="display: none;"></div>
                                            </div>
                                            <div class="col-4 col-md-3 px-1 mb-2">
                                                <label for="quantityInput" class="form-label">QTY Used: <span class="available-qty text-muted fw-normal"></span></label>
                                                <input type="number" name="products[0][used_qty]" class="form-control" min="1" required>
                                            </div>
                                            <div class="col-8 col-md-3 px-1 mb-2">
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
                                <?php else: ?>
                                    <!-- Loop through existing project products and display them -->
                                    <?php foreach ($projectProducts as $key => $product): ?>
                                        <div class="stock-row border p-2 rounded mb-2 bg-light position-relative">
                                            <div class="row d-flex align-items-end justify-content-between">
                                                <div class="col-12 col-md-5 position-relative mb-2">
                                                    <label for="productInput" class="form-label">Part:</label>
                                                    <input type="text" name="products[<?= $key ?>][product_search]" class="form-control product-search" placeholder="Search by name, tag, or part number" autocomplete="off" required value="<?= htmlspecialchars($product['part_number'] . ' (' . $product['product_name'] . ')') ?>">
                                                    <input type="hidden" name="products[<?= $key ?>][product_id]" class="product-id" value="<?= htmlspecialchars($product['product_id']) ?>">
                                                    <div class="autocomplete-box" style="display: none;"></div>
                                                </div>
                                                <div class="col-4 col-md-2 mb-2">
                                                    <label for="quantityInput" class="form-label">QTY Used: <span class="available-qty text-muted fw-normal"> (Available: <?= htmlspecialchars($product['current_qty']) ?>)</span></label>
                                                    <input type="number" name="products[<?= $key ?>][used_qty]" class="form-control" min="1" required value="<?= htmlspecialchars($product['used_qty']) ?>">
                                                </div>
                                                <div class="col-8 col-md-4 mb-2">
                                                    <label for="commentInput" class="form-label">Remarks:</label>
                                                    <textarea class="form-control" name="products[<?= $key ?>][remarks]" rows="1"><?= htmlspecialchars($product['remarks']) ?></textarea>
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

                            <div class="d-flex flex-row justify-content-between align-items-center w-100 px-1 mt-3">
                                <div title="Add Row">
                                    <button type="button" id="addRowBtn" class="btn btn-link btnSvg p-0">
                                        <svg width="28" height="28" fill="green" class="bi bi-plus-circle-dotted hoverSvg" viewBox="0 0 16 16">
                                            <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="">
                                    <button type="submit" class="btn btn-primary" title="Update">
                                        Update Project
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
       
    </div>


<?php if (!empty($success)): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: <?= json_encode($success) ?>
        });
    </script>
<?php elseif (!empty($errors)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Errors',
            html: <?= json_encode('<ul><li>' . implode('</li><li>', $errors) . '</li></ul>') ?>
        });
    </script>
<?php endif; ?>

<script>
    // This script dynamically adds and removes product rows and handles product search.
    
    // Function to re-index rows and manage remove button visibility
    const updateRows = () => {
        const rows = document.querySelectorAll('.stock-row');
        rows.forEach((row, index) => {
            // Re-index form fields
            row.querySelectorAll('input, textarea').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    el.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
            // Manage remove button visibility
            const removeButton = row.querySelector('.btn-remove-row');
            if (removeButton) {
                removeButton.style.display = rows.length > 1 ? 'block' : 'none';
            }
        });
    };

    // Add a new row
    const addRow = () => {
        const stockRowsContainer = document.getElementById('stockRows');
        const firstRow = stockRowsContainer.querySelector('.stock-row');
        if (firstRow) {
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input, textarea').forEach(el => {
                el.value = '';
                if (el.classList.contains('product-id')) {
                    el.value = '';
                }
            });
            const availableQtySpan = newRow.querySelector('.available-qty');
            if (availableQtySpan) {
                availableQtySpan.textContent = '';
            }
            stockRowsContainer.appendChild(newRow);
            updateRows();
        }
    }

    document.addEventListener('DOMContentLoaded', updateRows);
    document.getElementById('addRowBtn').addEventListener('click', addRow);

    // Remove a row
    document.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-row')) {
            const rowToRemove = e.target.closest('.stock-row');
            if (document.querySelectorAll('.stock-row').length > 1) {
                rowToRemove.remove();
                updateRows();
            }
        }
    });

    // Product search using AJAX
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
                fetch(`../ajax/search_products_by_keyword.php?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultBox.innerHTML = '';
                        resultBox.style.display = 'block';
                        if (data.length === 0) {
                            resultBox.innerHTML = '<div class="p-2 text-muted">No products found</div>';
                        } else {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('p-2', 'autocomplete-item');
                                div.textContent = `${item.name} (${item.part_number})`;
                                div.style.cursor = 'pointer';
                                div.addEventListener('click', () => {
                                    input.value = item.name;
                                    const productIdInput = productRow.querySelector('.product-id');
                                    productIdInput.value = item.id;
                                    
                                    // Fetch the available quantity using the new endpoint
                                    fetch(`../ajax/get_product_qty.php?product_id=${item.id}`)
                                        .then(res => res.json())
                                        .then(qtyData => {
                                            if (qtyData.qty !== undefined) {
                                                availableQtySpan.textContent = `(Available: ${qtyData.qty})`;
                                            } else {
                                                availableQtySpan.textContent = '';
                                            }
                                        })
                                        .catch(error => console.error('Error fetching product quantity:', error));
                                    
                                    resultBox.innerHTML = '';
                                    resultBox.style.display = 'none';
                                });
                                resultBox.appendChild(div);
                            });
                        }
                    })
                    .catch(error => console.error('Error fetching products:', error));
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
