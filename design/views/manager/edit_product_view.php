
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h2>Edit Product</h2>
    <a href="products_list.php">← Back to Product List</a>
        <h3><a href="../auth/dashboard.php">dashboard</a></h3>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">

        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>

        <label>Tag:</label>
        <input type="text" name="tag" value="<?= htmlspecialchars($product['tag']) ?>"><br>

        <label>Part Number (P-N):</label>
        <input type="text" name="p_n" value="<?= htmlspecialchars($product['part_number']) ?>"><br>

        <label>MFG:</label>
        <input type="text" name="MFG" value="<?= htmlspecialchars($product['mfg']) ?>"><br>

        <label>Quantity:</label>
        <input type="number" name="qty" value="<?= htmlspecialchars($product['qty']) ?>" min="0"><br>

        <label>Company Comment:</label><br>
        <textarea name="company_cmt" rows="4" cols="50"><?= htmlspecialchars($product['company_cmt']) ?></textarea><br>

        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($product['location']) ?>"><br>

        <label>status:</label>
        <input type="text" name="status" value="<?= htmlspecialchars($product['status']) ?>"><br>

        <label for="date_code">Date Code:</label>
        <select name="date_code" id="date_code" required>
            <option value="2024" <?= $product['date_code'] == '2024' ? 'selected' : '' ?>>2024</option>
            <option value="2024+" <?= $product['date_code'] == '2024+' ? 'selected' : '' ?>>2024+</option>
        </select>
       <br>
        
    

        <label>Receive Code:</label>
        <input type="text" name="recieve_code" value="<?= htmlspecialchars($product['recieve_code']) ?>"><br>

        <label for="category">Category (only last-level):</label>
        <input type="text" id="category_search" placeholder="Search category..." autocomplete="off" style="width: 300px; padding: 8px;">
        <input type="hidden" name="category_id" id="category_id" value="<?= htmlspecialchars($product['category_id']) ?>">
        <div id="category_dropdown" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none; background: #f9f9f9;"></div>
        <br>

        <label>Upload New Images (max 20MB each):</label>
        <input type="file" name="images[]" multiple accept="image/*"><br>

        <label>Upload New PDFs (max 20MB each):</label>
        <input type="file" name="pdfs[]" multiple accept="application/pdf"><br>

        <button type="submit">Save Changes</button>
    </form>

    <hr>
    <h3>Current Images</h3>
    <?php if (!empty($images)): ?>
        <ul>
            <?php foreach ($images as $img): ?>
                <li>
                    <img src="<?= htmlspecialchars($img['file_path']) ?>" width="100">
                    <button onclick="deleteFile('image', <?= $img['id'] ?>)">🗑</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No images uploaded.</p>
    <?php endif; ?>

    <h3>Current PDFs</h3>
    <?php if (!empty($pdfs)): ?>
        <ul>
            <?php foreach ($pdfs as $pdf): ?>
                <li>
                    <a href="<?= htmlspecialchars($pdf['file_path']) ?>" target="_blank">
                        <?= htmlspecialchars($pdf['file_name']) ?>
                    </a>
                    <button onclick="deleteFile('pdf', <?= $pdf['id'] ?>)">🗑</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No PDFs uploaded.</p>
    <?php endif; ?>



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
                    fetch('delete_file.php', {
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
                fetch('../../core/ajax/fetch_leaf_categories.php?search=' + encodeURIComponent(search))
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
            fetch('../../core/ajax/fetch_leaf_categories.php?id=<?= (int)$product['category_id'] ?>')
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
</body>
</html>
