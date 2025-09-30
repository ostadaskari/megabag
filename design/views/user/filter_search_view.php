<div class="container-fluid px-0">
    <div class="row">
        <aside class="col-12 col-md-3" >
            <div class="d-flex flex-row align-items-center justify-content-between titleTop">
                <h2 class="d-flex align-items-center">
                <svg width="22" height="22" fill="currentColor" class="bi bi-funnel mx-1 me-2" viewBox="0 0 16 16">
                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                </svg>    
                Filter Search</h2>
            </div>
            <div class="filters-panel" style="position: sticky;"> 
                <div class="mb-2">
                    <select id="category-select" class="form-select">
                        <option value="">-- Select Category --</option>
                        <option value="25">Capacitors</option>
                        <option value="22">Resistors</option>
                        <option value="64">ICs</option>
                    </select>
                </div>

                <div id="dynamic-filters"></div>
                <button id="search-btn" class="btn btn-primary w-100">Search</button>
            </div>
        </aside>

        <main class="col-12 col-md-9 pl-2">
            <div class="d-flex flex-row align-items-center justify-content-between titleTop">
                <h2 class="d-flex align-items-center">
                <svg width="22" height="22" fill="currentColor" class="bi bi-list-columns mx-1 me-2" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M0 .5A.5.5 0 0 1 .5 0h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 0 .5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-13 2A.5.5 0 0 1 .5 2h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-13 2A.5.5 0 0 1 .5 4h10a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-13 2A.5.5 0 0 1 .5 6h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-13 2A.5.5 0 0 1 .5 8h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-13 2a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-13 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-13 2a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
                </svg>    
                Search Results</h2>
            </div>
            <div class="results-panel">
                
                <div class="d-flex justify-content-end mb-2">
                    <button id="listViewBtn" class="btn btn-secondary ">List View</button>
                    <button id="tableViewBtn" class="btn btn-outline-secondary ml-2">Table View</button>
                </div>

                <div id="loader" class="loader" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div id="product-list" class="row"></div>
                <div id="product-table-container" class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="display: none;max-height: 65vh; overflow: auto;"></div>
            </div>
        </main>
    </div>
</div>

