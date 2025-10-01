<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

require_once '../db/db.php';

$format = $_GET['format'] ?? 'xlsx';
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT 
              p.id, 
              p.part_number, 
              p.mfg, 
              p.qty, 
              u.nickname, 
              c.name AS category_name, 
              p.location, 
              p.status
          FROM products p
          LEFT JOIN users u ON p.user_id = u.id
          LEFT JOIN categories c ON p.category_id = c.id
          WHERE 1";

$params = [];
$types = '';

if (!empty($search)) {
    $query .= " AND (p.part_number LIKE ?)";
    $kw = "%$search%";
    $params[] = $kw;
    $types .= 's';
}

// Status filter
if (!empty($status)) {
    $query .= " AND p.status = ?";
    $params[] = $status;
    $types .= 's';
}

$query .= " ORDER BY p.id ASC";

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Pastel colors
$excelColors = [
    'FFDED1E7', 'FFD9EAD3', 'FFF9D4BB', 'FFD0E0F6',
    'FFFCE4D6', 'FFD5D8DC', 'FFFBE5F0', 'FFC9CCC7', 'FFFAFAD2'
];
$pdfColors = [
    '#DED1E7', '#D9EAD3', '#F9D4BB', '#D0E0F6',
    '#FCE4D6', '#D5D8DC', '#FBE5F0', '#C9CCC7', '#FAFAD2'
];

// ---------------------------
// Excel Export
// ---------------------------
if ($format === 'excel' || $format === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers (tag removed)
    $header = ['#', 'P/N', 'MFG', 'Qty', 'Submitter', 'Category', 'Location', 'Status'];
    $sheet->fromArray($header, NULL, 'A1');

    // Header styles
    $column_index = 0;
    foreach (range('A', 'H') as $columnID) {
        $color = $excelColors[$column_index % count($excelColors)];
        $sheet->getStyle($columnID . '1')
              ->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()
              ->setARGB($color);
        $sheet->getStyle($columnID . '1')->getFont()->setBold(true);
        $column_index++;
    }

    // Data
    $row_index = 2;
    while ($row = $result->fetch_assoc()) {
        $data = [
            $row_index - 1,
            $row['part_number'],
            $row['mfg'],
            $row['qty'],
            $row['nickname'],
            $row['category_name'],
            $row['location'],
            $row['status'],
        ];
        $sheet->fromArray($data, NULL, 'A' . $row_index);
        $row_index++;
    }

    // Auto-size
    foreach (range('A', 'H') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="parts_export.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// ---------------------------
// PDF Export
// ---------------------------
elseif ($format === 'pdf') {
    require_once '../../tcpdf/tcpdf.php';

    $pdf = new TCPDF();
    $pdf->AddPage('L', 'A4');
    $pdf->SetFont('helvetica', '', 8);

    // Headers 
    $headers = ['#', 'P/N', 'MFG', 'Qty', 'Submitter', 'Category', 'Location', 'Status'];
    $columnWidths = ['4%', '30%', '10%', '8%', '12%', '20%', '10%', '6%'];

    $html = '<h3>Part List Report</h3><table border="1" cellpadding="4"><thead><tr>';
    $column_index = 0;
    foreach ($headers as $h) {
        $color = $pdfColors[$column_index % count($pdfColors)];
        $width = $columnWidths[$column_index];
        $html .= '<th style="background-color:' . $color . '; font-weight: bold; width:' . $width . ';">' . $h . '</th>';
        $column_index++;
    }
    $html .= '</tr></thead><tbody>';
    
    $row_count = 1;
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
                    <td style="width:' . $columnWidths[0] . ';">' . $row_count . '</td>
                    <td style="width:' . $columnWidths[1] . ';">' . htmlspecialchars($row['part_number']) . '</td>
                    <td style="width:' . $columnWidths[2] . ';">' . htmlspecialchars($row['mfg']) . '</td>
                    <td style="width:' . $columnWidths[3] . ';">' . htmlspecialchars($row['qty']) . '</td>
                    <td style="width:' . $columnWidths[4] . ';">' . htmlspecialchars($row['nickname']) . '</td>
                    <td style="width:' . $columnWidths[5] . ';">' . htmlspecialchars($row['category_name']) . '</td>
                    <td style="width:' . $columnWidths[6] . ';">' . htmlspecialchars($row['location']) . '</td>
                    <td style="width:' . $columnWidths[7] . ';">' . htmlspecialchars($row['status']) . '</td>
                  </tr>';
        $row_count++;
    }

    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('products_report.pdf', 'I');
    exit;
}

echo "Invalid export format.";
?>