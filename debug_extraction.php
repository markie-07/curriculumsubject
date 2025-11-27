<?php
require 'vendor/autoload.php';

$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('Chemistry-1.pdf');

$text = $pdf->getText();

// Find Quarter 1 block
if (preg_match('/Quarter 1(.*?)(?=Quarter 2|$)/is', $text, $matches)) {
    $q1Text = $matches[1];
    
    // Get main content (before Performance Standards)
    if (preg_match('/(.*?)(?=Performance Standards)/is', $q1Text, $matches2)) {
        $mainText = trim($matches2[1]);
        
        // Clean headers
        $mainText = preg_replace('/Content Content Standards/i', '', $mainText);
        $mainText = preg_replace('/Learning Competencies/i', '', $mainText);
        $mainText = preg_replace('/The learners learn that\s*/i', '', $mainText);
        $mainText = preg_replace('/The learners\s*/i', '', $mainText);
        
        $output = "=== CLEANED MAIN TEXT ===\n";
        $output .= $mainText;
        $output .= "\n\n=== LINES ===\n";
        
        $lines = explode("\n", $mainText);
        foreach ($lines as $i => $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $output .= "Line $i: [$line]\n";
            
            // Test patterns
            if (preg_match('/^(\d+)\.\s+([A-Z][^.]{0,80}?)$/i', $line)) {
                $output .= "  -> MATCHED as Content (short, no period)\n";
            } elseif (preg_match('/^(\d+)\.\s+(.*)/', $line)) {
                $output .= "  -> MATCHED as numbered item\n";
            }
        }
        
        file_put_contents('debug_output.txt', $output);
        echo "Output saved to debug_output.txt\n";
    }
}
