<?php
// غیرفعال‌سازی نمایش خطاها برای جلوگیری از خراب شدن فایل‌های خروجی
error_reporting(0);
ini_set('display_errors', 0);

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

$params = [];
$types = '';

if (!empty($keyword)) {
    $query .= " AND (p.part_number LIKE ? OR p.mfg LIKE ? OR pl.x_code LIKE ? OR u.nickname LIKE ?)";
    $kw = "%$keyword%";
    $params = array_fill(0, 4, $kw);
    $types .= str_repeat('s', 4);
}
if (!empty($from_date)) {
    $query .= " AND sr.created_at >= ?";
    $params[] = $from_date . " 00:00:00";
    $types .= 's';
}
if (!empty($to_date)) {
    $query .= " AND sr.created_at <= ?";
    $params[] = $to_date . " 23:59:59";
    $types .= 's';
}

$query .= " ORDER BY sr.created_at DESC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$excelColors = ['FFDED1E7', 'FFD9EAD3', 'FFF9D4BB', 'FFD0E0F6', 'FFFCE4D6', 'FFD5D8DC', 'FFFBE5F0', 'FFC9CCC7'];
$pdfColors = ['#DED1E7', '#D9EAD3', '#F9D4BB', '#D0E0F6', '#FCE4D6', '#D5D8DC', '#FBE5F0', '#C9CCC7'];

// Export to Excel
if ($format === 'excel' || $format === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $header = ['#', 'X-Code', 'P/N', 'MFG', 'Date Code', 'Lot Location', 'Project Name', 'VRM X-Code', 'Initial QTY', 'Available QTY', 'User', 'Comment'];
    $sheet->fromArray($header, NULL, 'A1');

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

    $row_index = 2;
    while ($row = $result->fetch_assoc()) {
        $data = [
            $row_index - 1,
            $row['x_code'] ?? '',
            $row['part_number'] ?? '',
            $row['mfg'] ?? '',
            $row['lot_date_code'] ?? '',
            $row['lot_location'] ?? '',
            $row['project_name'] ?? '',
            $row['vrm_x_code'] ?? '',
            $row['initial_qty'] ?? 0,
            $row['available_qty'] ?? 0,
            $row['nickname'] ?? '',
            $row['remarks'] ?? ''
        ];
        $sheet->fromArray($data, NULL, 'A' . $row_index);
        $row_index++;
    }

    foreach (range('A', 'L') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="receipts_export.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Export to PDF
elseif ($format === 'pdf') {
    require_once '../../tcpdf/tcpdf.php';

    $pdf = new TCPDF();
    $pdf->AddPage('L', 'A4');
    $pdf->SetFont('helvetica', '', 8);

    $headers = ['#', 'X-Code', 'P/N', 'MFG', 'Date Code', 'Lot Location', 'Project Name', 'VRM X-Code', 'Initial QTY', 'Available QTY', 'User', 'Comment'];
    $columnWidths = ['4%', '12%', '12%', '8%', '8%', '9%', '10%', '10%', '5%', '5%', '8%', '9%'];

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
        // تمامی مقادیر با ?? '' بررسی شده‌اند تا ارور Deprecated ندهند (خط ۲۱۲ و ۲۱۳)
        $html .= '<tr>
                    <td style="width:' . $columnWidths[0] . ';">' . $row_count . '</td>
                    <td style="width:' . $columnWidths[1] . ';">' . htmlspecialchars($row['x_code'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[2] . ';">' . htmlspecialchars($row['part_number'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[3] . ';">' . htmlspecialchars($row['mfg'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[4] . ';">' . htmlspecialchars($row['lot_date_code'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[5] . ';">' . htmlspecialchars($row['lot_location'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[6] . ';">' . htmlspecialchars($row['project_name'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[7] . ';">' . htmlspecialchars($row['vrm_x_code'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[8] . ';">' . htmlspecialchars($row['initial_qty'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[9] . ';">' . htmlspecialchars($row['available_qty'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[10] . ';">' . htmlspecialchars($row['nickname'] ?? '') . '</td>
                    <td style="width:' . $columnWidths[11] . ';">' . htmlspecialchars($row['remarks'] ?? '') . '</td>
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