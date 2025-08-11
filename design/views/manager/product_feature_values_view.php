<div class="container">
    <h2>Assign Feature Values to Product</h2>

    <div class="form-group">
        <label for="productSearch">Search Product:</label>
        <input type="text" id="productSearch" placeholder="Type to search...">
        <div id="productResults"></div>
    </div>

    <form method="POST" action="" id="featureForm">
        <input type="hidden" name="product_id" id="product_id">
        <div id="featuresContainer"></div>
        <button type="submit">Save Features</button>
    </form>
</div>

<script>
const productSearch = document.getElementById('productSearch');
const productResults = document.getElementById('productResults');
const featuresContainer = document.getElementById('featuresContainer');
const featureForm = document.getElementById('featureForm');
const productIdInput = document.getElementById('product_id');

productSearch.addEventListener('input', () => {
    const q = productSearch.value.trim();
    if (q.length < 2) {
        productResults.style.display = 'none';
        productResults.innerHTML = '';
        return;
    }
    fetch(`../manager/product_feature_values.php?search_product=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
            productResults.innerHTML = '';
            if (data.length > 0) {
                data.forEach(p => {
                    const div = document.createElement('div');
                    div.textContent = `${p.name} (ID: ${p.id})`;
                    div.style.cursor = 'pointer';
                    div.onclick = () => selectProduct(p.id, p.name);
                    productResults.appendChild(div);
                });
                productResults.style.display = 'block';
            } else {
                productResults.innerHTML = '<div>No products found.</div>';
                productResults.style.display = 'block';
            }
        });
});

function selectProduct(id, name) {
    productIdInput.value = id;
    productSearch.value = name;
    productResults.style.display = 'none';
    
    // Hide the form and show a loading spinner or message
    featureForm.style.display = 'none';
    featuresContainer.innerHTML = '<div>Loading features...</div>';

    fetch(`../manager/product_feature_values.php?product_id=${id}`)
        .then(res => res.json())
        .then(features => {
            featuresContainer.innerHTML = '';
            if (features.status === 'error') {
                Swal.fire('Error', features.message, 'error');
                return;
            }
            if (features.length === 0) {
                Swal.fire('No Features', 'This product category has no features assigned.', 'info');
                return;
            }
            
            features.forEach(f => {
                const formGroup = document.createElement('div');
                formGroup.classList.add('form-group');

                let inputHtml = '';
                // Render different input types based on data_type
                switch (f.data_type) {
                    case 'decimal(12,3)':
                        inputHtml = `<input type="number" name="features[${f.id}][value]" value="${f.value}" placeholder="Enter a number">`;
                        break;
                    case 'boolean':
                        // Checkbox requires special handling for its value
                        const isChecked = f.value === '1';
                        inputHtml = `
                            <label class="checkbox-label">
                                <input type="checkbox" name="features[${f.id}][value]" value="1" ${isChecked ? 'checked' : ''}>
                                ${f.name} ${f.is_required ? '*' : ''}
                            </label>
                            <!-- Hidden input to ensure a value of '0' is sent if unchecked -->
                            <input type="hidden" name="features[${f.id}][value]" value="${isChecked ? '1' : '0'}">
                        `;
                        break;
                    case 'varchar(50)':
                        inputHtml = `<input type="text" name="features[${f.id}][value]" value="${f.value}" placeholder="Enter text (max 50 characters)">`;
                        break;
                    case 'TEXT':
                        inputHtml = `<textarea name="features[${f.id}][value]" placeholder="Enter a long description">${f.value}</textarea>`;
                        break;
                    default:
                        // Fallback for any other data types not explicitly handled
                        inputHtml = `<input type="text" name="features[${f.id}][value]" value="${f.value}" placeholder="Enter text">`;
                        break;
                }

                formGroup.innerHTML = `
                    ${f.data_type !== 'boolean' ? `<label>${f.name} ${f.is_required ? '*' : ''}</label>` : ''}
                    <div style="display:flex; align-items:center;">
                        ${inputHtml}
                        ${f.unit ? unitSelect(f.unit, f.unit_value, f.id) : ''}
                    </div>
                `;
                featuresContainer.appendChild(formGroup);
            });
            featureForm.style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching features:', error);
            Swal.fire('Error', 'Failed to load features. Please try again.', 'error');
        });
}

function unitSelect(unitString, selectedUnit, featureId) {
    const units = unitString.split(',').map(u => u.trim());
    let html = `<select name="features[${featureId}][unit]" style="margin-left: 10px;">`;
    units.forEach(u => {
        const isSelected = u === selectedUnit ? 'selected' : '';
        html += `<option value="${u}" ${isSelected}>${u}</option>`;
    });
    html += '</select>';
    return html;
}

featureForm.addEventListener('submit', e => {
    e.preventDefault();
    
    // Client-side validation for required fields
    const requiredInputs = document.querySelectorAll('#featuresContainer [required]');
    let hasEmptyRequired = false;
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            hasEmptyRequired = true;
            input.style.border = '1px solid red';
        } else {
            input.style.border = '1px solid #ccc';
        }
    });

    if (hasEmptyRequired) {
        Swal.fire('Validation Error', 'Please fill in all required fields.', 'warning');
        return;
    }

    fetch('../manager/product_feature_values.php', {
        method: 'POST',
        body: new FormData(featureForm)
    }).then(res => res.text())
        .then(html => {
            // The server sends back a script tag with a SweetAlert call
            document.body.insertAdjacentHTML('beforeend', html);
        })
        .catch(error => {
            console.error('Error saving features:', error);
            Swal.fire('Error', 'Failed to save features. Please try again.', 'error');
        });
});

</script>
