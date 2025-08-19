
<!-- Title Section -->
<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
        <svg width="26" height="26" fill="currentColor" class="bi bi-ui-checks-grid mx-1" viewBox="0 0 16 16">
            <path d="M2 10h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1m9-9h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1m0 9a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zm0-10a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM2 9a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2zm7 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2zM0 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5.354.854a.5.5 0 1 0-.708-.708L3 3.793l-.646-.647a.5.5 0 1 0-.708.708l1 1a.5.5 0 0 0 .708 0z"/>
        </svg>
        Product Feature
    </h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
        <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
        </svg>
        <span>Back</span>
    </a>
</div>

<!-- Main Container -->
<div class="container px-0">
    <div class="row d-flex flex-column justify-content-center" style="width: 100%;">
        <!-- Search Product --> 
        <label for="productSearch" class="form-label" style="width:100%;text-align: center;">Search in Product:</label>
        <div class="col-12 col-md-6" style="margin: auto;">
            <div class="input-box" style="width: 100%; margin:0 0 5px 0;">
                <div class="svgSearch">
                    <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </div>
                <input type="text" id="productSearch" name="searchProduct" placeholder="Type to search by name P/N or Tag...">
                <div id="productResults"></div>
            </div>
        </div>
    </div>
    

    <!-- Dynamic Features Form -->
    <form method="POST" action="" id="featureForm" style="display:none;">
        <input type="hidden" name="product_id" id="product_id">

        <!-- Features Container (dynamic rows will be injected here) -->
        <div class="container border shadow-sm rounded bg-light p-2" style="width:100%;height: 62vh;overflow-y: auto;">
            <div class="row" id="featuresContainer">
               <!-- JS will insert feature fields here -->
            </div> 
        </div>

        <!-- Submit Button -->
        <div class="d-flex flex-row justify-content-end align-items-center" style="margin:10px auto;">
            <button type="submit" class="btn btn-primary" title="Submit">Submit</button>
        </div>
    </form>
</div>



<script>
// This script handles the dynamic loading and saving of product feature values.
// It includes an AJAX search for products, a dynamic form generator for features,
// and client-side form submission.

const productSearch = document.getElementById('productSearch');
const productResults = document.getElementById('productResults');
const featuresContainer = document.getElementById('featuresContainer');
const featureForm = document.getElementById('featureForm');
const productIdInput = document.getElementById('product_id');

// Event listener for the product search input.
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
                    div.textContent = `${p.name} (PN: ${p.part_number})`;
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

