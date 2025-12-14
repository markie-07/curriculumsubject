<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class ExtractChedSyllabusController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Clean extracted text by removing common labels and colons
     */
    private function cleanExtractedText($text)
    {
        if (empty($text)) {
            return $text;
        }
        
        // Remove common labels that might be included
        $labels = [
            'Course Code:',
            'Course Title:',
            'Credit Units:',
            'Contact Hours:',
            'Course Type:',
            'Course Description:',
            'Credit Prerequisites:',
            'Pre-requisite to:',
            'Content:',
            'ONSITE:',
            'OFFSITE:',
            'Face to Face (On-Site):',
            'Online (Off-Site):',
            'Learning and Teaching Support Materials (LTSM):',
            'Output Materials:',
            'Student Intended Learning Outcomes:',
            'Assessment Tasks (ATs):',
            'Suggested Teaching/Learning Activities (TLAs):',
            'Basic Readings / Textbooks:',
            'Extended Readings / References:',
            'Course Assessment:',
            'Committee Members:',
            'Consultation Schedule:',
            'Prepared:',
            'Reviewed:',
            'Approved:',
        ];
        
        foreach ($labels as $label) {
            if (stripos($text, $label) === 0) {
                $text = substr($text, strlen($label));
                break;
            }
        }
        
        return trim($text);
    }

    /**
     * Format extracted text to preserve line breaks and numbered lists
     */
    private function formatExtractedText($text)
    {
        if (empty($text)) {
            return $text;
        }
        
        // First clean any labels
        $text = $this->cleanExtractedText($text);
        
        // Add line breaks before numbered items with decimals (1.1, 2.3, etc.)
        $text = preg_replace('/(\d+\.\d+)/', "\n$1", $text);
        
        // Add line breaks before simple numbered items (1., 2., 3., etc.)
        // This pattern ensures we don't match decimal numbers like 1.1
        $text = preg_replace('/(\d+\.)(?!\d)/', "\n$1", $text);
        
        // Clean up multiple consecutive line breaks
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        
        // Trim leading/trailing whitespace
        return trim($text);
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
            $text = $pdf->getText();

            $extractionMethod = 'regex'; // Default method
            $data = null;

            // Try AI extraction if enabled
            if (config('services.gemini.enabled', false)) {
                Log::info('Attempting Gemini AI extraction for CHED syllabus');
                $data = $this->geminiService->extractChedSyllabus($text);
                
                if ($data !== null) {
                    $extractionMethod = 'ai';
                    Log::info('Gemini AI extraction successful');
                }
            }

            // Fallback to regex extraction if AI failed or disabled
            if ($data === null) {
                Log::info('Using regex-based extraction for CHED syllabus');
                $data = $this->extractWithRegex($text);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'extraction_method' => $extractionMethod,
                'message' => 'CHED Syllabus extracted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Extraction failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Original regex-based extraction (fallback method)
     */
    private function extractWithRegex(string $text): array
    {
        // Initialize data structure matching the CHED fields in course_builder.blade.php
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

            // --- Course Information ---
            // Course Code - look for pattern "Course Code:" followed by value
            if (preg_match('/Course Code:\s*([^\n]+?)(?=\n|$)/is', $text, $matches)) {
                $data['course_code'] = $this->cleanExtractedText(trim($matches[1]));
            }
            
            // Credit Units - look for number after "Credit Units:"
            if (preg_match('/Credit Units:\s*(\d+)/i', $text, $matches)) {
                $data['credit_units'] = $this->cleanExtractedText(trim($matches[1]));
            }
            
            // Course Title - look for pattern after "Course Title:"
            if (preg_match('/Course Title:\s*([^\n]+?)(?=\n|$)/is', $text, $matches)) {
                $data['course_title'] = $this->cleanExtractedText(trim($matches[1]));
            }
            
            // Contact Hours
            if (preg_match('/Contact Hours:\s*(\d+)/i', $text, $matches)) {
                $data['contact_hours'] = $this->cleanExtractedText(trim($matches[1]));
            }
            
            // Course Description - look for it after "Course Description:" label
            // Try multiple patterns to handle different formats
            if (preg_match('/Course Description:\s*\n+(.*?)(?=\n\s*INSTITUTIONAL INFORMATION|VISION|SCHOOL|DEPARTMENT|$)/is', $text, $matches)) {
                $data['course_description'] = $this->formatExtractedText(trim($matches[1]));
            } elseif (preg_match('/Course Description:\s*([^\n]+?)(?=\n|$)/is', $text, $matches)) {
                $data['course_description'] = $this->formatExtractedText(trim($matches[1]));
            }

            // --- Mapping Grids ---
            $parseMappingGrid = function($gridText) {
                $rows = [];
                $lines = explode("\n", $gridText);
                
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    // Look for lines with tab-separated values (PILO/CILO followed by 4 values)
                    if (preg_match('/^(.+?)\t+([^\t]+)\t+([^\t]+)\t+([^\t]+)\t+([^\t]+)$/i', $line, $matches)) {
                        $firstCol = trim($matches[1]);
                        
                        // Skip header rows - don't include if first column is exactly "PILO" or "CILO"
                        if (strtoupper($firstCol) === 'PILO' || strtoupper($firstCol) === 'CILO') {
                            continue;
                        }
                        
                        $rows[] = [
                            'pilo' => trim($matches[1]),
                            'ctpss' => strtoupper(trim($matches[2])),
                            'ecc' => strtoupper(trim($matches[3])),
                            'epp' => strtoupper(trim($matches[4])),
                            'glc' => strtoupper(trim($matches[5])),
                        ];
                    }
                }
                return $rows;
            };

            if (preg_match('/PROGRAM MAPPING GRID(.*?)(?=COURSE MAPPING GRID)/is', $text, $matches)) {
                $data['program_mapping'] = $parseMappingGrid($matches[1]);
            }

            if (preg_match('/COURSE MAPPING GRID(.*?)(?=Legend:|LEARNING OUTCOMES)/is', $text, $matches)) {
                $courseRows = $parseMappingGrid($matches[1]);
                $data['course_mapping'] = array_map(function($row) {
                    return [
                        'cilo' => $row['pilo'],
                        'ctpss' => strtoupper($row['ctpss']),
                        'ecc' => strtoupper($row['ecc']),
                        'epp' => strtoupper($row['epp']),
                        'glc' => strtoupper($row['glc']),
                    ];
                }, $courseRows);
            }

            // --- Learning Outcomes ---
            // PILO - look for content after "PROGRAM INTENDED LEARNING OUTCOMES (PILO):"
            if (preg_match('/PROGRAM INTENDED\s+LEARNING OUTCOMES \(PILO\):\s+([^\n]+?)(?=Course Intended Learning|$)/is', $text, $matches)) {
                $data['pilo_outcomes'] = trim($matches[1]);
            }
            
            // CILO
            if (preg_match('/Course Intended Learning\s+Outcomes \(CILO\):\s+([^\n]+?)(?=Expected BCP Graduate|Learning Outcomes:|$)/is', $text, $matches)) {
                $data['cilo_outcomes'] = trim($matches[1]);
            }
            
            // Learning Outcomes
            if (preg_match('/Learning Outcomes:\s+([^\n]+?)(?=WEEKLY PLAN|Week|$)/is', $text, $matches)) {
                $data['learning_outcomes'] = trim($matches[1]);
            }

            
            // --- Weekly Plan Extraction ---
            // Extract weekly lessons (Week 0, Week 1, etc.)
            $weeklyPlan = [];
            
            // Try to find all weeks with various patterns
            if (preg_match_all('/Week\s+(\d+)\s*\n+(.*?)(?=Week\s+\d+|Basic Readings|Extended Readings|Committee|COURSE REQUIREMENTS|$)/is', $text, $weekMatches, PREG_SET_ORDER)) {
                foreach ($weekMatches as $match) {
                    $weekNum = $match[1];
                    $weekContent = trim($match[2]);
                    
                    // Parse the week content
                    $lessonData = [
                        'content' => '',
                        'silo' => '',
                        'at_onsite' => '',
                        'at_offsite' => '',
                        'tla_onsite' => '',
                        'tla_offsite' => '',
                        'ltsm' => '',
                        'output' => ''
                    ];
                    
                    // Extract Content
                    if (preg_match('/Content:?\s*\n+(.*?)(?=Student Intended|Assessment|Suggested|Learning and Teaching|Output|$)/is', $weekContent, $contentMatch)) {
                        $lessonData['content'] = $this->cleanExtractedText(trim($contentMatch[1]));
                    }
                    
                    // Extract Student Intended Learning Outcomes (SILO)
                    if (preg_match('/Student Intended Learning Outcomes:?\s*\n+(.*?)(?=Assessment|Suggested|Learning and Teaching|Output|$)/is', $weekContent, $siloMatch)) {
                        $lessonData['silo'] = $this->cleanExtractedText(trim($siloMatch[1]));
                    }
                    
                    // Extract Assessment Tasks - ONSITE
                    if (preg_match('/ONSITE:?\s*\n+(.*?)(?=OFFSITE|Suggested|Face to Face|$)/is', $weekContent, $onsiteMatch)) {
                        $lessonData['at_onsite'] = $this->cleanExtractedText(trim($onsiteMatch[1]));
                    }
                    
                    // Extract Assessment Tasks - OFFSITE
                    if (preg_match('/OFFSITE:?\s*\n+(.*?)(?=Suggested|Face to Face|Learning and Teaching|$)/is', $weekContent, $offsiteMatch)) {
                        $lessonData['at_offsite'] = $this->cleanExtractedText(trim($offsiteMatch[1]));
                    }
                    
                    // Extract TLA - Face to Face (On-Site)
                    if (preg_match('/Face to Face.*?:?\s*\n+(.*?)(?=Online|Learning and Teaching|Output|$)/is', $weekContent, $f2fMatch)) {
                        $lessonData['tla_onsite'] = $this->cleanExtractedText(trim($f2fMatch[1]));
                    }
                    
                    // Extract TLA - Online (Off-Site)
                    if (preg_match('/Online.*?:?\s*\n+(.*?)(?=Learning and Teaching|Output|Week|$)/is', $weekContent, $onlineMatch)) {
                        $lessonData['tla_offsite'] = $this->cleanExtractedText(trim($onlineMatch[1]));
                    }
                    
                    // Extract LTSM
                    if (preg_match('/Learning and Teaching Support Materials.*?:?\s*\n+(.*?)(?=Output Materials|Week|$)/is', $weekContent, $ltsmMatch)) {
                        $lessonData['ltsm'] = $this->cleanExtractedText(trim($ltsmMatch[1]));
                    }
                    
                    // Extract Output Materials
                    if (preg_match('/Output Materials:?\s*\n+(.*?)(?=Week|Basic|Extended|$)/is', $weekContent, $outputMatch)) {
                        $lessonData['output'] = $this->cleanExtractedText(trim($outputMatch[1]));
                    }
                    
                    $weeklyPlan[$weekNum] = $lessonData;
                }
            }
            
            if (!empty($weeklyPlan)) {
                $data['weekly_plan'] = $weeklyPlan;
            }

            // --- Learning Outcomes ---
            // PILO - Program Intended Learning Outcomes
            // Try multiple patterns to find PILO, making sure to stop before Legend
            if (preg_match('/PROGRAM INTENDED LEARNING OUTCOMES?\s*\(PILO\):?\s*\n+(.*?)(?=\n\s*Legend:|Course Intended Learning|Expected BCP|$)/is', $text, $matches)) {
                $piloText = trim($matches[1]);
                // Remove Legend section if it got captured
                $piloText = preg_replace('/Legend:.*$/is', '', $piloText);
                $data['pilo_outcomes'] = $this->formatExtractedText($piloText);
            } elseif (preg_match('/PILO:?\s*\n+(.*?)(?=\n\s*Legend:|Course Intended|CILO|$)/is', $text, $matches)) {
                $piloText = trim($matches[1]);
                $piloText = preg_replace('/Legend:.*$/is', '', $piloText);
                $data['pilo_outcomes'] = $this->formatExtractedText($piloText);
            }
            
            // CILO - Course Intended Learning Outcomes
            if (preg_match('/Course Intended Learning Outcomes?\s*\(CILO\):?\s*\n+(.*?)(?=\n\s*Legend:|Expected BCP|Learning Outcomes:|Week 0|$)/is', $text, $matches)) {
                $ciloText = trim($matches[1]);
                $ciloText = preg_replace('/Legend:.*$/is', '', $ciloText);
                $data['cilo_outcomes'] = $this->formatExtractedText($ciloText);
            } elseif (preg_match('/CILO:?\s*\n+(.*?)(?=Legend:|Expected BCP|Learning Outcomes:|$)/is', $text, $matches)) {
                $ciloText = trim($matches[1]);
                $ciloText = preg_replace('/Legend:.*$/is', '', $ciloText);
                $data['cilo_outcomes'] = $this->formatExtractedText($ciloText);
            }
            
            // General Learning Outcomes - Make sure to skip PILO, CILO, and Expected BCP sections
            // Look for "Learning Outcomes:" that comes AFTER "Expected BCP Graduate Elements"
            if (preg_match('/Expected BCP Graduate Elements.*?Learning Outcomes:?\s*\n+(.*?)(?=\n\s*Week 0|Week 1|Basic Readings|Extended Readings|Committee|WEEKLY PLAN|$)/is', $text, $matches)) {
                $data['learning_outcomes'] = $this->formatExtractedText(trim($matches[1]));
            } elseif (preg_match('/(?:Expected BCP|Graduate Elements).*?\n.*?Learning Outcomes:?\s*\n+(.*?)(?=Week|Basic|Extended|$)/is', $text, $matches)) {
                $data['learning_outcomes'] = $this->formatExtractedText(trim($matches[1]));
            }

            // --- Readings and Assessment ---
            // Basic Readings
            if (preg_match('/Basic Readings?\s*\/?\s*Textbooks?:?\s*(.*?)(?=Extended Readings|Course Assessment|Committee Members|$)/is', $text, $matches)) {
                $data['basic_readings'] = $this->formatExtractedText(trim($matches[1]));
            }
            
            // Extended Readings
            if (preg_match('/Extended Readings?\s*\/?\s*References?:?\s*(.*?)(?=Course Assessment|Committee Members|$)/is', $text, $matches)) {
                $data['extended_readings'] = $this->formatExtractedText(trim($matches[1]));
            }
            
            // Course Assessment
            if (preg_match('/Course Assessment:?\s*(.*?)(?=Committee Members|Consultation Schedule|Prepared|$)/is', $text, $matches)) {
                $data['course_assessment'] = $this->formatExtractedText(trim($matches[1]));
            }
            
            // Committee Members
            if (preg_match('/Committee Members:?\s*(.*?)(?=Consultation Schedule|Prepared|Reviewed|$)/is', $text, $matches)) {
                $data['committee_members'] = $this->formatExtractedText(trim($matches[1]));
            }
            
            // Consultation Schedule
            if (preg_match('/Consultation Schedule:?\s*(.*?)(?=Prepared|Reviewed|Approved|$)/is', $text, $matches)) {
                $data['consultation_schedule'] = $this->formatExtractedText(trim($matches[1]));
            }

            // --- Approval Section ---
            // Prepared by (Cluster Leader)
            if (preg_match('/Prepared:?\s*(.*?)(?=Cluster Leader|Reviewed|$)/is', $text, $matches)) {
                $data['prepared_by'] = trim($matches[1]);
            } elseif (preg_match('/Cluster Leader:?\s*(.*?)(?=Reviewed|General Education|$)/is', $text, $matches)) {
                $data['prepared_by'] = trim($matches[1]);
            }
            
            // Reviewed by (General Education Program Head)
            if (preg_match('/Reviewed:?\s*(.*?)(?=General Education Program Head|Approved|$)/is', $text, $matches)) {
                $data['reviewed_by'] = trim($matches[1]);
            } elseif (preg_match('/General Education Program Head:?\s*(.*?)(?=Approved|Vice President|$)/is', $text, $matches)) {
                $data['reviewed_by'] = trim($matches[1]);
            }
            
            // Approved by (Vice President for Academic Affairs)
            if (preg_match('/Approved:?\s*(.*?)(?=Vice President for Academic Affairs|$)/is', $text, $matches)) {
                $data['approved_by'] = trim($matches[1]);
            } elseif (preg_match('/Vice President for Academic Affairs:?\s*(.*?)$/is', $text, $matches)) {
                $data['approved_by'] = trim($matches[1]);
            }

            return $data;
    }
}
