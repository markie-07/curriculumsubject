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

// Initialize data structure
$data = [
    'course_title' => null,
    'course_code' => null,
    'credit_units' => null,
    'contact_hours' => null,
    'course_description' => null,
    'pilo_outcomes' => null,
    'cilo_outcomes' => null,
    'learning_outcomes' => null,
    'weekly_plan' => [],
    'basic_readings' => null,
    'extended_readings' => null,
    'course_assessment' => null,
    'committee_members' => null,
    'consultation_schedule' => null,
    'prepared_by' => null,
    'reviewed_by' => null,
    'approved_by' => null,
    'program_mapping' => [],
    'course_mapping' => [],
];

echo "=== TESTING EXTRACTION ===\n\n";

// Course Code
if (preg_match('/Course Code\s+([^\t\n]+?)(?=\s+Credit Units|$)/is', $text, $matches)) {
    $data['course_code'] = trim($matches[1]);
    echo "✓ Course Code: " . $data['course_code'] . "\n";
} else {
    echo "✗ Course Code: NOT FOUND\n";
}

// Credit Units
if (preg_match('/Credit Units\s+(\d+)/i', $text, $matches)) {
    $data['credit_units'] = trim($matches[1]);
    echo "✓ Credit Units: " . $data['credit_units'] . "\n";
} else {
    echo "✗ Credit Units: NOT FOUND\n";
}

// Course Title
if (preg_match('/Course Title\s+([^\t\n]+?)(?=\s+Contact|$)/is', $text, $matches)) {
    $data['course_title'] = trim($matches[1]);
    echo "✓ Course Title: " . $data['course_title'] . "\n";
} else {
    echo "✗ Course Title: NOT FOUND\n";
}

// Contact Hours
if (preg_match('/Contact\s+Hours\s+(\d+)/i', $text, $matches)) {
    $data['contact_hours'] = trim($matches[1]);
    echo "✓ Contact Hours: " . $data['contact_hours'] . "\n";
} else {
    echo "✗ Contact Hours: NOT FOUND\n";
}

// Course Description
if (preg_match('/Course\s+Description\s+([^\n]+?)(?=INSTITUTIONAL INFORMATION|VISION|$)/is', $text, $matches)) {
    $data['course_description'] = trim($matches[1]);
    echo "✓ Course Description: " . $data['course_description'] . "\n";
} else {
    echo "✗ Course Description: NOT FOUND\n";
}

// PILO
if (preg_match('/PROGRAM INTENDED\s+LEARNING OUTCOMES \(PILO\):\s+([^\n]+?)(?=Course Intended Learning|$)/is', $text, $matches)) {
    $data['pilo_outcomes'] = trim($matches[1]);
    echo "✓ PILO: " . $data['pilo_outcomes'] . "\n";
} else {
    echo "✗ PILO: NOT FOUND\n";
}

// CILO
if (preg_match('/Course Intended Learning\s+Outcomes \(CILO\):\s+([^\n]+?)(?=Expected BCP Graduate|Learning Outcomes:|$)/is', $text, $matches)) {
    $data['cilo_outcomes'] = trim($matches[1]);
    echo "✓ CILO: " . $data['cilo_outcomes'] . "\n";
} else {
    echo "✗ CILO: NOT FOUND\n";
}

// Learning Outcomes
if (preg_match('/Learning Outcomes:\s+([^\n]+?)(?=WEEKLY PLAN|Week|$)/is', $text, $matches)) {
    $data['learning_outcomes'] = trim($matches[1]);
    echo "✓ Learning Outcomes: " . $data['learning_outcomes'] . "\n";
} else {
    echo "✗ Learning Outcomes: NOT FOUND\n";
}

// Week 0
echo "\n=== TESTING WEEK 0 ===\n";
if (preg_match('/Week 0\s+Content:\s+(.*?)Student Intended Learning Outcomes:\s+(.*?)Assessment Tasks \(ATs\):\s+ONSITE:\s+(.*?)OFFSITE:\s+(.*?)Suggested Teaching\/Learning Activities \(TLAs\):\s+Face to Face \(On-Site\):\s+(.*?)Online \(O[fƒ]+-Site\):\s+(.*?)Learning and Teaching Support\s+Materials \(LTSM\):\s+(.*?)Output Materials:\s+(.*?)(?=Week 1|BESTLINK COLLEGE)/is', $text, $matches)) {
    echo "✓ Week 0 extracted successfully\n";
    echo "  Content: " . substr(trim($matches[1]), 0, 50) . "...\n";
    echo "  SILO: " . substr(trim($matches[2]), 0, 50) . "...\n";
} else {
    echo "✗ Week 0: NOT FOUND\n";
}

// Week 1
echo "\n=== TESTING WEEK 1 ===\n";
$pattern = '/Week 1\s+Content:\s+(.*?)Student Intended Learning Outcomes:\s+(.*?)Assessment Tasks \(ATs\):\s+ONSITE:\s+(.*?)OFFSITE:\s+(.*?)Suggested Teaching\/Learning Activities \(TLAs\):\s+Face to Face \(On-Site\):\s+(.*?)Online \(O[fƒ]+-Site\):\s+(.*?)Learning and Teaching Support\s+Materials \(LTSM\):\s+(.*?)Output Materials:\s+(.*?)(?=Week 2|Week 6|COURSE REQUIREMENTS|BESTLINK COLLEGE)/is';

if (preg_match($pattern, $text, $matches)) {
    echo "✓ Week 1 extracted successfully\n";
    echo "  Content: " . trim($matches[1]) . "\n";
} else {
    echo "✗ Week 1: NOT FOUND\n";
}

// Basic Readings
echo "\n=== TESTING COURSE REQUIREMENTS ===\n";
if (preg_match('/Basic Readings\s*\/\s*Textbooks:\s+([^\n]+?)(?=Extended Readings|$)/is', $text, $matches)) {
    $data['basic_readings'] = trim($matches[1]);
    echo "✓ Basic Readings: " . $data['basic_readings'] . "\n";
} else {
    echo "✗ Basic Readings: NOT FOUND\n";
}

// Extended Readings
if (preg_match('/Extended Readings\s*\/\s*References:\s+([^\n]+?)(?=Course Assessment|$)/is', $text, $matches)) {
    $data['extended_readings'] = trim($matches[1]);
    echo "✓ Extended Readings: " . $data['extended_readings'] . "\n";
} else {
    echo "✗ Extended Readings: NOT FOUND\n";
}

// Course Assessment
if (preg_match('/Course Assessment:\s+([^\n]+?)(?=Course Policies|$)/is', $text, $matches)) {
    $data['course_assessment'] = trim($matches[1]);
    echo "✓ Course Assessment: " . $data['course_assessment'] . "\n";
} else {
    echo "✗ Course Assessment: NOT FOUND\n";
}

echo "\n=== EXTRACTION COMPLETE ===\n";
echo "\nFull data structure:\n";
print_r($data);
