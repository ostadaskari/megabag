<?php
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Define the new order of headers
$headers = ['Part Nomber', 'MFG',  'Tag', 'QTY', 'P-Name', 'Comment'];
$sheet->fromArray($headers, NULL, 'A1');

// Styling each header cell with vibrant colors
$headerColors = [
    'A1' => 'ff6347', // Tomato
    'B1' => 'ff8c00', // DarkOrange
    'C1' => '3cb371', // MediumSeaGreen
    'D1' => 'ba55d3', // MediumOrchid
    'E1' => '4169e1', // RoyalBlue
    'F1' => '20b2aa', // LightSeaGreen
];

foreach ($headerColors as $cell => $color) {
    $sheet->getStyle($cell)->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => $color],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'bottom' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ],
    ]);
}

// Set suitable column widths for the new headers
$sheet->getColumnDimension('A')->setWidth(20); // MFG
$sheet->getColumnDimension('B')->setWidth(20); // Part Nomber
$sheet->getColumnDimension('C')->setWidth(15); // Tag
$sheet->getColumnDimension('D')->setWidth(10); // QTY
$sheet->getColumnDimension('E')->setWidth(25); // P-Name
$sheet->getColumnDimension('F')->setWidth(45); // Comment

// Optional sample data row, updated to match the new header order
$sheet->fromArray(['PN001', 'Sample MFG', 'tag1', 100, 'Sample Item', 'Test remark'], NULL, 'A2');

// Prepare to download the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sample_stock.xlsx"');
header('Cache-Control: max-age=0');

// Write file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
