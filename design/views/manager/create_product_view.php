<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    
    <h2 class="d-flex align-items-center">
    <svg width="24" height="24" fill="currentColor" fill="currentColor" class="bi bi-patch-plus-fill mx-1 me-2" viewBox="0 0 16 16">
        <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zM8.5 6v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 1 0"/>
    </svg> 
     Defien a Part</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>

<!-- add single part -->
    <div id="Add-Single-part" class="tab-content mb-2 pb-3">
        <form method="post" enctype="multipart/form-data" class="d-flex flex-column partForm">
            <!-- hidden for updating after existed partnumber -->
        <input type="hidden" id="product_id" name="product_id" value="">


            <!-- Category Dropdown Section -->
            <div class="container bg-light border rounded shadow-sm p-2 mb-2" style="z-index:1000;">
              <div class="row">
                <!-- Category Dropdown Section -->
                    <div class="col-12 d-flex flex-row">
                        <div class="d-flex flex-row align-items-center">
                            <svg width="20" height="20" fill="currentColor" class="bi bi-substack" viewBox="0 0 16 16">
                            <path d="M15 3.604H1v1.891h14v-1.89ZM1 7.208V16l7-3.926L15 16V7.208zM15 0H1v1.89h14z"/>
                            </svg>
                            <h3 class="pl-1">Category :</h3>
                          </div>
                        <div style="width:40%;">
                            <input type="text" id="category_search" placeholder="Search categories..." autocomplete="off" class="form-select" >
                            <input type="hidden" name="category_id" id="category_id">
                            <ul class="category-suggestions" id="category-dropdown" style="width:40%;"></ul>
                        </div> 
                    </div>
                </div>
            </div>

            <!-- part number and Manufacturer inputs -->
            <div class="container bg-light border rounded shadow-sm p-2 mb-2">
                <div class="d-flex flex-row align-items-center mb-2">
                <svg width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
                <h3 class="pl-1">Part Details :</h3>
                <div id="editLinkContainer" class="ml-auto" style="display: none;">
                    <a id="editLink" href="#" class="btn btn-warning btn-sm">Edit Existing Part</a>
                </div>
                </div>
            
                <div class="row">
                    <div class="col-12 col-md-4 px-2 my-2">

                    <label class="form-label" for="partNumber" title="Part Number">P/N:</label>
                        <input class="form-control" type="text" name="pn" id="pn" placeholder="Part number" autocomplete="off" required />
                                        <span id="partNumberExistsMessage" class="error-message text-danger" style="display: none;">This part number already exists.</span>

                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                        <label class="form-label" for="manufacturer" title="Manufacturer">MFG:</label>
                        <input class="form-control" type="text" name="mfg" id="mfg" placeholder="Manufacturer" autocomplete="off"  />

                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                        <label class="form-label" for="tag name" title="Tag Name">Tag:</label>
                        <input class="form-control" type="text" name="tag" id="tag" placeholder="Tag Name" autocomplete="off" required />
                    </div>
                </div>

                <div class="row d-flex justify-content-between">


                    <!-- <div class="col-12 col-md-2 px-2 my-2">
                        <label class="form-label" for="Quantity" title="Quantity">QTY:</label>
                        <input class="form-control" type="number" name="qty" id="qty" placeholder="Quantity" autocomplete="off" min="0"  />
                    </div> -->

                    <div class="col-12 col-md-2 px-2 my-2">
                        <label for="location" class="form-label" title="location in Inventory">Location:</label>
                        <input class="form-control" type="text" name="location" id="location" placeholder="Enter Location" autocomplete="off"  />
                    </div>

                    <!-- <div class="col-12 col-md-2 px-2 my-2">
                        <label for="date-code" class="form-label">Date Code:</label>
                        <select id="date-code" name="date_code" class="form-select"> -->
                            <!-- Years will be populated here by JavaScript -->
                        <!-- </select>
                    </div> -->

                    <div class="col-12 col-md-2 px-2 my-2">
                        <label class="form-label" for="statusSelector">
                            Status:
                        </label>
                        <select id="" name="statusSelector" class="form-select">
                            <option value="Available">Available</option>
                            <option value="UnAvailable">UnAvailable</option>
                        </select>
                    </div>
                    
                    <div class="col-12 px-2">
                        <label class="form-label" for="description" title="Company Comment">Company Comment:</label>
                        <textarea class="form-control" id="company_cmt" class="mt-2" name="company_cmt" rows="3"></textarea>
                    </div>
                </div>

                <!-- inputs features -->
                <div class="container px-0 mt-3">
                    <div class="d-flex flex-row align-items-center mb-2">
                        <svg width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg> 
                        <h3 class="pl-1 mb-0">Specifications :</h3>
                        <div class="flex-grow-1 ms-2 border-bottom"></div>
                    </div>

                    <div class="row" id="featuresContainer">
                    </div>
                </div>
            </div>

            <!-- Attach Files -->
            <div class="container bg-light border rounded shadow-sm p-2 mb-2">
                <div class="d-flex flex-row align-items-center my-2">
                    <svg width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    <h3 class="pl-1">Attach Files:</h3>
                </div>

                <div class="row mt-1 mb-3 d-flex justify-content-between border rounded p-3">
                    <div class="col-12 col-md-6 d-flex justify-content-start">
                        <div style="width: 90%;">
                            <div class="form-label d-flex align-items-center">
                                <svg width="20" height="20" fill="#1087cf" class="bi bi-card-image" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm6.002 5.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0m.713 2.684-2.5-3.5a.5.5 0 0 0-.69-.03L1.002 12A1 1 0 0 0 2 13h12a1 1 0 0 0 .899-1.424l-3.5-5a.5.5 0 0 0-.899-.076L8.002 9.5z"/>
                                </svg>
                                <label for="coverImageUpload" class="mx-1">Upload Cover Image:</label>
                            </div>
                            <input class="form-control" type="file" id="coverImageUpload" name="cover_image" accept="image/*" >
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 d-flex justify-content-end">
                        <div style="width: 90%;">
                            <div class="form-label d-flex align-items-center">
                                <svg width="20" height="20" fill="green" class="bi bi-file-earmark-image" viewBox="0 0 16 16">
                                    <path d="M6.502 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                                    <path d="M14 14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zM4 1a1 1 0 0 0-1 1v10l2.224-2.224a.5.5 0 0 1 .61-.075L8 11l2.157-3.02a.5.5 0 0 1 .76-.063L13 10V4.5h-2A1.5 1.5 0 0 1 9.5 3V1z"/>
                                </svg>
                                <label for="imageUpload" class="mx-1">Upload Images:</label>
                            </div>
                            <input class="form-control" type="file" id="imageUpload" name="images[]" multiple accept="image/*" >
                        </div>
                    </div>
                </div>

                <div class="row mt-1 mb-3 d-flex justify-content-between">
                    <div class="col-12 col-md-6 d-flex">
                        <div class=" border rounded p-3" style="width: 90%;">
                            <div class="form-label d-flex align-items-center">
                                <svg width="20" height="20" fill="#cf2c10" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                                </svg>
                                <label for="datasheetUpload" class="mx-1">Upload Datasheet:</label>
                            </div>
                            <input class="form-control" type="file" id="datasheetUpload" name="pdfs[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align: end">
                <button type="submit" class="btn btn-primary" id="Addpart">Add part</button>
            </div>
        </form>
    </div>

