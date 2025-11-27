<?php
require 'vendor/autoload.php';

$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('Physics-1.pdf');

$text = $pdf->getText();
file_put_contents('physics_raw.txt', $text);
echo "Saved to physics_raw.txt\n";
