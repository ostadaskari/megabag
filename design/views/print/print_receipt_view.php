<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Barcode</title>
    <style>
        body {
            margin: 0;
            text-align: center;
        }
        .barcode {
            margin-top: 20px;
        }
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = () => window.close();">
    <div class="barcode">
        <img src="data:image/png;base64,<?php echo $barcode; ?>" alt="Barcode">
    </div>
    <div><?php echo htmlspecialchars($x_code); ?></div>
</body>
</html>