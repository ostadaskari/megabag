<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>receive stock</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

</head>
<body>
 <div class="container">

<h2 class="mb-4">Receive Stock (Grouped)</h2>
<h3><a href="../auth/dashboard.php">dashboard</a></h3>
<form method="POST" action="" id="groupStockForm">
    <div id="stockRows">
        <div class="stock-row border p-3 rounded mb-3 position-relative">
            <div class="row g-2 align-items-end">
                <div class="col-md-4 position-relative">
                    <label>Product</label>
                    <input type="text" name="products[0][product_search]" class="form-control product-search" placeholder="Search by name, tag, or part number" required>
                    <input type="hidden" name="products[0][product_id]" class="product-id">
                </div>
                <div class="col-md-2">
                    <label>Quantity</label>
                    <input type="number" name="products[0][qty_received]" class="form-control" min="1" required>
                </div>
                <div class="col-md-4">
                    <label>Notes (optional)</label>
                    <textarea name="products[0][remarks]" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-row d-none">Remove</button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" id="addRowBtn" class="btn btn-secondary">+ Add Row</button>
    <button type="submit" class="btn btn-primary mt-3">Submit Stock Receipts</button>
</form>

<!-- SweetAlert -->


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
.autocomplete-box {
    position: absolute;
    z-index: 9999;
    background: #fff;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
}
.autocomplete-box div:hover {
    background: #f0f0f0;
}
</style>

<script>
let rowIndex = 1;

document.getElementById('addRowBtn').addEventListener('click', () => {
    const lastRow = document.querySelector('.stock-row:last-of-type');
    const newRow = lastRow.cloneNode(true);

    newRow.querySelectorAll('input, textarea').forEach(el => {
        el.value = '';
    });

    newRow.querySelectorAll('input, textarea').forEach(el => {
        const name = el.getAttribute('name');
        if (name) {
            el.setAttribute('name', name.replace(/\[\d+\]/, `[${rowIndex}]`));
        }
    });

    newRow.querySelector('.btn-remove-row').classList.remove('d-none');
    document.getElementById('stockRows').appendChild(newRow);
    rowIndex++;
});

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('btn-remove-row')) {
        const rows = document.querySelectorAll('.stock-row');
        if (rows.length > 1) {
            e.target.closest('.stock-row').remove();
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

        if (!resultBox) {
            resultBox = document.createElement('div');
            resultBox.classList.add('autocomplete-box');
            wrapper.appendChild(resultBox);
        }

        if (keyword.length >= 2) {
            fetch(`../ajax/search_products_by_keyword.php?keyword=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    resultBox.innerHTML = '';
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
                            });
                            resultBox.appendChild(div);
                        });
                    }
                });
        } else {
            resultBox.innerHTML = '';
        }
    }
});
</script>


 </div>
</body>
</html>



