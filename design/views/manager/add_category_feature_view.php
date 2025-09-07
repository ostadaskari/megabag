<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
        <svg width="26" height="26" fill="currentColor" class="bi bi-node-plus mx-1" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8M6.025 7.5a5 5 0 1 1 0 1H4A1.5 1.5 0 0 1 2.5 10h-1A1.5 1.5 0 0 1 0 8.5v-1A1.5 1.5 0 0 1 1.5 6h1A1.5 1.5 0 0 1 4 7.5zM11 5a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 11 5M1.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
        </svg>
    Add Feature To Categories</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>

<form id="featureForm" method="POST" action="" style="padding-bottom: 50px;">
    <div class="col-12 col-md-6 search-container" style="margin: auto;">
        <label for="categorySearch" class="form-label" style="width:100%;text-align: center;">Search in categories:</label>
        <div class="input-box" style="width: 100%; margin:0 0 5px 0;" >
             <div class="svgSearch">
                 <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                     <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                 </svg>
             </div>
             <input type="text" id="categorySearch" placeholder="Search categories..." autocomplete="off">
             <input type="hidden" name="category_id" id="category_id">
             <div id="categoryResults" class="category-results" style="display:none;"></div>
        </div>
    </div>

    <!-- Container for existing feature rows -->
    <div id="existingFeaturesContainer" class="existing-features" style="display:none;">
        <label>Existing Features:</label>
        <div class="mt-2 border rounded shadow-sm bg-light" id="existingFeatureRows"></div>
    </div>
    
    <!-- Container for new dynamic feature rows -->
    <div class="mt-2" id="newFeaturesContainer" style="display:none;">
        <label>New Features:</label>
        <div class="mt-2 border rounded shadow-sm bg-light" id="newFeatureRows"></div>
        <div class="button-group">
            <button type="button" id="addRowBtn" class="add-row-btn btnSvg">
            <svg width="28" height="28" fill="green" class="bi bi-plus-circle-dotted hoverSvg" viewBox="0 0 16 16"><path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"></path></svg>
            </button>
            <button type="submit" id="addFeaturesBtn" class="add-features-btn">Add New Features</button>
        </div>
    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("categorySearch");
    const resultsBox = document.getElementById("categoryResults");
    const categoryIdInput = document.getElementById("category_id");
    const existingFeaturesContainer = document.getElementById("existingFeaturesContainer");
    const existingFeatureRowsContainer = document.getElementById("existingFeatureRows");
    const newFeaturesContainer = document.getElementById("newFeaturesContainer");
    const newFeatureRowsContainer = document.getElementById("newFeatureRows");
    const addRowBtn = document.getElementById("addRowBtn");
    const featureForm = document.getElementById("featureForm");

    let featureCounter = 0;

    searchInput.addEventListener("input", function () {
        const query = this.value.trim();
        if (query.length < 2) {
            resultsBox.style.display = "none";
            return;
        }
        fetch(`../../core/ajax/all_categories_ajax.php?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                resultsBox.innerHTML = "";
                if (data.length === 0) {
                    resultsBox.style.display = "none";
                    return;
                }
                data.forEach(cat => {
                    const div = document.createElement("div");
                    div.classList.add("category-item");
                    div.textContent = cat.name;
                    div.dataset.id = cat.id;
                    div.addEventListener("click", function () {
                        searchInput.value = cat.name;
                        categoryIdInput.value = cat.id;
                        resultsBox.style.display = "none";
                        // Now we show both the existing and new feature containers
                        existingFeaturesContainer.style.display = "block";
                        newFeaturesContainer.style.display = "block";
                        fetchExistingFeatures(cat.id);
                        newFeatureRowsContainer.innerHTML = '';
                        featureCounter = 0;
                        // --- FIX START ---
                        // Call this function to create the first row automatically
                        createNewFeatureRow();
                        // --- FIX END ---
                    });
                    resultsBox.appendChild(div);
                });
                resultsBox.style.display = "block";
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Could not fetch categories", "error");
            });
    });

    /**
     * Fetches existing features for a given category and renders them.
     * This function is called on category selection and after a successful update/delete.
     * @param {string} categoryId The ID of the category to fetch features for.
     */
    function fetchExistingFeatures(categoryId) {
        fetch(`../../core/ajax/edit_delete_feature_ajax.php?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                existingFeatureRowsContainer.innerHTML = ''; // Clear existing rows before rendering
                if (data.status === 'success' && data.features.length > 0) {
                    data.features.forEach(feature => {
                        createExistingFeatureRow(feature);
                    });
                    existingFeaturesContainer.style.display = "block";
                } else {
                    existingFeaturesContainer.style.display = "none";
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Could not fetch existing features", "error");
            });
    }


    function createExistingFeatureRow(feature) {
    const row = document.createElement("div");
    row.classList.add("feature-row");
    const isRequiredChecked = feature.is_required == 1 ? 'checked' : '';
    const dataTypes = ['varchar(50)', 'decimal(15,7)', 'TEXT', 'boolean', 'range', 'multiselect'];
    let optionsHtml = dataTypes.map(type =>
        `<option value="${type}" ${type === feature.data_type ? 'selected' : ''}>${type}</option>`
    ).join('');

    let metadata = {};
    try { metadata = feature.metadata ? JSON.parse(feature.metadata) : {}; } catch {}

    row.innerHTML = `
      <div class="feature-content">
        <input type="hidden" name="feature_id" value="${feature.id}">

        <div class="col-6 col-md-3 px-1 d-flex flex-row align-items-center">
          <label class="form-label">Name:</label>
          <input type="text" class="form-control" name="name" value="${feature.name}" required autocomplete="off">
        </div>

        <div class="col-6 col-md-3 px-1 d-flex flex-row align-items-center">
          <label class="form-label">Data Type:</label>
          <select class="form-control data-type-select" name="data_type">${optionsHtml}</select>
        </div>

        <div class="col-6 col-md-2 d-flex flex-row align-items-center">
          <label class="form-label">Unit:</label>
          <input class="form-control unit-input" type="text" name="unit" value="${feature.unit}" placeholder="Unit (optional)" autocomplete="off">
        </div>


        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
          <label>
            <input type="checkbox" name="is_required" ${isRequiredChecked}> Required
          </label>
        </div>

      </div>

      <div class="feature-actions">
        <button type="button" class="action-btn update-btn p-2" title="Edit" onclick="updateFeature(this)">
            <svg width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/></svg>
        </button>
        <button type="button" class="action-btn delete-btn p-2" title="delete" onclick="deleteFeature(this)">
            <svg width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
        </button>
      </div>
    `;

   // Find the newly created row's metadata container and populate it
   // Add event listener to the select to handle dynamic metadata fields
    const dataTypeSelect = row.querySelector('select[name="data_type"]');
    const unitInput = row.querySelector('.unit-input');

    // Parent column of select (closest div with col-* classes)
    const dataTypeCol = dataTypeSelect.closest('div[class*="col-"]');

    function clearMetadataFields() {
    row.querySelectorAll('.metadata-field').forEach(el => el.remove());
    }

    function renderMetadataFields(type, metadata = {}) {
    clearMetadataFields();

    // Disable the unit for multiselect, enable it in other cases.
    unitInput.disabled = (type === 'multiselect');

    if (type === 'range') {
        dataTypeCol.insertAdjacentHTML('afterend', `
        <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Min:</label>
            <input type="number" step="any" class="form-control" name="metadata_min"
                value="${metadata.min ?? ''}" autocomplete="off">
        </div>
        <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Max:</label>
            <input type="number" step="any" class="form-control" name="metadata_max"
                value="${metadata.max ?? ''}" autocomplete="off">
        </div>
        <div class="col-6 col-md-3 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Units:</label>
            <input type="text" class="form-control" name="metadata_units"
                value="${(metadata.units || []).join(', ')}"
                placeholder="e.g., mΩ, Ω, kΩ" autocomplete="off">
        </div>
        `);
    } else if (type === 'multiselect') {
        dataTypeCol.insertAdjacentHTML('afterend', `
        <div class="col-6 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Options (comma-separated):</label>
            <input type="text" class="form-control" name="metadata_options"
                value="${(metadata.options || []).join(', ')}"
                placeholder="e.g., SMD, Through Hole" autocomplete="off">
        </div>
        `);
    }
    }

    //Initial execution based on current type
    renderMetadataFields(feature.data_type, metadata);

    // Dynamic type change
    dataTypeSelect.addEventListener('change', (e) => {
    renderMetadataFields(e.target.value);
    });

    existingFeatureRowsContainer.appendChild(row);

    }

    window.updateFeature = function(button) {
        const row = button.closest('.feature-row');
        const featureId = row.querySelector('input[name="feature_id"]').value;
        const name = row.querySelector('input[name="name"]').value;
        const dataType = row.querySelector('select[name="data_type"]').value;
        const unit = row.querySelector('input[name="unit"]').value;
        const isRequired = row.querySelector('input[name="is_required"]').checked ? 1 : 0;
        
        // New: Collect and serialize metadata if applicable
        let metadata = {};
        if (dataType === 'range') {
            const min = row.querySelector('input[name="metadata_min"]').value;
            const max = row.querySelector('input[name="metadata_max"]').value;
            const units = row.querySelector('input[name="metadata_units"]').value;
            metadata = {
                min: min,
                max: max,
                units: units.split(',').map(s => s.trim())
            };
        } else if (dataType === 'multiselect') {
            const options = row.querySelector('input[name="metadata_options"]').value;
            metadata = {
                options: options.split(',').map(s => s.trim())
            };
        }

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to update this feature?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('update_feature_id', featureId);
                formData.append('name', name);
                formData.append('data_type', dataType);
                formData.append('unit', unit);
                formData.append('is_required', isRequired);
                formData.append('metadata', JSON.stringify(metadata));

                fetch("../../core/ajax/edit_delete_feature_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Updated!",
                            text: data.message,
                            icon: "success"
                        }).then(() => { 
                            // Re-fetch and re-render the list without reloading the whole page
                            fetchExistingFeatures(categoryIdInput.value);
                        });
                    } else {
                        Swal.fire("Error", data.message, "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", "Something went wrong", "error");
                });
            }
        });
    };

    window.deleteFeature = function(button) {
        const row = button.closest('.feature-row');
        const featureId = row.querySelector('input[name="feature_id"]').value;

        Swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this feature!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('delete_feature_id', featureId);

                fetch("../../core/ajax/edit_delete_feature_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Deleted!",
                            text: data.message,
                            icon: "success"
                        }).then(() => { 
                            // Re-fetch and re-render the list without reloading the whole page
                            fetchExistingFeatures(categoryIdInput.value); 
                        });
                    } else {
                        Swal.fire("Error", data.message, "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", "Something went wrong", "error");
                });
            }
        });
    };

    // Function to create a new feature row
    function createNewFeatureRow() {
        const row = document.createElement("div");
        row.classList.add("feature-row");
        const counter = featureCounter++;

        row.innerHTML = `
        <div class="feature-content">
            <div class="col-12 col-md-3 px-1 d-flex flex-row align-items-center">
            <label class="form-label">Name:</label>
            <input class="form-control" type="text" name="features[${counter}][name]" placeholder="Feature Name" required autocomplete="off">
            </div>

            <div class="col-12 col-md-3 px-1 d-flex flex-row align-items-center">
            <label class="form-label">Data Type:</label>
            <select name="features[${counter}][data_type]" class="data-type-select form-control">
                <option value="varchar(50)">(under 50char)</option>
                <option value="decimal(15,7)">Decimal</option>
                <option value="TEXT">Long Text</option>
                <option value="boolean">Boolean</option>
                <option value="range">Range</option>
                <option value="multiselect">Multiselect</option>
            </select>
            </div>

            <div class="col-12 col-md-2 d-flex flex-row align-items-center">
            <label class="form-label">Unit:</label>
            <input type="text" class="form-control unit-input" name="features[${counter}][unit]" placeholder="Unit (optional)">
            </div>

            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
            <label><input type="checkbox" name="features[${counter}][is_required]"> Required</label>
            </div>
        </div>

        <div class="feature-actions">
            <button type="button" class="action-btn delete-btn p-2" onclick="this.closest('.feature-row').remove();"><svg width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path></svg></button>
        </div>
        `;

        newFeatureRowsContainer.appendChild(row);
    }



    // Dynamic field creation based on data type selection
    newFeatureRowsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('data-type-select')) {
            const row = e.target.closest('.feature-row');
            const unitInput = row.querySelector('.unit-input');
            const counter = e.target.name.match(/\[(\d+)\]/)[1];

            // The data type of the parent column
            const dataTypeCol = e.target.closest('div[class*="col-"]');

            // Clear previous metadata fields
            row.querySelectorAll('.metadata-field').forEach(el => el.remove());
            unitInput.disabled = false;

            if (e.target.value === 'range') {
                dataTypeCol.insertAdjacentHTML('afterend', `
                    <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Min:</label>
                        <input type="number" step="any" class="form-control" name="features[${counter}][min]" autocomplete="off">
                    </div>
                    <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Max:</label>
                        <input type="number" step="any" class="form-control" name="features[${counter}][max]" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Units (comma-separated):</label>
                        <input type="text" class="form-control" name="features[${counter}][units]" placeholder="e.g., mΩ, Ω, kΩ" autocomplete="off">
                    </div>
                `);
            } else if (e.target.value === 'multiselect') {
                unitInput.disabled = true; // No unit for multiselect
                dataTypeCol.insertAdjacentHTML('afterend', `
                    <div class="col-12 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Options (comma-separated):</label>
                        <input type="text" class="form-control" name="features[${counter}][options]" placeholder="e.g., SMD, Through Hole" required autocomplete="off">
                    </div>
                `);
            }
        }
    });


    // Add row button handler
    addRowBtn.addEventListener("click", function() {
        createNewFeatureRow();
    });

    // Submit form for NEW features with AJAX
    featureForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('category_id', categoryIdInput.value);

        fetch("../../core/manager/add_category_feature.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success"
                    }).then(() => {
                        // Re-fetch and re-render the list without reloading the whole page
                        fetchExistingFeatures(categoryIdInput.value);
                        
                        // Clear the "New Features" section and add one new row
                        newFeatureRowsContainer.innerHTML = '';
                        featureCounter = 0;
                        createNewFeatureRow();
                    });
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Something went wrong", "error");
            });
    });

    // Initial row creation
    createNewFeatureRow();
});
</script>