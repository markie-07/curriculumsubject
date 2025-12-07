<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $subject->subject_name ?? 'Subject Details' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
        }

        /* Header Section */
        .header {
            text-align: center;
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
        }

        .header p {
            font-size: 9pt;
            margin: 3px 0;
        }

        /* Section Headers */
        .section-header {
            background-color: #d3d3d3;
            border: 1px solid #000;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11pt;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        /* Info Boxes */
        .info-box {
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        .info-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .info-cell {
            display: table-cell;
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 30%;
        }

        .info-value {
            width: 70%;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background-color: #d3d3d3;
            font-weight: bold;
            padding: 6px;
            text-align: center;
        }

        td {
            padding: 6px;
            vertical-align: top;
        }

        /* Two-column layout */
        .two-column {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            width: 50%;
            padding: 8px;
            border: 1px solid #000;
            vertical-align: top;
        }

        .column-header {
            font-weight: bold;
            text-align: center;
            background-color: #d3d3d3;
            padding: 5px;
            margin-bottom: 8px;
        }

        /* Weekly Plan */
        .week-header {
            background-color: #34495e;
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }

        /* Legend Box */
        .legend {
            border: 1px solid #000;
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
            font-size: 9pt;
        }

        .legend ul {
            margin: 5px 0;
            padding-left: 20px;
        }

        /* Approval Section */
        .approval-section {
            margin-top: 30px;
            text-align: center;
        }

        .approval-row {
            display: table;
            width: 100%;
            margin-top: 50px;
        }

        .approval-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 40px auto 5px auto;
        }

        .approval-title {
            font-size: 8pt;
            color: #666;
        }

        /* Utilities */
        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        @php
            $imagePath = public_path('/images/BCPLOGO.png');
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $src = 'data:image/png;base64,' . $imageData;
            } else {
                $src = '';
            }
        @endphp
        
        @if($src)
            <img src="{{ $src }}" alt="BCP Logo" class="header-logo">
        @endif
        <h1>BESTLINK COLLEGE OF THE PHILIPPINES</h1>
        <p>#1071 Brgy. Kaligayahan, Quirino Hi-way, Novaliches, Quezon City</p>
    </div>

    <!-- COURSE INFORMATION -->
    <div class="section-header">COURSE INFORMATION</div>
    <div class="info-box">
        <div class="info-row">
            <div class="info-cell info-label">Course Code</div>
            <div class="info-cell info-value">{{ $subject->subject_code }}</div>
            <div class="info-cell info-label">Credit Units</div>
            <div class="info-cell info-value">{{ $subject->subject_unit }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Course Title</div>
            <div class="info-cell info-value">{{ $subject->subject_name }}</div>
            <div class="info-cell info-label">Contact Hours</div>
            <div class="info-cell info-value">{{ $subject->contact_hours ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Course Type</div>
            <div class="info-cell info-value">{{ $subject->subject_type }}</div>
            <div class="info-cell info-label">Pre-requisite to</div>
            <div class="info-cell info-value">
                @if(!empty($prerequisiteData) && isset($prerequisiteData['subjectToChildrenMap']))
                    @php
                        $childSubjects = $prerequisiteData['subjectToChildrenMap'][$subject->subject_code] ?? [];
                        echo !empty($childSubjects) ? implode(', ', $childSubjects) : 'None';
                    @endphp
                @else
                    None
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Credit Prerequisites</div>
            <div class="info-cell info-value" style="width: 70%;" colspan="3">
                @if(!empty($prerequisiteData) && isset($prerequisiteData['subjectToParentsMap']))
                    @php
                        $directPrerequisites = $prerequisiteData['subjectToParentsMap'][$subject->subject_code] ?? [];
                        sort($directPrerequisites);
                        echo !empty($directPrerequisites) ? implode(', ', $directPrerequisites) : 'None';
                    @endphp
                @else
                    None
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Course Description</div>
            <div class="info-cell info-value" style="width: 70%;">{{ $subject->course_description ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- INSTITUTIONAL INFORMATION -->
    <div class="section-header">INSTITUTIONAL INFORMATION</div>
    
    <table>
        <tr>
            <th colspan="2">VISION</th>
        </tr>
        <tr>
            <td width="50%">
                <div class="bold">SCHOOL</div>
                BCP is committed to provide and promote quality education with a unique, modern and research-based curriculum with delivery systems geared towards excellence.
            </td>
            <td width="50%">
                <div class="bold">DEPARTMENT</div>
                To improve the quality of student's input and by promoting IT enabled, market driven and internationally comparable programs through quality assurance systems, upgrading faculty qualifications and establishing international linkages.
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="2">MISSION</th>
        </tr>
        <tr>
            <td width="50%">
                <div class="bold">SCHOOL</div>
                To produce self-motivated and self-directed individual who aims for academic excellence, God-fearing, peaceful, healthy and productive successful citizens.
            </td>
            <td width="50%">
                <div class="bold">DEPARTMENT</div>
                The College of Computer Studies is committed to provide quality information and communication technology education through the use of modern and transformation learning teaching process.
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="2">PHILOSOPHY</th>
        </tr>
        <tr>
            <td width="50%">
                <div class="bold">SCHOOL</div>
                BCP advocates threefold core values: "Fides", "Faith; "Ratio", Reason; Pax. Peace. "Fides" represents BCPs, endeavors for expansion, development, and growth amidst the challenges of the new millennium.
            </td>
            <td width="50%">
                <div class="bold">DEPARTMENT</div>
                General Education advocates threefold core values "Devotion", "Serenity', "Determination" representing commitment to provide quality education.
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <th>CORE VALUES</th>
        </tr>
        <tr>
            <td>
                <span class="bold">FAITH, KNOWLEDGE, CHARITY AND HUMILITY</span><br>
                <span class="bold">FAITH (Fides)</span> represents BCP's endeavor for expansion, development and for growth amidst the global challenges of the new millennium.<br>
                <span class="bold">KNOWLEDGE (Cognito)</span> connotes the institution's efforts to impart excellent lifelong education that can be used as human tool so that one can liberate himself/herself from ignorance and poverty<br>
                <span class="bold">CHARITY (Caritas)</span> is the institution's commitment towards its clienteles.<br>
                <span class="bold">HUMILITY (Humiliates)</span> refers to the institution's recognition of the human frailty, its imperfection.
            </td>
        </tr>
    </table>

    <!-- MAPPING GRIDS -->
    <div class="section-header">MAPPING GRIDS</div>
    
    <div class="bold" style="margin: 10px 0 5px 0;">PROGRAM MAPPING GRID</div>
    @if(!empty($subject->program_mapping_grid))
        <table>
            <thead>
                <tr>
                    <th>PILO</th>
                    <th>CTPSS</th>
                    <th>ECC</th>
                    <th>EPP</th>
                    <th>GLC</th>
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

    <div class="bold" style="margin: 10px 0 5px 0;">COURSE MAPPING GRID</div>
    @if(!empty($subject->course_mapping_grid))
        <table>
            <thead>
                <tr>
                    <th>CILO</th>
                    <th>CTPSS</th>
                    <th>ECC</th>
                    <th>EPP</th>
                    <th>GLC</th>
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

    <div class="legend">
        <span class="bold">Legend:</span>
        <ul>
            <li><span class="bold">L</span> – Facilitate Learning of the competencies</li>
            <li><span class="bold">P</span> – Allow student to practice competencies (No input but competency is evaluated)</li>
            <li><span class="bold">O</span> – Provide opportunity for development (No input or evaluation, but there is opportunity to practice the competencies)</li>
            <li><span class="bold">CTPSS</span> - critical thinking and problem-solving skills;</li>
            <li><span class="bold">ECC</span> - effective communication and collaboration;</li>
            <li><span class="bold">EPP</span> - ethical and professional practice; and,</li>
            <li><span class="bold">GLC</span> - global and lifelong learning commitment.</li>
        </ul>
    </div>

    <!-- LEARNING OUTCOMES -->
    <div class="section-header">LEARNING OUTCOMES</div>
    
    <table>
        <tr>
            <td colspan="2">
                <div class="bold">PROGRAM INTENDED LEARNING OUTCOMES (PILO):</div>
                {{ $subject->pilo_outcomes ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="bold">Course Intended Learning Outcomes (CILO):</div>
                {{ $subject->cilo_outcomes ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="bold">Expected BCP Graduate Elements:</div>
                The BCP ideal graduate demonstrates/internalizes this attribute:
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <li>critical thinking and problem-solving skills;</li>
                    <li>effective communication and collaboration;</li>
                    <li>ethical and professional practice; and,</li>
                    <li>global and lifelong learning commitment.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="bold">Learning Outcomes:</div>
                {{ $subject->learning_outcomes ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <!-- WEEKLY PLAN -->
    <div class="page-break"></div>
    <div class="section-header">WEEKLY PLAN</div>
    
    @if(!empty($subject->lessons) && is_array($subject->lessons))
        @foreach(collect($subject->lessons)->sortBy(fn($val, $key) => (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT)) as $week => $details)
            @php
                $lessonData = [
                    'content' => '', 'silo' => '', 'at_onsite' => '', 'at_offsite' => '',
                    'tla_onsite' => '', 'tla_offsite' => '', 'ltsm' => '', 'output' => ''
                ];
                if(is_string($details)) {
                    $parts = explode(',, ', $details);
                    foreach ($parts as $part) {
                        if (str_starts_with($part, 'Detailed Lesson Content:')) $lessonData['content'] = trim(str_replace('Detailed Lesson Content:', '', $part));
                        if (str_starts_with($part, 'Student Intended Learning Outcomes:')) $lessonData['silo'] = trim(str_replace('Student Intended Learning Outcomes:', '', $part));
                        if (str_starts_with($part, 'Assessment:')) { preg_match('/ONSITE: (.*) OFFSITE: (.*)/s', $part, $match); $lessonData['at_onsite'] = trim($match[1] ?? ''); $lessonData['at_offsite'] = trim($match[2] ?? ''); }
                        if (str_starts_with($part, 'Activities:')) { preg_match('/ON-SITE: (.*) OFF-SITE: (.*)/s', $part, $match); $lessonData['tla_onsite'] = trim($match[1] ?? ''); $lessonData['tla_offsite'] = trim($match[2] ?? ''); }
                        if (str_starts_with($part, 'Learning and Teaching Support Materials:')) $lessonData['ltsm'] = trim(str_replace('Learning and Teaching Support Materials:', '', $part));
                        if (str_starts_with($part, 'Output Materials:')) $lessonData['output'] = trim(str_replace('Output Materials:', '', $part));
                    }
                }
                $weekNum = (int) filter_var($week, FILTER_SANITIZE_NUMBER_INT);
            @endphp
            
            <table style="margin-bottom: 15px;">
                <tr>
                    <th colspan="2" class="week-header">{{ $week }}</th>
                </tr>
                @if(in_array($weekNum, [6, 12, 18]))
                    <tr>
                        <td colspan="2" class="text-center" style="padding: 20px;">
                            <strong>{{ $lessonData['content'] ?: ($weekNum == 6 ? 'Prelim Exam' : ($weekNum == 12 ? 'Midterm Exam' : 'Final Exam')) }}</strong>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td width="50%">
                            <div class="bold">Content:</div>
                            {{ $lessonData['content'] ?: 'N/A' }}
                        </td>
                        <td width="50%">
                            <div class="bold">Student Intended Learning Outcomes:</div>
                            {{ $lessonData['silo'] ?: 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><div class="bold">Assessment Tasks (ATs):</div></td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <div class="bold">ONSITE:</div>
                            {{ $lessonData['at_onsite'] ?: 'N/A' }}
                        </td>
                        <td width="50%">
                            <div class="bold">OFFSITE:</div>
                            {{ $lessonData['at_offsite'] ?: 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><div class="bold">Suggested Teaching/Learning Activities (TLAs):</div></td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <div class="bold">Face to Face (On-Site):</div>
                            {{ $lessonData['tla_onsite'] ?: 'N/A' }}
                        </td>
                        <td width="50%">
                            <div class="bold">Online (Off-Site):</div>
                            {{ $lessonData['tla_offsite'] ?: 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <div class="bold">Learning and Teaching Support Materials (LTSM):</div>
                            {{ $lessonData['ltsm'] ?: 'N/A' }}
                        </td>
                        <td width="50%">
                            <div class="bold">Output Materials:</div>
                            {{ $lessonData['output'] ?: 'N/A' }}
                        </td>
                    </tr>
                @endif
            </table>
        @endforeach
    @else
        <p>No weekly plan data available.</p>
    @endif

    <!-- COURSE REQUIREMENTS -->
    <div class="section-header">COURSE REQUIREMENTS AND POLICIES</div>
    
    <table>
        <tr>
            <td width="30%" class="bold">Basic Readings / Textbooks:</td>
            <td>{{ $subject->basic_readings ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="bold">Extended Readings / References:</td>
            <td>{{ $subject->extended_readings ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="bold">Course Assessment:</td>
            <td>{{ $subject->course_assessment ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="bold">Committee Members:</td>
            <td>{{ $subject->committee_members ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="bold">Consultation Schedule:</td>
            <td>{{ $subject->consultation_schedule ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- APPROVAL SECTION -->
    <div class="approval-section">
        <div class="approval-row">
            <div class="approval-cell">
                <div>Prepared:</div>
                <div class="signature-line"></div>
                <div class="bold">{{ $subject->prepared_by ?? '' }}</div>
                <div class="approval-title">Cluster Leader</div>
            </div>
            <div class="approval-cell">
                <div>Reviewed:</div>
                <div class="signature-line"></div>
                <div class="bold">{{ $subject->reviewed_by ?? '' }}</div>
                <div class="approval-title">General Education Program Head</div>
            </div>
            <div class="approval-cell">
                <div>Approved:</div>
                <div class="signature-line"></div>
                <div class="bold">{{ $subject->approved_by ?? '' }}</div>
                <div class="approval-title">Vice President for Academic Affairs</div>
            </div>
        </div>
    </div>

</body>
</html>