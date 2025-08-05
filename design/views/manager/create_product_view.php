
            <!-- "Add a single part":  -->
          <div id="Add-Single-part" class="tab-content">
            <form  method="post" enctype="multipart/form-data" class="d-flex flex-column partForm">
                <div class="container px-0">
                  <!-- part number and Manufacturer inputs -->
                <div class="d-flex flex-row align-items-center mb-2">
                  <svg width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                  </svg>
                  <h3 class="pl-1">Part Details :</h3>
                </div>
                  <div class="row bg-light border rounded shadow-sm p-3">
                    
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="partNumber" title="Part Number">P/N:</label>
                      <input class="form-control" type="text" name="pn" placeholder="Part number" autocomplete="off" required />
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="name" title="Name">Name:</label>
                      <input class="form-control" type="text" name="name" placeholder="Name" autocomplete="off" required />
                    </div>
                     <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="tag name" title="Tag Name">Tag Name:</label>
                      <input class="form-control" type="text" name="tag" placeholder="Tag Name" autocomplete="off" required />
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="manufacturer" title="Manufacturer">MFG:</label>
                      <input class="form-control" type="text" name="mfg" placeholder="Manufacturer" autocomplete="off"  />
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="Quantity" title="Quantity">QTY:</label>
                      <input class="form-control" type="text" name="qty" placeholder="Quantity" min="0" required />
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                        <label for="" class="form-label">Date Code:</label>
                        <select class="form-select" name="date_code">
                          <option value="2024+">2024+</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    
                    <div class="col-12 mt-2 px-2">
                      <label class="form-label" for="description" title="Company Comment">Company CMT:</label>
                      <textarea class="form-control"  id="description" class="mt-2" name="company_cmt" rows="3"></textarea>
                    </div>
                </div>

                <!-- Category selection -->
                <div class="d-flex flex-row align-items-center my-3">
                  <svg width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                  </svg>
                  <h3 class="pl-1">Part storage location:</h3>
                </div>

                <!-- Category Dropdown Section -->
                <div class="container mt-1 px-1 border rounded shadow-sm p-3 bg-light">
                  <div class="row d-flex flex-row justify-content-between">

                    <div class="col-12 col-md-4 px-2 my-2">
                        <label for="" class="form-label">Categories:</label>
                        <!-- categories -->
                        <input type="text" id="category_search" placeholder="Search categories..." autocomplete="off" class="form-select">
                        <input type="hidden" name="category_id" id="category_id">
                        <ul id="category-dropdown" style="z-index:1000; border:1px solid #ccc; max-height:150px; overflow-y:auto; list-style:none; padding:5px 10px; background-color:white;"></ul>

                    </div>

                    <div class="col-12 col-md-4 px-2 my-2">
                      <label for="location" class="form-label" title="location in Inventory">Location:</label>
                      <input class="form-control" type="text" name="location" placeholder="Enter Location" autocomplete="off" required />
                    </div>

                    <div class="col-12 col-md-4 px-2 my-2">
                      <label for="Received Code" class="form-label" title="Received Code">Received Code:</label>
                      <input class="form-control" type="text" name="recieve_code" placeholder="Received Code" autocomplete="off" required />
                    </div>

                  </div>

                </div>

                <!-- inputs files -->
                <div class="d-flex flex-row align-items-center my-3">
                  <svg width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                      <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                      <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                  <h3 class="pl-1">Upload Files:</h3>
                </div>

                <div class="row mt-1 d-flex flex-row justify-content-between">
                  <div class="col-12 col-md-5 mb-3 border rounded shadow-sm p-3 bg-light">
                    <label for="datasheetUpload" class="form-label">Upload Datasheet:</label>
                    <input class="form-control" type="file" id="datasheetUpload" name="pdfs[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
                  </div>

                  <div class="col-12 col-md-5 mb-3 border rounded shadow-sm p-3 bg-light">
                    <label for="imageUpload" class="form-label">Upload Images:</label>
                    <input class="form-control" type="file" id="imageUpload" name="images[]" multiple accept="image/*" >
                  </div>
                </div>


                <div class="" style="text-align: end">
                  <button type="submit" class="btn btn-primary" id="Addpart">Add part</button>
                </div>
                </div>
              </form>
          </div>




<script>
let categories = [];

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

    if (filtered.length === 0) {
        dropdown.innerHTML = '<li>No results found</li>';
        return;
    }

    filtered.forEach(cat => {
        const li = document.createElement('li');
        li.textContent = cat.name;
        li.onclick = () => {
            document.getElementById('category_search').value = cat.name;
            document.getElementById('category_id').value = cat.id;
            dropdown.innerHTML = ''; // Clear dropdown after selection
        };
        dropdown.appendChild(li);
    });
}

// Reload suggestions when user types
document.getElementById('category_search').addEventListener('input', function () {
    const searchText = this.value;
    renderDropdown(searchText);
});

// Load categories on page load
fetchCategories();
</script>

   <!-- sweet alerts -->
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

  <!-- end sweet alert -->

