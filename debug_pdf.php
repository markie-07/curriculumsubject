<?php
require 'vendor/autoload.php';

$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('Chemistry-1.pdf');

$text = $pdf->getText();
file_put_contents('pdf_debug.txt', $text);
echo "Text saved to pdf_debug.txt";
