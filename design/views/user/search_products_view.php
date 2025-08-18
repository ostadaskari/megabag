<div class="d-flex flex-row align-items-center justify-content-between titleTop">      
    <h2 class="d-flex align-items-center">
    <svg width="24" height="24" fill="currentColor" class="bi bi-search mx-1 me-2" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
    </svg>
    Search Available Products</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<div class="input-box mb-2">
    <svg width="24" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
    </svg>
    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, part number or tag" autocomplete="off">
</div>

<div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="max-height: 65vh;">
    <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
        <thead class="table-invitionLink sticky-top" style="top:-6px; z-index: 1;">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Tag</th>
                <th>P/N</th>
                <th>MFG</th>
                <th>Availability</th> <!-- Renamed QTY to Availability and removed Status TH -->
                <th>Location</th>
                <th>Category</th>
                <th>Submitter</th>
                <th>Submit Date</th>
            </tr>
        </thead>
        <tbody id="resultsBody">
            <!-- Adjusted colspan to 10 as per the new header count -->
            <tr><td colspan="10" class="text-muted">Start typing to search...(more than 3 characters)</td></tr>
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
                        // Adjusted colspan to 10
                        resultsBody.innerHTML = `<tr><td colspan="10" class="text-muted">No products found.</td></tr>`;
                        return;
                    }

                    resultsBody.innerHTML = data.products.map((p, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${p.name}</td>
                            <td>${p.tag}</td>
                            <td>${p.part_number}</td>
                            <td>-</td> <!-- MFG -->
                            <td> <!-- This is the Availability column -->
                                ${p.qty > 0
                                    ? '<span class="badge bg-success">Available</span>'
                                    : '<span class="badge bg-danger">Unavailable</span>'}
                            </td>
                            <td>-</td> <!-- Location -->
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
            // Adjusted colspan to 10
            resultsBody.innerHTML = `<tr><td colspan="10" class="text-muted">Start typing to search...(more than 3 characters)</td></tr>`;
        }
    });
</script>