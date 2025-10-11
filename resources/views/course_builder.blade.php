@extends('layouts.app')

@section('content')
<div class="px-6 py-8 bg-gray-50">
    <div class="bg-white p-10 md:p-12 rounded-2xl shadow-lg border border-gray-200">
        <h1 class="text-4xl font-bold text-gray-800 mb-12 border-b pb-4">Course Builder</h1>

        {{-- FORM STARTS HERE to wrap all input fields --}}
        <form id="courseForm" onsubmit="return false;">
            @csrf
            {{-- This hidden input will store the ID of the subject being edited --}}
            <input type="hidden" id="subject_id" name="subject_id">

            {{-- Section 1: Course Information --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Course Information
                </h2>
                <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <label for="course_title" class="block text-sm font-medium text-gray-700">Course Title</label>
                            <input type="text" name="course_title" id="course_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
                            <input type="text" name="course_code" id="course_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="subject_type" class="block text-sm font-medium text-gray-700">Course Type</label>
                            <select name="subject_type" id="subject_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled selected>Select a Type</option>
                                <option value="Major">Major</option>
                                <option value="Minor">Minor</option>
                                <option value="Elective">Elective</option>
                                <option value="General">General Education</option>
                            </select>
                        </div>
                        <div>
                            <label for="credit_units" class="block text-sm font-medium text-gray-700">Credit Units</label>
                            <input type="number" name="credit_units" id="credit_units" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="contact_hours" class="block text-sm font-medium text-gray-700">Contact Hours</label>
                            <input type="number" name="contact_hours" id="contact_hours" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="prerequisites" class="block text-sm font-medium text-gray-700">Credit Prerequisites</label>
                            <input type="text" name="prerequisites" id="prerequisites" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="lg:col-span-3">
                            <label for="pre_requisite_to" class="block text-sm font-medium text-gray-700">Pre-requisite to</label>
                            <input type="text" name="pre_requisite_to" id="pre_requisite_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="lg:col-span-3">
                            <label for="course_description" class="block text-sm font-medium text-gray-700">Course Description</label>
                            <textarea id="course_description" name="course_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Institutional Information --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6"></path></svg>
                    Institutional Information
                </h2>
                <div class="space-y-8">
                    {{-- Vision --}}
                    <div class="bg-gray-50 p-6 rounded-lg border">
                        <h3 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">VISION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="font-semibold text-gray-600 mb-2">SCHOOL</h4>
                                <p class="text-gray-700 text-sm">BCP is committed to provide and promote quality education with a unique, modern and research-based curriculum with delivery systems geared towards excellence.</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-600 mb-2">DEPARTMENT</h4>
                                <p class="text-gray-700 text-sm">To improve the quality of student’s input and by promoting IT enabled, market driven and internationally comparable programs through quality assurance systems, upgrading faculty qualifications and establishing international linkages.</p>
                            </div>
                        </div>
                    </div>
                    {{-- Mission --}}
                    <div class="bg-gray-50 p-6 rounded-lg border">
                        <h3 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">MISSION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="font-semibold text-gray-600 mb-2">SCHOOL</h4>
                                <p class="text-gray-700 text-sm">To produce self-motivated and self-directed individual who aims for academic excellence, God-fearing, peaceful, healthy and productive successful citizens.</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-600 mb-2">DEPARTMENT</h4>
                                <p class="text-gray-700 text-sm">The College of Computer Studies is committed to provide quality information and communication technology education through the use of modern and transformation learning teaching process.</p>
                            </div>
                        </div>
                    </div>
                    {{-- Philosophy --}}
                    <div class="bg-gray-50 p-6 rounded-lg border">
                        <h3 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">PHILOSOPHY</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="font-semibold text-gray-600 mb-2">SCHOOL</h4>
                                <p class="text-gray-700 text-sm">BCP advocates threefold core values: “Fides”, “Faith; “Ratio”, Reason; Pax. Peace. “Fides” represents BCPs, endeavors for expansion, development, and growth amidst the challenges of the new millennium. "Ratio" symbolizes BCP's efforts to provide an education which can be man's tool to be liberated from all forms of ignorance and poverty. "Pax". BCP is a forerunner in the promotion of a harmonious relationship between the different sectors of its academic community.</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-600 mb-2">DEPARTMENT</h4>
                                <p class="text-gray-700 text-sm">General Education advocates threefold core values “Devotion”, “Serenity’, “Determination” “Devotion” represents General Education commitment and dedication to provide quality education that will fuel the passion of the students for learning in driving academic success “Serenity” symbolizes a crucial element in the overall well-being and success of students by means of creating a more supportive, conducive, and enriching learning environment, enabling them to thrive academically, emotionally, and personally. “Determination” general education is committed to provide a high-quality, equitable, and supportive learning environment that empowers students to succeed.</p>
                            </div>
                        </div>
                    </div>
                    {{-- Core Values --}}
                     <div class="bg-gray-50 p-6 rounded-lg border">
                        <h3 class="text-xl font-bold text-gray-700 mb-2">CORE VALUES</h3>
                        <p class="mt-2 text-gray-700 text-sm"><strong>FAITH, KNOWLEDGE, CHARITY AND HUMILITY</strong><br><strong>FAITH (Fides)</strong> represents BCP’s endeavor for expansion, development and for growth amidst the global challenges of the new millennium.<br><strong>KNOWLEDGE (Cognito)</strong> connotes the institution’s efforts to impart excellent lifelong education that can be used as human tool so that one can liberate himself/herself from ignorance and poverty<br><strong>CHARITY (Caritas)</strong> is the institution’s commitment towards its clienteles.<br><strong>HUMILITY (Humiliates)</strong> refers to the institution’s recognition of the human frailty, its imperfection.</p>
                    </div>
                </div>
            </div>

            {{-- Mapping Grids --}}
            <div class="mb-12">
                 <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Mapping Grids
                </h2>
                <div class="space-y-8">
                    <div class="p-8 border rounded-2xl shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-700">PROGRAM MAPPING GRID</h3>
                            <button id="add-program-mapping-row" type="button" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">Add Row</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">PILO</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">CTPSS</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">ECC</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">EPP</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">GLC</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="program-mapping-table-body"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="p-8 border rounded-2xl shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-700">COURSE MAPPING GRID</h3>
                            <button id="add-course-mapping-row" type="button" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">Add Row</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">CILO</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">CTPSS</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">ECC</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">EPP</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">GLC</th>
                                        <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="course-mapping-table-body"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-8 p-6 bg-gray-50 border rounded-lg">
                        <h4 class="font-bold text-gray-700">Legend:</h4>
                        <ul class="list-disc list-inside mt-2 text-gray-600 space-y-1 text-sm">
                            <li><span class="font-semibold">L</span> – Facilitate Learning of the competencies</li>
                            <li><span class="font-semibold">P</span> – Allow student to practice competencies (No input but competency is evaluated)</li>
                            <li><span class="font-semibold">O</span> – Provide opportunity for development (No input or evaluation, but there is opportunity to practice the competencies)</li>
                            <li><span class="font-semibold">CTPSS</span> - critical thinking and problem-solving skills;</li>
                            <li><span class="font-semibold">ECC</span> - effective communication and collaboration;</li>
                            <li><span class="font-semibold">EPP</span> - ethical and professional practice; and,</li>
                            <li><span class="font-semibold">GLC</span> - global and lifelong learning commitment.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Learning Outcomes --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Learning Outcomes
                </h2>
                <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100 space-y-8">
                    <div>
                        <label for="pilo_outcomes" class="block text-xl font-semibold text-gray-700 mb-2">PROGRAM INTENDED LEARNING OUTCOMES (PILO)</label>
                        <textarea id="pilo_outcomes" name="pilo_outcomes" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div>
                        <label for="cilo_outcomes" class="block text-xl font-semibold text-gray-700 mb-2">Course Intended Learning Outcomes (CILO)</label>
                        <textarea id="cilo_outcomes" name="cilo_outcomes" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                     <div class="p-4 bg-gray-50 rounded-md border">
                        <h4 class="font-semibold text-gray-600">Expected BCP Graduate Elements:</h4>
                        <p class="mt-2 text-gray-700 text-sm">The BCP ideal graduate demonstrates/internalizes this attribute:</p>
                        <ul class="list-disc list-inside mt-2 text-gray-600 space-y-1 text-sm">
                            <li>critical thinking and problem-solving skills;</li>
                            <li>effective communication and collaboration;</li>
                            <li>ethical and professional practice; and,</li>
                            <li>global and lifelong learning commitment.</li>
                        </ul>
                    </div>
                    <div>
                        <label for="learning_outcomes" class="block text-xl font-semibold text-gray-700 mb-2">Learning Outcomes</label>
                        <textarea id="learning_outcomes" name="learning_outcomes" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>
            </div>


            {{-- Weekly Plan (Weeks 1-15) --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Weekly Plan (Weeks 1-15)
                </h2>
                <div class="space-y-4">
                    @for ($i = 1; $i <= 15; $i++)
                        <div class="border rounded-2xl overflow-hidden shadow-sm">
                            <button type="button" class="w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition-colors" onclick="toggleAccordion(this)">
                                <span class="font-semibold text-lg text-gray-700">Week {{ $i }}</span>
                                <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="accordion-content bg-gray-50 p-6 border-t" style="display: none;">
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="week_{{ $i }}_content" class="block text-sm font-medium text-gray-700">Content</label>
                                            <textarea id="week_{{ $i }}_content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                        </div>
                                        <div>
                                            <label for="week_{{ $i }}_silo" class="block text-sm font-medium text-gray-700">Student Intended Learning Outcomes</label>
                                            <textarea id="week_{{ $i }}_silo" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Assessment Tasks (ATs)</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2 p-4 border rounded-md bg-white">
                                            <div>
                                                <label for="week_{{ $i }}_at_onsite" class="block text-xs font-semibold text-gray-600 mb-1">ONSITE</label>
                                                <textarea id="week_{{ $i }}_at_onsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                            </div>
                                            <div>
                                                <label for="week_{{ $i }}_at_offsite" class="block text-xs font-semibold text-gray-600 mb-1">OFFSITE</label>
                                                <textarea id="week_{{ $i }}_at_offsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Suggested Teaching/Learning Activities (TLAs)</label>
                                        <div class="mt-2 p-4 border rounded-md bg-white">
                                            <p class="text-xs font-semibold text-gray-600 mb-2">Blended Learning Delivery Modality (BLDM)</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="week_{{ $i }}_tla_onsite" class="block text-xs font-semibold text-gray-600 mb-1">Face to Face (On-Site)</label>
                                                    <textarea id="week_{{ $i }}_tla_onsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                                </div>
                                                <div>
                                                    <label for="week_{{ $i }}_tla_offsite" class="block text-xs font-semibold text-gray-600 mb-1">Online (Off-Site)</label>
                                                    <textarea id="week_{{ $i }}_tla_offsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="week_{{ $i }}_ltsm" class="block text-sm font-medium text-gray-700">Learning and Teaching Support Materials (LTSM)</label>
                                            <textarea id="week_{{ $i }}_ltsm" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                        </div>
                                        <div>
                                            <label for="week_{{ $i }}_output" class="block text-sm font-medium text-gray-700">Output Materials</label>
                                            <textarea id="week_{{ $i }}_output" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Course Requirements and Policies --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6"></path></svg>
                    Course Requirements and Policies
                </h2>
                <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="basic_readings" class="block text-sm font-medium text-gray-700">Basic Readings / Textbooks</label>
                            <textarea id="basic_readings" name="basic_readings" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label for="extended_readings" class="block text-sm font-medium text-gray-700">Extended Readings / References</label>
                            <textarea id="extended_readings" name="extended_readings" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="course_assessment" class="block text-sm font-medium text-gray-700">Course Assessment</label>
                        <textarea id="course_assessment" name="course_assessment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <h4 class="font-semibold text-gray-600 mb-2">Course Policies and Statements:</h4>
                            <ul class="space-y-4 text-gray-700 text-sm">
                                <li>
                                    <span class="font-bold block">Learners with Disabilities</span>
                                    This course is committed in providing equal access and participation for all students including those with disabilities. If you have a disability that may require accommodations, please contact the OFFICE OF THE STUDENTS’ AFFAIRS and SERVICES to register in the LIST OF LEARNERS with Disabilities. Please be aware that it is your responsibility to communicate your needs and works with the instructor to ensure that appropriate accommodations can be arranged promptly.
                                </li>
                                <li>
                                    <span class="font-bold block">Syllabus Flexibility</span>
                                    The faculty reserves the right to change or amend this syllabus as needed. Any changes to the syllabus will be communicated promptly to the VPAA through the Department Heads / Deans, if any, adjustments will be made to ensure that all students can continue to meet the course objectives. Your feedback and input are valued, and we encourage open communication to facilitate a positive and productive learning experience for all.
                                </li>
                            </ul>
                        </div>
                        {{-- Hidden textarea to hold the data for submission --}}
                        <textarea id="course_policies" name="course_policies" class="hidden">This course is committed in providing equal access and participation for all students including those with disabilities. If you have a disability that may require accommodations, please contact the OFFICE OF THE STUDENTS’ AFFAIRS and SERVICES to register in the LIST OF LEARNERS with Disabilities. Please be aware that it is your responsibility to communicate your needs and works with the instructor to ensure that appropriate accommodations can be arranged promptly. The faculty reserves the right to change or amend this syllabus as needed. Any changes to the syllabus will be communicated promptly to the VPAA through the Department Heads / Deans, if any, adjustments will be made to ensure that all students can continue to meet the course objectives. Your feedback and input are valued, and we encourage open communication to facilitate a positive and productive learning experience for all.</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="committee_members" class="block text-sm font-medium text-gray-700">Committee Members</label>
                            <textarea id="committee_members" name="committee_members" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label for="consultation_schedule" class="block text-sm font-medium text-gray-700">Consultation Schedule</label>
                            <textarea id="consultation_schedule" name="consultation_schedule" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Approval Section --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Approval
                </h2>
                 <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label for="prepared_by" class="block text-sm font-medium text-gray-700">Prepared:</label>
                            <input type="text" id="prepared_by" name="prepared_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Cluster Leader</p>
                        </div>
                        <div>
                            <label for="reviewed_by" class="block text-sm font-medium text-gray-700">Reviewed:</label>
                            <input type="text" id="reviewed_by" name="reviewed_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">General Education Program Head</p>
                        </div>
                        <div>
                            <label for="approved_by" class="block text-sm font-medium text-gray-700">Approved:</label>
                            <input type="text" id="approved_by" name="approved_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Vice President for Academic Affairs</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Save/Update Button --}}
            <div class="mt-10 pt-6 border-t border-gray-200">
                <button id="saveCourseButton" type="submit" class="w-full flex items-center justify-center space-x-2 px-6 py-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>Save Course</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAccordion(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('svg');
    if (content.style.display === "none" || content.style.display === "") {
        content.style.display = "block";
        icon.style.transform = "rotate(180deg)";
    } else {
        content.style.display = "none";
        icon.style.transform = "rotate(0deg)";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const courseForm = document.getElementById('courseForm');
    const subjectIdField = document.getElementById('subject_id');
    const saveButton = document.getElementById('saveCourseButton');
    const pageTitle = document.querySelector('h1.text-4xl');

    // --- HELPER FUNCTIONS ---

    const collectMappingGridData = (tableBodyId) => {
        const tableBody = document.getElementById(tableBodyId);
        if (!tableBody) return null;
        const rows = tableBody.querySelectorAll('tr');
        const data = [];
        rows.forEach(row => {
            const inputs = row.querySelectorAll('input[type="text"]');
            const outcomeKey = inputs[0].placeholder.includes('PILO') ? 'pilo' : 'cilo';
            const rowData = {
                [outcomeKey]: inputs[0].value,
                ctpss: inputs[1].value,
                ecc: inputs[2].value,
                epp: inputs[3].value,
                glc: inputs[4].value,
            };
            if (rowData[outcomeKey] && rowData[outcomeKey].trim() !== '') data.push(rowData);
        });
        return data.length > 0 ? data : null;
    };

    const collectWeeklyPlan = () => {
        const lessons = {};
        for (let i = 1; i <= 15; i++) {
            const content = document.getElementById(`week_${i}_content`).value;
            const silo = document.getElementById(`week_${i}_silo`).value;
            const atOnsite = document.getElementById(`week_${i}_at_onsite`).value;
            const atOffsite = document.getElementById(`week_${i}_at_offsite`).value;
            const tlaOnsite = document.getElementById(`week_${i}_tla_onsite`).value;
            const tlaOffsite = document.getElementById(`week_${i}_tla_offsite`).value;
            const ltsm = document.getElementById(`week_${i}_ltsm`).value;
            const output = document.getElementById(`week_${i}_output`).value;
            if (content || silo || atOnsite || atOffsite || tlaOnsite || tlaOffsite || ltsm || output) {
                lessons[`Week ${i}`] = [
                    `Detailed Lesson Content:\n${content}`,
                    `Student Intended Learning Outcomes:\n${silo}`,
                    `Assessment: ONSITE: ${atOnsite} OFFSITE: ${atOffsite}`,
                    `Activities: ON-SITE: ${tlaOnsite} OFF-SITE: ${tlaOffsite}`,
                    `Learning and Teaching Support Materials:\n${ltsm}`,
                    `Output Materials:\n${output}`
                ].filter(part => !part.endsWith(':\n') && !part.endsWith(': ON-SITE:  OFF-SITE: ') && !part.endsWith(': ONSITE:  OFFSITE: ')).join(',, ');
            }
        }
        return Object.keys(lessons).length > 0 ? lessons : null;
    };

    const populateMappingGrid = (tableBodyId, data, outcomeKey) => {
        const tableBody = document.getElementById(tableBodyId);
        if (!tableBody || !data) return;
        tableBody.innerHTML = '';
        data.forEach(rowData => {
            const row = createMappingTableRow(outcomeKey.toUpperCase() === 'PILO');
            const inputs = row.querySelectorAll('input[type="text"]');
            inputs[0].value = rowData[outcomeKey] || '';
            inputs[1].value = rowData.ctpss || '';
            inputs[2].value = rowData.ecc || '';
            inputs[3].value = rowData.epp || '';
            inputs[4].value = rowData.glc || '';
            tableBody.appendChild(row);
        });
    };

    const populateWeeklyPlan = (lessons) => {
        if (!lessons) return;
        for (let i = 1; i <= 15; i++) {
            const weekKey = `Week ${i}`;
            if (lessons[weekKey]) {
                const lessonString = lessons[weekKey];
                const contentMatch = lessonString.match(/Detailed Lesson Content:\n(.*?)(?=,, |$)/s);
                document.getElementById(`week_${i}_content`).value = contentMatch ? contentMatch[1].trim() : '';
                const siloMatch = lessonString.match(/Student Intended Learning Outcomes:\n(.*?)(?=,, |$)/s);
                document.getElementById(`week_${i}_silo`).value = siloMatch ? siloMatch[1].trim() : '';
                const atMatch = lessonString.match(/Assessment: ONSITE: (.*?) OFFSITE: (.*?)(?=,, |$)/s);
                if (atMatch) {
                    document.getElementById(`week_${i}_at_onsite`).value = atMatch[1].trim();
                    document.getElementById(`week_${i}_at_offsite`).value = atMatch[2].trim();
                }
                const tlaMatch = lessonString.match(/Activities: ON-SITE: (.*?) OFF-SITE: (.*?)(?=,, |$)/s);
                if (tlaMatch) {
                    document.getElementById(`week_${i}_tla_onsite`).value = tlaMatch[1].trim();
                    document.getElementById(`week_${i}_tla_offsite`).value = tlaMatch[2].trim();
                }
                const ltsmMatch = lessonString.match(/Learning and Teaching Support Materials:\n(.*?)(?=,, |$)/s);
                document.getElementById(`week_${i}_ltsm`).value = ltsmMatch ? ltsmMatch[1].trim() : '';
                const outputMatch = lessonString.match(/Output Materials:\n(.*?)(?=,, |$)/s);
                document.getElementById(`week_${i}_output`).value = outputMatch ? outputMatch[1].trim() : '';
            }
        }
    };

    const populateForm = (subject) => {
        if (!subject) return;
        document.getElementById('subject_id').value = subject.id;
        document.getElementById('course_title').value = subject.subject_name;
        document.getElementById('course_code').value = subject.subject_code;
        document.getElementById('subject_type').value = subject.subject_type;
        document.getElementById('credit_units').value = subject.subject_unit;
        document.getElementById('contact_hours').value = subject.contact_hours;
        document.getElementById('prerequisites').value = subject.prerequisites;
        document.getElementById('pre_requisite_to').value = subject.pre_requisite_to;
        document.getElementById('course_description').value = subject.course_description;
        document.getElementById('pilo_outcomes').value = subject.pilo_outcomes;
        document.getElementById('cilo_outcomes').value = subject.cilo_outcomes;
        document.getElementById('learning_outcomes').value = subject.learning_outcomes;
        document.getElementById('basic_readings').value = subject.basic_readings;
        document.getElementById('extended_readings').value = subject.extended_readings;
        document.getElementById('course_assessment').value = subject.course_assessment;
        document.getElementById('committee_members').value = subject.committee_members;
        document.getElementById('consultation_schedule').value = subject.consultation_schedule;
        document.getElementById('prepared_by').value = subject.prepared_by;
        document.getElementById('reviewed_by').value = subject.reviewed_by;
        document.getElementById('approved_by').value = subject.approved_by;

        populateMappingGrid('program-mapping-table-body', subject.program_mapping_grid, 'pilo');
        populateMappingGrid('course-mapping-table-body', subject.course_mapping_grid, 'cilo');
        populateWeeklyPlan(subject.lessons);

        pageTitle.textContent = 'Edit Course';
        saveButton.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> <span>Update Course</span>`;
    };

    const fetchSubjectData = async (id) => {
        try {
            const response = await fetch(`/api/subjects/${id}`);
            if (!response.ok) throw new Error('Subject not found.');
            const subject = await response.json();
            populateForm(subject);
        } catch (error) {
            console.error('Error fetching subject data:', error);
            showError('Loading Error', 'Failed to load subject data. You can create a new subject instead.');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    };

    // --- FORM SUBMISSION LOGIC ---
    courseForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!courseForm.checkValidity()) {
            courseForm.reportValidity();
            return;
        }

        const payload = {
            course_title: document.getElementById('course_title').value,
            subject_code: document.getElementById('course_code').value,
            subject_type: document.getElementById('subject_type').value,
            subject_unit: document.getElementById('credit_units').value,
            contact_hours: document.getElementById('contact_hours').value,
            prerequisites: document.getElementById('prerequisites').value,
            pre_requisite_to: document.getElementById('pre_requisite_to').value,
            course_description: document.getElementById('course_description').value,
            pilo_outcomes: document.getElementById('pilo_outcomes').value,
            cilo_outcomes: document.getElementById('cilo_outcomes').value,
            learning_outcomes: document.getElementById('learning_outcomes').value,
            basic_readings: document.getElementById('basic_readings').value,
            extended_readings: document.getElementById('extended_readings').value,
            course_assessment: document.getElementById('course_assessment').value,
            course_policies: document.getElementById('course_policies').value, // Make sure to collect this
            committee_members: document.getElementById('committee_members').value,
            consultation_schedule: document.getElementById('consultation_schedule').value,
            prepared_by: document.getElementById('prepared_by').value,
            reviewed_by: document.getElementById('reviewed_by').value,
            approved_by: document.getElementById('approved_by').value,
            lessons: collectWeeklyPlan(),
            program_mapping_grid: collectMappingGridData('program-mapping-table-body'),
            course_mapping_grid: collectMappingGridData('course-mapping-table-body'),
        };

        const subjectId = subjectIdField.value;
        const method = subjectId ? 'PUT' : 'POST';
        const url = subjectId ? `/api/subjects/${subjectId}` : '/api/subjects';

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (!response.ok) {
                let errorMessage = result.message || `Failed to ${method === 'POST' ? 'create' : 'update'} course.`;
                if (result.errors) errorMessage += '\n' + Object.values(result.errors).flat().join('\n');
                throw new Error(errorMessage);
            }

            const action = subjectId ? 'updated' : 'created';
            if (action === 'updated') {
                showSuccess('Subject Updated', 'Subject updated successfully!');
                setTimeout(() => {
                    window.location.href = `/subject_mapping`;
                }, 1500);
            } else {
                showSuccess('Subject Created', 'Subject created successfully!');
                if (confirm("Do you want to set up the grade components now?")) {
                    const newSubjectName = encodeURIComponent(`${result.subject.subject_name} (${result.subject.subject_code})`);
                    window.location.href = `/grade-setup?new_subject_id=${result.subject.id}&new_subject_name=${newSubjectName}`;
                } else {
                    window.location.href = `/subject_mapping`;
                }
            }
        } catch (error) {
            showError('Save Error', `Error saving course: ${error.message}`);
        }
    });

    // --- MAPPING GRID ROW LOGIC ---
    const createMappingTableRow = (isPilo = true) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `<td class="py-2 px-4 border-b"><input type="text" placeholder="${isPilo ? 'PILO...' : 'CILO...'}" class="w-full p-1 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b text-center"><button type="button" class="delete-row-btn text-red-500 hover:text-red-700 font-semibold">Delete</button></td>`;
        return row;
    };
    document.getElementById('add-program-mapping-row').addEventListener('click', () => document.getElementById('program-mapping-table-body').appendChild(createMappingTableRow(true)));
    document.getElementById('add-course-mapping-row').addEventListener('click', () => document.getElementById('course-mapping-table-body').appendChild(createMappingTableRow(false)));
    document.querySelector('.bg-white').addEventListener('click', (e) => {
        if (e.target.classList.contains('delete-row-btn')) e.target.closest('tr').remove();
    });

    // --- INITIALIZATION ---
    const urlParams = new URLSearchParams(window.location.search);
    const subjectIdToEdit = urlParams.get('subject_id');
    if (subjectIdToEdit) {
        fetchSubjectData(subjectIdToEdit);
    }
});
</script>
@endsection