<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class ExtractChedSyllabusController extends Controller
{
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
            // Course Code - look for pattern "Course Code" followed by value before "Credit Units"
            if (preg_match('/Course Code\s+([^\t\n]+?)(?=\s+Credit Units|$)/is', $text, $matches)) {
                $data['course_code'] = trim($matches[1]);
            }
            
            // Credit Units - look for number before "UNITS" or after "Credit Units"
            if (preg_match('/Credit Units\s+(\d+)/i', $text, $matches)) {
                $data['credit_units'] = trim($matches[1]);
            }
            
            // Course Title - look for pattern after "Course Title"
            if (preg_match('/Course Title\s+([^\t\n]+?)(?=\s+Contact|$)/is', $text, $matches)) {
                $data['course_title'] = trim($matches[1]);
            }
            
            // Contact Hours
            if (preg_match('/Contact\s+Hours\s+(\d+)/i', $text, $matches)) {
                $data['contact_hours'] = trim($matches[1]);
            }
            
            // Course Description - look for it after "Course Description" label
            if (preg_match('/Course\s+Description\s+([^\n]+?)(?=INSTITUTIONAL INFORMATION|VISION|$)/is', $text, $matches)) {
                $data['course_description'] = trim($matches[1]);
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
                        $rows[] = [
                            'pilo' => trim($matches[1]),
                            'ctpss' => trim($matches[2]),
                            'ecc' => trim($matches[3]),
                            'epp' => trim($matches[4]),
                            'glc' => trim($matches[5]),
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
                        'ctpss' => $row['ctpss'],
                        'ecc' => $row['ecc'],
                        'epp' => $row['epp'],
                        'glc' => $row['glc'],
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

            // --- Weekly Plan ---
            // Week 0
            if (preg_match('/Week 0\s+Content:\s+(.*?)Student Intended Learning Outcomes:\s+(.*?)Assessment Tasks \(ATs\):\s+ONSITE:\s+(.*?)OFFSITE:\s+(.*?)Suggested Teaching\/Learning Activities \(TLAs\):\s+Face to Face \(On-Site\):\s+(.*?)Online \(O[fƒ]+-Site\):\s+(.*?)Learning and Teaching Support\s+Materials \(LTSM\):\s+(.*?)Output Materials:\s+(.*?)(?=Week 1|BESTLINK COLLEGE)/is', $text, $matches)) {
                $data['weekly_plan'][0] = [
                    'content' => trim($matches[1]),
                    'silo' => trim($matches[2]),
                    'at_onsite' => trim($matches[3]),
                    'at_offsite' => trim($matches[4]),
                    'tla_onsite' => trim($matches[5]),
                    'tla_offsite' => trim($matches[6]),
                    'ltsm' => trim($matches[7]),
                    'output' => trim($matches[8])
                ];
            }
            
            // Week 1-5, 7-11, 13-17 (regular weeks)
            for ($i = 1; $i <= 18; $i++) {
                // Skip exam weeks
                if (in_array($i, [6, 12, 18])) {
                    continue;
                }
                
                $pattern = '/Week ' . $i . '\s+Content:\s+(.*?)Student Intended Learning Outcomes:\s+(.*?)Assessment Tasks \(ATs\):\s+ONSITE:\s+(.*?)OFFSITE:\s+(.*?)Suggested Teaching\/Learning Activities \(TLAs\):\s+Face to Face \(On-Site\):\s+(.*?)Online \(O[fƒ]+-Site\):\s+(.*?)Learning and Teaching Support\s+Materials \(LTSM\):\s+(.*?)Output Materials:\s+(.*?)(?=Week ' . ($i + 1) . '|Week 6|Week 12|Week 18|COURSE REQUIREMENTS|BESTLINK COLLEGE)/is';
                
                if (preg_match($pattern, $text, $matches)) {
                    $data['weekly_plan'][$i] = [
                        'content' => trim($matches[1]),
                        'silo' => trim($matches[2]),
                        'at_onsite' => trim($matches[3]),
                        'at_offsite' => trim($matches[4]),
                        'tla_onsite' => trim($matches[5]),
                        'tla_offsite' => trim($matches[6]),
                        'ltsm' => trim($matches[7]),
                        'output' => trim($matches[8])
                    ];
                }
            }
            
            // Exam weeks (6, 12, 18) - just set content
            if (preg_match('/Week 6\s+Prelim Exam/is', $text)) {
                $data['weekly_plan'][6] = [
                    'content' => 'Prelim Exam',
                    'silo' => '',
                    'at_onsite' => '',
                    'at_offsite' => '',
                    'tla_onsite' => '',
                    'tla_offsite' => '',
                    'ltsm' => '',
                    'output' => ''
                ];
            }
            if (preg_match('/Week 12\s+Midterm Exam/is', $text)) {
                $data['weekly_plan'][12] = [
                    'content' => 'Midterm Exam',
                    'silo' => '',
                    'at_onsite' => '',
                    'at_offsite' => '',
                    'tla_onsite' => '',
                    'tla_offsite' => '',
                    'ltsm' => '',
                    'output' => ''
                ];
            }
            if (preg_match('/Week 18\s+Final Exam/is', $text)) {
                $data['weekly_plan'][18] = [
                    'content' => 'Final Exam',
                    'silo' => '',
                    'at_onsite' => '',
                    'at_offsite' => '',
                    'tla_onsite' => '',
                    'tla_offsite' => '',
                    'ltsm' => '',
                    'output' => ''
                ];
            }

            // --- Course Requirements and Policies ---
            if (preg_match('/Basic Readings\s*\/\s*Textbooks:\s+([^\n]+?)(?=Extended Readings|$)/is', $text, $matches)) {
                $data['basic_readings'] = trim($matches[1]);
            }
            if (preg_match('/Extended Readings\s*\/\s*References:\s+([^\n]+?)(?=Course Assessment|$)/is', $text, $matches)) {
                $data['extended_readings'] = trim($matches[1]);
            }
            if (preg_match('/Course Assessment:\s+([^\n]+?)(?=Course Policies|$)/is', $text, $matches)) {
                $data['course_assessment'] = trim($matches[1]);
            }
            
            // Committee Members
            if (preg_match('/Committee Members:\s+([^\n]+?)(?=Consultation|$)/is', $text, $matches)) {
                $data['committee_members'] = trim($matches[1]);
            }

            // Consultation Schedule
            if (preg_match('/Consultation\s+Schedule:\s+([^\n]+?)(?=GRADING SYSTEM|$)/is', $text, $matches)) {
                $data['consultation_schedule'] = trim($matches[1]);
            }
            
            // Approval
            if (preg_match('/Prepared:\s+([^\n]+?)(?=Cluster Leader|Reviewed|$)/is', $text, $matches)) {
                $data['prepared_by'] = trim($matches[1]);
            }
            if (preg_match('/Reviewed:\s+([^\n]+?)(?=General Education Program|Approved|$)/is', $text, $matches)) {
                $data['reviewed_by'] = trim($matches[1]);
            }
            if (preg_match('/Approved:\s+([^\n]+?)(?=Vice President|$)/is', $text, $matches)) {
                $data['approved_by'] = trim($matches[1]);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'CHED Syllabus extracted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Extraction failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
