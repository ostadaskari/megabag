<h2>Add Feature to Category</h2>
<form id="featureForm" method="POST" action="">
    <div class="search-container">
        <label for="categorySearch">Category</label><br>
        <input type="text" id="categorySearch" placeholder="Search categories..." autocomplete="off">
        <input type="hidden" name="category_id" id="category_id">
        <div id="categoryResults" class="category-results" style="display:none;"></div>
    </div>
    <br>

    <label for="name">Feature Name</label><br>
    <input type="text" name="name" id="name" required><br><br>

    <label for="data_type">Data Type</label><br>
    <select name="data_type" id="data_type">
        <option value="varchar(50)">Text (varchar)</option>
        <option value="decimal(12,3)">Decimal</option>
        <option value="TEXT">Long Text</option>
        <option value="boolean">Boolean</option>
    </select><br><br>

    <label for="unit">Unit (optional)</label><br>
    <input type="text" name="unit" id="unit"><br><br>

    <label>
        <input type="checkbox" name="is_required" value="1"> Required
    </label><br><br>

    <button type="submit">Add Feature</button>
</form>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("categorySearch");
    const resultsBox = document.getElementById("categoryResults");
    const categoryIdInput = document.getElementById("category_id");

    // Search categories as user types
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
                    // Corrected: show only the category name
                    div.textContent = cat.name;
                    div.dataset.id = cat.id;
                    div.addEventListener("click", function () {
                        searchInput.value = cat.name;
                        categoryIdInput.value = cat.id;
                        resultsBox.style.display = "none";
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

    // Submit form with AJAX
    document.getElementById("featureForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("../../core/manager/add_category_feature.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire("Success", data.message, "success");
                    this.reset();
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