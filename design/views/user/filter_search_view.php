<div class="container-fluid">
    <div class="row g-4">
        <!-- Filters Panel -->
        <aside class="col-lg-3">
            <div class="filters-panel">
                <h4 class="mb-3">Filter Search</h4>
                <div class="mb-3">
                    <label for="category-select" class="form-label">Category</label>
                    <select id="category-select" class="form-select">
                        <option value="">-- Select Category --</option>
                        <option value="25">Capacitors</option>
                        <option value="22">Resistors</option>
                    </select>
                </div>

                <div id="dynamic-filters"></div>
                <button id="search-btn" class="btn btn-primary w-100 mt-3">Search</button>
            </div>
        </aside>

        <!-- Results Panel -->
        <main class="col-lg-9">
            <div class="results-panel">
                <h4 class="mb-3">Search Results</h4>
                <div class="d-flex justify-content-end mb-3">
                    <button id="listViewBtn" class="btn btn-secondary me-2">List View</button>
                    <button id="tableViewBtn" class="btn btn-outline-secondary">Table View</button>
                </div>

                <div id="loader" class="loader" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div id="product-list" class="row"></div>
                <div id="product-table-container" class="table-responsive" style="display: none;"></div>
            </div>
        </main>
    </div>
</div>

<script>
    const categoryEl = document.getElementById("category-select");
    const filtersEl = document.getElementById("dynamic-filters");
    const searchBtn = document.getElementById("search-btn");
    const loaderEl = document.getElementById("loader");
    const productListEl = document.getElementById("product-list");
    const productTableContainerEl = document.getElementById("product-table-container");
    const listViewBtn = document.getElementById("listViewBtn");
    const tableViewBtn = document.getElementById("tableViewBtn");

    let currentView = 'list';
    
    // Global variable to hold fetched products for re-rendering
    let productsData = [];

    // Function to show/hide loader and results
    function showLoader(isVisible) {
        loaderEl.style.display = isVisible ? 'flex' : 'none';
        if (isVisible) {
            productListEl.style.display = 'none';
            productTableContainerEl.style.display = 'none';
        } else {
            if (currentView === 'list') {
                productListEl.style.display = 'flex';
                productTableContainerEl.style.display = 'none';
            } else {
                productListEl.style.display = 'none';
                productTableContainerEl.style.display = 'block';
            }
        }
    }

    // Function to render products as a numbered list
    function renderProductsAsList(products) {
        let html = '';
        if (products.length === 0) {
            html = "<p class='text-center text-muted mt-3'>No products found.</p>";
        } else {
            products.forEach((p, i) => {
                html += `
                <div class="col-12 mb-3">
                    <div class="product-card">
                        <h5>${i + 1}.<strong>P/N:</strong> ${p.part_number}</h5>
                        <p class="mb-1"><strong>Tag:</strong> ${p.tag || "N/A"}</p>
                        <p class="mb-1"><strong>Manufacturer:</strong> ${p.mfg || "N/A"}</p>
                        <p class="mb-1"><strong>Quantity:</strong> ${p.qty}</p>
                        <p class="mb-0"><strong>Status:</strong> ${p.status}</p>
                    </div>
                </div>
                `;
            });
        }
        productListEl.innerHTML = html;
        productTableContainerEl.innerHTML = '';
        productListEl.style.display = 'flex';
        productTableContainerEl.style.display = 'none';
    }
    
    // Function to render products as a table
    function renderProductsAsTable(products) {
        let html = '';
        if (products.length === 0) {
            html = "<p class='text-center text-muted mt-3'>No products found.</p>";
        } else {
            html = `
            <table class="table table-hover table-striped">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Part Number</th>
                        <th scope="col">Tag</th>
                        <th scope="col">Manufacturer</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
            `;
            products.forEach((p, i) => {
                html += `
                <tr>
                    <th scope="row">${i + 1}</th>
                    <td>${p.part_number}</td>
                    <td>${p.tag || "N/A"}</td>
                    <td>${p.mfg || "N/A"}</td>
                    <td>${p.qty}</td>
                    <td>${p.status}</td>
                </tr>
                `;
            });
            html += `</tbody></table>`;
        }
        productTableContainerEl.innerHTML = html;
        productListEl.innerHTML = '';
        productTableContainerEl.style.display = 'block';
        productListEl.style.display = 'none';
    }

    // Function to decide which rendering function to call
    function renderCurrentProducts() {
        if (currentView === 'table') {
            renderProductsAsTable(productsData);
        } else {
            renderProductsAsList(productsData);
        }
    }

    // Handle view toggle buttons manually
    listViewBtn.addEventListener('click', () => {
        currentView = 'list';
        renderCurrentProducts();
        listViewBtn.classList.remove('btn-outline-secondary');
        listViewBtn.classList.add('btn-secondary');
        tableViewBtn.classList.remove('btn-secondary');
        tableViewBtn.classList.add('btn-outline-secondary');
    });

    tableViewBtn.addEventListener('click', () => {
        currentView = 'table';
        renderCurrentProducts();
        tableViewBtn.classList.remove('btn-outline-secondary');
        tableViewBtn.classList.add('btn-secondary');
        listViewBtn.classList.remove('btn-secondary');
        listViewBtn.classList.add('btn-outline-secondary');
    });

    // Handle category change
    categoryEl.addEventListener("change", () => {
        const catId = categoryEl.value;
        filtersEl.innerHTML = "";
        productListEl.innerHTML = "";
        productTableContainerEl.innerHTML = "";
        productsData = [];
        if (!catId) return;
        
        showLoader(true);
        fetch(`../ajax/filter_search_ajax.php?category_id=${catId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.features) return;
                data.features.forEach(f => {
                    const filterBox = document.createElement("div");
                    filterBox.className = "mb-3";

                    let inputHtml = "";
                    const inputName = `feature_${f.id}`;
                    const unitName = `feature_${f.id}_unit`;

                    if (f.data_type === "decimal(15,7)") {
                        let unitHtml = "";
                        if (f.unit) {
                            const units = f.unit.split(",").map(u => u.trim()).filter(Boolean);
                            unitHtml = `<select name="${unitName}" class="form-select w-25">
                                <option value="">Unit</option>
                                ${units.map(u => `<option value="${u}">${u}</option>`).join('')}
                            </select>`;
                        }
                        inputHtml = `<div class="d-flex gap-2">
                                        <input type="number" step="any" name="${inputName}" class="form-control" placeholder="Enter value">
                                        ${unitHtml}
                                     </div>`;
                    } else if (f.data_type === "varchar(50)" || f.data_type === "TEXT") {
                        inputHtml = `<input type="text" name="${inputName}" class="form-control" placeholder="Enter text">`;
                    } else if (f.data_type === "boolean") {
                        inputHtml = `<select name="${inputName}" class="form-select">
                                        <option value="">--Any--</option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                     </select>`;
                    } else if (f.data_type === "multiselect") {
                        try {
                            const meta = JSON.parse(f.metadata || "{}");
                            if (meta.options) {
                                inputHtml = `<select name="${inputName}" class="form-select">
                                                <option value="">--Any--</option>
                                                ${meta.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                                             </select>`;
                            }
                        } catch (e) {
                            console.error("Invalid metadata JSON", f.metadata);
                        }
                    } else if (f.data_type === "range") {
                         inputHtml = `<input type="text" name="${inputName}" class="form-control" placeholder="e.g. 10-20">`;
                    }

                    filterBox.innerHTML = `<label class="form-label">${f.name}</label>${inputHtml}`;
                    filtersEl.appendChild(filterBox);
                });
                showLoader(false);
            })
            .catch(err => {
                console.error("Error loading filters:", err);
                showLoader(false);
            });
    });

    // Handle search button click
    searchBtn.addEventListener("click", () => {
        const catId = categoryEl.value;
        if (!catId) return;

        const params = new URLSearchParams();
        params.set('category_id', catId);

        document.querySelectorAll("#dynamic-filters input, #dynamic-filters select").forEach(el => {
            if (el.value.trim() !== "") {
                params.set(el.name, el.value.trim());
            }
        });
        
        showLoader(true);
        fetch(`../ajax/filter_search_ajax.php?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                productsData = data.products || [];
                renderCurrentProducts();
                showLoader(false);
            })
            .catch(err => {
                console.error("Error loading products:", err);
                productsData = [];
                renderCurrentProducts();
                showLoader(false);
            });
    });

</script>