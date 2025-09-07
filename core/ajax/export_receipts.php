<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

require_once '../db/db.php';


$format = $_GET['format'] ?? 'excel';
$keyword = $_GET['keyword'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// The query is now updated to join `stock_receipts` with `product_lots` and `products`
// to pull all the detailed information from the new schema.
$query = "SELECT 
             sr.id, 
             sr.qty_received, 
             sr.remarks, 
             sr.created_at, 
             u.nickname,
             pl.x_code, 
             pl.vrm_x_code, 
             pl.date_code AS lot_date_code,
             pl.lot_location,
             pl.project_name,
             pl.qty_received AS initial_qty,
             pl.qty_available AS available_qty,
             p.part_number, 
             p.mfg
           FROM stock_receipts sr
           JOIN users u ON sr.user_id = u.id
           JOIN product_lots pl ON sr.product_lot_id = pl.id
           JOIN products p ON pl.product_id = p.id
           WHERE 1";

// Add filters
$params = [];
$types = '';
//search section
if (!empty($keyword)) {
    // Search now includes fields from products and product_lots
    $query .= " AND (p.part_number LIKE ? OR p.mfg LIKE ? OR pl.x_code LIKE ? OR u.nickname LIKE ?)";
    $kw = "%$keyword%";
    $params = array_fill(0, 4, $kw);
    $types .= str_repeat('s', 4);
}
//filter date from
if (!empty($from_date)) {
    $query .= " AND sr.created_at >= ?";
    $params[] = $from_date;
    $types .= 's';
}
//filter date to 
if (!empty($to_date)) {
    $query .= " AND sr.created_at <= ?";
    $params[] = $to_date;
    $types .= 's';
}

$query .= " ORDER BY sr.created_at DESC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Define an array of pastel colors (ARGB format for Excel, hex for PDF)
$excelColors = [
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

if ($format === 'excel' || $format === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the column headers to match the new requested format
    $header = [
        '#',
        'X-Code',
        'P/N',
        'MFG',
        'Date Code',
        'Lot Location',
        'Project Name',
        'VRM X-Code',
        'Initial QTY',
        'Available QTY',
        'User',
        'Comment'
    ];
    $sheet->fromArray($header, NULL, 'A1');

    // Apply styling to each header cell individually with a different color
    $column_index = 0;
    foreach (range('A', 'L') as $columnID) {
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
        $formattedDate = date('Y-m-d H:i', strtotime($row['created_at']));
        $data = [
            $row_index - 1,
            $row['x_code'],
            $row['part_number'],
            $row['mfg'],
            $row['lot_date_code'],
            $row['lot_location'],
            $row['project_name'],
            $row['vrm_x_code'],
            $row['initial_qty'],
            $row['available_qty'],
            $row['nickname'],
            $row['remarks']
        ];
        $sheet->fromArray($data, NULL, 'A' . $row_index);
        $row_index++;
    }

    // Auto-size columns for better readability
    foreach (range('A', 'L') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Set HTTP headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="receipts_export.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    
    exit;
}

// ---------------------------
// Export to PDF
// ---------------------------
elseif ($format === 'pdf') {
    require_once '../../tcpdf/tcpdf.php'; // TCPDF must be installed without composer

    $pdf = new TCPDF();

    $pdf->AddPage('L', 'A4'); // Change page orientation to landscape for more columns
    $pdf->SetFont('helvetica', '', 8);

    $headers = ['#', 'X-Code', 'P/N', 'MFG', 'Date Code', 'Lot Location', 'Project Name', 'VRM X-Code', 'Initial QTY', 'Available QTY', 'User', 'Comment'];

    // Define an array of column widths for TCPDF to ensure alignment
    // These widths are a percentage of the total page width and should sum to 100
    $columnWidths = [
        '4%', '12%', '12%', '8%', '8%', '9%', '10%', '10%', '5%', '5%', '8%', '9%'
    ];

    // Start building the HTML with the table and colored headers
    $html = '<h3>Stock In Report</h3><table border="1" cellpadding="4"><thead><tr>';
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
                     <td style="width:' . $columnWidths[1] . ';">' . htmlspecialchars($row['x_code']) . '</td>
                     <td style="width:' . $columnWidths[2] . ';">' . htmlspecialchars($row['part_number']) . '</td>
                     <td style="width:' . $columnWidths[3] . ';">' . htmlspecialchars($row['mfg']) . '</td>
                     <td style="width:' . $columnWidths[4] . ';">' . htmlspecialchars($row['lot_date_code']) . '</td>
                     <td style="width:' . $columnWidths[5] . ';">' . htmlspecialchars($row['lot_location']) . '</td>
                     <td style="width:' . $columnWidths[6] . ';">' . htmlspecialchars($row['project_name']) . '</td>
                     <td style="width:' . $columnWidths[7] . ';">' . htmlspecialchars($row['vrm_x_code']) . '</td>
                     <td style="width:' . $columnWidths[8] . ';">' . htmlspecialchars($row['initial_qty']) . '</td>
                     <td style="width:' . $columnWidths[9] . ';">' . htmlspecialchars($row['available_qty']) . '</td>
                     <td style="width:' . $columnWidths[10] . ';">' . htmlspecialchars($row['nickname']) . '</td>
                     <td style="width:' . $columnWidths[11] . ';">' . htmlspecialchars($row['remarks']) . '</td>
                  </tr>';
        $row_count++;
    }

    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('receipts_report.pdf', 'I');
    exit;
}

echo "Invalid export format.";
?>