<!-- teeeeeeeeeeeeeeeeest -->

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
    let productsData = [];

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

    function renderProductsAsList(products) {
        let html = '';
        if (products.length === 0) {
            html = "<p class='text-center text-muted mt-3'>No products found.</p>";
        } else {
            products.forEach((p, i) => {
                const featureHtml = (p.features && p.features.length > 0) ?
                    `<label class="form-label bg-light py-2 w-98 rounded">Features:</label>
                    <ul class="list-unstyled">
                        ${p.features.map(f => `<li class="mb-2"><strong>${f.name}:</strong> ${f.value} ${f.unit || ''}</li>`).join('')}
                    </ul>` : '';

                const lotHtml = (p.lots && p.lots.length > 0) ?
                    `<label class="form-label bg-light py-2 w-98 rounded">Lots:</label>
                        <ul class="list-unstyled">
                            ${p.lots.map(l => `
                                <li>
                                    <div class="card card-body mb-2 bg-light p-2">
                                        <div class="row align-items-center">
                                            <div class="col-6 my-1"><strong>X-Code:</strong></div>
                                            <div class="col-6 my-1">${l.x_code}</div>

                                            <div class="col-6 my-1"><strong>Available Qty:</strong></div>
                                            <div class="col-6 my-1">${l.qty_available}</div>

                                            <div class="col-6 my-1"><strong>Date Code:</strong></div>
                                            <div class="col-6 my-1">${l.date_code || 'N/A'}</div>

                                            <div class="col-6 my-1"><strong>Location:</strong></div>
                                            <div class="col-6 my-1">${l.lot_location || 'N/A'}</div>

                                            <div class="col-6 my-1"><strong>Project:</strong></div>
                                            <div class="col-6 my-1">${l.project_name || 'N/A'}</div>

                                            <div class="col-6 my-1"><strong>Lock:</strong></div>
                                            <div class="col-6 my-1 d-flex align-items-center gap-2">
                                                ${l.lock 
                                                    ? `<svg  width="18" height="18" fill="#FF0000" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                                                    </svg> Locked`
                                                    : `<svg  width="18" height="18" fill="green" class="bi bi-unlock-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M12 0a4 4 0 0 1 4 4v2.5h-1V4a3 3 0 1 0-6 0v2h.5A2.5 2.5 0 0 1 12 8.5v5A2.5 2.5 0 0 1 9.5 16h-7A2.5 2.5 0 0 1 0 13.5v-5A2.5 2.5 0 0 1 2.5 6H8V4a4 4 0 0 1 4-4"/>
                                                    </svg> Unlocked`}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            `).join('')}
                        </ul>` : '';


                html += `
                <div class="col-12 mb-2">
                    <div class="product-card">
                        <div class="product-card-header d-flex align-items-center">
                            <span class="me-2">${i + 1}.</span>
                            <h5 class="mb-0 text-white">${p.part_number}</h5>
                        </div>
                        <div class="product-card-body">
                            <div class="row g-4">
                                <div class="col-md-6">

                                    <p class="mb-2"><strong>Manufacturer:</strong> ${p.mfg || "N/A"}</p>
                                    <p class="mb-2"><strong>Quantity:</strong> ${p.qty}</p>
                                    <p class="mb-2"><strong>Status:</strong> ${p.status}</p>
                                    <p> ${featureHtml}</p>
                                </div>
                                <div class="col-md-6">
                                    
                                    ${lotHtml}
                                </div>
                            </div>
                        </div>
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
    
    // --- UPDATED FUNCTION ---
    function renderProductsAsTable(products) {
        let tableHtml = '';
        if (products.length === 0) {
            tableHtml = "<p class='text-center text-muted mt-3'>No products found.</p>";
        } else {
            tableHtml = `
            <table class="table table-bordered table-striped table-hover mb-0 text-center">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Part Number</th>

                        <th scope="col">Features</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Status</th>
                        <th scope="col">Lots</th>
                    </tr>
                </thead>
                <tbody>
            `;
            
            products.forEach((p, i) => {
                const featureHtml = (p.features && p.features.length > 0) ?
                    p.features.map(f => `<strong>${f.name}:</strong> ${f.value} ${f.unit || ''}`).join('<br>') : 'N/A';
                
                // Button now targets the row's ID directly
                const lotsButtonHtml = (p.lots && p.lots.length > 0)
                ? `<button class="btn btn-sm btn-info view-lots-btn" style="width: 79px;"
                            data-target="lots-row-${p.id}"
                            aria-controls="lots-row-${p.id}"
                            aria-expanded="false">View Lots</button>`
                : 'N/A';

                tableHtml += `
                <tr id="product-row-${p.id}">
                    <th scope="row">${i + 1}</th>
                    <td>${p.part_number}</td>

                    <td>${featureHtml}</td>
                    <td>${p.qty}</td>
                    <td>${p.status}</td>
                    <td>${lotsButtonHtml}</td>
                </tr>
                `;
            if (p.lots && p.lots.length > 0) {
                const lotSubtableHtml = `
                    <div class="card card-body">
                        <h6>Lots for ${p.part_number}</h6>
                        <table class="table table-sm lot-subtable table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>X-Code</th>
                                    <th>Available Qty</th>
                                    <th>Date Code</th>
                                    <th>Location</th>
                                    <th>Project</th>
                                    <th>Lock</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${p.lots.map(l => `
                                    <tr>
                                        <td>${l.x_code}</td>
                                        <td>${l.qty_available}</td>
                                        <td>${l.date_code || 'N/A'}</td>
                                        <td>${l.lot_location || 'N/A'}</td>
                                        <td>${l.project_name || 'N/A'}</td>
                                        <td>
                                            ${l.lock 
                                                ? `<svg width="16" height="16" fill="#FF0000" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                                                </svg>`
                                                : `<svg width="16" height="16" fill="green" class="bi bi-unlock-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M12 0a4 4 0 0 1 4 4v2.5h-1V4a3 3 0 1 0-6 0v2h.5A2.5 2.5 0 0 1 12 8.5v5A2.5 2.5 0 0 1 9.5 16h-7A2.5 2.5 0 0 1 0 13.5v-5A2.5 2.5 0 0 1 2.5 6H8V4a4 4 0 0 1 4-4"/>
                                                </svg>`}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>`;

                // The row itself gets the 'collapse' class and the target ID
                tableHtml += `<tr id="lots-row-${p.id}" class="lots-row" style="display:none;"><td colspan="7">${lotSubtableHtml}</td></tr>`;
            }

            });
            tableHtml += `</tbody></table>`;
        }
        productTableContainerEl.innerHTML = tableHtml;
        productListEl.innerHTML = '';
        productTableContainerEl.style.display = 'block';
        productListEl.style.display = 'none';

        // --- UPDATED AND SIMPLIFIED CLICK HANDLER ---
        // One-time, delegated click handler for any "View Lots" button
        productTableContainerEl.addEventListener('click', (e) => {
        const btn = e.target.closest('.view-lots-btn');
        if (!btn) return;

        const targetId = btn.getAttribute('data-target');
        if (!targetId) return;

        const row = document.getElementById(targetId);
        if (!row) return;

        const isHidden = row.style.display === '' || row.style.display === 'none';
        row.style.display = isHidden ? 'table-row' : 'none';

        btn.setAttribute('aria-expanded', String(isHidden));
        btn.textContent = isHidden ? 'Hide Lots' : 'View Lots';
        });
    }

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
                        // RE-ADDED BOOLEAN FILTER UI
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
                        // Range filter uses a min-max text input (e.g., 10-20)
                        inputHtml = `<input type="text" name="${inputName}" class="form-control" placeholder="e.g. 10-20">`;
                    }
                    
                    filterBox.innerHTML = `<label class="form-label">${f.name}</label>${inputHtml}`;
                    
                    // Only append the filter box if an input element was successfully generated
                    if (inputHtml.trim() !== "") {
                         filtersEl.appendChild(filterBox);
                    }
                });
                showLoader(false);
            })
            .catch(err => {
                console.error("Error loading filters:", err);
                showLoader(false);
            });
    });

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