
    <h2>Edit Product</h2>


          <!-- Edit product -->
           <div class="tab-content" id="edit-product">
            <a href="../auth/dashboard.php?page=products_list" class="backBtn mb-3">
              <svg width="24" height="24"fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"/>
              </svg>
              <span>Back</span>
            </a>

            <form id="EditPartForm" class="d-flex flex-column partForm" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">

                <div class="container px-0">
                  <!-- part number and Manufacturer inputs -->
                <div class="d-flex flex-row align-items-center mb-3">
                  <svg width="24" height="24" fill="currentColor" class="bi bi-pencil-square hoverSvg" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
                  <h3 class="pl-1">Part Details :</h3>
                </div>
                  <div class="row border rounded shadow-sm bg-light p-3">
                    
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="partNumber" title="Part Number">P/N:</label>
                      <input class="form-control" type="text" name="p_n" value="<?= htmlspecialchars($product['part_number']) ?>" autocomplete="off" placeholder="Part number" required>
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="name" title="Name">Name:</label>
                      <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" autocomplete="off" placeholder="Name" required>
                    </div>
                     <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="tag name" title="Tag Name">Tag Name:</label>
                      <input type="text"name="tag" value="<?= htmlspecialchars($product['tag']) ?>" autocomplete="off" placeholder="Tag Name" >
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="manufacturer" title="Manufacturer">MFG:</label>
                      <input type="text" name="MFG" value="<?= htmlspecialchars($product['mfg']) ?>" autocomplete="off" placeholder="Manufacturer" >
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                      <label class="form-label" for="Quantity" title="Quantity">QTY:</label>
                      <input type="text" name="qty" value="<?= htmlspecialchars($product['qty']) ?>" min="0" autocomplete="off" placeholder="Quantity" required>
                    </div>
                    <div class="col-12 col-md-4 px-2 my-2">
                        <label for="" class="form-label">Date Code:</label>
                        <select name="date_code" id="date_code" class="form-select" required>
                            <option value="2024+" <?= $product['date_code'] == '2024+' ? 'selected' : '' ?>>2024+</option>
                            <option value="2024" <?= $product['date_code'] == '2024' ? 'selected' : '' ?>>2024</option>
                        </select>
                    </div>
                    
                    <div class="col-12 mt-2 px-2">
                      <label for="editDescription" title="Company Comment">Company CMT:</label>
                      <textarea id="editDescription" class="mt-2" name="company_cmt" rows="3"><?= htmlspecialchars($product['company_cmt']) ?></textarea>
                    </div>
                </div>

                <!-- Category selection -->
                <div class="d-flex flex-row align-items-center my-3">
                  <svg width="24" height="24" fill="currentColor" class="bi bi-pencil-square hoverSvg" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                  </svg>
                  <h3 class="pl-1">Part storage location:</h3>
                </div>

                <!-- Category Dropdown Section -->
                <div class="container mt-1 Category border rounded shadow-sm bg-light p-3">
                  <div class="row d-flex flex-row justify-content-between">

                    <div class="col-12 col-md-4 px-2">
                        <label for="" class="form-label">Categories:</label>
                        <input type="text" id="category_search" placeholder="Search category..." autocomplete="off" style="width: 300px; padding: 8px;" class="form-select">
                        <input type="hidden" name="category_id" id="category_id" value="<?= htmlspecialchars($product['category_id']) ?>">
                        <div id="category_dropdown" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none; background: #f9f9f9;"></div>
                    </div>

                    <div class="col-12 col-md-4 px-2">
                      <label for="location" class="form-label" title="location in Inventory">Location:</label>
                      <input type="text" name="location" value="<?= htmlspecialchars($product['location']) ?>" autocomplete="off" placeholder="Enter Location" >
                    </div>

                    <div class="col-12 col-md-4 px-2 dropdown">
                      <label for="Received Code" class="form-label" title="Received Code">Received Code:</label>
                      <input type="text" name="recieve_code" value="<?= htmlspecialchars($product['recieve_code']) ?>" autocomplete="off" placeholder="Received Code">
                    </div>

                  </div>

                </div>

                <!-- inputs files -->
                <div class="d-flex flex-row align-items-center my-3">
                  <svg  width="24" height="24" fill="currentColor" class="bi bi-pencil-square hoverSvg" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                  </svg>
                  <h3 class="pl-1">Upload Files:</h3>
                </div>



                <div class="row mt-1 d-flex flex-row justify-content-between">
                    <!-- Datasheet Upload -->
                    <div class="col-12 col-md-5 mb-3 border rounded shadow-sm bg-light p-3">
                      <label for="editDatasheetUpload" class="form-label">Upload Datasheet:</label>
                      <input class="form-control" type="file" id="editDatasheetUpload" name="pdfs[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
                        <?php if (!empty($pdfs)): ?>
                      <ul id="editDatasheetList" class="mt-3 list-group small">
                        <?php foreach ($pdfs as $pdf): ?>
                        <li class="d-flex flex-row align-items-center itemfile">
                            <a href="<?= htmlspecialchars($pdf['file_path']) ?>" target="_blank">
                                <span><?= htmlspecialchars($pdf['file_name']) ?></span>  
                            </a>
                            <button  type="button" onclick="deleteFile('pdf', <?= $pdf['id'] ?>)">
                                <svg width="18" height="18" fill="" class="bi bi-trash hoverSvg mx-1" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                            </button>
                        </li>
                        <?php endforeach; ?>
                      </ul>
                          <?php else: ?>
                            <p>No PDFs uploaded.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-12 col-md-5 mb-3 border rounded shadow-sm bg-light p-3">
                      <label for="editImageUpload" class="form-label">Uploadd Images:</label>
                      <input class="form-control" type="file" id="editImageUpload" name="images[]" multiple accept="image/*">
                      <?php if (!empty($images)): ?>
                      <ul id="editImageList" class="mt-3 list-group small">
                        <?php foreach ($images as $img): ?>
                        <li class="d-flex flex-row align-items-center itemfile">
                            <button  type="button" onclick="deleteFile('image', <?= $img['id'] ?>)">
                                <svg width="18" height="18" fill="" class="bi bi-trash hoverSvg mx-1" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                            </button>
                          
                         <span><img src="<?= htmlspecialchars($img['file_path']) ?>" width="60"></span>   
                        </li>
                        <?php endforeach; ?>
                      </ul>
                      <?php else: ?>
                        <p>No images uploaded.</p>
                    <?php endif; ?>
                    </div>
                  </div>

                <div class="mt-3" style="text-align: end">
                  <button type="submit" class="btn btn-primary" >Edit Part</button>
                </div>

                </div>
              </form>
           </div>


   <!-- delete files scripts -->
    <script>
        function deleteFile(type, id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'File will be deleted permanently.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../manager/delete_file.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'type=' + encodeURIComponent(type) + '&id=' + encodeURIComponent(id)
                    }).then(res => res.json())
                      .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', '', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message || 'Failed to delete', 'error');
                        }
                    });
                }
            });
        }
    </script>
    <!-- end delete file -->

    <!-- search for categories  -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdown = document.getElementById('category_dropdown');
            const searchInput = document.getElementById('category_search');

            function renderDropdown(search = '') {
                fetch('../ajax/fetch_leaf_categories.php?search=' + encodeURIComponent(search))
                    .then(res => res.json())
                    .then(categories => {
                        dropdown.innerHTML = '';
                        dropdown.style.display = categories.length ? 'block' : 'none';

                        categories.forEach(cat => {
                            const item = document.createElement('div');
                            item.textContent = cat.name;
                            item.dataset.id = cat.id;
                            item.style.padding = '5px';
                            item.style.cursor = 'pointer';

                            item.addEventListener('click', () => {
                                document.getElementById('category_id').value = cat.id;
                                searchInput.value = cat.name;
                                dropdown.style.display = 'none';
                            });

                            dropdown.appendChild(item);
                        });
                    })
                    .catch(err => {
                        dropdown.innerHTML = 'Error loading categories.';
                        dropdown.style.display = 'block';
                    });
            }

            searchInput.addEventListener('input', function () {
                renderDropdown(this.value);
            });
            let debounceTimer;
            searchInput.addEventListener('input', function () {
                const searchText = this.value;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    renderDropdown(searchText);
                }, 300);
            });

            // Preload selected category name if editing
            <?php if (!empty($product['category_id'])): ?>
            fetch('../ajax/fetch_leaf_categories.php?id=<?= (int)$product['category_id'] ?>')
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        searchInput.value = data[0].name;
                    }
                });
            <?php endif; ?>
        });
        

    </script>

     <!-- end search category  -->

     <!-- sweet alerts  -->
   <?php if (!empty($success)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: <?= json_encode($success) ?>,
                confirmButtonColor: '#3085d6'
            });
        </script>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `<ul style="text-align:left;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>`,
                confirmButtonColor: '#d33'
            });
        </script>
    <?php endif; ?>

<!-- end sweet alerts -->
