<?php
require_once '../db/db.php';


$format = $_GET['format'] ?? 'excel';
$keyword = $_GET['keyword'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// Base query
$query = "SELECT s.*, u.nickname, p.name, p.part_number, p.tag 
          FROM stock_receipts s
          JOIN users u ON s.user_id = u.id
          JOIN products p ON s.product_id = p.id
          WHERE 1";

// Add filters
$params = [];
$types = '';

if (!empty($keyword)) {
    $query .= " AND (p.name LIKE ? OR p.tag LIKE ? OR p.part_number LIKE ? OR u.name LIKE ? OR u.family LIKE ? OR u.nickname LIKE ?)";
    $kw = "%$keyword%";
    $params = array_fill(0, 6, $kw);
    $types .= str_repeat('s', 6);
}

if (!empty($from_date)) {
    $query .= " AND s.created_at >= ?";
    $params[] = $from_date;
    $types .= 's';
}

if (!empty($to_date)) {
    $query .= " AND s.created_at <= ?";
    $params[] = $to_date;
    $types .= 's';
}

$query .= " ORDER BY s.created_at DESC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// ---------------------------
// Export to CSV (Excel style)
// ---------------------------
if ($format === 'excel') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=receipts_export.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Product Name', 'Part Number', 'Tag', 'Qty', 'User', 'Date', 'Remarks']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['name'],
            $row['part_number'],
            $row['tag'],
            $row['qty_received'],
            $row['nickname'],
            $row['created_at'],
            $row['remarks']
        ]);
    }

    fclose($output);
    exit;
}

// ---------------------------
// Export to PDF (optional)
// ---------------------------
elseif ($format === 'pdf') {
    require_once '../../tcpdf/tcpdf.php'; // TCPDF must be installed without composer

    $pdf = new TCPDF();

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $html = '<h3>Stock Receipt Report</h3><table border="1" cellpadding="4"><thead><tr>
            <th>Product Name</th><th>Part No.</th><th>Tag</th><th>Qty</th>
            <th>User</th><th>Date</th><th>Remarks</th></tr></thead><tbody>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>' . htmlspecialchars($row['name']) . '</td>
            <td>' . htmlspecialchars($row['part_number']) . '</td>
            <td>' . htmlspecialchars($row['tag']) . '</td>
            <td>' . $row['qty_received'] . '</td>
            <td>' . htmlspecialchars($row['nickname']) . '</td>
            <td>' . $row['created_at'] . '</td>
            <td>' . htmlspecialchars($row['remarks']) . '</td>
        </tr>';
    }

    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('receipts_report.pdf', 'I');
    exit;
}

echo "Invalid export format.";
