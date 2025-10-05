<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Curriculum Details</title>
    <style>
        @page { margin: 25px; }
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
        
        .page-break { page-break-before: always; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    @php
        // Fetch all prerequisite relationships for the current curriculum.
        $allPrerequisites = \App\Models\Prerequisite::where('curriculum_id', $curriculum->id)->get();

        // MAP 1: PARENTS (for Credit Prerequisites)
        // Maps a subject to the prerequisite(s) it immediately requires.
        // DB: subject_code='qqq', prerequisite_subject_code='111'
        // Result: 'qqq' => ['111']
        $subjectToParentsMap = $allPrerequisites->groupBy('subject_code')->map(function ($item) {
            return $item->pluck('prerequisite_subject_code')->all();
        })->all();

        // MAP 2: CHILDREN (for Pre-requisite to)
        // Maps a subject to the subject(s) that require it.
        // DB: subject_code='qqq', prerequisite_subject_code='111'
        // Result: '111' => ['qqq']
        $subjectToChildrenMap = $allPrerequisites->groupBy('prerequisite_subject_code')->map(function ($item) {
            return $item->pluck('subject_code')->all();
        })->all();

        // Recursive function to find the entire chain of "Credit Prerequisites" by traversing up the parent chain.
        $findAllCreditPrerequisites = function($subjectCode, $map) use (&$findAllCreditPrerequisites) {
            if (!isset($map[$subjectCode])) {
                return [];
            }
            $parents = $map[$subjectCode];
            foreach ($parents as $parentCode) {
                $parents = array_merge($parents, $findAllCreditPrerequisites($parentCode, $map));
            }
            return array_unique($parents);
        };
    @endphp

    {{-- =================================================================== --}}
    {{-- PART 1: CURRICULUM SUMMARY PAGE --}}
    {{-- =================================================================== --}}
    <div class="header">
        <img src="{{ public_path('/images/bcp logo.png') }}" alt="BCP Logo">
        <h1>BESTLINK COLLEGE OF THE PHILIPPINES</h1>
        <p>#1071 Brgy. Kaligayahan, Quirino Hi-way, Novaliches, Quezon City</p>
    </div>

    <div class="section-title">CURRICULUM OVERVIEW</div>
    <table>
        <tr>
            <td width="20%"><span class="field-label">Curriculum</span></td><td width="30%">{{ $curriculum->curriculum }}</td>
            <td width="20%"><span class="field-label">Program Code</span></td><td width="30%">{{ $curriculum->program_code }}</td>
        </tr>
        <tr>
            <td><span class="field-label">Academic Year</span></td><td>{{ $curriculum->academic_year }}</td>
            <td><span class="field-label">Year Level</span></td><td>{{ $curriculum->year_level }}</td>
        </tr>
    </table>

    @php
        $subjectsByYearAndSem = [];
        foreach ($curriculum->subjects as $subject) {
            $key = $subject->pivot->year . '-' . $subject->pivot->semester;
            if (!isset($subjectsByYearAndSem[$key])) {
                $subjectsByYearAndSem[$key] = [];
            }
            $subjectsByYearAndSem[$key][] = $subject;
        }
        ksort($subjectsByYearAndSem);
    @endphp

    @foreach($subjectsByYearAndSem as $key => $subjects)
        @php
            [$year, $semester] = explode('-', $key);
            $semesterText = $semester == 1 ? 'First Semester' : ($semester == 2 ? 'Second Semester' : 'Summer');
            $yearOrdinal = match((int)$year) {
                1 => '1st',
                2 => '2nd',
                3 => '3rd',
                default => $year . 'th',
            };
        @endphp
        <div class="section-title">{{ $yearOrdinal }} Year - {{ $semesterText }}</div>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                <tr>
                    <td>{{ $subject->subject_code }}</td>
                    <td>{{ $subject->subject_name }}</td>
                    <td class="text-center">{{ $subject->subject_unit }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    {{-- =================================================================== --}}
    {{-- PART 2: DETAILED SUBJECT SYLLABUS PAGES --}}
    {{-- =================================================================== --}}
    @foreach($curriculum->subjects as $subject)
        <div class="page-break"></div>

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
                <td><span class="field-label">Pre-requisite to</span></td>
                <td>
                    @php
                        $childSubjects = $subjectToChildrenMap[$subject->subject_code] ?? [];
                        $prereqToString = !empty($childSubjects) ? implode(', ', $childSubjects) : 'None';
                    @endphp
                    {{ $prereqToString }}
                </td>
            </tr>
             <tr>
                <td><span class="field-label">Credit Prerequisites</span></td>
                <td colspan="3">
                    @php
                        $allCreditPrerequisites = $findAllCreditPrerequisites($subject->subject_code, $subjectToParentsMap);
                    @endphp
                    @if(!empty($allCreditPrerequisites))
                        {{ implode(', ', $allCreditPrerequisites) }}
                    @else
                        None
                    @endif
                </td>
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
        {{-- ... other institutional info tables ... --}}

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
        
        <div class="page-break"></div>
        <div class="header">
            <img src="{{ public_path('/images/bcp logo.png') }}" alt="BCP Logo">
            <h1>BESTLINK COLLEGE OF THE PHILIPPINES</h1>
            <p>#1071 Brgy. Kaligayahan, Quirino Hi-way, Novaliches, Quezon City</p>
        </div>
        <div class="section-title">WEEKLY PLAN (WEEKS 1-15) for {{ $subject->subject_code }}</div>
        @if(!empty($subject->lessons) && is_array($subject->lessons))
            @foreach(collect($subject->lessons)->sortBy(fn($val, $key) => (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT)) as $week => $details)
                @php
                    $lessonData = ['content' => '','silo' => '','at_onsite' => '','at_offsite' => '','tla_onsite' => '','tla_offsite' => '','ltsm' => '','output' => ''];
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
                @endphp
                <table class="weekly-plan-table">
                    <tr class="header-row"><th colspan="2">{{ $week }}</th></tr>
                    <tr><td width="50%"><span class="sub-label">Content:</span><br>{!! nl2br(e($lessonData['content'] ?: 'N/A')) !!}</td><td width="50%"><span class="sub-label">Student Intended Learning Outcomes:</span><br>{!! nl2br(e($lessonData['silo'] ?: 'N/A')) !!}</td></tr>
                    <tr><td colspan="2"><span class="sub-label">Assessment Tasks (ATs):</span></td></tr>
                    <tr><td width="50%"><span class="sub-label">ONSITE:</span><br>{!! nl2br(e($lessonData['at_onsite'] ?: 'N/A')) !!}</td><td width="50%"><span class="sub-label">OFFSITE:</span><br>{!! nl2br(e($lessonData['at_offsite'] ?: 'N/A')) !!}</td></tr>
                    <tr><td colspan="2"><span class="sub-label">Suggested Teaching/Learning Activities (TLAs):</span></td></tr>
                    <tr><td width="50%"><span class="sub-label">Face to Face (On-Site):</span><br>{!! nl2br(e($lessonData['tla_onsite'] ?: 'N/A')) !!}</td><td width="50%"><span class="sub-label">Online (Off-Site):</span><br>{!! nl2br(e($lessonData['tla_offsite'] ?: 'N/A')) !!}</td></tr>
                    <tr><td><span class="sub-label">Learning and Teaching Support Materials (LTSM):</span><br>{!! nl2br(e($lessonData['ltsm'] ?: 'N/A')) !!}</td><td><span class="sub-label">Output Materials:</span><br>{!! nl2br(e($lessonData['output'] ?: 'N/A')) !!}</td></tr>
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

        <div class="section-title">GRADING SYSTEM</div>
        <table>
            <tr>
                <td>
                    @if($subject->grades->isNotEmpty() && !empty($subject->grades->first()->components))
                        @php
                            $gradeData = $subject->grades->first()->components;
                        @endphp
                        <table style="margin-bottom: 0; border: none;">
                            @foreach($gradeData as $period => $details)
                                @if(isset($details['components']) && count($details['components']) > 0)
                                    <tr>
                                        <th colspan="2" style="background-color: #e2e8f0; text-align: left; border: 1px solid #bdc3c7; padding: 7px;">
                                            <strong>{{ ucfirst($period) }} (Overall: {{ $details['weight'] ?? 0 }}%)</strong>
                                        </th>
                                    </tr>
                                    @foreach($details['components'] as $component)
                                        <tr>
                                            <td style="width: 70%; padding-left: 20px; border: 1px solid #bdc3c7;">{{ $component['name'] }}</td>
                                            <td class="text-center" style="border: 1px solid #bdc3c7;">{{ $component['weight'] }}%</td>
                                        </tr>
                                        @if(isset($component['sub_components']) && count($component['sub_components']) > 0)
                                            @foreach($component['sub_components'] as $sub)
                                                <tr>
                                                    <td style="padding-left: 40px; font-style: italic; border: 1px solid #bdc3c7;">{{ $sub['name'] }}</td>
                                                    <td class="text-center" style="border: 1px solid #bdc3c7;">{{ $sub['weight'] }}%</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </table>
                    @else
                        <p style="margin:0;">No grading system has been set up for this subject.</p>
                    @endif
                </td>
            </tr>
        </table>
        
        <div class="section-title">APPROVAL</div>
        <table class="approval-table">
            <tr>
                <td><p class="label">Prepared:</p><p class="name">{{ $subject->prepared_by ?? '' }}</p><p class="line"></p><p class="title">Cluster Leader</p></td>
                <td><p class="label">Reviewed:</p><p class="name">{{ $subject->reviewed_by ?? '' }}</p><p class="line"></p><p class="title">General Education Program Head</p></td>
                <td><p class="label">Approved:</p><p class="name">{{ $subject->approved_by ?? '' }}</p><p class="line"></p><p class="title">Vice President for Academic Affairs</p></td>
            </tr>
        </table>
    @endforeach
</body>
</html>

