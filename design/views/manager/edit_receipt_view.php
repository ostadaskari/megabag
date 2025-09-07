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

            <input type="hidden" name="receipt_id" value="<?php echo htmlspecialchars($receiptData['id']); ?>">
            
            <div id="receiptRow">
                <div class="stock-row border p-1 rounded mb-1 bg-light position-relative">
                    <div class="row d-flex align-items-end justify-content-between">

                        <!-- Product info (read-only) -->
                        <div class="col-12 col-md-3 px-1">
                            <label for="product_part_number" class="form-label">Part Number:</label>
                            <input type="text" id="product_part_number" class="form-control disabled" value="<?php echo htmlspecialchars($receiptData['part_number']); ?>" readonly >
                        </div>
                        <div class="col-12 col-md-2 px-1">
                            <label for="product_x_code" class="form-label" style="color:coral;">X-Code:</label>
                            <input type="text" id="product_x_code" class="form-control disabled" value="<?php echo htmlspecialchars($receiptData['x_code']); ?>" readonly >
                        </div>

                        <!-- New Fields: Lot Location and Project Name -->
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
                            <input type="number" name="qty_received" class="form-control" min="1" value="<?php echo htmlspecialchars($receiptData['qty_received']); ?>" required>
                        </div>

                        <!-- Optional purchase code -->
                        <div class="col-6 col-md-2 px-1 mt-2 mt-md-0">
                            <label class="form-label">Purchase Code:</label>
                            <input type="text" name="purchase_code" class="form-control" placeholder="Invoice # (optional)" value="<?php echo htmlspecialchars($receiptData['purchase_code']); ?>">
                        </div>

                        <!-- Optional VRM X Code -->
                        <div class="col-6 col-md-2 px-1 mt-2 mt-md-0">
                            <label class="form-label">VRM X Code:</label>
                            <input type="text" name="vrm_x_code" class="form-control" placeholder="VRM-X-Code(optional)" value="<?php echo htmlspecialchars($receiptData['vrm_x_code']); ?>">
                        </div>

                        <!-- Optional date code -->
                        <div class="col-6 col-md-2 px-1 mt-2 mt-md-0">
                            <label for="" class="form-label">Date Code:</label>
                            <select name="date_code" id="date_code" class="form-select" required>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>

                        <!-- New Field: Lock Checkbox -->
                        <div class="col-12 col-md-1 px-1 mt-2 mt-md-0">
                            <div class="form-check d-flex flex-column align-items-center justify-content-center h-100">
                                <label class="form-check-label" for="lockCheckbox">Lock:</label>
                                <input class="form-check-input mt-2" type="checkbox" name="lock" id="lockCheckbox" value="1" <?php echo ($receiptData['is_locked'] ?? false) ? 'checked' : ''; ?>>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="col-12 px-1 mt-2">
                            <label class="form-label">Comment:</label>
                            <textarea class="form-control" name="remarks" rows="3"><?php echo htmlspecialchars($receiptData['remarks']); ?></textarea>
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
        // Function to populate the date code select with years from current year back to 2017
        function populateDateCode(select, selectedYear) {
            const startYear = 2017;
            const currentYear = new Date().getFullYear();
            select.innerHTML = ''; // Clear existing options

            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                // Set the option as selected if it matches the current value
                if (year == selectedYear) {
                    option.selected = true;
                }
                select.appendChild(option);
            }
        }

        const dateCodeSelect = document.getElementById('date_code');
        if (dateCodeSelect) {
            const savedDateCode = "<?= htmlspecialchars($receiptData['date_code'] ?? '') ?>";
            populateDateCode(dateCodeSelect, savedDateCode);
        }
    });
</script>
