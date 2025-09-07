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

// The query is updated to join `stock_issues` with `product_lots` and `products`
// to pull all the detailed information for the new headers.
$query = "
    SELECT
        si.qty_issued,
        si.created_at,
        si.remarks,
        u1.nickname AS issued_by,
        u2.nickname AS issued_to,
        pl.x_code,
        pl.vrm_x_code,
        pl.date_code AS lot_date_code,
        p.part_number,
        p.mfg
    FROM stock_issues si
    JOIN users u1 ON si.user_id = u1.id
    JOIN users u2 ON si.issued_to = u2.id
    JOIN product_lots pl ON si.product_lot_id = pl.id
    JOIN products p ON pl.product_id = p.id
";

// Add filters to the query
if ($keyword) {
    // Search now includes fields from products and product_lots
    $conds[] = "(p.part_number LIKE ? OR p.mfg LIKE ? OR pl.x_code LIKE ? OR pl.vrm_x_code LIKE ? OR u1.nickname LIKE ? OR u2.nickname LIKE ?)";
    $kw = "%$keyword%";
    $params = array_fill(0, 6, $kw);
    $types .= str_repeat('s', 6);
}
if ($from) { $conds[] = "si.created_at >= ?"; $params[] = $from.' 00:00:00'; $types .= 's'; }
if ($to) { $conds[] = "si.created_at <= ?"; $params[] = $to.' 23:59:59'; $types .= 's'; }

$where = $conds ? 'WHERE '.implode(' AND ', $conds) : '';
$query .= $where;
$query .= " ORDER BY si.created_at DESC";

$stmt = $conn->prepare($query);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

// Define an array of pastel colors (ARGB format for Excel, hex for PDF)
$pastelColors = [
    'FFDED1E7',
    'FFD9EAD3',
    'FFF9D4BB',
    'FFD0E0F6',
    'FFFCE4D6',
    'FFD5D8DC',
    'FFFBE5F0',
    'FFC9CCC7'
];
$pdfColors = [
    '#DED1E7',
    '#D9EAD3',
    '#F9D4BB',
    '#D0E0F6',
    '#FCE4D6',
    '#D5D8DC',
    '#FBE5F0',
    '#C9CCC7'
];

// ---------------------------
// Export to Excel (using PhpSpreadsheet)
// ---------------------------
if ($format === 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the column headers
    $header = ['#', 'X-Code', 'P/N', 'VRM X-code', 'MFG', 'Date Code', 'Qty', 'Issued By', 'Issued To', 'Date', 'Comment'];
    $sheet->fromArray($header, NULL, 'A1');

    // Apply styling to each header cell individually with a different color
    $column_index = 0;
    foreach (range('A', 'K') as $columnID) {
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
            $row_index - 1,
            $r['x_code'],
            $r['part_number'],
            $r['vrm_x_code'],
            $r['mfg'],
            $r['lot_date_code'],
            $r['qty_issued'],
            $r['issued_by'],
            $r['issued_to'],
            $formattedDate,
            $r['remarks']
        ];
        $sheet->fromArray($data, NULL, 'A' . $row_index);
        $row_index++;
    }

    // Auto-size columns for better readability
    foreach (range('A', 'K') as $columnID) {
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
    $pdf->AddPage('L', 'A4');
    $pdf->SetFont('helvetica','',8);

    $headers = ['#', 'X-Code', 'P/N', 'VRM X-Code', 'MFG', 'Date Code', 'Qty', 'Issued By', 'Issued To', 'Date', 'Comment'];
    
    // Define an array of column widths for TCPDF to ensure alignment
    // These widths are a percentage of the total page width and should sum to 100
    $columnWidths = [
        '4%', '12%', '12%', '10%', '9%', '8%', '5%', '9%', '9%', '10%', '12%'
    ];

    $html = '<h3>Stock Out Report</h3><table border="1" cellpadding="4"><thead><tr>';
    $column_index = 0;
    foreach ($headers as $h) {
        $color = $pdfColors[$column_index % count($pdfColors)];
        $width = $columnWidths[$column_index];
        $html .= '<th style="background-color:' . $color . '; font-weight: bold; width:' . $width . ';">' . $h . '</th>';
        $column_index++;
    }
    $html .= '</tr></thead><tbody>';

    $row_count = 1;
    while ($r = $res->fetch_assoc()) {
        $html .= '<tr>
                    <td style="width:' . $columnWidths[0] . ';">' . $row_count . '</td>
                    <td style="width:' . $columnWidths[1] . ';">' . htmlspecialchars($r['x_code']) . '</td>
                    <td style="width:' . $columnWidths[2] . ';">' . htmlspecialchars($r['part_number']) . '</td>
                    <td style="width:' . $columnWidths[3] . ';">' . htmlspecialchars($r['vrm_x_code']) . '</td>
                    <td style="width:' . $columnWidths[4] . ';">' . htmlspecialchars($r['mfg']) . '</td>
                    <td style="width:' . $columnWidths[5] . ';">' . htmlspecialchars($r['lot_date_code']) . '</td>
                    <td style="width:' . $columnWidths[6] . ';">' . htmlspecialchars($r['qty_issued']) . '</td>
                    <td style="width:' . $columnWidths[7] . ';">' . htmlspecialchars($r['issued_by']) . '</td>
                    <td style="width:' . $columnWidths[8] . ';">' . htmlspecialchars($r['issued_to']) . '</td>
                    <td style="width:' . $columnWidths[9] . ';">' . htmlspecialchars($r['created_at']) . '</td>
                    <td style="width:' . $columnWidths[10] . ';">' . htmlspecialchars($r['remarks']) . '</td>
                 </tr>';
        $row_count++;
    }
    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('stock_issues_report.pdf','I');
    exit;
}

echo "Invalid format";
?>
