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

    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card shadow-sm p-4 mb-4">
                    <h3 class="card-title mb-4 d-flex align-items-center">
                        <svg width="22" height="22" fill="currentColor" class="bi bi-bricks mx-1 me-2" viewBox="0 0 16 16">
                            <path d="M0 .5A.5.5 0 0 1 .5 0h15a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5H2v-2H.5a.5.5 0 0 1-.5-.5v-3A.5.5 0 0 1 .5 6H2V4H.5a.5.5 0 0 1-.5-.5zM3 4v2h4.5V4zm5.5 0v2H13V4zM3 10v2h4.5v-2zm5.5 0v2H13v-2zM1 1v2h3.5V1zm4.5 0v2h5V1zm6 0v2H15V1zM1 7v2h3.5V7zm4.5 0v2h5V7zm6 0v2H15V7zM1 13v2h3.5v-2zm4.5 0v2h5v-2zm6 0v2H15v-2z"></path>
                        </svg>
                        Manage Categories
                    </h3>

                    <!-- Category Form -->
                    <form method="post" action="" id="category-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name:</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter category name" autocomplete="off" required>
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="search-category" class="form-label">Parent Category:</label>
                            <input type="text" id="search-category" class="form-control" autocomplete="off" placeholder="Search and select parent category">
                            <div id="category-tree" class="border rounded mt-2 p-2"></div>
                        </div>

                        <input type="hidden" name="parent_id" id="parent_id" value="">
                        <input type="hidden" name="action" id="action" value="add">
                        <input type="hidden" name="category_id" id="category_id" value="">
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" onclick="clearCategory()" class="btn btn-secondary">Clear</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                <hr>

                <!-- Category Tree Display -->
                <div class="card shadow-sm p-4">
                    <h3 class="mb-3">Category Tree</h3>
                    <div class="tree-display fixed-height-scroll root-tree">
                        <?php
                        // Function to build the tree structure
                        function displayTree($categories, $parentId = null, $depth = 0) {
                            $hasChildren = false;
                            foreach ($categories as $cat) {
                                if ($cat['parent_id'] == $parentId) {
                                    if (!$hasChildren) {
                                        echo "<ul>";
                                        $hasChildren = true;
                                    }
                                    echo "<li class='d-flex align-items-center mb-1 level-" . $depth . "' data-id='" . htmlspecialchars($cat['id']) . "'>";
                                    echo htmlspecialchars($cat['name']);
                                    echo "<span class='d-flex align-items-center ms-2'>";
                                    echo "<button type='button' class='action-icon' onclick=\"editCategory(" . $cat['id'] . ", '" . addslashes($cat['name']) . "', " . ($cat['parent_id'] ?? 'null') . ")\">";
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16" style="color: #ff8c00;"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4.793 8.914 3.207 7.328 8.914 1.621l1.586 1.586zM2 13l4-4 2.586 2.586L4.586 15z"/></svg>';
                                    echo "</button>";
                                    echo "<button type='button' class='action-icon' onclick=\"confirmDelete(" . $cat['id'] . ")\">";
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16" style="color: #dc3545;"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
                                    echo "</button>";
                                    echo "</span>";
                                    displayTree($categories, $cat['id'], $depth + 1);
                                    echo "</li>";
                                }
                            }
                            if ($hasChildren) {
                                echo "</ul>";
                            }
                        }
                        displayTree($allCategories);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

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