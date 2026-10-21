
<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
    <svg width="25" height="25" fill="currentColor" class="bi bi-pencil-square mx-1 me-2" viewBox="0 0 16 16">
        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
    </svg>
    Edit Receipt</h2>
    <a href="../auth/dashboard.php?page=list_receipts" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div id="Edit-Receipt" class="tab-content">
    <div class="container px-0 mt-1">
        <form method="POST" action="">
            <!-- CSRF token -->
            <?php generate_csrf_token(); ?>

            <input type="hidden" name="receipt_id" value="<?php echo htmlspecialchars((string)($receiptData['id'] ?? '')); ?>">
            
            <div id="receiptRow">
                <div class="stock-row border p-1 rounded mb-1 bg-light position-relative">
                    <div class="row d-flex align-items-end justify-content-between">

                        <!-- Product info (read-only) -->
                        <div class="col-12 col-md-3 px-1">
                            <label for="product_part_number" class="form-label">Part Number:</label>
                            <input type="text" id="product_part_number" class="form-control disabled" value="<?php echo htmlspecialchars($receiptData['part_number'] ?? ''); ?>" readonly >
                        </div>
                        <div class="col-12 col-md-2 px-1">
                            <label for="product_x_code" class="form-label" style="color:coral;">X-Code:</label>
                            <input type="text" id="product_x_code" class="form-control disabled" value="<?php echo htmlspecialchars($receiptData['x_code'] ?? ''); ?>" readonly >
                        </div>

                        <!-- Lot Location and Project Name -->
                        <div class="col-12 col-md-2 px-1 mt-2 mt-md-0">
                            <label for="lotLocation" class="form-label">Lot Location:</label>
                            <input type="text" id="lotLocation" name="lot_location" class="form-control" value="<?php echo htmlspecialchars($receiptData['lot_location'] ?? ''); ?>">
                        </div>
                        <div class="col-12 col-md-2 px-1 mt-2 mt-md-0">
                            <label for="projectName" class="form-label">Project Name:</label>
                            <input type="text" id="projectName" name="project_name" class="form-control" value="<?php echo htmlspecialchars($receiptData['project_name'] ?? ''); ?>">
                        </div>

                        <!-- Qty -->
                        <div class="col-6 col-md-1 px-1 mt-2 mt-md-0">
                            <label for="quantityInput" class="form-label">Rcvd QTY:</label>
                            <input type="number" name="qty_received" class="form-control" min="0" value="<?php echo htmlspecialchars((string)($receiptData['qty_received'] ?? 0)); ?>" required>
                        </div>

                        <!-- Purchase Code -->
                        <div class="col-6 col-md-2 px-1 mt-2 mt-md-0">
                            <label class="form-label">Purchase Code:</label>
                            <input type="text" name="purchase_code" class="form-control" placeholder="Invoice # (optional)" value="<?php echo htmlspecialchars($receiptData['purchase_code'] ?? ''); ?>">
                        </div>

                        <!-- VRM X Code -->
                        <div class="col-6 col-md-2 px-1 mt-2 mt-md-0">
                            <label class="form-label">VRM X Code:</label>
                            <input type="text" name="vrm_x_code" class="form-control" placeholder="VRM-X-Code(optional)" value="<?php echo htmlspecialchars($receiptData['vrm_x_code'] ?? ''); ?>">
                        </div>

                        <!-- Date Code -->
                        <div class="col-6 col-md-2 px-1 mt-2 mt-md-0">
                            <label for="date_code" class="form-label">Date Code:</label>
                            <select name="date_code" id="date_code" class="form-select" required>
                                <!-- Options populated by JavaScript -->
                            </select>
                        </div>

                        <!-- Lock Checkbox -->
                        <div class="col-12 col-md-1 px-1 mt-2 mt-md-0">
                            <div class="form-check d-flex flex-column align-items-center justify-content-center h-100">
                                <label class="form-check-label" for="lockCheckbox">Lock:</label>
                                <input class="form-check-input mt-2" type="checkbox" name="lock" id="lockCheckbox" value="1" <?php echo !empty($receiptData['is_locked']) ? 'checked' : ''; ?>>
                            </div>
                        </div>

                        <!-- Remarks / Comment with WordPress-style Tag Input -->
                        <div class="col-12 px-1 mt-2 position-relative">
                            <label class="form-label">Comment:</label>
                            <input type="hidden" name="remarks" class="comment-hidden-value" value="<?php echo htmlspecialchars($receiptData['remarks'] ?? ''); ?>">
                            <div class="tag-textarea-box form-control d-flex flex-wrap align-items-center gap-1" style="min-height: 38px; height: auto !important; background-color: #fff; cursor: text; padding: 4px 8px;">
                                <div class="tags-container d-flex flex-wrap align-items-center gap-1"></div>
                                <input type="text" class="tag-search-input border-0 bg-transparent p-0 flex-grow-1 text-dark" placeholder="Type or select comment..." autocomplete="off" style="outline: none; min-width: 130px; height: 28px;">
                            </div>
                            <div class="autocomplete-box comment-suggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; background: #fff; border: 1px solid #ced4da; border-radius: 0.25rem; max-height: 150px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row justify-content-end align-items-center w-100 px-1 mt-3">
                <div>
                    <button type="submit" class="btn" title="Submit">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        function populateDateCode(select, selectedYear) {
            const startYear = 2017;
            const currentYear = new Date().getFullYear();
            select.innerHTML = '';

            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year == selectedYear) {
                    option.selected = true;
                }
                select.appendChild(option);
            }
        }

        const dateCodeSelect = document.getElementById('date_code');
        if (dateCodeSelect) {
            const savedDateCode = "<?= htmlspecialchars((string)($receiptData['date_code'] ?? '')) ?>";
            populateDateCode(dateCodeSelect, savedDateCode);
        }

        // ==========================================
        // WordPress-Style Multi-Tag Comments Feature (Edit Page)
        // ==========================================
        const predefinedComments = [
            "Cap detached",
            "Suspected Faulty",
            "Broken Pin",
            "Damaged Pin",
            "Dmontaje",
            "Desoldered",
            "Component Lead Oxidation",
            "New Component",
            "Used",
            "unused",
            "Plastic Coating melted",
            "Component in good condition",
            "Test before use",
            "Not tested",
            "Condition to be verified",
            "in good Functional",
            "Refurbished",
            "Repaired",
            "Reconditioned",
            "Reballing done",
            "Solder balls removed",
            "Part number to be verified"
        ];

        const commentWrapper = document.querySelector('.tag-textarea-box').closest('.position-relative');
        const hiddenCommentInput = commentWrapper.querySelector('.comment-hidden-value');
        
        // Load initial existing remarks if available
        if (hiddenCommentInput && hiddenCommentInput.value.trim() !== '') {
            // Split by comma or handle custom formatting if needed
            const initialTags = hiddenCommentInput.value.split(',').map(t => t.trim()).filter(t => t !== '');
            initialTags.forEach(tagText => {
                addTag(commentWrapper, tagText);
            });
        }

        // Focus input when clicking tag textarea box
        document.addEventListener('click', function(e) {
            const wrapper = e.target.closest('.tag-textarea-box');
            if (wrapper) {
                const input = wrapper.querySelector('.tag-search-input');
                if (input) input.focus();
            }
        });

        // Handle tag input typing & suggestions
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('tag-search-input')) {
                const input = e.target;
                const keyword = input.value.trim().toLowerCase();
                const parentWrapper = input.closest('.position-relative');
                let resultBox = parentWrapper.querySelector('.comment-suggestions');

                if (keyword.length > 0) {
                    const filtered = predefinedComments.filter(item => item.toLowerCase().includes(keyword));
                    
                    resultBox.innerHTML = '';
                    if (filtered.length > 0) {
                        resultBox.style.display = 'block';
                        filtered.forEach(item => {
                            const div = document.createElement('div');
                            div.textContent = item;
                            div.classList.add('p-2', 'comment-suggestion-item', 'text-dark');
                            div.style.cursor = 'pointer';
                            div.style.borderBottom = '1px solid #f1f1f1';
                            
                            div.addEventListener('mouseover', () => div.style.backgroundColor = '#f8f9fa');
                            div.addEventListener('mouseout', () => div.style.backgroundColor = '#fff');

                            div.addEventListener('click', () => {
                                addTag(parentWrapper, item);
                                input.value = '';
                                resultBox.innerHTML = '';
                                resultBox.style.display = 'none';
                                input.focus();
                            });
                            resultBox.appendChild(div);
                        });
                    } else {
                        resultBox.style.display = 'none';
                    }
                } else {
                    resultBox.innerHTML = '';
                    resultBox.style.display = 'none';
                }
            }
        });

        // Handle Enter key to add custom or typed tags & Backspace to delete last tag
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('tag-search-input')) {
                const input = e.target;
                const parentWrapper = input.closest('.position-relative');
                const resultBox = parentWrapper.querySelector('.comment-suggestions');

                if (e.key === 'Enter') {
                    e.preventDefault();
                    const val = input.value.trim();
                    if (val !== '') {
                        addTag(parentWrapper, val);
                        input.value = '';
                        resultBox.innerHTML = '';
                        resultBox.style.display = 'none';
                    }
                } else if (e.key === 'Backspace' && input.value === '') {
                    const tagsContainer = parentWrapper.querySelector('.tags-container');
                    const lastTag = tagsContainer.lastElementChild;
                    if (lastTag) {
                        lastTag.remove();
                        updateHiddenCommentValue(parentWrapper);
                    }
                }
            }
        });

        // Function to add a tag pill with fixed layout wrapping
        function addTag(parentWrapper, text) {
            const tagsContainer = parentWrapper.querySelector('.tags-container');
            
            // Check if tag already exists in this row
            const existingTags = Array.from(tagsContainer.querySelectorAll('.comment-tag-text')).map(el => el.textContent.trim().toLowerCase());
            if (existingTags.includes(text.toLowerCase())) return;

            const tagBadge = document.createElement('span');
            tagBadge.className = 'badge border d-inline-flex align-items-center gap-1 px-2 py-1 my-1';
            tagBadge.style.backgroundColor = '#e9ecef';
            tagBadge.style.color = '#212529';
            
            tagBadge.innerHTML = `<span class="comment-tag-text fw-normal" style="color: #212529;">${text}</span> <button type="button" class="btn-close remove-tag-btn shadow-none" style="font-size: 0.5rem; filter: none !important; opacity: 0.8;" aria-label="Remove"></button>`;

            // Remove tag event
            tagBadge.querySelector('.remove-tag-btn').addEventListener('click', function(e) {
                e.stopPropagation();
                tagBadge.remove();
                updateHiddenCommentValue(parentWrapper);
            });

            tagsContainer.appendChild(tagBadge);
            updateHiddenCommentValue(parentWrapper);
        }

        // Update hidden input value with comma-separated tags
        function updateHiddenCommentValue(parentWrapper) {
            const tagsContainer = parentWrapper.querySelector('.tags-container');
            const hiddenInput = parentWrapper.querySelector('.comment-hidden-value');
            const tags = Array.from(tagsContainer.querySelectorAll('.comment-tag-text')).map(el => el.textContent.trim());
            hiddenInput.value = tags.join(', ');
        }

        // Hide comment autocomplete on click outside
        document.addEventListener('click', function(e) {
            if (!e.target.classList.contains('tag-search-input') && !e.target.closest('.comment-suggestions')) {
                document.querySelectorAll('.comment-suggestions').forEach(box => {
                    box.style.display = 'none';
                });
            }
        });
    });
</script>