// Function to handle the selection of a product from the search results.
function selectProduct(id, name) {
    productIdInput.value = id;
    productSearch.value = name;
    productResults.style.display = 'none';

    // Hide the form and show a loading spinner or message
    featureForm.style.display = 'none';
    featuresContainer.innerHTML = '<div class="text-center text-muted">Loading features...</div>';

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
                formGroup.classList.add(
                    'col-6',
                    'd-flex',
                    'align-items-end',
                    'p-2'
                    );


                let inputHtml = '';
                let inputColClass = 'col px-1 d-flex flex-row'; // Default column class
                let unitColClass = 'col-auto px-1 d-flex flex-row'; // Default column class for unit
                const requiredAttr = f.is_required ? 'required' : '';
                
                // Render different input types based on data_type
                switch (f.data_type) {
                    case 'decimal(12,3)':
                        // Added required attribute and min value for better user experience
                        inputHtml = `<input type="text" class="form-control" name="features[${f.id}][value]" value="${f.value}" placeholder="e.g. 12.345" pattern="[0-9]+(\\.[0-9]{1,3})?" title="Please enter a number with up to 3 decimal places (e.g., 123.456)." ${requiredAttr}>`;
                        // Using a reasonable default of 4 columns, since not specified
                        inputColClass = 'col-6 col-md-5 px-1 d-flex flex-row';
                        unitColClass = 'col-6 col-md-3 px-1 d-flex flex-row';
                        break;
                    case 'boolean':
                        const isChecked = f.value === '1';
                        // Using a Bootstrap switch for a cleaner look
                        inputHtml = `
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="feature-${f.id}" name="features[${f.id}][value]" value="1" ${isChecked ? 'checked' : ''}>
                                <label class="form-check-label" for="feature-${f.id}">${f.name} ${f.is_required ? '*' : ''}</label>
                            </div>
                            <input type="hidden" name="features[${f.id}][value]" value="${isChecked ? '1' : '0'}">
                        `;
                        // Boolean is a special case, so it takes the full row
                        inputColClass = 'col-6 col-md-5 px-1 d-flex flex-row';

                        break;
                    case 'varchar(50)':
                        inputHtml = `<input type="text" class="form-control" name="features[${f.id}][value]" value="${f.value}" placeholder="Enter text (max 50 characters)" ${requiredAttr}>`;
                        inputColClass = 'col-6 col-md-5 px-1 d-flex flex-row';
                        unitColClass = 'col-6 col-md-3 px-1 d-flex flex-row';
                        break;
                    case 'TEXT':
                        inputHtml = `<textarea class="form-control" name="features[${f.id}][value]" placeholder="Enter a long description" ${requiredAttr}>${f.value}</textarea>`;
                        inputColClass = 'col-6 col-md-5 px-1 d-flex flex-row';
                        unitColClass = 'col-6 col-md-3 px-1 d-flex flex-row';
                        break;
                    default:
                        // Fallback for any other data types not explicitly handled
                        inputHtml = `<input type="text" class="form-control" name="features[${f.id}][value]" value="${f.value}" placeholder="Enter text" ${requiredAttr}>`;
                        inputColClass = 'col-6 col-md-5 px-1 d-flex flex-row';
                        unitColClass = 'col-6 col-md-3 px-1 d-flex flex-row';
                        break;
                }
                
                const unitHtml = f.unit ? unitSelect(f.unit, f.unit_value, f.id) : '';

                // Use the new row and col structure
                formGroup.innerHTML = `
                    ${f.data_type !== 'boolean' ? `<label class="form-label" style="width: 100px;max-width: 120px;">${f.name}: ${f.is_required ? '*' : ''}</label>` : ''}
                    <div class="${inputColClass}">${inputHtml}</div>
                    ${f.unit ? `<div class="ml-2 ${unitColClass}">${unitHtml}</div>` : ''}
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
    const optionsHtml = units.map(u => {
        const isSelected = u === selectedUnit ? 'selected' : '';
        return `<option value="${u}" ${isSelected}>${u}</option>`;
    }).join('');
    
    return `<select class="form-select" name="features[${featureId}][unit]">${optionsHtml}</select>`;
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
            input.style.border = '1px solid #dee2e6';
        }
    });

    if (hasEmptyRequired) {
        Swal.fire('Validation Error', 'Please fill in all required fields.', 'warning');
        return;
    }

    // Client-side validation for the decimal field, now allowing empty values
    const decimalInputs = document.querySelectorAll('input[name*="[value]"][pattern]');
    let hasInvalidDecimal = false;
    decimalInputs.forEach(input => {
        const value = input.value.trim();
        // Only validate if the value is not empty
        if (value !== '') {
            const pattern = new RegExp(input.pattern);
            if (!pattern.test(value)) {
                hasInvalidDecimal = true;
                input.style.border = '1px solid red';
            } else {
                input.style.border = '1px solid #dee2e6';
            }
        } else {
            // If the value is empty, clear the error styling
            input.style.border = '1px solid #dee2e6';
        }
    });

    if (hasInvalidDecimal) {
        Swal.fire('Validation Error', 'Please correct the format of the decimal fields.', 'warning');
        return;
    }

    fetch('../manager/product_feature_values.php', {
        method: 'POST',
        body: new FormData(featureForm)
    })
        .then(res => res.json()) // Expect JSON response
        .then(response => {
            // Manually trigger SweetAlert based on the JSON response
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'dashboard.php?page=product_feature_values';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        })
        .catch(error => {
            console.error('Error saving features:', error);
            Swal.fire('Error', 'Failed to save features. Please try again.', 'error');
        });
});

</script>
