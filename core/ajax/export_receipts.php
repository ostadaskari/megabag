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

// Base query
$query = "SELECT s.*, u.nickname, p.name, p.mfg, p.part_number, p.tag 
          FROM stock_receipts s
          JOIN users u ON s.user_id = u.id
          JOIN products p ON s.product_id = p.id
          WHERE 1";

// Add filters
$params = [];
$types = '';
//search section
if (!empty($keyword)) {
    $query .= " AND (p.name LIKE ? OR p.tag LIKE ? OR p.part_number LIKE ? OR u.name LIKE ? OR u.family LIKE ? OR u.nickname LIKE ?)";
    $kw = "%$keyword%";
    $params = array_fill(0, 6, $kw);
    $types .= str_repeat('s', 6);
}
//filter date from
if (!empty($from_date)) {
    $query .= " AND s.created_at >= ?";
    $params[] = $from_date;
    $types .= 's';
}
//filter date to 
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
// Export to Excel (using PhpSpreadsheet)
// ---------------------------

if ($format === 'excel' || $format === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the column headers
    $header = [ 'Part Number', 'MFG', 'Tag', 'Qty', 'User', 'Date', 'P-Name', 'Comment'];
    $sheet->fromArray($header, NULL, 'A1');

    // Define an array of pastel colors (ARGB format)
    $pastelColors = [
        'FFDED1E7', // 
        'FFD9EAD3', // Light Green
        'FFF9D4BB', // Light Peach
        'FFD0E0F6', // Light Blue
        'FFFCE4D6', // Light Orange
        'FFD5D8DC', // Light Gray
        'FFFBE5F0', // Light Pink
        'FFC9CCC7'  // Pastel Gray
    ];

    // Apply styling to each header cell individually with a different color
    $column_index = 0;
    foreach (range('A', 'H') as $columnID) {
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
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date('Y-m-d H:i', strtotime($row['created_at']));
        $data = [

            $row['part_number'],
            $row['mfg'],
            $row['tag'],
            $row['qty_received'],
            $row['nickname'],
            $formattedDate,
            $row['name'],
            $row['remarks']
        ];
        $sheet->fromArray($data, NULL, 'A' . $row_index);
        $row_index++;
    }

    // Auto-size columns for better readability
    foreach (range('A', 'G') as $columnID) {
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

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $html = '<h3>Stock In Report</h3><table border="1" cellpadding="4"><thead><tr>
             <th>Part No.</th><th>MFG</th><th>Tag</th><th>Qty</th>
             <th>User</th><th>Date</th><th>P-Name</th><th>Remarks</th></tr></thead><tbody>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
             <td>' . htmlspecialchars($row['part_number']) . '</td>
             <td>' . htmlspecialchars($row['mfg']) . '</td>
             <td>' . htmlspecialchars($row['tag']) . '</td>
             <td>' . $row['qty_received'] . '</td>
             <td>' . htmlspecialchars($row['nickname']) . '</td>
             <td>' . $row['created_at'] . '</td>
             <td>' . htmlspecialchars($row['name']) . '</td>
             <td>' . htmlspecialchars($row['remarks']) . '</td>
         </tr>';
    }

    $html .= '</tbody></table>';
    $pdf->writeHTML($html);
    $pdf->Output('receipts_report.pdf', 'I');
    exit;
}

echo "Invalid export format.";
?>
