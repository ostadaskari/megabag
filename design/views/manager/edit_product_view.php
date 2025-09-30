<!-- Edit product -->
<div class="tab-content" id="edit-product">
    <div class="d-flex flex-row align-items-center justify-content-between titleTop">
        <h2 class="d-flex align-items-center">
            <svg width="24" height="24" fill="currentColor" class="bi bi-pen mx-1 me-2" viewBox="0 0 16 16">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
            </svg>
            Edit Part</h2>

        <a href="../auth/dashboard.php?page=products_list" class="backBtn">
            <svg width="24" height="24"fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"/>
            </svg>
            <span>Back</span>
        </a>
    </div>

    <form id="EditPartForm" class="d-flex flex-column partForm" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
            <!-- CSRF  -->
        <?php generate_csrf_token(); ?>

        <div class="container bg-light border rounded shadow-sm p-2 mb-2" style="z-index:1000;">
              <div class="row">
                <!-- Category Dropdown Section -->
                    <div class="col-12 d-flex flex-row">
                        <div class="d-flex flex-row align-items-center">
                            <svg width="20" height="20" fill="currentColor" class="bi bi-substack" viewBox="0 0 16 16">
                            <path d="M15 3.604H1v1.891h14v-1.89ZM1 7.208V16l7-3.926L15 16V7.208zM15 0H1v1.89h14z"></path>
                            </svg>
                            <h3 class="pl-1">Category :</h3>
                          </div>
                        <div style="width:40%;">
                            <input type="text" id="category_search" placeholder="Search categories..." autocomplete="off" class="form-select" required>
                            <input type="hidden" name="category_id" id="category_id" value="<?= htmlspecialchars($product['category_id']) ?>">
                            <ul class="category-suggestions" id="category-dropdown" style="width: 40%; display: none;"></ul>
                        </div> 
                    </div>
                </div>
            </div> 
        
        <div class="container border rounded shadow-sm bg-light p-2">
            <!-- Part details section -->
            <div class="d-flex flex-row align-items-center mb-1">
                <svg width="24" height="24" fill="var(--main-bg0-color)" class="bi bi-pencil-square hoverSvg" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
                <h3 class="pl-1">Part Details :</h3>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 px-2 my-2">
                    <label class="form-label" for="p_n" title="Part Number">P/N:</label>
                    <input class="form-control" type="text" name="p_n" value="<?= htmlspecialchars($product['part_number']) ?>" autocomplete="off" placeholder="Part number" required>
                </div>
                <div class="col-12 col-md-4 px-2 my-2">
                <label class="form-label" for="MFG" title="Manufacturer">MFG:</label>
                    <input class="form-control" type="text" name="MFG" value="<?= htmlspecialchars($product['mfg']) ?>" autocomplete="off" placeholder="Manufacturer" >
                </div>
                <div class="col-12 col-md-2 px-2 my-2">
                    <label for="location" class="form-label" title="location in Inventory">Location:</label>
                    <input class="form-control" type="text" name="location" value="<?= htmlspecialchars($product['location']) ?>" autocomplete="off" placeholder="Enter Location" >
                </div>
                <div class="col-12 col-md-2 px-2 my-2">
                    <label class="form-label" for="status">
                        Status:
                    </label>
                    <select id="" name="status" class="form-select" >
                        <option value="<?= htmlspecialchars($product['status']) ?>"><?= htmlspecialchars($product['status']) ?></option>
                        <option value="Available">Available</option>
                        <option value="UnAvailable">UnAvailable</option>
                    </select>
                </div>

            </div> 
            <div class="row d-flex flex-row align-items-center justify-content-between">    

                    

                <div class="col-12 pr-2">

                    <label class="form-label" for="editDescription" title="Company Comment">Company Comment:</label>
                    <textarea id="editDescription" class="form-control" name="company_cmt" rows="3"><?= htmlspecialchars($product['company_cmt']) ?></textarea>

                </div>
            </div>

            
            <!-- Product Features Section - Newly added -->
            <div class="d-flex flex-row align-items-center mt-3">
                        <svg width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"></path>
                        </svg> 
                        <h3 class="pl-1 mb-0">Specifications:</h3>
                        <div class="flex-grow-1 ms-2 border-bottom"></div>
                    </div>


            <div  class="container p-1">
               <div class="row my-2 d-flex flex-row align-items-center" id="product_features_container">

                <!-- Features will be populated here dynamically by JavaScript -->
                <?php if (!empty($product['features'])): ?>
                    <?php foreach ($product['features'] as $feature): ?>
                    <div class="col-12 col-md-4 px-2 my-2">
                            <label class="form-label" for="feature_<?= htmlspecialchars($feature['id']) ?>">
                                <?= htmlspecialchars($feature['name']) ?>:
                            </label>

                            <?php if (!empty($feature['unit'])): ?>
                                <div class="input-group">
                                    <input class="form-control" type="text" 
                                        id="feature_<?= htmlspecialchars($feature['id']) ?>"
                                        name="features[<?= htmlspecialchars($feature['id']) ?>][value]" 
                                        value="<?= htmlspecialchars($feature['value']) ?>" 
                                        placeholder="Value" autocomplete="off">

                                    <select class="form-select" 
                                            name="features[<?= htmlspecialchars($feature['id']) ?>][unit]">
                                        <?php foreach (explode(',', $feature['unit']) as $unit): ?>
                                            <option value="<?= htmlspecialchars(trim($unit)) ?>" 
                                                <?= trim($unit) === $feature['unit'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars(trim($unit)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <input class="form-control" type="text" 
                                    id="feature_<?= htmlspecialchars($feature['id']) ?>"
                                    name="features[<?= htmlspecialchars($feature['id']) ?>][value]" 
                                    value="<?= htmlspecialchars($feature['value']) ?>" 
                                    placeholder="Value" autocomplete="off">
                            <?php endif; ?>

                            <!-- Hidden inputs -->
                            <input type="hidden" name="features[<?= htmlspecialchars($feature['id']) ?>][feature_id]" 
                                value="<?= htmlspecialchars($feature['id']) ?>">
                            <input type="hidden" name="features[<?= htmlspecialchars($feature['id']) ?>][name]" 
                                value="<?= htmlspecialchars($feature['name']) ?>">
                        </div>

                        
                   
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted small">No features to display. Select a category to see available features.</p>
                <?php endif; ?>

               </div>
            </div>
            <!-- End Product Features Section -->   
            </div>

            <div class="container border rounded shadow-sm bg-light p-2 mt-2">
                <!-- inputs files -->
            <div class="d-flex flex-row align-items-center mb-2">
                <svg width="24" height="24" fill="var(--main-bg0-color)" class="bi bi-pencil-square hoverSvg" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
                <h3 class="pl-1">Upload Files:</h3>
            </div>

            <div class="row mb-2 d-flex justify-content-between border rounded shadow-sm p-3">
                <div class="col-12 col-md-6 d-flex justify-content-start">
                    <div style="width: 90%;">
                        <div class="form-label d-flex align-items-center">
                            <svg width="20" height="20" fill="rgb(16, 135, 207)" class="bi bi-card-image" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm6.002 5.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0m.713 2.684-2.5-3.5a.5.5 0 0 0-.69-.03L1.002 12A1 1 0 0 0 2 13h12a1 1 0 0 0 .899-1.424l-3.5-5a.5.5 0 0 0-.899-.076L8.002 9.5z"/>
                            </svg>
                            <label for="coverImageUpload" class="mx-1">Upload Cover Image:</label>
                        </div>
                        <input class="form-control" type="file" id="coverImageUpload" name="cover_image" accept="image/*">
                        <?php if (!empty($cover_image)): ?>
                        <div class="mt-3">
                            <p class="mb-1">Current Cover:</p>
                            <div class="d-flex flex-row align-items-center justify-content-between itemfile">
                                <span><img src="<?= $base_path . htmlspecialchars($cover_image['file_path']) ?>" width="60" style="border: 1px solid #ddd; padding: 2px;"></span>
                                <button class="btnSvg" type="button" onclick="deleteFile('image', <?= $cover_image['id'] ?>)">
                                    <svg width="18" height="18" fill="rgb(207, 44, 16)" class="bi bi-trash hoverSvg mx-1" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <?php else: ?>
                            <p class="mt-3 text-muted small">No cover image uploaded.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 d-flex justify-content-end">
                    <div style="width: 90%;">
                        <div class="form-label d-flex align-items-center">
                            <svg width="20" height="20" fill="green" class="bi bi-file-earmark-image" viewBox="0 0 16 16">
                                <path d="M6.502 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                                <path d="M14 14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zM4 1a1 1 0 0 0-1 1v10l2.224-2.224a.5.5 0 0 1 .61-.075L8 11l2.157-3.02a.5.5 0 0 1 .76-.063L13 10V4.5h-2A1.5 1.5 0 0 1 9.5 3V1z"/>
                            </svg>
                            <label for="imageUpload" class="mx-1">Upload Additional Images:</label>
                        </div>
                        <input class="form-control" type="file" id="imageUpload" name="images[]" multiple accept="image/*">
                        <?php if (!empty($images)): ?>
                        <ul id="editImageList" class="mt-1 list-group small" style="max-height: 150px; overflow-y: scroll;">
                            <?php foreach ($images as $img): ?>
                            <li class="d-flex flex-row align-items-center justify-content-between itemfile">
                                <span><img src="<?=$base_path . htmlspecialchars($img['file_path']) ?>" width="40" style="border: 1px solid #ddd; padding: 2px;"></span>
                                <button class="btnSvg" type="button" onclick="deleteFile('image', <?= $img['id'] ?>)">
                                    <svg width="18" height="18" fill="currentColor" class="bi bi-trash hoverSvg mx-1" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p class="mt-3 text-muted small">No additional images uploaded.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row mt-1 mb-1 d-flex justify-content-between">
                <div class="col-12 col-md-6 d-flex">
                    <div class="border rounded shadow-sm p-3" style="width: 90%;">
                        <div class="form-label d-flex align-items-center">
                            <svg width="20" height="20" fill="rgb(207, 44, 16)" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                            </svg>
                            <label for="editDatasheetUpload" class="mx-1">Upload Datasheet:</label>
                        </div>
                        <input class="form-control" type="file" id="editDatasheetUpload" name="pdfs[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
                        <?php if (!empty($pdfs)): ?>
                        <ul id="editDatasheetList" class="mt-3 list-group small" style="max-height: 180px;overflow-y:scroll;">
                            <?php foreach ($pdfs as $pdf): ?>
                            <li class="d-flex flex-row align-items-center justify-content-between itemfile">
                                <a style="color:#101010;" href="<?= $base_path . htmlspecialchars($pdf['file_path']) ?>" target="_blank">
                                    <span><?= htmlspecialchars($pdf['file_name']) ?></span>
                                </a>
                                <button class="btnSvg" type="button" onclick="deleteFile('pdf', <?= $pdf['id'] ?>)">
                                    <svg width="18" height="18" fill="" class="bi bi-trash hoverSvg mx-1" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p class="mt-2 text-muted small">No PDFs uploaded.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            </div>
            <div class="mt-2" style="text-align: end">
                <button type="submit" class="btn btn-primary" >Edit Part</button>
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

    <!-- Dynamic Date Code Population Script -->
    <script>
// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {


    // --- Category search & feature loading ---
    const dropdown = document.getElementById('category_dropdown');
    const searchInput = document.getElementById('category_search');
    const categoryIdInput = document.getElementById('category_id');
    const featuresContainer = document.getElementById('product_features_container');

    const debounce = (func, delay) => {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    };

    function renderCategoryDropdown(search = '') {
        fetch(`../ajax/fetch_leaf_categories.php?search=${encodeURIComponent(search)}`)
            .then(res => res.json())
            .then(categories => {
                dropdown.innerHTML = '';
                dropdown.style.display = categories.length ? 'block' : 'none';

                if (categories.length === 0) {
                    const noResults = document.createElement('div');
                    noResults.textContent = 'No categories found.';
                    noResults.style.padding = '5px';
                    noResults.style.color = '#888';
                    dropdown.appendChild(noResults);
                }

                categories.forEach(cat => {
                    const item = document.createElement('div');
                    item.textContent = cat.name;
                    item.dataset.id = cat.id;
                    item.style.padding = '5px';
                    item.style.cursor = 'pointer';
                    item.className = 'dropdown-item category-suggestion-item';

                    item.addEventListener('click', () => {
                        categoryIdInput.value = cat.id;
                        searchInput.value = cat.name;
                        dropdown.style.display = 'none';
                        fetchFeatures(cat.id);
                    });

                    dropdown.appendChild(item);
                });
            })
            .catch(err => {
                dropdown.innerHTML = 'Error loading categories.';
                dropdown.style.display = 'block';
                console.error('Error fetching categories:', err);
            });
    }

    async function fetchFeatures(categoryId) {
        if (!featuresContainer) return;
        featuresContainer.innerHTML = '';
        if (!categoryId) return;

        try {
            const response = await fetch(`../ajax/fetch_category_features.php?category_id=${encodeURIComponent(categoryId)}&product_id=<?= (int)$product['id'] ?>`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            if (!data.success) throw new Error(data.error || 'Unknown server error.');

            const features = data.features;
            if (!Array.isArray(features) || features.length === 0) {
                featuresContainer.innerHTML = `<div class="col-12"><p class="text-muted">No specifications found.</p></div>`;
                return;
            }

            features.forEach(feature => {
                const featureElement = document.createElement('div');
                featureElement.classList.add('col-12', 'col-md-4', 'px-2', 'my-2');

                const metadata = feature.metadata || {};
                let value = '';
                let selectedUnit = null;

                // Parse JSON value if possible
                if (feature.value) {
                    try {
                        const parsed = JSON.parse(feature.value);
                        if (parsed.value) value = parsed.value;
                        if (parsed.unit) selectedUnit = parsed.unit;
                        if (feature.data_type === 'range' && parsed.min && parsed.max) {
                            value = [parsed.min, parsed.max];
                        } else if (feature.data_type === 'multiselect' && parsed.values) {
                            value = parsed.values;
                        }
                    } catch (e) {
                        value = feature.value;
                    }
                }

                const units = feature.unit || [];
                const unitDropdown = units.length ? unitSelect(units, selectedUnit, feature.id) : '';

                let inputHtml = '';
                switch (feature.data_type) {
                    case 'range': {
                        const [valMin, valMax] = Array.isArray(value) ? value : ['', ''];
                        const min = metadata.min || '';
                        const max = metadata.max || '';
                        inputHtml = `
                            <label class="form-label">${feature.name}${feature.is_required ? ' *' : ''}</label>
                            <div class="row gx-1 d-flex justify-content-between">
                                <div class="col-4">
                                    <input class="form-control" type="number" step="any"
                                        name="features[${feature.id}][min]"
                                        value="${valMin}" placeholder="from ${min}" />
                                </div>
                                <div class="col-4">
                                    <input class="form-control" type="number" step="any"
                                        name="features[${feature.id}][max]"
                                        value="${valMax}" placeholder="to ${max}" />
                                </div>
                                ${unitDropdown ? `<div class="col-3">${unitDropdown}</div>` : ''}
                            </div>
                        `;
                        break;
                    }
                    case 'multiselect': {
                        const options = metadata.options || [];
                        const selectedValues = Array.isArray(value) ? value : [];
                        inputHtml = `
                            <label class="form-label">${feature.name}${feature.is_required ? ' *' : ''}</label>
                            <select class="form-select" name="features[${feature.id}][]" >
                                ${options.map(opt => `
                                    <option value="${opt}" ${selectedValues.includes(opt) ? 'selected' : ''}>
                                        ${opt}
                                    </option>
                                `).join('')}
                            </select>
                        `;
                        break;
                    }
                    case 'boolean':
                        inputHtml = `
                            <div class="form-check">
                                <input type="hidden" name="features[${feature.id}][value]" value="0">
                                <input class="form-check-input" type="checkbox" id="feature_${feature.id}"
                                    name="features[${feature.id}][value]" value="1"
                                    ${value === true || value === '1' || value === 1 ? 'checked' : ''}>
                                <label class="form-check-label" for="feature_${feature.id}">
                                    ${feature.name}${feature.is_required ? ' *' : ''}
                                </label>
                            </div>`;
                        break;
                    case 'TEXT':
                        inputHtml = `
                            <label class="form-label">${feature.name}${feature.is_required ? ' *' : ''}</label>
                            <textarea class="form-control py-1" name="features[${feature.id}]"
                                placeholder="${feature.name}" rows="1">${value}</textarea>`;
                        break;
                    default: {
                        const inputType = feature.data_type === 'decimal(15,7)' ? 'number' : 'text';
                        const inputName = feature.data_type === 'decimal(15,7)'
                            ? `features[${feature.id}][value]`
                            : `features[${feature.id}]`;
                        inputHtml = `
                            <label class="form-label">${feature.name}${feature.is_required ? ' *' : ''}</label>
                            <div class="input-group">
                                <input class="form-control" type="${inputType}" step="any"
                                    name="${inputName}" value="${value}" placeholder="${feature.name}" />
                                ${unitDropdown ? unitDropdown : ''}
                            </div>`;
                        break;
                    }
                }

                featureElement.innerHTML = inputHtml;
                featuresContainer.appendChild(featureElement);
            });

        } catch (err) {
            console.error('Error fetching features:', err);
            featuresContainer.innerHTML = `<div class="col-12"><p class="text-danger">Failed to load specifications.</p></div>`;
        }
    }

    function unitSelect(unitsArray, selectedUnit, featureId) {
        if (!unitsArray || !unitsArray.length) return '';
        const optionsHtml = unitsArray.map(u => {
            const isSelected = u.trim() === (selectedUnit || '').trim() ? 'selected' : '';
            return `<option value="${u}" ${isSelected}>${u}</option>`;
        }).join('');
        return `<select class="form-select" name="features[${featureId}][unit]">${optionsHtml}</select>`;
    }


    const debouncedRender = debounce(renderCategoryDropdown, 300);
    if (searchInput) {
        searchInput.addEventListener('input', e => {
            if (e.target.value.trim() !== '') {
                debouncedRender(e.target.value);
            } else if (dropdown) {
                dropdown.style.display = 'none';
            }
        });
    }

    document.addEventListener('click', (e) => {
        if ((searchInput && !searchInput.contains(e.target)) &&
            (dropdown && !dropdown.contains(e.target))) {
            if (dropdown) dropdown.style.display = 'none';
        }
    });

    <?php if (!empty($product['category_id'])): ?>
    fetch('../ajax/fetch_leaf_categories.php?id=<?= (int)$product['category_id'] ?>')
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                searchInput.value = data[0].name;
                fetchFeatures(data[0].id);
            } else {
                searchInput.value = '';
                categoryIdInput.value = '';
                featuresContainer.innerHTML = '<p class="text-muted small">The previous category was deleted. Please select a new one.</p>';
                renderCategoryDropdown('');
            }
        })
        .catch(err => {
            console.error('Error fetching initial category:', err);
            searchInput.value = '';
            categoryIdInput.value = '';
            featuresContainer.innerHTML = `<p class="text-danger small">Error loading the previous category: ${err.message}</p>`;
            renderCategoryDropdown('');
        });
    <?php else: ?>
    renderCategoryDropdown('');
    <?php endif; ?>
});

    </script>
    <!-- end search categories and fetch features -->



    <!-- sweet alerts -->
    <?php if (!empty($success) || !empty($errors)): ?>
    <script>
        // Combine all logic into a single script block
        const url = new URL(window.location.href);

        <?php if (!empty($success)): ?>
            // Display the success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: <?= json_encode($success) ?>,
                confirmButtonColor: '#3085d6'
            });
            // After displaying the message, clean the URL
            url.searchParams.delete('success');

        <?php elseif (!empty($errors)): ?>
            // Display the error message
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
            // After displaying the message, clean the URL
            url.searchParams.delete('errors');
        <?php endif; ?>

        // Update the URL in the browser without reloading the page
        history.replaceState(null, '', url);
    </script>
    <?php endif; ?>
    <!-- end sweet alerts -->