<script>
let categories = [];
const featuresContainer = document.getElementById('featuresContainer');
const categoryIdInput = document.getElementById('category_id');

async function fetchCategories() {
    const response = await fetch('../ajax/fetch_leaf_categories.php');
    categories = await response.json();
    renderDropdown('');
}

function renderDropdown(searchText) {
    const dropdown = document.getElementById('category-dropdown');
    dropdown.innerHTML = '';

    const filtered = categories.filter(cat =>
        cat.name.toLowerCase().includes(searchText.toLowerCase())
    );

    // Hide the dropdown if no search text or no results
    if (searchText === '' || filtered.length === 0) {
        dropdown.style.display = 'none'; // Hide the box
        if (filtered.length === 0 && searchText !== '') {
            dropdown.innerHTML = '<li>No results found</li>';
            dropdown.style.display = 'block'; // Show "No results" message
        }
        return;
    }

    dropdown.style.display = 'block'; // Show the box when there are results

    filtered.forEach(cat => {
        const li = document.createElement('li');
        li.textContent = cat.name;
        li.classList.add('category-suggestion-item');
        
        li.onclick = () => {
            document.getElementById('category_search').value = cat.name;
            categoryIdInput.value = cat.id;
            dropdown.innerHTML = '';
            dropdown.style.display = 'none'; // Hide the box after selection
            
            // Call the function to load features for the selected category
            loadFeatures(cat.id);
        };
        dropdown.appendChild(li);
    });

}

/**
 * Fetches and displays dynamic features (specifications) for a given category.
 * @param {number} categoryId The ID of the selected category.
 */
