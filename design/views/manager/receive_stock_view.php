 <div class="d-flex flex-row align-items-center justify-content-between titleTop">       
    <h2 class="d-flex align-items-center">
    <svg width="25" height="25" fill="currentColor" class="bi bi-box-arrow-in-right  mx-1 me-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
        <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
    </svg>
    
    Receive Items</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<div id="Insert-Items" class="tab-content">
    <div class="container px-0 mt-1">
        <form method="POST" action="" id="groupStockForm">
            <div id="stockRows">
                <div class="stock-row border p-2 rounded mb-3 bg-light position-relative">
                    <div class="row d-flex align-items-end justify-content-between">
                        <div class="col-6 col-md-5 px-1 position-relative">
                            <label for="productInput" class="form-label">Product:</label>
                            <input type="text" name="products[0][product_search]" class="form-control product-search" placeholder="Search by name, tag, or part number" autocomplete="off" required>
                            <input type="hidden" name="products[0][product_id]" class="product-id">
                            <div class="autocomplete-box" style="display: none;"></div>
                        </div>

                        <div class="col-6 col-md-1 px-1">
                            <label for="quantityInput" class="form-label">QTY:</label>
                            <input type="number" name="products[0][qty_received]" class="form-control" min="1" required>
                        </div>
                        
                        <div class="col-10 col-md-5 px-1">
                            <label for="commentInputIn" class="form-label">Comment:</label>
                            <textarea class="form-control responsive-textarea" name="products[0][remarks]" rows="1"></textarea>
                        </div>
                        
                        <div class="col d-flex justify-content-end align-items-end">
                            <button type="button" class="btn btn-link btn-remove-row btnSvg p-0" title="Remove" style="display:none;">
                                <svg width="24" height="24" fill="#8b000d" class="bi bi-trash hoverSvg" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
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
                    <button type="submit" class="btn" title="Submit">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
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
    let rowIndex = 1;

    document.getElementById('addRowBtn').addEventListener('click', () => {
        const stockRowsContainer = document.getElementById('stockRows');
        const firstRow = stockRowsContainer.querySelector('.stock-row');
        const newRow = firstRow.cloneNode(true);

        // Reset values for new row
        newRow.querySelectorAll('input, textarea').forEach(el => {
            el.value = '';
        });

        // Update name attributes to new index
        newRow.querySelectorAll('input, textarea').forEach(el => {
            const name = el.getAttribute('name');
            if (name) {
                el.setAttribute('name', name.replace(/\[\d+\]/, `[${rowIndex}]`));
            }
        });

        // Show the remove button for the new row
        const removeButton = newRow.querySelector('.btn-remove-row');
        if (removeButton) {
            removeButton.style.display = 'block';
        }

        stockRowsContainer.appendChild(newRow);
        rowIndex++;
    });

    // Remove row functionality
    document.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-row')) {
            const rowToRemove = e.target.closest('.stock-row');
            const rows = document.querySelectorAll('.stock-row');
            if (rows.length > 1) {
                rowToRemove.remove();
            }
        }
    });

    // Product Search using AJAX
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('product-search')) {
            const input = e.target;
            const keyword = input.value;
            const wrapper = input.closest('.position-relative');
            let resultBox = wrapper.querySelector('.autocomplete-box');

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
                                div.textContent = `${item.name} (${item.part_number})`;
                                div.classList.add('p-2');
                                div.style.cursor = 'pointer';
                                div.addEventListener('click', () => {
                                    input.value = item.name;
                                    input.closest('.stock-row').querySelector('.product-id').value = item.id;
                                    resultBox.innerHTML = '';
                                    resultBox.style.display = 'none';
                                });
                                resultBox.appendChild(div);
                            });
                        }
                    });
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

<style>
/* This is a slight modification to ensure the autocomplete box is positioned correctly relative to the input */
.autocomplete-box {
    position: absolute;
    z-index: 9999;
    background: #fff;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    left: 0;
    top: 100%;
}
.autocomplete-box div:hover {
    background: #f0f0f0;
}
</style>