<?php
require __DIR__ . '/vendor/autoload.php';

use Smalot\PdfParser\Parser;

$pdfPath = __DIR__ . '/3_details (1).pdf';

if (!file_exists($pdfPath)) {
    die("PDF file not found at: $pdfPath\n");
}

$parser = new Parser();
$pdf = $parser->parseFile($pdfPath);
$text = $pdf->getText();

// Save the extracted text to a file
file_put_contents(__DIR__ . '/debug_output_3_details.txt', $text);

echo "PDF text extracted successfully!\n";
echo "Output saved to: debug_output_3_details.txt\n";
echo "\n--- First 2000 characters ---\n";
echo substr($text, 0, 2000);
echo "\n\n--- Searching for key sections ---\n";

// Check for key sections
$sections = [
    'Course Title',
    'Course Code',
    'Credit Units',
    'Contact hours',
    'Course Description',
    'PROGRAM MAPPING GRID',
    'COURSE MAPPING GRID',
    'PROGRAM INTENDED LEARNING OUTCOMES',
    'Course Intended Learning Outcomes',
    'Week 0',
    'Week 1',
    'Basic Readings',
    'Extended Readings',
    'Course Assessment',
    'Committee Members',
    'Consultation Schedule',
    'Prepared',
    'Reviewed',
    'Approved'
];

foreach ($sections as $section) {
    $pos = stripos($text, $section);
    if ($pos !== false) {
        echo "✓ Found: $section (at position $pos)\n";
    } else {
        echo "✗ NOT FOUND: $section\n";
    }
}