async function loadFeatures(categoryId) {
    // Check if the container element exists before trying to manipulate it
    if (!featuresContainer) {
        console.error('Features container element not found.');
        return;
    }

    // Clear previous features
    featuresContainer.innerHTML = '';

    if (!categoryId) {
        return; // Exit if no category is selected
    }

    try {
        const response = await fetch(`../ajax/fetch_features.php?category_id=${categoryId}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const features = await response.json();

        if (features.length === 0) {
            featuresContainer.innerHTML = '<div class="col-12"><p class="text-muted">No specifications found for this category or its parents.</p></div>';
        } else {
            features.forEach(feature => {
                const featureElement = document.createElement('div');
                featureElement.classList.add('col-12', 'col-md-4', 'px-2', 'my-2');

                let inputHtml = '';
                
                // Parse the metadata JSON if it exists
                const metadata = feature.metadata ? JSON.parse(feature.metadata) : {};

                // Logic to build the correct input HTML based on feature data type
                switch (feature.data_type) {
                    case 'range':
                        // Handle 'range' type with min/max inputs and optional units from metadata
                        const min = metadata.min || '';
                        const max = metadata.max || '';
                        const units = metadata.units || []; // Correctly reads units from the metadata JSON
                        const defaultUnit = metadata.defaultUnit || '';
                        const unitOptions = units.map(unit => 
                            `<option value="${unit}" ${unit === defaultUnit ? 'selected' : ''}>${unit}</option>`
                        ).join('');

                        inputHtml = `
                            <label class="form-label" for="feature_${feature.id}" title="${feature.name}">${feature.name}:</label>
                            <div class="row gx-1">
                                <div class="col-4">
                                    <input class="form-control" type="number" step="any" name="feature[${feature.id}][min]" placeholder="from ${min}" autocomplete="off" />
                                </div>
                                <div class="col-4">
                                    <input class="form-control" type="number" step="any" name="feature[${feature.id}][max]" placeholder="to ${max}" autocomplete="off" />
                                </div>
                                ${units.length > 0 ? `
                                <div class="col-3">
                                    <select class="form-select" name="feature_unit[${feature.id}]">
                                        ${unitOptions}
                                    </select>
                                </div>
                                ` : ''}
                            </div>
                        `;
                        break;
                        
                    case 'multiselect':
                        const options = metadata.options || [];
                        inputHtml = `
                            <label class="form-label" for="feature_${feature.id}" title="${feature.name}">${feature.name}:</label>
                            <select class="form-select" id="feature_${feature.id}" name="feature[${feature.id}][]" >
                                ${options.map(opt => `
                                    <option value="${opt}">${opt}</option>
                                `).join('')}
                            </select>
                        `;
                        break;

                    case 'boolean':
                        // Corrected logic: The label and input are now combined into a single HTML string
                        // to ensure the label is always rendered correctly.
                        inputHtml = `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="feature_${feature.id}" name="feature[${feature.id}]" value="1">
                                <label class="form-check-label" for="feature_${feature.id}">${feature.name}</label>
                            </div>
                        `;
                        break;

                    case 'TEXT':
                        inputHtml = `
                            <label class="form-label" for="feature_${feature.id}" title="${feature.name}">${feature.name}:</label>
                            <textarea class="form-control py-1" name="feature[${feature.id}]" placeholder="${feature.name}" rows="1"></textarea>
                        `;
                        break;

                    case 'varchar(50)':
                    case 'decimal(15,7)':
                    default:
                        let inputType = 'text';
                        if (feature.data_type === 'decimal(15,7)') {
                            inputType = 'number';
                        }
                        
                        // Fallback case that uses the 'unit' field directly.
                        if (feature.unit && feature.unit.trim() !== '') {
                            const unitOptions = feature.unit.split(',').map(unit => unit.trim()).map(unit => `<option value="${unit}">${unit}</option>`).join('');
                            inputHtml = `
                                <label class="form-label" for="feature_${feature.id}" title="${feature.name}">${feature.name}:</label>
                                <div class="input-group">
                                    <input class="form-control" type="${inputType}" step="any" name="feature[${feature.id}]" placeholder="${feature.name}" autocomplete="off" />
                                    <select class="form-select" name="feature_unit[${feature.id}]">
                                        ${unitOptions}
                                    </select>
                                </div>
                            `;
                        } else {
                            inputHtml = `
                                <label class="form-label" for="feature_${feature.id}" title="${feature.name}">${feature.name}:</label>
                                <input class="form-control" type="${inputType}" name="feature[${feature.id}]" placeholder="${feature.name}" autocomplete="off" step="any" />
                            `;
                        }
                        break;
                }

                // Append the complete element to the container. The HTML is now pre-built in the switch case.
                featureElement.innerHTML = inputHtml;
                featuresContainer.appendChild(featureElement);
            });
        }
    } catch (error) {
        console.error('Error fetching features:', error);
        featuresContainer.innerHTML = '<div class="col-12"><p class="text-danger">Failed to load specifications.</p></div>';
    }
}


// Reload suggestions when user types
document.getElementById('category_search').addEventListener('input', function () {
    const searchText = this.value;
    renderDropdown(searchText);
});

// Hide the dropdown when the input field loses focus
document.getElementById('category_search').addEventListener('blur', function () {
    setTimeout(() => {
        document.getElementById('category-dropdown').style.display = 'none';
    }, 200); // A small delay to allow for click event to register
});

// Add an event listener to the hidden input to trigger feature loading
categoryIdInput.addEventListener('change', (event) => {
    loadFeatures(event.target.value);
});

// Load categories on page load
fetchCategories();

// Wait for the DOM to be fully loaded before running the script
document.addEventListener('DOMContentLoaded', function() {
    // Get a reference to the select element
    const dateCodeSelect = document.getElementById('date-code');

    // Define the starting year
    const startYear = 2017;

    // Get the current year
    const currentYear = new Date().getFullYear();

    // Loop from the current year down to the start year
    for (let year = currentYear; year >= startYear; year--) {
        // Create a new option element for each year
        const option = document.createElement('option');

        // Set the value and display text of the option
        option.value = year;
        option.textContent = year;

        // Append the option to the select element
        dateCodeSelect.appendChild(option);
    }

    // Optional: You can set the currently selected year to the current year
    dateCodeSelect.value = currentYear;
});
</script>







<?php if (!empty($success)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: <?= json_encode($success) ?>,
                confirmButtonColor: '#3085d6'
            });
        });
    </script>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: <?= json_encode('<ul><li>' . implode('</li><li>', array_map('htmlspecialchars', $errors)) . '</li></ul>') ?>,
                confirmButtonColor: '#d33'
            });
        });
    </script>
<?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pnInput = document.getElementById('pn');
            const form = document.querySelector('form');
            const productIdInput = document.getElementById('product_id');
            const submitBtn = document.getElementById('Addpart');
            const editLinkContainer = document.getElementById('editLinkContainer');
            const editLink = document.getElementById('editLink');
            const categorySearchInput = document.getElementById('category_search');
            const mfgInput = document.getElementById('mfg');
            const tagNameInput = document.getElementById('tag');
            const nameInput = document.getElementById('name');
            const qtyInput = document.getElementById('qty');
            const locationInput = document.getElementById('location');
            const receivedCodeInput = document.getElementById('recieve_code');
            const dateCodeSelect = document.getElementById('date-code');
            const rfCheckbox = document.getElementById('rfCheckbox');
            const companyCmtTextarea = document.getElementById('company_cmt');
            const featuresContainer = document.getElementById('featuresContainer');
            const existsMessage = document.getElementById('partNumberExistsMessage');


            let timeoutId;

            // Reset the button and message when the user starts typing or pastes
            pnInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                submitBtn.disabled = false;
                existsMessage.style.display = 'none';
                const pn = pnInput.value.trim();
                if (pn.length === 0) {
                    editLinkContainer.style.display = 'none';
                    return;
                }
                timeoutId = setTimeout(() => {
                    fetchProductData(pn);
                }, 500);
            });
            
            pnInput.addEventListener('paste', function() {
                setTimeout(() => {
                    const pn = pnInput.value.trim();
                    if (pn.length > 0) {
                        fetchProductData(pn);
                    }
                }, 10);
            });

            async function fetchProductData(pn) {
                try {
                    const response = await fetch(`../ajax/get_product_data.php?pn=${encodeURIComponent(pn)}`);
                    const result = await response.json();

                    if (result.status === 'success') {
                        const data = result.data;
                        const editUrl = `../auth/dashboard.php?page=edit_product&id=${data.id}`;
                        editLink.href = editUrl;
                        editLinkContainer.style.display = 'block';
                        
                        // Disable the button and show the alarm message
                        submitBtn.disabled = true;
                        existsMessage.style.display = 'block';
                        
                        console.log('Product found! Add part button is disabled and edit link is now visible.'); 
                    } else {
                        editLinkContainer.style.display = 'none';
                        
                        // Re-enable the button and hide the alarm message
                        submitBtn.disabled = false;
                        existsMessage.style.display = 'none';

                        console.log('Part number not found. You can create a new product.');
                    }
                } catch (error) {
                    console.error('Error fetching product data:', error);
                    console.log('An error occurred while fetching product data.');
                    editLinkContainer.style.display = 'none';
                    submitBtn.disabled = false;
                    existsMessage.style.display = 'none';
                }
            }
        });
    </script>
