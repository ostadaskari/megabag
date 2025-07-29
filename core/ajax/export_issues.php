<?php
require_once '../db/db.php';
require_once '../auth/check_manager.php';

$format = $_GET['format'] ?? 'excel';
$keyword = $_GET['keyword'] ?? '';
$from = $_GET['from_date'] ?? '';
$to = $_GET['to_date'] ?? '';

$params = [];
$types = '';
$conds = [];

if ($keyword) {
    $conds[] = "(p.name LIKE ? OR p.tag LIKE ? OR p.part_number LIKE ? OR u1.name LIKE ? OR u1.family LIKE ? OR u1.nickname LIKE ? OR u2.name LIKE ? OR u2.family LIKE ? OR u2.nickname LIKE ?)";
    for ($i=0; $i<9; $i++) {
        $params[] = "%$keyword%"; $types .= 's';
    }
}
if ($from) { $conds[] = "si.created_at >= ?"; $params[] = $from.' 00:00:00'; $types .= 's'; }
if ($to) { $conds[] = "si.created_at <= ?"; $params[] = $to.' 23:59:59'; $types .= 's'; }
$where = $conds ? 'WHERE '.implode(' AND ', $conds) : '';

$query = "
  SELECT p.name, p.part_number, p.tag, si.qty_issued, u1.nickname AS issued_by,
         u2.nickname AS issued_to, si.created_at, si.remarks
  FROM stock_issues si
  JOIN products p ON si.product_id = p.id
  JOIN users u1 ON si.user_id = u1.id
  JOIN users u2 ON si.issued_to = u2.id
  $where
  ORDER BY si.created_at DESC
";
$stmt = $conn->prepare($query);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

if ($format === 'excel') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=stock_issues_export.csv');
    fprintf(fopen('php://output', 'w'), chr(0xEF).chr(0xBB).chr(0xBF));
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Product', 'Part No', 'Tag', 'Qty', 'Issued By', 'Issued To', 'Date', 'Remarks']);
    while ($r = $res->fetch_assoc()) {
        $dt = date('Y-m-d H:i', strtotime($r['created_at']));
        fputcsv($out, [
            $r['name'], $r['part_number'], $r['tag'], $r['qty_issued'],
            $r['issued_by'], $r['issued_to'], $dt, $r['remarks']
        ]);
    }
    fclose($out); exit;
} elseif ($format === 'pdf') {
    require_once '../../tcpdf/tcpdf.php';
    $pdf = new TCPDF();
    $pdf->AddPage(); $pdf->SetFont('helvetica','',10);
    $html = '<h3>Stock Issues Report</h3><table border="1" cellpadding="4"><thead><tr>'
        .'<th>Product</th><th>P/N</th><th>Tag</th><th>Qty</th><th>Issued By</th>'
        .'<th>Issued To</th><th>Date</th><th>Remarks</th></tr></thead><tbody>';
    while ($r = $res->fetch_assoc()) {
        $html .= '<tr><td>'.htmlspecialchars($r['name']).'</td><td>'.htmlspecialchars($r['part_number']).'</td>'
            .'<td>'.htmlspecialchars($r['tag']).'</td><td>'.$r['qty_issued'].'</td>'
            .'<td>'.htmlspecialchars($r['issued_by']).'</td><td>'.htmlspecialchars($r['issued_to']).'</td>'
            .'<td>'.$r['created_at'].'</td><td>'.htmlspecialchars($r['remarks']).'</td></tr>';
    }
    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('stock_issues_report.pdf','I');
    exit;
}

echo "Invalid format";
