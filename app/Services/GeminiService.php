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
            
            if (empty($apiKey)) {
                Log::warning('Gemini API key not configured');
                return null;
            }
            
            $client = Gemini::client($apiKey);
            
            $prompt = $this->buildDepEdPrompt($pdfText);
            
            $result = $client->geminiPro()->generateContent($prompt);
            
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
            
            if (empty($apiKey)) {
                Log::warning('Gemini API key not configured');
                return null;
            }
            
            $client = Gemini::client($apiKey);
            
            $prompt = $this->buildChedPrompt($pdfText);
            
            $result = $client->geminiPro()->generateContent($prompt);
            
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

Return ONLY valid JSON (no markdown, no explanations) with this exact structure:

{
  "course_title": "string or null",
  "course_description": "string or null",
  "time_allotment": "string or null",
  "schedule": "string or null",
  "q_1_rows": [
    {
      "content": "content topic",
      "content_standards": "standards text",
      "learning_competencies": "competencies text"
    }
  ],
  "q_1_performance_standards": "string or null",
  "q_1_performance_task": "string or null",
  "q_2_rows": [],
  "q_2_performance_standards": "string or null",
  "q_2_performance_task": "string or null"
}

EXTRACTION RULES:
1. Course Title: Look for "STRENGTHENED SENIOR HIGH SCHOOL CURRICULUM" or "Course Title:"
2. Course Description: Text after "Course Description" label
3. Time Allotment: Look for "No. of Hours" or "Time Allotment"
4. Schedule: Look for "Schedule" field
5. For each Quarter:
   - Content topics are SHORT (1-10 words), like "1. Scientific measurement"
   - Content Standards are longer descriptions, numbered
   - Learning Competencies start with action verbs (explain, conduct, calculate, etc.)
   - Performance Standards: Overall standards for the quarter
   - Performance Task: Suggested tasks for assessment
6. If a field is not found, use null
7. Preserve all numbering in the original text

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
  "program_mapping": [],
  "course_mapping": []
}

EXTRACTION RULES:
1. Course Information: Extract course code, title, credit units, contact hours, description
2. Learning Outcomes: Extract PILO, CILO, and general learning outcomes
3. Weekly Plan: Extract for weeks 0-18 (use week number as key)
   - Week 6, 12, 18 are exam weeks (just set content, leave others empty)
4. Readings: Extract basic and extended readings/references
5. Assessment: Extract course assessment information
6. Committee/Approval: Extract committee members, consultation schedule, prepared/reviewed/approved by
7. Mapping Grids: Extract program and course mapping if present
8. If a field is not found, use null or empty string as appropriate

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
