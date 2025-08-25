    <!-- SweetAlert2 messages from URL parameters -->
    <?php if (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo htmlspecialchars($_GET['success']); ?>',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo htmlspecialchars($_GET['error']); ?>',
                icon: 'error',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>
    <div class="d-flex flex-row align-items-center justify-content-between titleTop">
        <h2 class="d-flex align-items-center">
        <svg width="22" height="22" fill="currentColor" class="bi bi-bricks mx-1 me-2" viewBox="0 0 16 16">
            <path d="M0 .5A.5.5 0 0 1 .5 0h15a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5H2v-2H.5a.5.5 0 0 1-.5-.5v-3A.5.5 0 0 1 .5 6H2V4H.5a.5.5 0 0 1-.5-.5zM3 4v2h4.5V4zm5.5 0v2H13V4zM3 10v2h4.5v-2zm5.5 0v2H13v-2zM1 1v2h3.5V1zm4.5 0v2h5V1zm6 0v2H15V1zM1 7v2h3.5V7zm4.5 0v2h5V7zm6 0v2H15V7zM1 13v2h3.5v-2zm4.5 0v2h5v-2zm6 0v2H15v-2z"></path>
        </svg>    
        Manage Categories</h2>
        <a href="../auth/dashboard.php?page=home" class="backBtn">
        <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
        </svg>
        <span>Back</span>
        </a>
    </div>
    <div class="container px-0 mt-2">
        <div class="row">
            <div class="col-12" style="z-index: 10;">
                <div class="container border rounded bg-light shadow-sm p-2 mb-2">
                    <!-- Category Form -->
                    <form class="row d-flex align-items-center justify-content-between" method="post" action="" id="category-form">
                        <div class="col-12 col-md-4">
                            <label for="name" class="form-label">Category Name:</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter category name" autocomplete="off" required>
                        </div>

                        <div class="col-12 col-md-4 position-relative">
                            <label for="search-category" class="form-label">Parent Category:</label>
                            <input type="text" id="search-category" class="form-control" autocomplete="off" placeholder="Search and select parent category">
                            <div id="category-tree" class="border rounded mt-2 p-2"></div>
                        </div>

                        <input type="hidden" name="parent_id" id="parent_id" value="">
                        <input type="hidden" name="action" id="action" value="add">
                        <input type="hidden" name="category_id" id="category_id" value="">
                        
                        <div class="col-12 col-md-3 mt-4 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mx-1">Submit</button>
                            <button type="button" onclick="clearCategory()" class="btn btn-secondary mx-1">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Category Tree Display -->
                <div class="card border rounded bg-light shadow-sm p-2" style="max-height: 75vh; overflow: auto;">
                <h3 class="mb-3">Category Tree</h3>
                <div>
                    <?php
                  function displayTreeFancy($categories, $parentId = null){
                      $hasChildren = false;
                      foreach ($categories as $cat) {
                          if ($cat['parent_id'] == $parentId) {
                              if (!$hasChildren) {
                                  echo "<ul class='tree'>";
                                  $hasChildren = true;
                              }
                  
                              
                              $hasSub = false;
                              foreach ($categories as $child) {
                                  if ($child['parent_id'] == $cat['id']) {
                                      $hasSub = true;
                                      break;
                                  }
                              }
                  
                              if ($hasSub) {
                                  echo "<li>";
                                  echo "<details>";
                                  echo "<summary class='d-flex align-items-center'>";
                                  echo "<span class='d-flex align-items-center'>";
                                  echo "<span class='folder-icon me-2 closed'>
                                          <svg width='22' height='22' fill='#ffd55f' viewBox='0 0 16 16'>
                                            <path d='M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3z'/>
                                          </svg>
                                        </span>";
                                  echo " <span class='folder-icon open d-none'>
                                            <svg width='22' height='22' fill='#ffd55f' class='bi bi-folder mr-2' viewBox='0 0 16 16'>
                                              <path d='M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139q.323-.119.684-.12h5.396z'/>
                                            </svg>
                                          </span>";       
                                  echo htmlspecialchars($cat['name']) . " <span class='text-muted ms-1'>(ID: " . $cat['id'] . ")</span>";
                                  echo "</span>";
                  
                                  
                                  echo "<span class='d-flex align-items-center ms-2'>";
                                  echo "<button type='button' class='action-icon btnSvg me-1' onclick=\"editCategory(" . $cat['id'] . ", '" . addslashes($cat['name']) . "', " . ($cat['parent_id'] ?? 'null') . ")\">";
                                  echo '<svg width="16" height="16" fill="#0780c7ff" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/></svg>';
                                  echo "</button>";
                                  echo "<button type='button' class='action-icon btnSvg' onclick=\"confirmDelete(" . $cat['id'] . ")\">";
                                  echo '<svg width="16" height="16" fill="#b81509ff" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
                                  echo "</button>";
                                  echo "</span>";
                  
                                  echo "</summary>";
                  
                                  displayTreeFancy($categories, $cat['id']);
                                  echo "</details>";
                                  echo "</li>";
                              } else {
                                  
                                  echo "<li class='d-flex align-items-center'>";
                                  echo "<i class='fa fa-file me-2'></i> " . htmlspecialchars($cat['name']) . " <span class='text-muted ms-1'>(ID: " . $cat['id'] . ")</span>";
                                  echo "<span class='d-flex align-items-center ms-2'>";
                                  echo "<button type='button' class='action-icon btnSvg me-1' onclick=\"editCategory(" . $cat['id'] . ", '" . addslashes($cat['name']) . "', " . ($cat['parent_id'] ?? 'null') . ")\">";
                                  echo '<svg width="16" height="16" fill="#0780c7ff" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/></svg>';
                                  echo "</button>";
                                  echo "<button type='button' class='action-icon btnSvg' onclick=\"confirmDelete(" . $cat['id'] . ")\">";
                                  echo '<svg width="16" height="16" fill="#b81509ff" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
                                  echo "</button>";
                                  echo "</span>";
                                  echo "</li>";
                              }
                          }
                      }
                      if ($hasChildren) {
                          echo "</ul>";
                      }
                  }
                  
                  
                    displayTreeFancy($allCategories);
                    ?>
                </div>
                </div>

            </div>
        </div>
    </div>



    <!-- script for open or close folder in tree -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".tree details").forEach((det) => {
            det.addEventListener("toggle", function () {
            const summary = this.querySelector("summary");
            if (this.open) {
                summary.querySelector(".folder-icon.closed").classList.add("d-none");
                summary.querySelector(".folder-icon.open").classList.remove("d-none");
            } else {
                summary.querySelector(".folder-icon.closed").classList.remove("d-none");
                summary.querySelector(".folder-icon.open").classList.add("d-none");
            }
            });
        });
        });
        </script>

    <!-- JavaScript functions -->  
    <script>
        // The full list of categories is loaded once from PHP
        let allCategories = <?= json_encode($allCategories) ?>;
        const treeContainer = document.getElementById('category-tree');
        const searchInput = document.getElementById('search-category');
        const categoryForm = document.getElementById('category-form');
        
        /**
         * Builds a full nested tree structure from a flat array of categories.
         * This function now only builds the tree, it doesn't filter.
         * @param {Array} data The flat array of categories to build from.
         * @param {number|null} parentId The parent ID to start with.
         * @returns {HTMLElement} The <ul> element representing the tree.
         */
        function buildTree(data, parentId = null) {
            const ul = document.createElement('ul');
            data.filter(cat => (cat.parent_id == parentId))
                .forEach(cat => {
                    const li = document.createElement('li');
                    li.textContent = cat.name;
                    li.dataset.id = cat.id;
                    li.dataset.name = cat.name;

                    li.onclick = function (e) {
                        e.stopPropagation();
                        document.getElementById('parent_id').value = cat.id;
                        document.getElementById('search-category').value = cat.name;
                        highlightSelected(li);
                        treeContainer.style.display = 'none';
                    };

                    const children = buildTree(data, cat.id);
                    if (children.children.length > 0) {
                        li.appendChild(children);
                    }
                    ul.appendChild(li);
                });
            return ul;
        }

        /**
         * Filters the already-built category tree based on a search query.
         * It shows matching items and their ancestors.
         * @param {string} query The search term.
         */
        function filterTree(query) {
            const listItems = treeContainer.querySelectorAll('li');
            const lowerCaseQuery = query.toLowerCase();

            if (query.trim() === '') {
                // If query is empty, show all items
                listItems.forEach(li => li.style.display = '');
            } else {
                let hasMatches = false;
                listItems.forEach(li => {
                    const text = li.textContent.toLowerCase();
                    const isMatch = text.includes(lowerCaseQuery);

                    li.style.display = 'none'; // Hide all list items initially
                    if (isMatch) {
                        li.style.display = ''; // Show matches
                        let parent = li.closest('ul').closest('li');
                        while (parent) {
                            parent.style.display = ''; // Show all ancestors of the match
                            parent = parent.closest('ul').closest('li');
                        }
                        hasMatches = true;
                    }
                });

                // Instead of showing an error, the list will simply be empty
                // if no results are found, which is a cleaner user experience.
            }
        }

        function editCategory(id, name, parentId) {
            document.getElementById('name').value = name;
            document.getElementById('parent_id').value = parentId;
            document.getElementById('action').value = 'edit';
            document.getElementById('category_id').value = id;

            const parentCategory = allCategories.find(cat => cat.id == parentId);
            document.getElementById('search-category').value = parentCategory ? parentCategory.name : '';

            highlightSelected(null);
            if (parentId) {
                const parentLi = document.querySelector(`.root-tree li[data-id='${parentId}']`);
                if (parentLi) {
                    highlightSelected(parentLi);
                }
            }
        }

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This category and its subcategories will be removed permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';

                    const inputAction = document.createElement('input');
                    inputAction.type = 'hidden';
                    inputAction.name = 'action';
                    inputAction.value = 'delete';

                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'category_id';
                    inputId.value = id;

                    form.appendChild(inputAction);
                    form.appendChild(inputId);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function highlightSelected(selectedLi) {
            document.querySelectorAll('#category-tree li').forEach(li => {
                li.classList.remove('selected');
            });
            if (selectedLi) {
                selectedLi.classList.add('selected');
            }
        }

        function clearCategory() {
            document.getElementById('name').value = '';
            document.getElementById('search-category').value = '';
            document.getElementById('parent_id').value = '';
            document.getElementById('action').value = 'add';
            document.getElementById('category_id').value = '';
            highlightSelected(null);
            treeContainer.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Build the initial tree for the search box from all categories
            const initialTree = buildTree(allCategories);
            treeContainer.appendChild(initialTree);

            // Event listener for input on the search box
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();
                treeContainer.style.display = 'block'; // Show the tree on input
                filterTree(query);
            });
            
            // Show the full tree when the input is focused
            searchInput.addEventListener('focus', function () {
                treeContainer.style.display = 'block';
                filterTree(this.value.trim()); // Filter with current value
            });

            // Hide the tree when clicking outside the input and tree container
            document.addEventListener('click', (event) => {
                if (!searchInput.contains(event.target) && !treeContainer.contains(event.target)) {
                    treeContainer.style.display = 'none';
                }
            });

            // Form validation for unique category names
            categoryForm.addEventListener('submit', function(event) {
                const newName = document.getElementById('name').value.trim();
                const action = document.getElementById('action').value;
                const currentId = document.getElementById('category_id').value;

                // Check for duplicate name
                const isDuplicate = allCategories.some(cat => {
                    // For editing, a category can have its own name, so we exclude it from the check
                    if (action === 'edit' && cat.id == currentId) {
                        return false;
                    }
                    return cat.name.toLowerCase() === newName.toLowerCase();
                });

                if (isDuplicate) {
                    event.preventDefault(); // Stop form submission
                    Swal.fire({
                        title: 'Error!',
                        text: 'A category with this name already exists. Please choose a unique name.',
                        icon: 'error',
                        timer: 5000,
                        showConfirmButton: true
                    });
                }
            });
        });

    </script>