<?php
// This file exports stock issue data to an Excel or PDF file.
// composer require phpoffice/phpspreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
    SELECT p.name, p.mfg, p.part_number, p.tag, si.qty_issued, u1.nickname AS issued_by,
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

// ---------------------------
// Export to Excel (using PhpSpreadsheet)
// ---------------------------
if ($format === 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the column headers
    $header = [ 'Part No', 'MFG', 'Tag', 'Qty', 'Issued By', 'Issued To', 'Date', 'P-Name', 'Comment'];
    $sheet->fromArray($header, NULL, 'A1');

    // Define an array of pastel colors (ARGB format)
    $pastelColors = [
        'FFDED1E7', // 
        'FFD9EAD3', // Light Green
        'FFC9CCC7', // 
        'FFF9D4BB', // Light Peach
        'FFD0E0F6', // Light Blue
        'FFFCE4D6', // Light Orange
        'FFD5D8DC', // Light Gray
        'FFFBE5F0', // Light Pink
        'FFC9CCC7'  // Pastel Gray
    ];

    // Apply styling to each header cell individually with a different color
    $column_index = 0;
    foreach (range('A', 'I') as $columnID) {
        $color = $pastelColors[$column_index % count($pastelColors)];
        $sheet->getStyle($columnID . '1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($color);
        $sheet->getStyle($columnID . '1')->getFont()->setBold(true);
        $column_index++;
    }

    // Get the data and add it to the spreadsheet
    $row_index = 2;
    while ($r = $res->fetch_assoc()) {
        $formattedDate = date('Y-m-d H:i', strtotime($r['created_at']));
        $data = [
            $r['part_number'],
            $r['mfg'],
            $r['tag'],
            $r['qty_issued'],
            $r['issued_by'],
            $r['issued_to'],
            $formattedDate,
            $r['name'],
            $r['remarks']
        ];
        $sheet->fromArray($data, NULL, 'A' . $row_index);
        $row_index++;
    }

    // Auto-size columns for better readability
    foreach (range('A', 'H') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Set HTTP headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="stock_issues_export.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    
    exit;
}
// ---------------------------
// Export to PDF
// ---------------------------
elseif ($format === 'pdf') {
    require_once '../../tcpdf/tcpdf.php';
    $pdf = new TCPDF();
    $pdf->AddPage(); $pdf->SetFont('helvetica','',10);
    $html = '<h3>Stock Out Report</h3><table border="1" cellpadding="4"><thead><tr>'
        .'<th>P/N</th><th>MFG</th><th>Tag</th><th>Qty</th><th>Issued By</th>'
        .'<th>Issued To</th><th>Date</th><th>P-Name</th><th>Comment</th></tr></thead><tbody>';
    while ($r = $res->fetch_assoc()) {
        $html .= '<tr><td>'.htmlspecialchars($r['part_number']).'</td><td>'.$r['mfg'].'</td>'
            .'<td>'.htmlspecialchars($r['tag']).'</td><td>'.$r['qty_issued'].'</td>'
            .'<td>'.htmlspecialchars($r['issued_by']).'</td><td>'.htmlspecialchars($r['issued_to']).'</td>'
            .'<td>'.$r['created_at'].'</td><td>'.htmlspecialchars($r['name']).'</td><td>'.htmlspecialchars($r['remarks']).'</td></tr>';
    }
    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('stock_issues_report.pdf','I');
    exit;
}

echo "Invalid format";
?>
