<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    #category-tree ul {
        padding-left: 20px;
        border-left: 1px dashed #ccc;
    }
    #category-tree li:hover {
        background-color: #eef;
    }
</style>

</head>
<body>
    
<h2>Manage Categories</h2>
<h3><a href="../auth/dashboard.php">dashboard</a></h3>
<!-- Category Form -->
<form method="post" action="" id="category-form" style="margin-bottom: 30px; padding: 15px; border: 1px solid #ccc; border-radius: 8px; width: 400px;">
    <label for="name"><strong>Category Name:</strong></label><br>
    <input type="text" name="name" id="name" placeholder="Category Name" required style="width: 100%; padding: 8px; margin-bottom: 15px;">

    <label for="search-category"><strong>Parent Category:</strong></label><br>
    <input type="text" id="search-category" placeholder="Search category..." style="width: 100%; padding: 8px; margin-bottom: 5px;" readonly>
    <div id="category-tree" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 5px; background: #f9f9f9; border-radius: 5px; margin-bottom: 15px;"></div>

    <input type="hidden" name="parent_id" id="parent_id" value="">

    <input type="hidden" name="action" id="action" value="add">
    <input type="hidden" name="category_id" id="category_id" value="">
    <button type="submit" style="padding: 10px 20px; background-color: #007BFF; border: none; color: white; border-radius: 5px;">Submit</button>
    <button type="button" onclick="clearCategory()" style="margin-top: 5px; padding: 5px 10px;">Clear Selection</button>
</form>


<hr>

<!-- Category Tree -->
<h3>Category Tree</h3>
<ul>
    <?php
    function displayTree($categories, $parent_id = null) {
        echo "<ul>";
        foreach ($categories as $cat) {
            if ($cat['parent_id'] == $parent_id) {
                echo "<li>";
                echo htmlspecialchars($cat['name']);
                echo " 
                    <button onclick=\"editCategory(" . $cat['id'] . ", '" . addslashes($cat['name']) . "', " . ($cat['parent_id'] ?? "''") . ")\">✏️</button> 
                    <button onclick=\"confirmDelete(" . $cat['id'] . ")\">🔥</button>";
                displayTree($categories, $cat['id']);
                echo "</li>";
            }
        }
        echo "</ul>";
    }
    displayTree($allCategories);
    ?>
</ul>

<script>
function editCategory(id, name, parentId) {
    document.getElementById('name').value = name;
    document.getElementById('parent_id').value = parentId;
    document.getElementById('action').value = 'edit';
    document.getElementById('category_id').value = id;
}

function confirmDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This category and its subcategories will be removed.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit delete form via POST
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
</script>


<script>
    const categories = <?= json_encode($allCategories) ?>;
</script>
<script>
function buildTree(data, parentId = null) {
    const ul = document.createElement('ul');
    ul.style.listStyleType = 'none';
    ul.style.paddingLeft = '20px';

    data.filter(cat => cat.parent_id == parentId)
        .forEach(cat => {
            const li = document.createElement('li');
            li.textContent = cat.name;
            li.style.cursor = 'pointer';
            li.style.padding = '2px 5px';
            li.dataset.id = cat.id;
            li.dataset.name = cat.name;

            li.onclick = function (e) {
                e.stopPropagation();
                document.getElementById('parent_id').value = cat.id;
                document.getElementById('search-category').value = cat.name;
                highlightSelected(li);
            };

            const children = buildTree(data, cat.id);
            if (children.children.length > 0) {
                li.appendChild(children);
            }

            ul.appendChild(li);
        });

    return ul;
}

function highlightSelected(selectedLi) {
    document.querySelectorAll('#category-tree li').forEach(li => {
        li.style.background = '';
        li.style.fontWeight = '';
    });
    selectedLi.style.background = '#d0f0ff';
    selectedLi.style.fontWeight = 'bold';
}

function filterTree(term) {
    const nodes = document.querySelectorAll('#category-tree li');
    nodes.forEach(li => {
        const matches = li.textContent.toLowerCase().includes(term.toLowerCase());
        li.style.display = matches ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const treeContainer = document.getElementById('category-tree');
    treeContainer.innerHTML = '';
    treeContainer.appendChild(buildTree(categories));

    // Enable manual clearing
    document.getElementById('search-category').addEventListener('click', function () {
        this.value = '';
        document.getElementById('parent_id').value = '';
        highlightSelected(null);
        filterTree('');
    });

    document.getElementById('search-category').addEventListener('input', function () {
        filterTree(this.value);
    });
});
</script>

<script>
function clearCategory() {
    document.getElementById('search-category').value = '';
    document.getElementById('parent_id').value = '';
    highlightSelected(null);
    filterTree('');
}
</script>


</body>
</html>





