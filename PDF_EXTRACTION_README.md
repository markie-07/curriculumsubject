# PDF Syllabus Extraction System

## Overview
The system attempts to automatically extract curriculum data from DepEd syllabus PDFs and populate the Course Builder form.

## How It Works

### 1. **Basic Metadata Extraction**
- Course Title
- Course Description  
- Time Allotment
- Schedule

These are extracted using pattern matching and work reliably across most PDF formats.

### 2. **Quarter Data Extraction (Content, Standards, Competencies)**

The system uses the following logic:

#### Step 1: Text Cleanup
- Removes headers like "Content Content Standards", "Learning Competencies", "The learners"
- Joins line-wrapped text back together

#### Step 2: Row Detection
- **Content Topics**: Short phrases (1-10 words) without ending punctuation
  - Examples: "1. Scientific measurement", "2. Kinematics"
- **Content Standards**: Numbered items that don't start with action verbs
  - Examples: "1. accuracy, precision, and appropriate use of..."
- **Learning Competencies**: Numbered items starting with action verbs
  - Examples: "1. explain...", "2. conduct...", "3. calculate..."

#### Step 3: Grouping
- Each Content topic creates a new row
- All subsequent Standards and Competencies are grouped into that row
- This continues until the next Content topic is found

### 3. **Performance Standards & Tasks**
- Extracted as single blocks of text
- These are typically well-defined in PDFs and extract reliably

## Limitations

### Why Perfect Extraction Is Impossible

1. **PDF Structure Loss**: PDFs don't preserve table structure - we only get raw text
2. **Format Variations**: Different schools/years use different layouts
3. **Text Wrapping**: Long sentences split across lines unpredictably
4. **Ambiguous Numbering**: Can't always tell if "2." is a new Content topic or a Standard

### What Works Well
✅ Chemistry-1.pdf (3 clear content topics)
✅ Physics-1.pdf (2 content topics with multiple standards each)
✅ Standard DepEd formats with clear topic titles

### What May Need Manual Adjustment
⚠️ PDFs with unusual layouts
⚠️ Very long content topic names (>10 words)
⚠️ Standards that start with action verbs
⚠️ Multiple topics on the same line

## Best Practices

### For Users:
1. **Always review extracted data** - Don't assume it's 100% accurate
2. **Use "Add Content Row"** button to create additional rows as needed
3. **Cut and paste** between textareas to reorganize content
4. **Check the original PDF** side-by-side while editing

### For Optimal Results:
- Use PDFs that follow standard DepEd curriculum guide format
- Ensure PDF text is selectable (not scanned images)
- Have the original PDF open for reference during editing

## Fallback Behavior

If the system can't detect clear Content topics:
- All text is dumped into the first row's "Learning Competencies" field
- A note "[Auto-extraction failed - Please organize manually]" appears
- You can then manually cut/paste into proper rows

## Future Improvements

Potential enhancements:
1. Machine learning-based extraction
2. Manual column mapping UI
3. Template-based extraction (select PDF format type)
4. Interactive correction mode
5. Save extraction patterns for reuse

## Technical Details

**Backend**: `ExtractSyllabusController.php`
- Uses `smalot/pdfparser` library
- Pattern matching with regex
- Heuristic-based classification

**Frontend**: `course_builder.blade.php`
- Dynamic row creation
- AJAX file upload
- Real-time form population

**Action Verbs Detected**:
use, conduct, define, calculate, create, interpret, derive, carry, explain, solve, describe, apply, determine, develop, design, draw, demonstrate
