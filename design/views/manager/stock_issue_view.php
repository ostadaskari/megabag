<div class="d-flex flex-row align-items-center justify-content-between titleTop">      
    <h2 class="d-flex align-items-center">
    <svg width="24" height="24" fill="currentColor" class="bi bi-box-arrow-right mx-1 me-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 1 0 3.5v9A1.5 1.5 0 0 1 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
    </svg>
    Withdraw Items</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<div class="container p-0">
    <form method="POST" action="" id="groupIssueForm">
        <div id="issueRows">
            <!-- Dynamic rows will be added here by JavaScript -->
        </div>

        <div class="d-flex flex-row justify-content-between align-items-center w-100 px-1 mt-3">
            <div id="addRowBtn" class="add-row-btn" title="Add Row">
                <svg width="28" height="28" fill="green" class="bi bi-plus-circle-dotted hoverSvg" viewBox="0 0 16 16">
                    <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"></path>
                </svg>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" title="Submit">Submit</button>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($success)): ?>
<script>
Swal.fire({ icon: 'success', title: 'Success', text: <?= json_encode($success) ?> });
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

<style>
    /* Styles for autocomplete dropdown */
    .autocomplete-dropdown {
        position: absolute;
        border: 1px solid #ddd;
        background-color: white;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000; /* Ensure it's above other content */
        width: calc(100% - 2px); /* Match input width */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 5px;
        left: 1px; /* Align with input */
        top: 100%; /* Position below the input */
    }
    .autocomplete-item {
        padding: 8px 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }
    .autocomplete-item:hover {
        background-color: #f0f0f0;
    }
    .autocomplete-item:last-child {
        border-bottom: none;
    }
</style>

<!-- JavaScript for Dynamic Rows + AJAX Search -->
<script>
// Keep a global counter for row indices
let rowCounter = 0;

// Template for a new row
function createRowHtml(index) {
    return `
        <div class="stock-row border p-3 rounded mb-3 bg-light">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2 px-1 position-relative"> <!-- Added position-relative -->
                    <label for="productInput" class="form-label">Product:</label>
                    <input type="text" name="products[${index}][product_search]" class="form-control product-search" placeholder="Search name/tag/part" autocomplete="off" required>
                    <input type="hidden" name="products[${index}][product_id]" class="product-id">
                </div>
                <div class="col-6 col-md-3 px-1">
                    <label for="quantityInput" class="form-label">QTY: <span class="text-muted small current-qty">(Available: <span class="qty-value" style="color: green;">--</span>)</span></label>
                    <input type="number" name="products[${index}][qty_issued]" class="form-control qty-input" min="1" required>
                </div>
                <div class="col-6 col-md-2 px-1 position-relative"> <!-- Added position-relative -->
                    <label for="Issued-ToInput" class="form-label">Issued To:</label>
                    <input type="text" name="products[${index}][issued_to_search]" class="form-control user-search" placeholder="Name, Family, Nickname" autocomplete="off" required>
                    <input type="hidden" name="products[${index}][issued_to_id]" class="user-id">
                </div>
                <div class="col-10 col-md-4 px-1">
                    <label for="commentInputOut" class="form-label">Remarks (optional)</label>
                    <textarea name="products[${index}][remarks]" class="form-control" rows="1"></textarea>
                </div>
                <div class="col-1 d-flex justify-content-end align-items-end">
                    <a href="#" class="btn-remove-row aButton btnSvg" title="Remove">
                        <svg width="24" height="24" fill="#8b000d" class="bi bi-trash hoverSvg" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    `;
}

// Add a new row to the form
function addRow() {
    const issueRowsContainer = document.getElementById('issueRows');
    const newRow = document.createElement('div');
    newRow.innerHTML = createRowHtml(rowCounter).trim();
    issueRowsContainer.appendChild(newRow.firstChild);

    // Show remove buttons if there is more than one row
    const removeButtons = document.querySelectorAll('.btn-remove-row');
    if (removeButtons.length > 1) {
        removeButtons.forEach(btn => btn.classList.remove('d-none'));
    }

    rowCounter++;
}

// Event listener to add a new row
document.getElementById('addRowBtn').addEventListener('click', addRow);

// Event listener to remove a row
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-row')) {
        const row = e.target.closest('.stock-row');
        // Prevent removing the last row
        if (document.querySelectorAll('.stock-row').length > 1) {
            row.remove();
        }

        // Hide remove buttons if only one row is left
        if (document.querySelectorAll('.stock-row').length === 1) {
            document.querySelector('.btn-remove-row').classList.add('d-none');
        }
    }
});

// Initial row on page load
document.addEventListener("DOMContentLoaded", function () {
    addRow(); // Add the first row on page load
    // Initially hide the remove button as there's only one row
    document.querySelector('.btn-remove-row').classList.add('d-none');
});

