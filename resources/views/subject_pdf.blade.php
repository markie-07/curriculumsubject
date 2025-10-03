<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Subject Details</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 9.5px; color: #333; line-height: 1.4; }

        /* --- HEADER --- */
        .header { text-align: center; margin-bottom: 25px; }
        .header img { width: 65px; height: 65px; margin-bottom: 5px; }
        .header h1 { font-size: 15px; font-weight: bold; margin: 0; color: #2c3e50; }
        .header p { font-size: 10px; margin: 2px 0; color: #555; }

        /* --- SECTIONS --- */
        .section-title { font-size: 12px; font-weight: bold; color: #2c3e50; margin-top: 20px; margin-bottom: 10px; background-color: #ecf0f1; padding: 8px 12px; border-left: 4px solid #3498db; }
        
        /* --- TABLES --- */
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; page-break-inside: avoid; }
        th, td { border: 1px solid #bdc3c7; padding: 7px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        
        .field-label { font-weight: bold; color: #000000; }
        .description { white-space: pre-wrap; }

        /* --- SPECIALIZED TABLES --- */
        .info-table th { text-align: center; background-color: #dde1e3; }
        .info-table .sub-header { font-weight: bold; margin-bottom: 5px; }
        .core-values-table .values { font-weight: bold; }
        
        .weekly-plan-table { margin-bottom: 15px; }
        .weekly-plan-table .header-row th { background-color: #34495e; color: #FFFFFF; font-size: 11px; text-align: center; }
        .weekly-plan-table .sub-label { font-weight: bold; color: #000000; }

        .legend-box { margin-top: 15px; padding: 10px; border: 1px solid #ecf0f1; background-color: #f9f9f9; font-size: 9px; }
        .legend-box ul { padding-left: 15px; margin: 0; }
        
        .policy-box .policy-title { font-weight: bold; margin-bottom: 3px; }

        /* --- APPROVAL SECTION --- */
        .approval-table { margin-top: 30px; border: none; table-layout: fixed; }
        .approval-table td { border: none; text-align: center; vertical-align: bottom; height: 100px; width: 33.33%; }
        .approval-table .line { border-top: 1px solid #7f8c8d; width: 90%; margin: 0 auto; }
        .approval-table .label { font-size: 9px; color: #555; }
        .approval-table .name { font-weight: bold; padding-top: 5px; }
        .approval-table .title { font-size: 9px; color: #7f8c8d; }
        
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('/images/bcp logo.png') }}" alt="BCP Logo">
        <h1>BESTLINK COLLEGE OF THE PHILIPPINES</h1>
        <p>#1071 Brgy. Kaligayahan, Quirino Hi-way, Novaliches, Quezon City</p>
    </div>

    <div class="section-title">COURSE INFORMATION</div>
    <table>
        <tr>
            <td width="20%"><span class="field-label">Course Code</span></td><td width="30%">{{ $subject->subject_code }}</td>
            <td width="20%"><span class="field-label">Credit Units</span></td><td width="30%">{{ $subject->subject_unit }}</td>
        </tr>
        <tr>
            <td><span class="field-label">Course Title</span></td><td>{{ $subject->subject_name }}</td>
            <td><span class="field-label">Contact Hours</span></td><td>{{ $subject->contact_hours ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><span class="field-label">Course Type</span></td><td>{{ $subject->subject_type }}</td>
            <td><span class="field-label">Pre-requisite to</span></td><td>{{ $subject->pre_requisite_to ?? 'None' }}</td>
        </tr>
         <tr>
            <td><span class="field-label">Credit Prerequisites</span></td><td colspan="3">{{ $subject->prerequisites ?? 'None' }}</td>
        </tr>
        <tr>
            <td width="20%"><span class="field-label">Course Description</span></td>
            <td colspan="3" class="description">{{ $subject->course_description ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">INSTITUTIONAL INFORMATION</div>
    <table class="info-table">
        <thead><tr><th colspan="2">VISION</th></tr></thead>
        <tbody><tr>
            <td width="50%"><div class="sub-header">SCHOOL</div><p>BCP is committed to provide and promote quality education with a unique, modern and research-based curriculum with delivery systems geared towards excellence.</p></td>
            <td width="50%"><div class="sub-header">DEPARTMENT</div><p>To improve the quality of student's input and by promoting IT enabled, market driven and internationally comparable programs through quality assurance systems, upgrading faculty qualifications and establishing international linkages.</p></td>
        </tr></tbody>
    </table>
    <table class="info-table">
        <thead><tr><th colspan="2">MISSION</th></tr></thead>
        <tbody><tr>
            <td width="50%"><div class="sub-header">SCHOOL</div><p>To produce self-motivated and self-directed individual who aims for academic excellence, God-fearing, peaceful, healthy and productive successful citizens.</p></td>
            <td width="50%"><div class="sub-header">DEPARTMENT</div><p>The College of Computer Studies is committed to provide quality information and communication technology education through the use of modern and transformation learning teaching process.</p></td>
        </tr></tbody>
    </table>
    <table class="info-table">
        <thead><tr><th colspan="2">PHILOSOPHY</th></tr></thead>
        <tbody><tr>
            <td width="50%"><div class="sub-header">SCHOOL</div><p>BCP advocates threefold core values: “Fides”, “Faith; “Ratio”, Reason; Pax. Peace. “Fides” represents BCPs, endeavors for expansion, development, and growth amidst the challenges of the new millennium. "Ratio" symbolizes BCP's efforts to provide an education which can be man's tool to be liberated from all forms of ignorance and poverty. "Pax". BCP is a forerunner in the promotion of a harmonious relationship between the different sectors of its academic community.</p></td>
            <td width="50%"><div class="sub-header">DEPARTMENT</div><p>General Education advocates threefold core values “Devotion”, “Serenity’, “Determination” “Devotion” represents General Education commitment and dedication to provide quality education that will fuel the passion of the students for learning in driving academic success “Serenity” symbolizes a crucial element in the overall well-being and success of students by means of creating a more supportive, conducive, and enriching learning environment, enabling them to thrive academically, emotionally, and personally. “Determination” general education is committed to provide a high-quality, equitable, and supportive learning environment that empowers students to succeed.</p></td>
        </tr></tbody>
    </table>
    <table class="info-table core-values-table">
        <thead><tr><th>CORE VALUES</th></tr></thead>
        <tbody><tr><td><span class="values">FAITH, KNOWLEDGE, CHARITY AND HUMILITY</span><br><span class="values">FAITH (Fides)</span> represents BCP’s endeavor for expansion, development and for growth amidst the global challenges of the new millennium.<br><span class="values">KNOWLEDGE (Cognito)</span> connotes the institution’s efforts to impart excellent lifelong education that can be used as human tool so that one can liberate himself/herself from ignorance and poverty<br><span class="values">CHARITY (Caritas)</span> is the institution’s commitment towards its clienteles.<br><span class="values">HUMILITY (Humiliates)</span> refers to the institution’s recognition of the human frailty, its imperfection.</td></tr></tbody>
    </table>

    <div class="section-title">MAPPING GRIDS</div>
    <h3>PROGRAM MAPPING GRID</h3>
    @if(!empty($subject->program_mapping_grid))
        <table><thead><tr><th>PILO</th><th class="text-center">CTPSS</th><th class="text-center">ECC</th><th class="text-center">EPP</th><th class="text-center">GLC</th></tr></thead><tbody>@foreach($subject->program_mapping_grid as $row)<tr><td>{{ $row['pilo'] ?? '' }}</td><td class="text-center">{{ $row['ctpss'] ?? '' }}</td><td class="text-center">{{ $row['ecc'] ?? '' }}</td><td class="text-center">{{ $row['epp'] ?? '' }}</td><td class="text-center">{{ $row['glc'] ?? '' }}</td></tr>@endforeach</tbody></table>
    @else<p>No program mapping data available.</p>@endif
    <h3>COURSE MAPPING GRID</h3>
    @if(!empty($subject->course_mapping_grid))
        <table><thead><tr><th>CILO</th><th class="text-center">CTPSS</th><th class="text-center">ECC</th><th class="text-center">EPP</th><th class="text-center">GLC</th></tr></thead><tbody>@foreach($subject->course_mapping_grid as $row)<tr><td>{{ $row['cilo'] ?? '' }}</td><td class="text-center">{{ $row['ctpss'] ?? '' }}</td><td class="text-center">{{ $row['ecc'] ?? '' }}</td><td class="text-center">{{ $row['epp'] ?? '' }}</td><td class="text-center">{{ $row['glc'] ?? '' }}</td></tr>@endforeach</tbody></table>
    @else<p>No course mapping data available.</p>@endif
    <div class="legend-box"><span class="field-label">Legend:</span><ul><li><span class="field-label">L</span> – Facilitate Learning of the competencies</li><li><span class="field-label">P</span> – Allow student to practice competencies (No input but competency is evaluated)</li><li><span class="field-label">O</span> – Provide opportunity for development (No input or evaluation, but there is opportunity to practice the competencies)</li><li><span class="field-label">CTPSS</span> - critical thinking and problem-solving skills;</li><li><span class="field-label">ECC</span> - effective communication and collaboration;</li><li><span class="field-label">EPP</span> - ethical and professional practice; and,</li><li><span class="field-label">GLC</span> - global and lifelong learning commitment.</li></ul></div>

    <div class="section-title">LEARNING OUTCOMES</div>
    <table>
        <tr><td width="40%"><span class="field-label">PROGRAM INTENDED LEARNING OUTCOMES (PILO):</span></td><td class="description">{{ $subject->pilo_outcomes ?? 'N/A' }}</td></tr>
        <tr><td><span class="field-label">Course Intended Learning Outcomes (CILO):</span></td><td class="description">{{ $subject->cilo_outcomes ?? 'N/A' }}</td></tr>
        <tr><td><span class="field-label">Expected BCP Graduate Elements:</span></td>
            <td>
                The BCP ideal graduate demonstrates/internalizes this attribute:
                <ul style="padding-left: 20px; margin-top: 5px;">
                    <li>critical thinking and problem-solving skills;</li>
                    <li>effective communication and collaboration;</li>
                    <li>ethical and professional practice; and,</li>
                    <li>global and lifelong learning commitment.</li>
                </ul>
            </td>
        </tr>
        <tr><td><span class="field-label">Learning Outcomes:</span></td><td class="description">{{ $subject->learning_outcomes ?? 'N/A' }}</td></tr>
    </table>
    
    <div class="section-title">WEEKLY PLAN (WEEKS 1-15)</div>
    @if(!empty($subject->lessons))
        @foreach($subject->lessons as $week => $details)
            @php
                $lessonData = [];
                $parts = explode(',, ', $details);
                foreach ($parts as $part) {
                    if (str_starts_with($part, 'Detailed Lesson Content:')) $lessonData['content'] = trim(str_replace('Detailed Lesson Content:', '', $part));
                    if (str_starts_with($part, 'Student Intended Learning Outcomes:')) $lessonData['silo'] = trim(str_replace('Student Intended Learning Outcomes:', '', $part));
                    if (str_starts_with($part, 'Assessment:')) { preg_match('/ONSITE: (.*) OFFSITE: (.*)/s', $part, $match); $lessonData['at_onsite'] = trim($match[1] ?? ''); $lessonData['at_offsite'] = trim($match[2] ?? ''); }
                    if (str_starts_with($part, 'Activities:')) { preg_match('/ON-SITE: (.*) OFF-SITE: (.*)/s', $part, $match); $lessonData['tla_onsite'] = trim($match[1] ?? ''); $lessonData['tla_offsite'] = trim($match[2] ?? ''); }
                    if (str_starts_with($part, 'Learning and Teaching Support Materials:')) $lessonData['ltsm'] = trim(str_replace('Learning and Teaching Support Materials:', '', $part));
                    if (str_starts_with($part, 'Output Materials:')) $lessonData['output'] = trim(str_replace('Output Materials:', '', $part));
                }
            @endphp
            <table class="weekly-plan-table">
                <tr class="header-row"><th colspan="2">{{ $week }}</th></tr>
                <tr><td width="50%"><span class="sub-label">Content:</span><br>{{ $lessonData['content'] ?? '' }}</td><td width="50%"><span class="sub-label">Student Intended Learning Outcomes:</span><br>{{ $lessonData['silo'] ?? '' }}</td></tr>
                <tr><td colspan="2"><span class="sub-label">Assessment Tasks (ATs):</span></td></tr>
                <tr><td width="50%"><span class="sub-label">ONSITE:</span><br>{{ $lessonData['at_onsite'] ?? '' }}</td><td width="50%"><span class="sub-label">OFFSITE:</span><br>{{ $lessonData['at_offsite'] ?? '' }}</td></tr>
                <tr><td colspan="2"><span class="sub-label">Suggested Teaching/Learning Activities (TLAs):</span></td></tr>
                <tr><td width="50%"><span class="sub-label">Face to Face (On-Site):</span><br>{{ $lessonData['tla_onsite'] ?? '' }}</td><td width="50%"><span class="sub-label">Online (Off-Site):</span><br>{{ $lessonData['tla_offsite'] ?? '' }}</td></tr>
                <tr><td><span class="sub-label">Learning and Teaching Support Materials (LTSM):</span><br>{{ $lessonData['ltsm'] ?? '' }}</td><td><span class="sub-label">Output Materials:</span><br>{{ $lessonData['output'] ?? '' }}</td></tr>
            </table>
        @endforeach
    @else<p>No weekly plan data available.</p>@endif
    
    <div class="section-title">COURSE REQUIREMENTS AND POLICIES</div>
    <table>
        <tr><td width="30%"><span class="field-label">Basic Readings / Textbooks:</span></td><td class="description">{{ $subject->basic_readings ?? 'N/A' }}</td></tr>
        <tr><td><span class="field-label">Extended Readings / References:</span></td><td class="description">{{ $subject->extended_readings ?? 'N/A' }}</td></tr>
        <tr><td><span class="field-label">Course Assessment:</span></td><td class="description">{{ $subject->course_assessment ?? 'N/A' }}</td></tr>
        <tr><td><span class="field-label">Course Policies and Statements:</span></td>
            <td><div class="policy-box">
                <p><span class="policy-title">Learners with Disabilities</span>This course is committed in providing equal access and participation for all students including those with disabilities. If you have a disability that may require accommodations, please contact the OFFICE OF THE STUDENTS’ AFFAIRS and SERVICES to register in the LIST OF LEARNERS with Disabilities. Please be aware that it is your responsibility to communicate your needs and works with the instructor to ensure that appropriate accommodations can be arranged promptly.</p>
                <p><span class="policy-title">Syllabus Flexibility</span>The faculty reserves the right to change or amend this syllabus as needed. Any changes to the syllabus will be communicated promptly to the VPAA through the Department Heads / Deans, if any, adjustments will be made to ensure that all students can continue to meet the course objectives. Your feedback and input are valued, and we encourage open communication to facilitate a positive and productive learning experience for all.</p>
            </div></td></tr>
        <tr><td><span class="field-label">Committee Members:</span></td><td class="description">{{ $subject->committee_members ?? 'N/A' }}</td></tr>
        <tr><td><span class="field-label">Consultation Schedule:</span></td><td class="description">{{ $subject->consultation_schedule ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">APPROVAL</div>
    <table class="approval-table">
        <tr>
            <td><p class="label">Prepared:</p><p class="name">{{ $subject->prepared_by ?? '' }}</p><p class="line"></p><p class="title">Cluster Leader</p></td>
            <td><p class="label">Reviewed:</p><p class="name">{{ $subject->reviewed_by ?? '' }}</p><p class="line"></p><p class="title">General Education Program Head</p></td>
            <td><p class="label">Approved:</p><p class="name">{{ $subject->approved_by ?? '' }}</p><p class="line"></p><p class="title">Vice President for Academic Affairs</p></td>
        </tr>
    </table>
</body>
</html>