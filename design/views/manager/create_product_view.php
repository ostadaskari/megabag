<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    <style>
        form { max-width: 600px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input, textarea, select { width: 100%; padding: 6px; margin-top: 5px; }
        .alert { padding: 10px; margin: 10px 0; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<h2>Create New Product</h2>
<h3><a href="../auth/dashboard.php">dashboard</a></h3>
<?php if (!empty($success)): ?>
    <div class="alert success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Name:
        <input type="text" name="name" required>
    </label>

    <label>Part Number:
        <input type="text" name="pn">
    </label>

    <label>MFG:
        <input type="text" name="mfg">
    </label>

    <label>Quantity:
        <input type="number" name="qty" min="0">
    </label>

    <label>Company Comment:
        <textarea name="company_cmt"></textarea>
    </label>

                <!-- categories -->
       <input type="text" id="category_search" placeholder="Search categories...">
<input type="hidden" name="category_id" id="category_id">
<ul id="category-dropdown" style="border:1px solid #ccc; max-height:150px; overflow-y:auto; list-style:none; padding:0;"></ul>


    <label>Location:
        <input type="text" name="location">
    </label>

    <label>Status:
        <input type="text" name="status">
    </label>

    <label>Tag:
        <input type="text" name="tag">
    </label>

    <label>Date Code:
        <select name="date_code">
                <option value="2024+">2024+</option>
                <option value="2024">2024</option>
        </select>
    </label>

    <label>Receive Code:
        <input type="text" name="recieve_code">
    </label>

    <label>Upload Images (multiple, max 20MB each):
        <input type="file" name="images[]" multiple accept="image/*">
    </label>

    <label>Upload PDFs (multiple, max 20MB each):
        <input type="file" name="pdfs[]" multiple accept="application/pdf">
    </label>

    <button type="submit">Submit Product</button>
</form>


<script>
let categories = [];

async function fetchCategories() {
    const response = await fetch('../../core/ajax/fetch_leaf_categories.php');
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



</body>
</html>
