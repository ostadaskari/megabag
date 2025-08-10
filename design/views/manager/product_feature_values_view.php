

<h2>Assign Feature Values to Product</h2>

<div>
    <label for="productSearch">Search Product:</label>
    <input type="text" id="productSearch" placeholder="Type to search...">
    <div id="productResults" style="border:1px solid #ccc; display:none;"></div>
</div>

<form method="POST" id="featureForm" style="margin-top:20px; display:none;">
    <input type="hidden" name="product_id" id="product_id">
    <div id="featuresContainer"></div>
    <button type="submit">Save Features</button>
</form>

<script>
const productSearch = document.getElementById('productSearch');
const productResults = document.getElementById('productResults');
const featuresContainer = document.getElementById('featuresContainer');
const featureForm = document.getElementById('featureForm');

productSearch.addEventListener('input', () => {
    const q = productSearch.value.trim();
    if (q.length < 2) {
        productResults.style.display = 'none';
        return;
    }
    fetch(`../manager/product_feature_values.php?search_product=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
            productResults.innerHTML = '';
            data.forEach(p => {
                const div = document.createElement('div');
                div.textContent = p.name;
                div.style.cursor = 'pointer';
                div.onclick = () => selectProduct(p.id, p.name);
                productResults.appendChild(div);
            });
            productResults.style.display = 'block';
        });
});

function selectProduct(id, name) {
    document.getElementById('product_id').value = id;
    productSearch.value = name;
    productResults.style.display = 'none';
    fetch(`../manager/product_feature_values.php?product_id=${id}`)
        .then(res => res.json())
        .then(features => {
            if (features.length === 0) {
                Swal.fire('No Features', 'This product category has no features', 'info');
                return;
            }
            featuresContainer.innerHTML = '';
            features.forEach(f => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label>${f.name} ${f.is_required ? '*' : ''}</label>
                    <input type="text" name="features[${f.id}][value]" placeholder="Enter value">
                    ${f.unit ? unitSelect(f.unit, f.id) : ''}
                `;
                featuresContainer.appendChild(div);
            });
            featureForm.style.display = 'block';
        });
}

function unitSelect(unitString, featureId) {
    const units = unitString.split(',');
    let html = `<select name="features[${featureId}][unit]">`;
    units.forEach(u => {
        html += `<option value="${u.trim()}">${u.trim()}</option>`;
    });
    html += '</select>';
    return html;
}

featureForm.addEventListener('submit', e => {
    e.preventDefault();
    fetch('product_feature_values.php', {
        method: 'POST',
        body: new FormData(featureForm)
    }).then(res => res.text())
      .then(html => document.body.insertAdjacentHTML('beforeend', html));
});
</script>