// Live input listener for product and user search
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('product-search')) {
        const input = e.target;
        const keyword = input.value;
        const parentCol = input.closest('.position-relative');
        
        let dropdown = parentCol.querySelector('.autocomplete-dropdown');
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.classList.add('autocomplete-dropdown');
            parentCol.appendChild(dropdown);
        }
        
        const hiddenInput = input.closest('.stock-row').querySelector('.product-id');
        const qtyInput = input.closest('.stock-row').querySelector('.qty-input');
        const qtyEl = input.closest('.stock-row').querySelector('.qty-value');

        // Reset if input is cleared
        if (keyword.length === 0) {
            hiddenInput.value = '';
            qtyInput.max = '';
            qtyEl.textContent = '--';
            if (dropdown) dropdown.innerHTML = ''; // Clear dropdown content
            return;
        }

        if (keyword.length >= 2) {
            fetch(`../ajax/search_products_by_keyword.php?keyword=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    showDropdown(data, input, dropdown, 'product-id', 'id', 'name', 'part_number', true);
                });
        } else {
            if (dropdown) dropdown.innerHTML = ''; // Clear dropdown content
        }
    }

    if (e.target.classList.contains('user-search')) {
        const input = e.target;
        const keyword = input.value;
        const parentCol = input.closest('.position-relative');
        
        let dropdown = parentCol.querySelector('.autocomplete-dropdown');
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.classList.add('autocomplete-dropdown');
            parentCol.appendChild(dropdown);
        }
        
        const hiddenInput = input.closest('.stock-row').querySelector('.user-id');

        // Reset if input is cleared
        if (keyword.length === 0) {
            hiddenInput.value = '';
            if (dropdown) dropdown.innerHTML = ''; // Clear dropdown content
            return;
        }
        
        if (keyword.length >= 2) {
            fetch(`../ajax/search_users_by_keyword.php?keyword=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    showDropdown(data, input, dropdown, 'user-id', 'id', 'name', 'nickname', false);
                });
        } else {
            if (dropdown) dropdown.innerHTML = ''; // Clear dropdown content
        }
    }
});

// Hide dropdown on outside click
document.addEventListener('click', function (e) {
    document.querySelectorAll('.autocomplete-dropdown').forEach(dropdown => {
        // Check if the click target is not inside the current dropdown and not the input itself
        if (!dropdown.contains(e.target) && !e.target.classList.contains('form-control')) {
            dropdown.innerHTML = ''; // Clear dropdown content instead of removing the element
        }
    });
});

// Show dropdown
function showDropdown(data, input, dropdown, hiddenInputClass, idKey, nameKey, secondaryKey, isProduct = false) {
    dropdown.innerHTML = '';

    if (data.length > 0) {
        data.forEach(item => {
            const div = document.createElement('div');
            div.classList.add('autocomplete-item');
            div.innerHTML = `<strong>${item[nameKey]}</strong> - ${item[secondaryKey]}`;

            div.addEventListener('click', () => {
                input.value = `${item[nameKey]} (${item[secondaryKey]})`;
                const hiddenInput = input.closest('.stock-row').querySelector(`.${hiddenInputClass}`);
                if (hiddenInput) hiddenInput.value = item[idKey];

                dropdown.innerHTML = ''; // Clear dropdown after selection

                // Fetch and display product quantity
                if (isProduct) {
                    fetch(`../ajax/get_product_qty.php?product_id=${item[idKey]}`)
                        .then(res => res.json())
                        .then(qtyData => {
                            const qtyEl = input.closest('.stock-row').querySelector('.qty-value');
                            const qtyInput = input.closest('.stock-row').querySelector('.qty-input');
                            if (qtyEl) {
                                qtyEl.textContent = qtyData.qty;
                                qtyInput.max = qtyData.qty; // Set max attribute
                                qtyInput.value = Math.min(qtyInput.value, qtyData.qty); // Adjust value if it exceeds max
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching quantity:', error);
                            const qtyEl = input.closest('.stock-row').querySelector('.qty-value');
                            if (qtyEl) {
                                qtyEl.textContent = '--';
                            }
                        });
                }
            });
            dropdown.appendChild(div);
        });
    } else {
        dropdown.innerHTML = '<div class="autocomplete-item text-muted">No results found.</div>';
    }
}

// Check if product-id and user-id are filled before allowing form submission
document.getElementById('groupIssueForm').addEventListener('submit', function (e) {
    let valid = true;

    document.querySelectorAll('.stock-row').forEach(row => {
        const productId = row.querySelector('.product-id')?.value;
        const userId = row.querySelector('.user-id')?.value;

        if (!productId || !userId) {
            valid = false;
        }
    });

    if (!valid) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Missing Fields',
            text: 'Please make sure you have selected a valid product and user for all rows from the dropdowns.'
        });
    }
});
</script>