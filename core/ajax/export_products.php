<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

require_once '../db/db.php';

$format = $_GET['format'] ?? 'xlsx';
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

// Start the query with a join to the 'users' and 'categories' tables
$query = "SELECT 
              p.id, 
              p.part_number, 
              p.mfg, 
              p.tag, 
              p.qty, 
              u.nickname, 
              c.name AS category_name, 
              p.location, 
              p.status,
              p.date_code
          FROM products p
          LEFT JOIN users u ON p.user_id = u.id
          LEFT JOIN categories c ON p.category_id = c.id
          WHERE 1";

$params = [];
$types = '';

// Add search filter
if (!empty($search)) {
    $query .= " AND (p.part_number LIKE ? OR p.tag LIKE ?)";
    $kw = "%$search%";
    $params = array_merge($params, [$kw, $kw]);
    $types .= 'ss';
}

// Add status filter
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

// Define pastel colors for the headers
$excelColors = [
    'FFDED1E7', 'FFD9EAD3', 'FFF9D4BB', 'FFD0E0F6', 'FFFCE4D6',
    'FFD5D8DC', 'FFFBE5F0', 'FFC9CCC7', 'FFFAFAD2'
];
$pdfColors = [
    '#DED1E7', '#D9EAD3', '#F9D4BB', '#D0E0F6', '#FCE4D6',
    '#D5D8DC', '#FBE5F0', '#C9CCC7', '#FAFAD2'
];

// ---------------------------
// Export to Excel (using PhpSpreadsheet)
// ---------------------------
if ($format === 'excel' || $format === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the column headers
    $header = ['#', 'P/N', 'MFG', 'Tag', 'Qty', 'Submitter', 'Category', 'Location', 'Status'];
    $sheet->fromArray($header, NULL, 'A1');

    // Apply styling to each header cell with a different color
    $column_index = 0;
    foreach (range('A', 'I') as $columnID) {
        $color = $excelColors[$column_index % count($excelColors)];
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
    while ($row = $result->fetch_assoc()) {
        $data = [
            $row_index - 1,
            $row['part_number'],
            $row['mfg'],
            $row['tag'],
            $row['qty'],
            $row['nickname'],
            $row['category_name'],
            $row['location'],
            $row['status'],

        ];
        $sheet->fromArray($data, NULL, 'A' . $row_index);
        $row_index++;
    }

    // Auto-size columns for better readability
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Set HTTP headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="parts_export.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    
    exit;
}

// ---------------------------
// Export to PDF
// ---------------------------
elseif ($format === 'pdf') {
    require_once '../../tcpdf/tcpdf.php'; // TCPDF must be installed

    $pdf = new TCPDF();
    $pdf->AddPage('L', 'A4');
    $pdf->SetFont('helvetica', '', 8);

    $headers = ['#', 'P/N', 'MFG', 'Tag', 'Qty', 'Submitter', 'Category', 'Location', 'Status'];
    $columnWidths = ['4%', '16%', '10%', '10%', '5%', '10%', '19%', '10%', '6%'];

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
                    <td style="width:' . $columnWidths[3] . ';">' . htmlspecialchars($row['tag']) . '</td>
                    <td style="width:' . $columnWidths[4] . ';">' . htmlspecialchars($row['qty']) . '</td>
                    <td style="width:' . $columnWidths[5] . ';">' . htmlspecialchars($row['nickname']) . '</td>
                    <td style="width:' . $columnWidths[6] . ';">' . htmlspecialchars($row['category_name']) . '</td>
                    <td style="width:' . $columnWidths[7] . ';">' . htmlspecialchars($row['location']) . '</td>
                    <td style="width:' . $columnWidths[8] . ';">' . htmlspecialchars($row['status']) . '</td>
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