<?php
require '../../vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorPNG;

if (!isset($_GET['xc']) || empty($_GET['xc'])) {
    die("Error: Missing x_code.");
}

$x_code = $_GET['xc'];

$generator = new BarcodeGeneratorPNG();
$barcode = base64_encode($generator->getBarcode($x_code, $generator::TYPE_CODE_128, 2, 30)); // scale 1, height 20

// Minimal HTML wrapper for print
echo '<!DOCTYPE html>
<html>
<head>
    <title>Print Barcode</title>
</head>
<body onload="window.print(); window.onafterprint = () => window.close();" style="text-align:center;margin:0;">
    <img src="data:image/png;base64,' . $barcode . '" alt="Barcode">
    <div>' . htmlspecialchars($x_code) . '</div>
</body>
</html>';



///////////// for printer



// require '../../vendor/autoload.php';
// use Picqer\Barcode\BarcodeGeneratorPNG;

// if (!isset($_GET['xc']) || empty($_GET['xc'])) {
//     die("Error: Missing x_code.");
// }

// $x_code = $_GET['xc'];

// // Generate barcode
// $generator = new BarcodeGeneratorPNG();
// $barcode = $generator->getBarcode($x_code, $generator::TYPE_CODE_128, 1, 40); // scale 2, height 60

// // Output PNG directly
// header('Content-Type: image/png');
// header('Content-Disposition: inline; filename="' . $x_code . '.png"');
// echo $barcode;
// exit;