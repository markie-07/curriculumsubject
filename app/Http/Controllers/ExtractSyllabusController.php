<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class ExtractSyllabusController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function extract(Request $request)
    {
        $request->validate([
            'syllabus_file' => 'required|mimes:pdf|max:10240',
        ]);

        try {
            $file = $request->file('syllabus_file');
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            
            $pages = $pdf->getPages();
            $text = "";
            foreach ($pages as $page) {
                $text .= $page->getText() . "\n";
            }

            $extractionMethod = 'regex'; // Default method
            $data = null;

            // Try AI extraction if enabled
            if (config('services.gemini.enabled', false)) {
                Log::info('Attempting Gemini AI extraction for DepEd syllabus');
                $data = $this->geminiService->extractDepEdSyllabus($text);
                
                if ($data !== null) {
                    $extractionMethod = 'ai';
                    Log::info('Gemini AI extraction successful');
                }
            }

            // Fallback to regex extraction if AI failed or disabled
            if ($data === null) {
                Log::info('Using regex-based extraction for DepEd syllabus');
                $data = $this->extractWithRegex($text);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'extraction_method' => $extractionMethod,
                'message' => 'Extraction complete'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Original regex-based extraction (fallback method)
     */
    private function extractWithRegex(string $text): array
    {
        $data = [
            'course_title' => null,
            'course_description' => null,
            'time_allotment' => null,
            'schedule' => null,
        ];

            // 1. Course Title
            if (preg_match('/STRENGTHENED SENIOR HIGH SCHOOL CURRICULUM\s+(.*)/i', $text, $matches)) {
                $data['course_title'] = trim($matches[1]);
            } elseif (preg_match('/Course Title\s*:\s*(.*)/i', $text, $matches)) {
                $data['course_title'] = trim($matches[1]);
            }

            // 2. Course Description
            if (preg_match('/Course Description(.*?)(?:Content|Performance Standards|Time Allotment|Semester|Quarter|Content Standard)/is', $text, $matches)) {
                $desc = trim($matches[1]);
                $desc = ltrim($desc, ": \t\n\r\0\x0B");
                $data['course_description'] = $desc;
            }

            // 3. Time Allotment
            if (preg_match('/(No\. of Hours|Time Allotment)(.*?)(?:\n\n|\n[A-Z]|$)/is', $text, $matches)) {
                $val = trim($matches[2]);
                $val = ltrim($val, ": \t\n\r\0\x0B");
                $data['time_allotment'] = $val;
            }

            // 4. Schedule
            if (preg_match('/Schedule(.*?)(?:\n\n|\n[A-Z]|$)/is', $text, $matches)) {
                $val = trim($matches[1]);
                $val = ltrim($val, ": \t\n\r\0\x0B");
                $data['schedule'] = $val;
            }

            // 5. Quarter extraction with improved row detection
            $extractQuarter = function($qText) {
                $qData = [
                    'rows' => [],
                    'performance_standards' => '',
                    'performance_task' => ''
                ];

                // Extract Performance Standards
                if (preg_match('/Performance Standards(.*?)(?=Suggested Performance Task|$)/is', $qText, $matches)) {
                    $qData['performance_standards'] = trim($matches[1]);
                }

                // Extract Suggested Performance Task
                if (preg_match('/Suggested Performance Task(.*?)(?=$)/is', $qText, $matches)) {
                    $qData['performance_task'] = trim($matches[1]);
                }

                // Extract Main Content
                if (preg_match('/(.*?)(?=Performance Standards)/is', $qText, $matches)) {
                    $mainText = trim($matches[1]);
                    
                    // Clean headers
                    $mainText = preg_replace('/Content Content Standards/i', '', $mainText);
                    $mainText = preg_replace('/Learning Competencies/i', '', $mainText);
                    $mainText = preg_replace('/The learners learn that\s*/i', '', $mainText);
                    $mainText = preg_replace('/The learners\s*/i', '', $mainText);
                    
                    // First, join continuation lines
                    $lines = explode("\n", $mainText);
                    $joinedLines = [];
                    $buffer = '';
                    
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (empty($line)) continue;
                        
                        // Check if this line has multiple numbered items (e.g., "4. Isotopes 2. isotopes...")
                        // Only split if there are actually 2+ numbered items on the same line
                        if (preg_match_all('/(\d+\.\s+[^0-9]+?)(?=\d+\.|$)/', $line, $matches) && count($matches[0]) > 1) {
                            // Multiple items on one line - split them
                            foreach ($matches[0] as $segment) {
                                $segment = trim($segment);
                                if (!empty($segment)) {
                                    if ($buffer && preg_match('/^\d+\./', $segment)) {
                                        $joinedLines[] = $buffer;
                                        $buffer = $segment;
                                    } elseif (preg_match('/^\d+\./', $segment)) {
                                        $buffer = $segment;
                                    } else {
                                        $buffer .= ' ' . $segment;
                                    }
                                }
                            }
                        }
                        // If line starts with number, it's a new item
                        elseif (preg_match('/^\d+\./', $line)) {
                            if ($buffer) {
                                $joinedLines[] = $buffer;
                            }
                            $buffer = $line;
                        } else {
                            // Continuation of previous line
                            $buffer .= ' ' . $line;
                        }
                    }
                    if ($buffer) {
                        $joinedLines[] = $buffer;
                    }
                    
                    // Now parse joined lines
                    $currentRow = null;
                    
                    foreach ($joinedLines as $line) {
                        $line = trim($line);
                        
                        // Check if this is a Content topic (very short, 1-10 words)
                        if (preg_match('/^(\d+)\.\s+(.+)$/i', $line, $match)) {
                            $content = trim($match[2]);
                            $wordCount = str_word_count($content);
                            
                            // Content topics are short (1-10 words) and don't end with punctuation like ; or .
                            if ($wordCount <= 10 && !preg_match('/[;.]$/', $content)) {
                                // This is a Content topic - start new row
                                if ($currentRow !== null) {
                                    $qData['rows'][] = $currentRow;
                                }
                                
                                $currentRow = [
                                    'content' => $line,
                                    'content_standards' => '',
                                    'learning_competencies' => ''
                                ];
                            }
                            // Otherwise it's a Standard or Competency - add to current row
                            elseif ($currentRow !== null) {
                                // Heuristic: Learning Competencies typically start with action verbs
                                if (preg_match('/^\d+\.\s+(use|conduct|define|calculate|create|interpret|derive|carry|explain|solve|describe|apply|determine|develop|design|draw|demonstrate)/i', $line)) {
                                    // This is a Learning Competency
                                    $currentRow['learning_competencies'] .= ($currentRow['learning_competencies'] ? "\n" : '') . $line;
                                } else {
                                    // This is a Content Standard
                                    $currentRow['content_standards'] .= ($currentRow['content_standards'] ? "\n" : '') . $line;
                                }
                            }
                        }
                    }
                    
                    // Save last row
                    if ($currentRow !== null) {
                        $qData['rows'][] = $currentRow;
                    }
                    
                    // Fallback with confidence check
                    if (empty($qData['rows'])) {
                        $qData['rows'][] = [
                            'content' => '[Auto-extraction failed - Please organize manually]',
                            'content_standards' => '',
                            'learning_competencies' => "=== EXTRACTED TEXT (Please organize into rows) ===\n\n" . trim($mainText)
                        ];
                    } else {
                        if (count($qData['rows']) === 1 && strlen($mainText) > 500) {
                            $qData['rows'][0]['content'] = '[Please verify] ' . $qData['rows'][0]['content'];
                        }
                    }
                }

                return $qData;
            };

            // Find Quarter 1 Block
            if (preg_match('/Quarter 1(.*?)(?=Quarter 2|$)/is', $text, $matches)) {
                $q1Text = $matches[1];
                $q1Data = $extractQuarter($q1Text);
                $data['q_1_rows'] = $q1Data['rows'];
                $data['q_1_performance_standards'] = $q1Data['performance_standards'];
                $data['q_1_performance_task'] = $q1Data['performance_task'];
            }

            // Find Quarter 2 Block
            if (preg_match('/Quarter 2(.*?)(?=$)/is', $text, $matches)) {
                $q2Text = $matches[1];
                $q2Data = $extractQuarter($q2Text);
                $data['q_2_rows'] = $q2Data['rows'];
                $data['q_2_performance_standards'] = $q2Data['performance_standards'];
                $data['q_2_performance_task'] = $q2Data['performance_task'];
            }

            return $data;
    }
}
