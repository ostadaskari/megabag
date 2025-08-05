<h3>Search Available Products</h3>
<div class="input-box mb-3">
    <svg width="24" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
    </svg>
    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, part number or tag">
</div>

<div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-3" style="max-height: 65vh;">
    <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
        <thead class="table-invitionLink sticky-top" style="top:-3px; z-index: 1;">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Tag</th>
                <th>P/N</th>
                <th>MFG</th>
                <th>QTY</th>
                <th>Location</th>
                <th>Status</th>
                <th>Category</th>
                <th>Submitter</th>
                <th>Submit Date</th>
            </tr>
        </thead>
        <tbody id="resultsBody">
            <tr><td colspan="11" class="text-muted">Start typing to search...(more than 3 characters)</td></tr>
        </tbody>
    </table>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const resultsBody = document.getElementById('resultsBody');

    function searchProducts(query) {
        fetch(`../user/user_search_products.php?ajax=1&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.products.length === 0) {
                        resultsBody.innerHTML = `<tr><td colspan="11" class="text-muted">No products found.</td></tr>`;
                        return;
                    }

                    resultsBody.innerHTML = data.products.map((p, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${p.name}</td>
                            <td>${p.tag}</td>
                            <td>${p.part_number}</td>
                            <td>-</td> <!-- MFG -->
                            <td>${p.qty}</td>
                            <td>-</td> <!-- Location -->
                            <td>
                                ${p.qty > 0
                                    ? '<span class="badge bg-success">Active</span>'
                                    : '<span class="badge bg-danger">Unavailable</span>'}
                            </td>
                            <td>${p.category_name || '-'}</td>
                            <td>-</td> <!-- Submitter -->
                            <td>-</td> <!-- Submit Date -->
                        </tr>
                    `).join('');
                }
            });
    }

    searchInput.addEventListener('input', () => {
        const q = searchInput.value.trim();
        if (q.length >= 3) {
            searchProducts(q);
        } else {
            resultsBody.innerHTML = `<tr><td colspan="11" class="text-muted">Start typing to search...(more than 3 characters)</td></tr>`;
        }
    });
</script>
