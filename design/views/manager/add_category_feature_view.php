<h2>Manage Features for Category</h2>
<form id="featureForm" method="POST" action="">
    <div class="search-container">
        <label for="categorySearch">Category</label><br>
        <input type="text" id="categorySearch" placeholder="Search categories..." autocomplete="off">
        <input type="hidden" name="category_id" id="category_id">
        <div id="categoryResults" class="category-results" style="display:none;"></div>
    </div>
    <br>

    <!-- Container for existing feature rows -->
    <div id="existingFeaturesContainer" class="existing-features" style="display:none;">
        <label>Existing Features:</label>
        <div id="existingFeatureRows"></div>
    </div>
    
    <!-- Container for new dynamic feature rows -->
    <div id="newFeaturesContainer" style="display:none;">
        <label>New Features:</label>
        <div id="newFeatureRows"></div>
        <div class="button-group">
            <button type="button" id="addRowBtn" class="add-row-btn">
                <svg  viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
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
        const dataTypes = ['varchar(50)', 'decimal(12,3)', 'TEXT', 'boolean'];
        let optionsHtml = '';
        dataTypes.forEach(type => {
            const isSelected = type === feature.data_type ? 'selected' : '';
            optionsHtml += `<option value="${type}" ${isSelected}>${type}</option>`;
        });

        row.innerHTML = `
            <input type="hidden" name="feature_id" value="${feature.id}">
            <input type="text" name="name" value="${feature.name}" required autocomplete="off">
            <select name="data_type" autocomplete="off">${optionsHtml}</select>
            <input type="text" name="unit" value="${feature.unit}" placeholder="Unit (optional)" autocomplete="off">
            <label>
                <!-- This line is key. The checked attribute is dynamically added or removed. -->
                <input type="checkbox" name="is_required" ${isRequiredChecked}> Required
            </label>
            <button type="button" class="action-btn update-btn" onclick="updateFeature(this)">
                <svg viewBox="0 0 512 512"><path fill="blue" d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.5-30.5c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-26.6 80.1c-15.5 46.5-1.9 96.8 39.5 138.2C241.9 444.6 307.7 465.1 370.9 459.7l-97.9-97.9 97.9-97.9-97.9-97.9-97.9 97.9zM208 144a144 144 0 1 1 0 288 144 144 0 1 1 0-288z"/></svg>
            </button>
            <button type="button" class="action-btn delete-btn" onclick="deleteFeature(this)">
                <svg viewBox="0 0 448 512"><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
            </button>
        `;
        existingFeatureRowsContainer.appendChild(row);
    }
    
    window.updateFeature = function(button) {
        const row = button.closest('.feature-row');
        const featureId = row.querySelector('input[name="feature_id"]').value;
        const name = row.querySelector('input[name="name"]').value;
        const dataType = row.querySelector('select[name="data_type"]').value;
        const unit = row.querySelector('input[name="unit"]').value;
        
        // This is the key change: explicitly check the checkbox state
        const isRequired = row.querySelector('input[name="is_required"]').checked ? 1 : 0;
        
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
        row.innerHTML = `
            <input type="text" name="features[${featureCounter}][name]" placeholder="Feature Name" required autocomplete="off">
            <select name="features[${featureCounter}][data_type]" autocomplete="off">
                <option value="varchar(50)"> (under 50char)</option>
                <option value="decimal(12,3)">Decimal</option>
                <option value="TEXT">Long Text</option>
                <option value="boolean">Boolean</option>
            </select>
            <input type="text" name="features[${featureCounter}][unit]" placeholder="Unit (optional)" autocomplete="off">
            <label>
                <input type="checkbox" name="features[${featureCounter}][is_required]"> Required
            </label>
            <button type="button" class="action-btn delete-btn" onclick="this.closest('.feature-row').remove();">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
            </button>
        `;
        newFeatureRowsContainer.appendChild(row);
        featureCounter++;
    }

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
                        
                        // Clear the "New Features" section
                        newFeatureRowsContainer.innerHTML = '';
                        featureCounter = 0;
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
});
</script>

