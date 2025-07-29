<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="p-4 container">
    <h3>Search Available Products</h3>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by name, part number or tag">

    <div id="resultsContainer">
        <p class="text-muted">Start typing to search...(more than 3 characters)</p>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('resultsContainer');

        function searchProducts(query) {
            fetch(`user_search_products.php?ajax=1&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.products.length === 0) {
                            resultsContainer.innerHTML = `<p class="text-muted">No products found.</p>`;
                            return;
                        }

                        const rows = data.products.map(p => `
                            <tr>
                                <td>${p.name}</td>
                                <td>${p.part_number}</td>
                                <td>${p.tag}</td>
                                <td><span class="text-success">✅ Available</span></td>
                                <td>${p.category_name || '-'}</td>
                            </tr>
                        `).join('');

                        resultsContainer.innerHTML = `
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Part Number</th>
                                        <th>Tag</th>
                                        <th>Availability</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        `;
                    }
                });
        }

        searchInput.addEventListener('input', () => {
            const q = searchInput.value.trim();
            if (q.length >= 3) {
                searchProducts(q);
            } else {
                resultsContainer.innerHTML = `<p class="text-muted">Start typing to search...</p>`;
            }
        });
    </script>
</body>
</html>