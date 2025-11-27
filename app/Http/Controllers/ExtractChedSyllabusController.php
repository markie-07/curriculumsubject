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
                'learning_outcomes' => null, // This might be redundant if PILO/CILO covers it, but the view has it
                'weekly_plan' => [], // Will structure this for the frontend to populate
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
            if (preg_match('/Course Title\s+(.*?)(?=Contact hours|Credit Units|$)/is', $text, $matches)) {
                $data['course_title'] = trim($matches[1]);
            }
            if (preg_match('/Course Code\s+(.*?)(?=Course Title|Credit Units|$)/is', $text, $matches)) {
                $data['course_code'] = trim($matches[1]);
            }
            if (preg_match('/Credit Units\s+(\d+)\s*UNITS/i', $text, $matches)) {
                $data['credit_units'] = $matches[1];
            }
            if (preg_match('/Contact hours\s+(\d+)\s*HOURS/i', $text, $matches)) {
                $data['contact_hours'] = $matches[1];
            }
            if (preg_match('/Course\s+Description\s+(.*?)(?=PROGRAM MAPPING GRID|Week|BESTLINK COLLEGE|$)/is', $text, $matches)) {
                $data['course_description'] = trim($matches[1]);
            }

            // --- Mapping Grids ---
            $parseMappingGrid = function($gridText) {
                $rows = [];
                $lines = explode("\n", $gridText);
                $currentDesc = '';
                
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    // Check if line is the codes line (e.g. "L P O O")
                    // Allow for flexible spacing between letters
                    if (preg_match('/^([LPO])\s+([LPO])\s+([LPO])\s+([LPO])$/i', $line, $matches)) {
                        if ($currentDesc) {
                            $rows[] = [
                                'pilo' => trim($currentDesc),
                                'ctpss' => $matches[1],
                                'ecc' => $matches[2],
                                'epp' => $matches[3],
                                'glc' => $matches[4],
                            ];
                            $currentDesc = '';
                        }
                    } else {
                        $currentDesc .= ' ' . $line;
                    }
                }
                return $rows;
            };

            if (preg_match('/PROGRAM MAPPING GRID(.*?)(?=COURSE MAPPING GRID)/is', $text, $matches)) {
                $data['program_mapping'] = $parseMappingGrid($matches[1]);
            }

            if (preg_match('/COURSE MAPPING GRID(.*?)(?=School Goals|PROGRAM INTENDED LEARNING OUTCOMES)/is', $text, $matches)) {
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
            // PILO: Capture everything until CILO, then clean up School/Program Goals
            if (preg_match('/PROGRAM INTENDED LEARNING OUTCOMES \(PILO\)(.*?)(?=Course Intended Learning Outcomes \(CILO\)|$)/is', $text, $matches)) {
                $piloContent = trim($matches[1]);
                
                // Remove the exact School Goals text
                $schoolGoalsText = "School Goals: BCP puts God in the center of all its efforts realize and operationalize its vision and missions through the following. Instruction, Research, Extension and Productivity";
                $piloContent = str_replace($schoolGoalsText, '', $piloContent);
                $piloContent = str_replace("School Goals:", '', $piloContent);
                $piloContent = preg_replace('/BCP puts God in the center.*?Productivity/is', '', $piloContent);
                
                // Remove the exact Program Goals text
                $programGoalsText = "Program Goals: To cultivate a dynamic and inclusive learning environment that empowers students to become self-directed, ethical, and engaged citizens, equipped with the critical thinking, communication, and problem-solving skills necessary to thrive in a rapidly evolving world.";
                $piloContent = str_replace($programGoalsText, '', $piloContent);
                $piloContent = str_replace("Program Goals:", '', $piloContent);
                $piloContent = preg_replace('/To cultivate a dynamic and inclusive.*?rapidly evolving world\.?/is', '', $piloContent);
                
                // Clean up whitespace
                $piloContent = trim($piloContent);
                
                // If after cleanup we still have "Program Goals" at the start, try to extract from "General Education Program"
                if (stripos($piloContent, 'Program Goals') === 0 || strlen($piloContent) < 50) {
                    if (preg_match('/General Education Program aims to[;:]?(.*?)(?=Course Intended Learning Outcomes|$)/is', $text, $subMatches)) {
                        $data['pilo_outcomes'] = trim($subMatches[1]);
                    } else {
                        // Last resort: just remove everything before "General Education Program"
                        if (preg_match('/General Education Program(.*)/is', $piloContent, $genEdMatch)) {
                            $data['pilo_outcomes'] = trim($genEdMatch[1]);
                        } else {
                            $data['pilo_outcomes'] = $piloContent;
                        }
                    }
                } else {
                    $data['pilo_outcomes'] = $piloContent;
                }
            }
            
            
            if (preg_match('/Course Intended Learning Outcomes \(CILO\)(.*?)(?=Expected BCP Graduate Elements|Week|$)/is', $text, $matches)) {
                $data['cilo_outcomes'] = trim($matches[1]);
            }
            if (preg_match('/Expected BCP Graduate Elements\s+Learning Outcomes(.*?)(?=Week|$)/is', $text, $matches)) {
                $data['learning_outcomes'] = trim($matches[1]);
            }

            // --- Weekly Plan ---
            for ($i = 0; $i <= 18; $i++) {
                $nextWeek = $i + 1;
                
                // Regex: 
                // Start with newline or start of string
                // Optional whitespace
                // The week number $i
                // Followed by EITHER a newline OR a non-dot character (to avoid matching "1." list items)
                // Capture content until...
                // The next week number (with same rules) OR "Basic Readings" OR "Course Requirements"
                
                $pattern = '/(?:^|\n)\s*' . $i . '\s*(?:\n|[^\d\.])(.*?)(?=(?:^|\n)\s*' . $nextWeek . '\s*(?:\n|[^\d\.])|Basic Readings|Course Requirements|$)/is';
                
                if (preg_match($pattern, $text, $matches)) {
                    $weekContent = trim($matches[1]);
                    
                    // Clean up common headers that might be captured
                    $weekContent = preg_replace('/Week\s+Content\s+Student Intended Learning Outcomes.*?Output Materials/s', '', $weekContent);
                    
                    $lesson = [
                        'content' => $weekContent,
                        'silo' => '',
                        'at_onsite' => '',
                        'at_offsite' => '',
                        'tla_onsite' => '',
                        'tla_offsite' => '',
                        'ltsm' => '',
                        'output' => ''
                    ];
                    $data['weekly_plan'][$i] = $lesson;
                }
            }

            // --- Course Requirements and Policies ---
            // Use \s*\/ to handle "Readings / Textbooks" with potential newlines or spaces
            if (preg_match('/Basic Readings\s*\/\s*Textbooks(.*?)(?=Extended Readings|$)/is', $text, $matches)) {
                $data['basic_readings'] = trim($matches[1]);
            }
            if (preg_match('/Extended Readings\s*\/\s*References(.*?)(?=Course Assessment|$)/is', $text, $matches)) {
                $data['extended_readings'] = trim($matches[1]);
            }
            if (preg_match('/Course Assessment(.*?)(?=Course Policies|$)/is', $text, $matches)) {
                $data['course_assessment'] = trim($matches[1]);
            }
            
            // Committee Members
            if (preg_match('/Committee Members(.*?)(?=Consultation Schedule|$)/is', $text, $matches)) {
                $data['committee_members'] = trim($matches[1]);
            }

            // Consultation Schedule
            if (preg_match('/Consultation Schedule(.*?)(?=AY\/Term|Prepared|$)/is', $text, $matches)) {
                $data['consultation_schedule'] = trim($matches[1]);
            }
            
            // Approval
            if (preg_match('/Prepared:?\s*(.*?)(?=Reviewed|$)/is', $text, $matches)) {
                $data['prepared_by'] = trim($matches[1]);
            }
            if (preg_match('/Reviewed:?\s*(.*?)(?=Approved|$)/is', $text, $matches)) {
                $data['reviewed_by'] = trim($matches[1]);
            }
            if (preg_match('/Approved:?\s*(.*?)(?=Vice President|$)/is', $text, $matches)) {
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
