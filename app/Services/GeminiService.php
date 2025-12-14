<?php

namespace App\Services;

use Gemini;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Extract DepEd syllabus data from PDF text using Google Gemini AI
     * 
     * @param string $pdfText The raw text extracted from PDF
     * @return array|null Structured syllabus data or null on failure
     */
    public function extractDepEdSyllabus(string $pdfText): ?array
    {
        try {
            $apiKey = config('services.gemini.api_key');
            $model = config('services.gemini.model', 'gemini-1.5-flash');
            
            if (empty($apiKey)) {
                Log::warning('Gemini API key not configured');
                return null;
            }
            
            $client = Gemini::client($apiKey);
            
            $prompt = $this->buildDepEdPrompt($pdfText);
            
            $result = $client->generativeModel($model)->generateContent($prompt);
            
            $responseText = $result->text();
            
            // Extract JSON from response (handle markdown code blocks)
            $jsonText = $this->extractJson($responseText);
            
            $data = json_decode($jsonText, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gemini JSON decode error: ' . json_last_error_msg());
                return null;
            }
            
            Log::info('Gemini DepEd extraction successful');
            
            return $data;
            
        } catch (\Exception $e) {
            Log::error('Gemini DepEd extraction failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Extract CHED syllabus data from PDF text using Google Gemini AI
     * 
     * @param string $pdfText The raw text extracted from PDF
     * @return array|null Structured syllabus data or null on failure
     */
    public function extractChedSyllabus(string $pdfText): ?array
    {
        try {
            $apiKey = config('services.gemini.api_key');
            $model = config('services.gemini.model', 'gemini-1.5-flash');
            
            if (empty($apiKey)) {
                Log::warning('Gemini API key not configured');
                return null;
            }
            
            $client = Gemini::client($apiKey);
            
            $prompt = $this->buildChedPrompt($pdfText);
            
            $result = $client->generativeModel($model)->generateContent($prompt);
            
            $responseText = $result->text();
            
            // Extract JSON from response
            $jsonText = $this->extractJson($responseText);
            
            $data = json_decode($jsonText, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gemini JSON decode error: ' . json_last_error_msg());
                return null;
            }
            
            Log::info('Gemini CHED extraction successful');
            
            return $data;
            
        } catch (\Exception $e) {
            Log::error('Gemini CHED extraction failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Build prompt for DepEd syllabus extraction
     */
    private function buildDepEdPrompt(string $pdfText): string
    {
        return <<<PROMPT
You are a curriculum data extraction expert. Extract structured data from this DepEd syllabus PDF text.

This syllabus may be in one of TWO formats:
- **Format A (Table-based)**: Simple 3-column table with Content | Content Standards | Learning Competencies
- **Format B (Narrative-based)**: Sections with CONTENT STANDARDS, PERFORMANCE STANDARDS, SUGGESTED COMMUNICATIVE EVENTS, LEARNING COMPETENCIES

Return ONLY valid JSON (no markdown, no explanations) with this exact structure:

{
  "course_title": "string or null",
  "course_description": "string or null",
  "time_allotment": "string or null",
  "schedule": "string or null",
  "q_1_rows": [
    {
      "content": "Content topic or theme",
      "content_standards": "What learners should understand",
      "learning_competencies": "What learners should be able to do"
    }
  ],
  "q_1_performance_standards": "string or null",
  "q_1_performance_task": "string or null",
  "q_2_rows": [],
  "q_2_performance_standards": "string or null",
  "q_2_performance_task": "string or null"
}

EXTRACTION RULES:

1. **Course Title**: 
   - Look for "STRENGTHENED SENIOR HIGH SCHOOL CURRICULUM" followed by subject name
   - Examples: "CHEMISTRY 1", "EFFECTIVE COMMUNICATION", "GENERAL MATHEMATICS"

2. **Course Description**: 
   - Text after "Course Description:" label
   - Usually 1-3 paragraphs explaining the course

3. **Time Allotment**: 
   - Look for "Time Allotment:" or "80 hours for one year, 2 hours per week"
   - Extract complete time specification

4. **Schedule**: 
   - Look for "Schedule:" field
   - Examples: "First Semester", "One School Year"

5. **QUARTER DATA EXTRACTION** (Handle both formats):

   **FORMAT A - Table-based (e.g., Chemistry)**:
   - Look for tables with columns: Content | Content Standards | Learning Competencies
   - Content: Short topics (1-10 words) like "1. Scientific measurement"
   - Content Standards: Numbered descriptions or "The learners learn that..."
   - Learning Competencies: Numbered action verbs (explain, investigate, calculate)
   - Extract EVERY row from the table

   **FORMAT B - Narrative-based (e.g., Effective Communication)**:
   - Look for sections labeled:
     * CONTENT STANDARDS (what learners demonstrate/understand)
     * PERFORMANCE STANDARDS (what learners are able to do/perform)
     * SUGGESTED COMMUNICATIVE EVENTS (bulleted activities)
     * LEARNING COMPETENCIES (detailed competencies, often with italic headers)
   
   For Format B, create rows by:
   - **content**: Use the quarter theme/title (e.g., "EFFECTIVE COMMUNICATION IN PERSONAL AND INTERPERSONAL CONTEXTS")
   - **content_standards**: Combine CONTENT STANDARDS text
   - **learning_competencies**: Combine all LEARNING COMPETENCIES text (including italic section headers and their items)

6. **Performance Standards**:
   - Look for "Performance Standards" or "PERFORMANCE STANDARDS" section
   - Usually starts with "By the end of the quarter, learners..." or "The learners are able to..."
   - Extract complete paragraph(s)

7. **Suggested Performance Task**:
   - Look for "Suggested Performance Task" or "SUGGESTED COMMUNICATIVE EVENTS"
   - May be bulleted list or paragraph
   - Examples: 
     * "Make a 2-minute video presentation..."
     * "Introducing oneself in diverse settings"
     * "Writing personal narratives, journal entries"

8. **Quarter Identification**:
   - Look for "Quarter 1", "QUARTER 1:", "Quarter 2", "QUARTER 2:"
   - Quarter titles may include themes like:
     * "QUARTER 1: EFFECTIVE COMMUNICATION IN PERSONAL AND INTERPERSONAL CONTEXTS"
     * "QUARTER 2: EFFECTIVE COMMUNICATION IN SOCIAL AND CULTURAL CONTEXTS"
   - Extract data for both quarters using the same structure
   - If only one quarter exists, set the other to empty arrays/null

9. **Handling Multiple Rows**:
   - Format A: Create one row per table row
   - Format B: Can create one comprehensive row per quarter, OR split by major learning competency themes if clearly separated

10. If a field is not found, use null or empty array as appropriate

11. Preserve important formatting and numbering from the original text

PDF TEXT:
{$pdfText}

Return ONLY the JSON object, nothing else.
PROMPT;
    }

    
    /**
     * Build prompt for CHED syllabus extraction
     */
    private function buildChedPrompt(string $pdfText): string
    {
        return <<<PROMPT
You are a curriculum data extraction expert. Extract structured data from this CHED syllabus PDF text.

Return ONLY valid JSON (no markdown, no explanations) with this exact structure:

{
  "course_title": "string or null",
  "course_code": "string or null",
  "credit_units": "string or null",
  "contact_hours": "string or null",
  "course_description": "string or null",
  "pilo_outcomes": "string or null",
  "cilo_outcomes": "string or null",
  "learning_outcomes": "string or null",
  "weekly_plan": {
    "0": {
      "content": "string",
      "silo": "string",
      "at_onsite": "string",
      "at_offsite": "string",
      "tla_onsite": "string",
      "tla_offsite": "string",
      "ltsm": "string",
      "output": "string"
    }
  },
  "basic_readings": "string or null",
  "extended_readings": "string or null",
  "course_assessment": "string or null",
  "committee_members": "string or null",
  "consultation_schedule": "string or null",
  "prepared_by": "string or null",
  "reviewed_by": "string or null",
  "approved_by": "string or null",
  "program_mapping": [
    {
      "pilo": "text from PILO column",
      "ctpss": "single letter/value",
      "ecc": "single letter/value",
      "epp": "single letter/value",
      "glc": "single letter/value"
    }
  ],
  "course_mapping": [
    {
      "cilo": "text from CILO column",
      "ctpss": "single letter/value",
      "ecc": "single letter/value",
      "epp": "single letter/value",
      "glc": "single letter/value"
    }
  ]
}

EXTRACTION RULES:
1. Course Information: Extract course code, title, credit units, contact hours, description
2. Learning Outcomes: Extract PILO, CILO, and general learning outcomes
3. Weekly Plan: Extract for weeks 0-18 (use week number as key)
   - Week 6, 12, 18 are exam weeks (just set content, leave others empty)
4. Readings: Extract basic and extended readings/references
5. Assessment: Extract course assessment information
6. Committee/Approval: Extract committee members, consultation schedule, prepared/reviewed/approved by
7. **MAPPING GRIDS - CRITICAL**:
   
   PROGRAM MAPPING GRID:
   - Look for table with header: PILO | CTPSS | ECC | EPP | GLC
   - Extract EVERY row below the header
   - First column (PILO) contains text descriptions
   - Other columns (CTPSS, ECC, EPP, GLC) contain single letters or short values
   - Skip the header row itself
   - Example row: "qweqwe" in PILO, "a" in CTPSS, "s" in ECC, "d" in EPP, "f" in GLC
   
   COURSE MAPPING GRID:
   - Look for table with header: CILO | CTPSS | ECC | EPP | GLC
   - Extract EVERY row below the header
   - First column (CILO) contains text descriptions
   - Other columns (CTPSS, ECC, EPP, GLC) contain single letters or short values
   - Skip the header row itself
   - Example row: "zxczxc" in CILO, "a" in CTPSS, "s" in ECC, "d" in EPP, "g" in GLC
   
   The tables appear after "MAPPING GRIDS" section heading.
   Extract ALL data rows from both tables.
   
8. If a field is not found, use null or empty array as appropriate

PDF TEXT:
{$pdfText}

Return ONLY the JSON object, nothing else.
PROMPT;
    }
    
    /**
     * Extract JSON from response text (handles markdown code blocks)
     */
    private function extractJson(string $text): string
    {
        // Remove markdown code blocks if present
        $text = preg_replace('/```json\s*/', '', $text);
        $text = preg_replace('/```\s*/', '', $text);
        $text = trim($text);
        
        return $text;
    }
}
