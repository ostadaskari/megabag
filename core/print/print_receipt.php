<?php
require '../../vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorSVG;

if (empty($_GET['xc'])) die("Missing x_code");
$x_code = $_GET['xc'];
$part_number = $_GET['pn'] ?? '';

$generator = new BarcodeGeneratorSVG();
$barcodeSVG = $generator->getBarcode($x_code, $generator::TYPE_CODE_128, 1.2, 60); 


echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Print Label</title>
<style>
@font-face {
  font-family: "RobotoMono-Bold";
  src: url("../fonts/RobotoMono-Bold.ttf") format("truetype");
}

body {
  font-family: "RobotoMono-Bold", monospace;
  font-weight: bold;
  margin-top: 4mm;
  text-align: center;
  background: white;
}

.wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 14mm;
  width: 88mm;
  height: 20mm;
  margin: 3mm auto 0;
}

.label {
  width: 40mm;
  height: 20mm;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.label p {
  margin: 0;
  line-height: 1.1;
}

.label .pn {
  font-size: 8pt;
  
}

.label .barcode {
  width: 100%;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.label .code {
  font-size: 12pt;
  
}
</style>
</head>

<body onload="window.print(); window.onafterprint = () => window.close();">

<div class="wrapper">

  <!-- Label 1 -->
  <div class="label">
    <p class="pn">' . htmlspecialchars($part_number) . '</p>
    <div class="barcode">' . $barcodeSVG . '</div>
    <p class="code">' . htmlspecialchars($x_code) . '</p>
  </div>

  <!-- Label 2 -->
  <div class="label">
    <p class="pn">' . htmlspecialchars($part_number) . '</p>
    <div class="barcode">' . $barcodeSVG . '</div>
    <p class="code">' . htmlspecialchars($x_code) . '</p>
  </div>

</div>

</body>
</html>';
