<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Subject Details</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { width: 60px; height: 60px; }
        .header h1 { font-size: 14px; font-weight: bold; margin: 0; }
        .header p { font-size: 10px; margin: 0; }
        .section-title { font-size: 12px; font-weight: bold; margin-top: 15px; margin-bottom: 8px; background-color: #f2f2f2; padding: 5px; }
        .field-label { font-weight: bold; }
        .description { white-space: pre-wrap; padding: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: left; vertical-align: top; }
        th { background-color: #e9e9e9; font-weight: bold; }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .approval-table { margin-top: 30px; }
        .approval-table td { border: none; text-align: center; padding-top: 30px; }
        .approval-table .line { border-top: 1px solid #000; width: 60%; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('/images/bcp logo.png') }}" alt="BCP Logo">
        <h1>BESTLINK COLLEGE OF THE PHILIPPINES</h1>
        <p>#1071 Brgy. Kaligayahan, Quirino Hi-way, Novaliches, Quezon City</p>
        <h1 style="margin-top: 10px;">OUTCOME-BASED TEACHING AND LEARNING (OBTL) PLAN</h1>
    </div>

    {{-- Course Information --}}
    <div class="section-title">COURSE INFORMATION</div>
    <table>
        <tr>
            <td width="15%"><span class="field-label">Course Code</span></td>
            <td width="35%">{{ $subject->subject_code }}</td>
            <td width="15%"><span class="field-label">Credit Units</span></td>
            <td width="35%">{{ $subject->subject_unit }}</td>
        </tr>
        <tr>
            <td><span class="field-label">Course Title</span></td>
            <td>{{ $subject->subject_name }}</td>
            <td><span class="field-label">Contact Hours</span></td>
            <td>{{ $subject->contact_hours ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><span class="field-label">Course Type</span></td>
            <td>{{ $subject->subject_type }}</td>
            <td><span class="field-label">Pre-requisite to</span></td>
            <td>{{ $subject->pre_requisite_to ?? 'None' }}</td>
        </tr>
         <tr>
            <td><span class="field-label">Credit Prerequisites</span></td>
            <td>{{ $subject->prerequisites ?? 'None' }}</td>
             <td></td>
             <td></td>
        </tr>
        <tr>
            <td colspan="4"><span class="field-label">Course Description</span></td>
        </tr>
        <tr>
            <td colspan="4" class="description">{{ $subject->course_description ?? 'N/A' }}</td>
        </tr>
    </table>

    {{-- Institutional Information --}}
    <div class="section-title">INSTITUTIONAL INFORMATION</div>
    <table>
        <tr>
            <th width="50%">VISION (SCHOOL & DEPARTMENT)</th>
            <th width="50%">MISSION (SCHOOL & DEPARTMENT)</th>
        </tr>
        <tr>
            <td>BCP is committed to provide and promote quality education with a unique, modern and research-based curriculum with delivery systems geared towards excellence.</td>
            <td>To produce self-motivated and self-directed individual who aims for academic excellence, God-fearing, peaceful, healthy and productive successful citizens.</td>
        </tr>
        <tr>
            <th>PHILOSOPHY (SCHOOL & DEPARTMENT)</th>
            <th>CORE VALUES</th>
        </tr>
        <tr>
            <td>BCP advocates threefold core values: “Fides”, “Faith; “Ratio”, Reason; Pax. Peace.</td>
            <td>FAITH, KNOWLEDGE, CHARITY AND HUMILITY</td>
        </tr>
    </table>

    <div class="page-break"></div>

    {{-- Mapping Grids --}}
    <div class="section-title">MAPPING GRIDS</div>
    <h3>PROGRAM MAPPING GRID</h3>
    @if(!empty($subject->program_mapping_grid))
        <table>
            <thead>
                <tr>
                    <th>PILO</th>
                    <th class="text-center">CTPSS</th>
                    <th class="text-center">ECC</th>
                    <th class="text-center">EPP</th>
                    <th class="text-center">GLC</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subject->program_mapping_grid as $row)
                    <tr>
                        <td>{{ $row['pilo'] ?? '' }}</td>
                        <td class="text-center">{{ $row['ctpss'] ?? '' }}</td>
                        <td class="text-center">{{ $row['ecc'] ?? '' }}</td>
                        <td class="text-center">{{ $row['epp'] ?? '' }}</td>
                        <td class="text-center">{{ $row['glc'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No program mapping data available.</p>
    @endif

    <h3>COURSE MAPPING GRID</h3>
    @if(!empty($subject->course_mapping_grid))
        <table>
            <thead>
                <tr>
                    <th>CILO</th>
                    <th class="text-center">CTPSS</th>
                    <th class="text-center">ECC</th>
                    <th class="text-center">EPP</th>
                    <th class="text-center">GLC</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subject->course_mapping_grid as $row)
                    <tr>
                        <td>{{ $row['cilo'] ?? '' }}</td>
                        <td class="text-center">{{ $row['ctpss'] ?? '' }}</td>
                        <td class="text-center">{{ $row['ecc'] ?? '' }}</td>
                        <td class="text-center">{{ $row['epp'] ?? '' }}</td>
                        <td class="text-center">{{ $row['glc'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No course mapping data available.</p>
    @endif
    <p><span class="field-label">Legend:</span> L – Facilitate Learning, P – Allow student to practice, O – Provide opportunity for development</p>


    {{-- Learning Outcomes --}}
    <div class="section-title">LEARNING OUTCOMES</div>
    <table>
        <tr><td width="40%"><span class="field-label">PROGRAM INTENDED LEARNING OUTCOMES (PILO):</span></td><td><div class="description">{{ $subject->pilo_outcomes ?? 'N/A' }}</div></td></tr>
        <tr><td><span class="field-label">Course Intended Learning Outcomes (CILO):</span></td><td><div class="description">{{ $subject->cilo_outcomes ?? 'N/A' }}</div></td></tr>
        <tr><td><span class="field-label">Expected BCP Graduate Elements:</span></td><td><div class="description">critical thinking and problem-solving skills; effective communication and collaboration; ethical and professional practice; and, global and lifelong learning commitment.</div></td></tr>
        <tr><td><span class="field-label">Learning Outcomes:</span></td><td><div class="description">{{ $subject->learning_outcomes ?? 'N/A' }}</div></td></tr>
    </table>
    
    <div class="page-break"></div>

    {{-- Weekly Plan --}}
    <div class="section-title">WEEKLY PLAN (WEEKS 1-15)</div>
    @if(!empty($subject->lessons))
        @foreach($subject->lessons as $week => $details)
            @php
                $lessonData = [];
                $parts = explode(',, ', $details);
                foreach ($parts as $part) {
                    if (str_starts_with($part, 'Detailed Lesson Content:')) $lessonData['content'] = trim(str_replace('Detailed Lesson Content:', '', $part));
                    if (str_starts_with($part, 'Student Intended Learning Outcomes:')) $lessonData['silo'] = trim(str_replace('Student Intended Learning Outcomes:', '', $part));
                    if (str_starts_with($part, 'Assessment:')) {
                        $match = [];
                        preg_match('/ONSITE: (.*) OFFSITE: (.*)/', $part, $match);
                        $lessonData['at_onsite'] = $match[1] ?? '';
                        $lessonData['at_offsite'] = $match[2] ?? '';
                    }
                    if (str_starts_with($part, 'Activities:')) {
                        $match = [];
                        preg_match('/ON-SITE: (.*) OFF-SITE: (.*)/', $part, $match);
                        $lessonData['tla_onsite'] = $match[1] ?? '';
                        $lessonData['tla_offsite'] = $match[2] ?? '';
                    }
                    if (str_starts_with($part, 'Learning and Teaching Support Materials:')) $lessonData['ltsm'] = trim(str_replace('Learning and Teaching Support Materials:', '', $part));
                    if (str_starts_with($part, 'Output Materials:')) $lessonData['output'] = trim(str_replace('Output Materials:', '', $part));
                }
            @endphp
            <h3>{{ $week }}</h3>
            <table>
                <tr><th width="30%">Content</th><td>{{ $lessonData['content'] ?? '' }}</td></tr>
                <tr><th>Student Intended Learning Outcomes</th><td>{{ $lessonData['silo'] ?? '' }}</td></tr>
                <tr><th>Assessment Tasks (ATs)</th><td><strong>ONSITE:</strong> {{ $lessonData['at_onsite'] ?? '' }}<br><strong>OFFSITE:</strong> {{ $lessonData['at_offsite'] ?? '' }}</td></tr>
                <tr><th>Suggested Teaching/Learning Activities (TLAs)</th><td><strong>On-Site:</strong> {{ $lessonData['tla_onsite'] ?? '' }}<br><strong>Off-Site:</strong> {{ $lessonData['tla_offsite'] ?? '' }}</td></tr>
                <tr><th>Learning and Teaching Support Materials (LTSM)</th><td>{{ $lessonData['ltsm'] ?? '' }}</td></tr>
                <tr><th>Output Materials</th><td>{{ $lessonData['output'] ?? '' }}</td></tr>
            </table>
        @endforeach
    @else
        <p>No weekly plan data available.</p>
    @endif
    
    <div class="page-break"></div>
    
    {{-- Course Requirements and Policies --}}
    <div class="section-title">COURSE REQUIREMENTS AND POLICIES</div>
    <table>
        <tr><td width="30%"><span class="field-label">Basic Readings / Textbooks:</span></td><td><div class="description">{{ $subject->basic_readings ?? 'N/A' }}</div></td></tr>
        <tr><td><span class="field-label">Extended Readings / References:</span></td><td><div class="description">{{ $subject->extended_readings ?? 'N/A' }}</div></td></tr>
        <tr><td><span class="field-label">Course Assessment:</span></td><td><div class="description">{{ $subject->course_assessment ?? 'N/A' }}</div></td></tr>
        <tr><td><span class="field-label">Course Policies and Statements:</span></td><td><div class="description">{{ $subject->course_policies ?? 'Standard school policies apply.' }}</div></td></tr>
        <tr><td><span class="field-label">Committee Members:</span></td><td><div class="description">{{ $subject->committee_members ?? 'N/A' }}</div></td></tr>
        <tr><td><span class="field-label">Consultation Schedule:</span></td><td><div class="description">{{ $subject->consultation_schedule ?? 'N/A' }}</div></td></tr>
    </table>

    {{-- Approval --}}
    <div class="section-title">APPROVAL</div>
    <table class="approval-table">
        <tr>
            <td>
                <p class="line"></p>
                <p class="field-label">{{ $subject->prepared_by ?? 'N/A' }}</p>
                <p>Prepared By</p>
            </td>
            <td>
                <p class="line"></p>
                <p class="field-label">{{ $subject->reviewed_by ?? 'N/A' }}</p>
                <p>Reviewed By</p>
            </td>
            <td>
                <p class="line"></p>
                <p class="field-label">{{ $subject->approved_by ?? 'N/A' }}</p>
                <p>Approved By</p>
            </td>
        </tr>
    </table>
</body>
</html>