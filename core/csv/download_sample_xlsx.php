<?php
require_once '../../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$headers = ['name', 'part_number', 'tag', 'qty', 'remark'];
$sheet->fromArray($headers, NULL, 'A1');

// Styling each header cell with different colors
$headerColors = [
    'A1' => '4a90e2', // Blue
    'B1' => 'f39c12', // Orange
    'C1' => '27ae60', // Green
    'D1' => '8e44ad', // Purple
    'E1' => 'c0392b', // Red
];

foreach ($headerColors as $cell => $color) {
    $sheet->getStyle($cell)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => $color],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ]);
}

// Set suitable column widths
$sheet->getColumnDimension('A')->setWidth(25); // name
$sheet->getColumnDimension('B')->setWidth(20); // part_number
$sheet->getColumnDimension('C')->setWidth(15); // tag
$sheet->getColumnDimension('D')->setWidth(10); // qty
$sheet->getColumnDimension('E')->setWidth(45); // remark

// Optional sample data row
$sheet->fromArray(['Sample Item', 'PN001', 'tag1', 100, 'Test remark'], NULL, 'A2');

// Prepare to download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sample_stock.xlsx"');
header('Cache-Control: max-age=0');

// Write file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
