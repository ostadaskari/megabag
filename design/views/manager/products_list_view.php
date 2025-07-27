<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        th {
            background-color: #f0f0f0;
        }
        .action-btn {
            cursor: pointer;
            margin: 0 5px;
        }
        .actions svg {
            width: 18px;
            height: 18px;
            vertical-align: middle;
        }
        .pagination {
    margin-top: 20px;
    text-align: center;
}
.pagination ul {
    list-style: none;
    padding: 0;
    display: inline-flex;
    gap: 5px;
}
.pagination li {
    display: inline;
}
.pagination a {
    text-decoration: none;
    padding: 6px 12px;
    color: #333;
    background-color: #f2f2f2;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}
.pagination a:hover {
    background-color: #ddd;
}
.pagination a[style*="font-weight:bold"] {
    background-color: #007bff;
    color: white;
    pointer-events: none;
}

    </style>
</head>
<body>

<h2>Product List</h2>
<h3><a href="../auth/dashboard.php">dashboard</a></h3>
<!-- Search and Filter -->
    <div style="margin-bottom: 15px;">
        <input type="text" id="searchInput" placeholder="Search by name, tag or P/N..." style="padding: 6px; width: 250px;">
        
        <select id="statusFilter" style="padding: 6px;">
            <option value="">All Statuses</option>
            <option value="available">Available</option>
        </select>
    </div>

        <!-- table -->
         <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>P/N</th>
                <th>MFG</th>
                <th>Qty</th>
                <th>Submitter</th>
                <th>Category</th>
                <th>Submit Date</th>
                <th>Location</th>
                <th>Status</th>
                <th>Tag</th>
                <th>Date Code</th>
                <th>Recieve Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <!-- Filled dynamically via AJAX -->
        </tbody>
    </table>
        <div id="pagination" class="pagination-container"></div>



            <!-- fetchProducts -->
<script>
let currentPage = 1;

function fetchProducts(page = 1) {
    currentPage = page;
    const keyword = document.getElementById("searchInput").value;
    const status = document.getElementById("statusFilter").value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `../ajax/search_products.php?keyword=${encodeURIComponent(keyword)}&status=${status}&page=${page}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const result = JSON.parse(xhr.responseText);
            document.getElementById("productsTableBody").innerHTML = result.html;
            document.getElementById("pagination").innerHTML = result.pagination;
        }
    };
    xhr.send();
}

// Handle typing and filter change
document.getElementById("searchInput").addEventListener("input", () => fetchProducts(1));
document.getElementById("statusFilter").addEventListener("change", () => fetchProducts(1));

// Initial load
fetchProducts();
//pagination
document.addEventListener('click', function (e) {
    if (e.target.matches('.pagination a')) {
        e.preventDefault();
        const page = parseInt(e.target.getAttribute('data-page'));
        if (!isNaN(page)) fetchProducts(page);
    }
});

</script>

            <!-- end fetchProducts -->

<script>
function deleteProduct(productId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the product.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'product_id';
            idInput.value = productId;

            form.appendChild(actionInput);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}


function editProduct(productId) {
    window.location.href = "edit_product.php?id=" + productId;
}
</script>

</body>
</html>
