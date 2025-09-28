<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
        <svg width="24" height="24" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
        </svg>
        Edit Issue</h2>
    <a href="../auth/dashboard.php?page=list_issues" class="backBtn">
        <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
        </svg>
        <span>Back</span>
    </a>
</div>

<?php 
// Removed standard PHP conditional alerts, relying on JS SweetAlert below.

if ($issueData): // Only display the form if the data was fetched successfully
?>
    <div class="container p-0">
        <form method="POST" action="" id="editIssueForm">
            <input type="hidden" name="action" value="edit_issue">
            <input type="hidden" name="issue_id" value="<?php echo htmlspecialchars($issueData['id']); ?>">
            
            <div id="issueRow" class="stock-row border p-1 rounded mb-1 bg-light">
                <div class="row g-2 align-items-end">
                    <div class="col-6 col-md-3 px-1 position-relative">
                        <label for="productInput" class="form-label">X-Code:</label>
                        <input type="text" name="product_lot_search" class="form-control lot-search" value="<?php echo htmlspecialchars($issueData['x_code'] . ' (' . $issueData['part_number'] . ')'); ?>" autocomplete="off" required readonly disabled>
                        <input type="hidden" name="product_lot_id" class="product-lot-id" value="<?php echo htmlspecialchars($issueData['product_lot_id']); ?>">
                    </div>
                    <div class="col-6 col-md-2 px-1">
                        <?php 
                            // Calculate the TRUE maximum available quantity for this EDIT.
                            // Max = Current Available + Original Issued Qty (since the original Qty is being "returned" to the lot)
                            $maxAllowed = $issueData['qty_available'] + $issueData['qty_issued']; 
                        ?>
                        <label for="quantityInput" class="form-label">QTY: <span class="text-muted small current-qty">(Available: <span class="qty-value" style="color: green;"><?php echo htmlspecialchars($issueData['qty_available']); ?></span>)</span></label>
                        <input 
                            type="number" 
                            name="qty_issued" 
                            class="form-control qty-input" 
                            min="1" 
                            max="<?php echo htmlspecialchars($maxAllowed); ?>" 
                            value="<?php echo htmlspecialchars($issueData['qty_issued']); ?>" 
                            required>
                    </div>
                    <div class="col-6 col-md-2 px-1 position-relative">
                        <label for="Issued-ToInput" class="form-label">Issued To:</label>
                        <input type="text" name="issued_to_search" class="form-control user-search" value="<?php echo htmlspecialchars($issueData['name'] . ' (' . $issueData['nickname'] . ')'); ?>" autocomplete="off" required readonly disabled>
                        <input type="hidden" name="issued_to_id" class="user-id" value="<?php echo htmlspecialchars($issueData['user_id']); ?>">
                    </div>
                    <div class="col-12 col-md-4 px-1">
                        <label for="commentInputOut" class="form-label">Remarks (optional)</label>
                        <textarea name="remarks" class="form-control" rows="1"><?php echo htmlspecialchars($issueData['remarks']); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row justify-content-end align-items-center w-100 px-1 mt-3">
                <button type="submit" class="btn btn-primary" title="Update">Update Issue</button>
            </div>
        </form>
    </div>
<?php 
elseif ($errorMessage): 
    // This handles errors that occurred during the initial GET request (e.g., invalid ID, issue not found).
?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($errorMessage); ?>
    </div>
<?php
endif;
?>

<script>
    // A simple form validation for the quantity (client-side pre-check)
    document.getElementById('editIssueForm')?.addEventListener('submit', function (e) {
        const qtyInput = document.querySelector('.qty-input');
        const qtyValue = parseInt(qtyInput.value, 10);
        
        // maxQty is now correctly set in PHP to (Available + Original Issued)
        const maxQty = parseInt(qtyInput.getAttribute('max'), 10);

        if (qtyValue > maxQty) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Quantity',
                // The message is updated to reflect the combined limit
                text: `The quantity entered exceeds the maximum allowable amount for this lot (Original Issued Qty + Current Available Stock). Max allowed: ${maxQty}`
            });
        }
    });
    
    // --- SweetAlert Display from URL Parameters ---
    // Get the URL and its parameters
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    
    // Check if the success message should be shown
    const successMessage = params.get('success');
    if (successMessage) {
        Swal.fire({ 
            icon: 'success', 
            title: 'Success', 
            // Decode the message for display
            text: decodeURIComponent(successMessage)
        });
        // Remove the 'success' parameter from the URL
        params.delete('success');
    }
    
    // Check if the error message should be shown
    const errorMessage = params.get('error');
    if (errorMessage) {
        // Since the controller now sends a single error message via redirect, we display it directly.
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: decodeURIComponent(errorMessage)
        });
        // Remove the 'error' parameter from the URL
        params.delete('error');
    }

    // Replace the current URL with the cleaned-up version
    // This prevents the SweetAlert from re-appearing if the user refreshes the page.
    const newUrl = `${url.pathname}?${params.toString()}`;
    history.replaceState(null, '', newUrl);
</script>
