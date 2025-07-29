<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>stock issues list</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

</head>
<body>
   <div class="container">
     <h2 class="mb-4">Stock Issues Log</h2>
    <h3><a href="../auth/dashboard.php">dashboard</a> </h3>
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by product/user...">
  </div>
  <div class="col-md-2">
    <input type="date" id="fromDate" class="form-control">
  </div>
  <div class="col-md-2">
    <input type="date" id="toDate" class="form-control">
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary w-100" onclick="fetchIssues(1)">Search</button>
  </div>
  <div class="col-md-2">
    <button class="btn btn-success w-100 mb-2" onclick="exportIssues('excel')">Export Excel</button>
    <button class="btn btn-danger w-100" onclick="exportIssues('pdf')">Export PDF</button>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>#</th><th>Product</th><th>P/N</th><th>Tag</th>
        <th>Qty</th><th>Issued By</th><th>Issued To</th>
        <th>Date</th><th>Remarks</th>
      </tr>
    </thead>
    <tbody id="issuesTableBody"></tbody>
  </table>
</div>

<div id="pagination" class="mt-3"></div>

   </div>
<script>
function fetchIssues(page=1) {
  const k=document.getElementById('searchInput').value,
        f=document.getElementById('fromDate').value,
        t=document.getElementById('toDate').value;
  fetch(`../ajax/search_issues.php?keyword=${encodeURIComponent(k)}&from=${f}&to=${t}&page=${page}`)
    .then(r=>r.json()).then(data=>{
      document.getElementById('issuesTableBody').innerHTML=data.html;
      document.getElementById('pagination').innerHTML=data.pagination;
    });
}

function exportIssues(format) {
  const k=document.getElementById('searchInput').value,
        f=document.getElementById('fromDate').value,
        t=document.getElementById('toDate').value;
  window.open(`../ajax/export_issues.php?format=${format}&keyword=${encodeURIComponent(k)}&from_date=${f}&to_date=${t}`,'_blank');
}

document.addEventListener('DOMContentLoaded', ()=>fetchIssues());
</script>

</body>
</html>

