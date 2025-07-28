<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>stock issues </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

</head>
<body>
    <div class="container">
   <h2 class="mb-4">Withdraw (Grouped)</h2>
    <h3><a href="../auth/dashboard.php">dashboard</a> </h3>
    <form method="POST" action="" id="groupIssueForm">
        <div id="issueRows">
            <div class="stock-row border p-3 rounded mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label>Product</label>
                        <input type="text" name="products[0][product_search]" class="form-control product-search" placeholder="Search name/tag/part" required>
                        <input type="hidden" name="products[0][product_id]" class="product-id">
                    </div>
                    <div class="col-md-2">
                        <label>Quantity to Issue</label>
                        <div class="d-flex align-items-center">
                            <input type="number" name="issues[0][qty_issued]" class="form-control qty-input" min="1" required>
                            <span class="ms-2 text-muted small current-qty">(Available: <span class="qty-value">--</span>)</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label>Issued To</label>
                        <input type="text" name="products[0][issued_to_search]" class="form-control user-search" placeholder="Name, Family, Nickname" required>
                        <input type="hidden" name="products[0][issued_to_id]" class="user-id">
                    </div>
                    <div class="col-md-3">
                        <label>Remarks (optional)</label>
                        <textarea name="products[0][remarks]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-remove-row d-none">Remove</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="addRowBtn" class="btn btn-secondary">+ Add Row</button>
        <button type="submit" class="btn btn-primary mt-3">Submit Stock Issues</button>
    </form>
    </div>
 

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<!-- JavaScript for Dynamic Rows + AJAX Search -->
<script>
// DOM Ready
document.addEventListener("DOMContentLoaded", function () {

    // Live input listener for product and user search
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('product-search')) {
            const input = e.target;
            const keyword = input.value;

            if (keyword.length >= 2) {
                fetch(`../ajax/search_products_by_keyword.php?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        showDropdown(data, input, 'product-id', 'id', 'name', 'part_number', true);
                    });
            }
        }

        if (e.target.classList.contains('user-search')) {
            const input = e.target;
            const keyword = input.value;

            if (keyword.length >= 2) {
                fetch(`../ajax/search_users_by_keyword.php?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        showDropdown(data, input, 'user-id', 'id', 'name', 'nickname', false);
                    });
            }
        }
    });

    // Remove dropdown on outside click
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.autocomplete-box').forEach(box => {
            if (!box.contains(e.target)) {
                box.remove();
            }
        });
    });

});

// Show dropdown
function showDropdown(data, input, hiddenInputClass, idKey, nameKey, secondaryKey, isProduct = false) {
    // Remove existing dropdown
    const existing = input.parentNode.querySelector('.autocomplete-box');
    if (existing) existing.remove();

    const box = document.createElement('div');
    box.classList.add('autocomplete-box');
    box.style.position = 'absolute';
    box.style.zIndex = 999;
    box.style.background = '#fff';
    box.style.border = '1px solid #ccc';
    box.style.width = '100%';
    box.style.maxHeight = '200px';
    box.style.overflowY = 'auto';

    data.forEach(item => {
        const div = document.createElement('div');
        div.textContent = `${item[nameKey]} (${item[secondaryKey]})`;
        div.style.padding = '5px';
        div.style.cursor = 'pointer';

        div.addEventListener('click', () => {
            // Set input and hidden ID
            input.value = item[nameKey];
            const hiddenInput = input.closest('.stock-row').querySelector(`.${hiddenInputClass}`);
            if (hiddenInput) hiddenInput.value = item[idKey];

            // Remove dropdown
            box.remove();

            // --- Fetch product quantity ---
            if (isProduct) {
                fetch(`../ajax/get_product_qty.php?product_id=${item[idKey]}`)
                    .then(res => res.json())
                    .then(data => {
                        const qtyEl = input.closest('.stock-row').querySelector('.qty-value');
                        if (qtyEl) {
                            qtyEl.textContent = data.qty;
                        }
                    });
            }
        });

        box.appendChild(div);
    });

    input.parentNode.appendChild(box);
}
</script>
</body>
</html>