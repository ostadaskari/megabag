<?php

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample_stock.csv"');

echo "name,part_number,tag,qty,remark\n";

exit;
