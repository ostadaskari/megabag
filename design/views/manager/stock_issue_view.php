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
                                <!-- CSRF  -->
        <?php generate_csrf_token(); ?>
        <div id="issueRows">
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



<script>
    // Keep a global counter for row indices
    let rowCounter = 0;

    // Template for a new row
    function createRowHtml(index) {
        return `
            <div class="stock-row border p-3 rounded mb-3 bg-light">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-3 px-1 position-relative">
                        <label for="productInput${index}" class="form-label">X-Code:</label>
                        <input type="text" name="products[${index}][lot_search]" id="productInput${index}" class="form-control lot-search" placeholder="Search by X-Code & P/N" autocomplete="off" required>
                        <input type="hidden" name="products[${index}][product_lot_id]" class="product-lot-id">
                    </div>
                    <div class="col-6 col-md-2 px-1">
                        <label for="quantityInput${index}" class="form-label">QTY: <span class="text-muted small current-qty">(Available: <span class="qty-value" style="color: green;">--</span>)</span></label>
                        <input type="number" name="products[${index}][qty_issued]" id="quantityInput${index}" class="form-control qty-input" min="1" required>
                    </div>
                    <div class="col-6 col-md-3 px-1 position-relative">
                        <label for="IssuedToInput${index}" class="form-label">Issued To:</label>
                        <input type="text" name="products[${index}][issued_to_search]" id="IssuedToInput${index}" class="form-control user-search" placeholder="Name, Family, Nickname" autocomplete="off" required>
                        <input type="hidden" name="products[${index}][issued_to_id]" class="user-id">
                    </div>
                    <div class="col-11 col-md-3 px-1">
                        <label for="commentInputOut${index}" class="form-label">Remarks (optional)</label>
                        <textarea name="products[${index}][remarks]" id="commentInputOut${index}" class="form-control" rows="1"></textarea>
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

        // Update visibility of remove buttons
        const removeButtons = document.querySelectorAll('.btn-remove-row');
        if (removeButtons.length > 1) {
            removeButtons.forEach(btn => btn.classList.remove('d-none'));
        } else {
            // If only one row, ensure the button is hidden
            document.querySelector('.btn-remove-row').classList.add('d-none');
        }

        rowCounter++;
    }

    // Event listener to add a new row
    document.getElementById('addRowBtn').addEventListener('click', addRow);

    // Event listener to remove a row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-row')) {
            e.preventDefault();
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
    });

    // --- Core Logic Refactoring ---

    // Function to handle the blur event on lot-search input and barcode scan (Enter)
    function handleLotSearchLookup(input, isBarcodeScan = false) {
        const keyword = input.value.trim();
        const row = input.closest('.stock-row');
        const hiddenInput = row.querySelector('.product-lot-id');
        const qtyEl = row.querySelector('.qty-value');
        const qtyInput = row.querySelector('.qty-input');
        
        // Reset state
        hiddenInput.value = '';
        input.classList.remove('selection-complete', 'locked-input');
        toggleRowInputs(row, false);
        qtyEl.textContent = '--';
        qtyInput.max = '';

        if (!keyword) {
            // If input is empty, nothing to do
            return;
        }

        // Use the exact_keyword parameter for blur/scan logic
        fetch(`../ajax/search_lot_by_x.php?exact_keyword=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => {
                
                if (data.length === 1) {
                    const item = data[0];
                    
                    // 1. Mark selection as complete (Success state)
                    input.classList.add('selection-complete');
                    hiddenInput.value = item.id;
                    
                    // Update input display value (consistent with showDropdown)
                    input.value = item.part_number 
                                ? `${item.x_code} (${item.part_number})`
                                : item.x_code;

                    // Update Quantity info
                    qtyEl.textContent = item.qty_available;
                    qtyInput.max = item.qty_available;
                    qtyInput.value = Math.min(qtyInput.value || 1, item.qty_available); 
                    
                    // 2. Check for actual lock status (Error state)
                    if (item.lock) {
                        // Apply the 'locked-input' class if the item is truly locked
                        input.classList.add('locked-input');
                        Swal.fire({
                            icon: 'info',
                            title: 'Item Locked',
                            text: `This item is locked for the project: ${item.project_name}. This row will be ignored during submission.`
                        });
                        // IMPORTANT: We keep hiddenInput.value, but the form submission will now reject this row.
                    }

                    // 3. If it was a barcode scan, auto-add a row and focus
                    if (isBarcodeScan) {
                        // Wait for a short delay to ensure UI updates before focus
                        setTimeout(() => {
                            addRow();
                            const lastRow = document.querySelector('#issueRows').lastElementChild;
                            lastRow.querySelector('.lot-search').focus();
                        }, 100);
                    }

                } else {
                    // No exact match found
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Exact Match',
                        text: 'The code did not match an available item. Use search dropdown or try again.'
                    });
                    input.value = keyword; // Keep the user's input for easy editing
                    // State is already reset (no hidden ID, no selection-complete)
                }
            })
            .catch(error => {
                console.error("Error during exact lot lookup:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lookup Failed',
                    text: 'An error occurred while searching for the item.'
                });
            });
    }

    // Function to toggle the disabled state of other inputs in a row
    function toggleRowInputs(row, disable) {
        row.querySelectorAll('.qty-input, .user-search, textarea').forEach(el => {
            // Inputs should not be disabled. We should only disable if we want to visually lock them.
            // For now, based on your original code, it seems you wanted to disable them when a match was NOT found, which is counter-intuitive.
            // I'm modifying this to NOT disable, but you can re-introduce the logic if needed. 
            // The original logic was: toggleRowInputs(row, false) on success, which means 'enable'.
            // The issue is, what to do on failure? We keep them enabled so the user can use the dropdown.
            // Since the original code had 'toggleRowInputs(row, false)' on success/reset, I will remove the disable logic here entirely, as it seems unnecessary or incorrect for this use case.
        });
    }


    // --- Event Listeners ---

    // New event listener for checking an exact match on blur (user tabs out)
    document.addEventListener('blur', function(e) {
        if (e.target.classList.contains('lot-search')) {
            // Only perform blur action if no successful selection has been made yet
            if (!e.target.classList.contains('selection-complete')) {
                handleLotSearchLookup(e.target, false);
            }
        }
    }, true);

    // BARCODE SCANNER LOGIC: Listen for Enter key
    document.addEventListener('keyup', function(e) {
        // Check if the key is 'Enter' and the target is a lot-search input
        if (e.key === 'Enter' && e.target.classList.contains('lot-search')) {
            e.preventDefault(); // Prevent form submission
            handleLotSearchLookup(e.target, true);
        }
    });

    // Live input listener for product lot and user search (Dropdown logic)
    document.addEventListener('input', function(e) {
        // Clear selection state on input start
        if (e.target.classList.contains('lot-search')) {
            const input = e.target;
            const row = input.closest('.stock-row');
            const hiddenInput = row.querySelector('.product-lot-id');
            const qtyEl = row.querySelector('.qty-value');
            const qtyInput = row.querySelector('.qty-input');
            
            // Clear successful selection markers and hidden ID when the user starts typing again
            input.classList.remove('selection-complete', 'locked-input'); 
            hiddenInput.value = '';
            qtyEl.textContent = '--';
            qtyInput.max = '';
            
            // Proceed with fetching dropdown suggestions
            const keyword = input.value;
            const parentCol = input.closest('.position-relative');
            let dropdown = parentCol.querySelector('.autocomplete-dropdown');
            if (!dropdown) {
                dropdown = document.createElement('div');
                dropdown.classList.add('autocomplete-dropdown');
                parentCol.appendChild(dropdown);
            }

            if (keyword.length >= 2) {
                fetch(`../ajax/search_lot_by_x.php?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        showDropdown(data, input, dropdown, 'product-lot-id', 'id', 'x_code', 'part_number', true);
                    });
            } else {
                if (dropdown) dropdown.style.display = 'none';
            }
        }

        // Handle user search (remains largely the same)
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
                if (dropdown) dropdown.style.display = 'none';
                return;
            }
            
            if (keyword.length >= 2) {
                fetch(`../ajax/search_users_by_keyword.php?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        showDropdown(data, input, dropdown, 'user-id', 'id', 'nickname', null, false); 
                    });
            } else {
                if (dropdown) dropdown.style.display = 'none';
            }
        }
    });

    // Show dropdown (updated to manage selection-complete class)
    function showDropdown(data, input, dropdown, hiddenInputClass, idKey, nameKey, secondaryKey, isLot = false) {
        dropdown.innerHTML = '';
        
        if (data.length > 0) {
            data.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('autocomplete-item');

                // Check if the item is a locked product lot (for display in dropdown)
                if (isLot && item.lock) {
                    div.classList.add('locked-item');
                    div.innerHTML = `<strong>${item[nameKey]}</strong> ${item[secondaryKey] ? `- ${item[secondaryKey]}` : ''} <span class="text-danger small">(LOCKED)</span>`;
                } else {
                    div.innerHTML = `<strong>${item[nameKey]}</strong>${item[secondaryKey] ? ` - ${item[secondaryKey]}` : ''}`;
                }

                div.addEventListener('click', () => {
                    const row = input.closest('.stock-row');
                    const hiddenInput = row.querySelector(`.${hiddenInputClass}`);

                    // Clear any previous status
                    input.classList.remove('selection-complete', 'locked-input');
                    
                    if (isLot && item.lock) {
                        // Truly locked item selected from dropdown
                        input.classList.add('locked-input');
                        hiddenInput.value = item[idKey]; // Keep the ID for server-side logging/handling
                        input.value = item[secondaryKey] 
                            ? `${item[nameKey]} (${item[secondaryKey]})`
                            : item[nameKey];
                        
                        Swal.fire({
                            icon: 'info',
                            title: 'Item Locked',
                            text: `This item is locked for the project: ${item.project_name}. Submission will be blocked.`
                        });
                        
                        // Mark selection as complete even if locked, but apply the locked-input color
                        input.classList.add('selection-complete'); 

                    } else {
                        // Valid item selected or User selected
                        input.value = item[secondaryKey] 
                            ? `${item[nameKey]} (${item[secondaryKey]})`
                            : item[nameKey];
                            
                        if (hiddenInput) {
                            hiddenInput.value = item[idKey];
                        }

                        // Set selection status
                        if (isLot) {
                            input.classList.add('selection-complete');
                            
                            // Fetch and display product lot quantity
                            const qtyEl = row.querySelector('.qty-value');
                            const qtyInput = row.querySelector('.qty-input');
                            if (qtyEl) {
                                qtyEl.textContent = item.qty_available;
                                qtyInput.max = item.qty_available; // Set max attribute
                                qtyInput.value = Math.min(qtyInput.value || 1, item.qty_available); // Adjust value if it exceeds max
                            }
                        }
                    }

                    dropdown.innerHTML = ''; // Clear dropdown content
                    dropdown.style.display = 'none'; // Hide the dropdown
                });
                dropdown.appendChild(div);
            });
            dropdown.style.display = 'block';
        } else {
            dropdown.innerHTML = '<div class="autocomplete-item text-muted">No results found.</div>';
            dropdown.style.display = 'block';
        }
    }


    // Check if product-lot-id and user-id are filled before allowing form submission
    document.getElementById('groupIssueForm').addEventListener('submit', function (e) {
        let valid = true;
        const invalidRows = [];

        document.querySelectorAll('.stock-row').forEach((row, index) => {
            const productLotId = row.querySelector('.product-lot-id')?.value;
            const userId = row.querySelector('.user-id')?.value;
            
            // NEW VALIDATION: Check for missing IDs or if the item is truly locked (red input)
            const isTrulyLocked = row.querySelector('.lot-search').classList.contains('locked-input');

            if (!productLotId || !userId || isTrulyLocked) {
                valid = false;
                invalidRows.push(index + 1);
                // Optionally add an error border to the invalid row
                row.classList.add('border-danger');
            } else {
                row.classList.remove('border-danger');
            }
        });

        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Submission',
                html: `Please correct the issues in the following rows: 
                        <ul>
                            <li>Make sure a valid product lot and user are selected from the **dropdowns**.</li>
                            <li>A selected item might be **locked** (Row(s): ${invalidRows.join(', ')}) and cannot be withdrawn.</li>
                        </ul>`
            });
        }
    });
    </script>
