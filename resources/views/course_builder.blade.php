@extends('layouts.app')

@section('content')
<div class="px-6 py-8 bg-gray-50">
    <div class="bg-white p-10 md:p-12 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-12 border-b pb-4">
            <h1 class="text-4xl font-bold text-gray-800">Course Builder</h1>
            {{-- Syllabus Type Toggle --}}
            <div class="bg-gray-200 p-1 rounded-lg inline-flex shadow-inner">
                <button type="button" id="btn-ched" class="px-6 py-2 rounded-md text-sm font-semibold transition-all duration-200 bg-white text-blue-600 shadow-sm" onclick="switchSyllabus('CHED')">
                    CHED Format
                </button>
                <button type="button" id="btn-deped" class="px-6 py-2 rounded-md text-sm font-semibold text-gray-600 hover:text-gray-800 transition-all duration-200" onclick="switchSyllabus('DepEd')">
                    DepEd Format
                </button>
            </div>
        </div>

        {{-- FORM STARTS HERE to wrap all input fields --}}
        <form id="courseForm" onsubmit="return false;">
            @csrf
            {{-- This hidden input will store the ID of the subject being edited --}}
            <input type="hidden" id="subject_id" name="subject_id">
            <input type="hidden" id="syllabus_type" name="syllabus_type" value="CHED">

            {{-- Section 1: Course Information (Shared) --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Course Information
                    </span>
                </h2>
                <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <label for="course_title" class="block text-sm font-medium text-gray-700">Course Title</label>
                            <input type="text" name="course_title" id="course_title" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
                            <input type="text" name="course_code" id="course_code" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="subject_type" class="block text-sm font-medium text-gray-700">Course Type</label>
                            <select name="subject_type" id="subject_type" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled selected>Select a Type</option>
                                <option value="Major">Major</option>
                                <option value="Minor">Minor</option>
                            </select>
                        </div>
                        {{-- CHED Specific Fields --}}
                        <div id="ched-course-info-fields" class="contents">
                            <div>
                                <label for="credit_units" class="block text-sm font-medium text-gray-700">Credit Units</label>
                                <input type="number" name="credit_units" id="credit_units" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="contact_hours" class="block text-sm font-medium text-gray-700">Contact Hours</label>
                                <input type="number" name="contact_hours" id="contact_hours" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        {{-- DepEd Specific Fields --}}
                        <div id="deped-course-info-fields" class="contents hidden">
                            <div>
                                <label for="time_allotment" class="block text-sm font-medium text-gray-700">Time Allotment</label>
                                <input type="text" name="time_allotment" id="time_allotment" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="e.g. 80 hours / semester">
                            </div>
                            <div>
                                <label for="schedule" class="block text-sm font-medium text-gray-700">Schedule</label>
                                <input type="text" name="schedule" id="schedule" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="e.g. M-W-F 9:00-10:00">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Applicable Curriculums</label>
                            <button type="button" id="openCurriculumModal" class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm bg-white text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <span class="text-gray-500" id="curriculumButtonText">Select curriculums for this subject...</span>
                                <svg class="w-5 h-5 text-gray-400 float-right mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="lg:col-span-3">
                            <label for="course_description" class="block text-sm font-medium text-gray-700">Course Description</label>
                            <textarea id="course_description" name="course_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    

                </div>
            </div>


            {{-- DepEd Curriculum Guide Grids (Hidden by default) --}}
            <div id="deped-curriculum-grids" class="mb-12 hidden">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Curriculum Guide
                </h2>
                <div class="space-y-6">
                    @for ($q = 1; $q <= 2; $q++)
                    <div class="border rounded-2xl overflow-hidden shadow-sm">
                        <button type="button" class="w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition-colors" onclick="toggleAccordion(this)">
                            <span class="font-semibold text-lg text-gray-700">Quarter {{ $q }}</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content bg-gray-50 p-6 border-t" style="display: none;">
                            <div class="grid grid-cols-1 gap-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="q_{{ $q }}_content" class="block text-sm font-medium text-gray-700">Content</label>
                                        <textarea id="q_{{ $q }}_content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Enter Content..."></textarea>
                                    </div>
                                    <div>
                                        <label for="q_{{ $q }}_content_standards" class="block text-sm font-medium text-gray-700">Content Standards</label>
                                        <textarea id="q_{{ $q }}_content_standards" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="The learners demonstrate understanding of..."></textarea>
                                    </div>
                                    <div>
                                        <label for="q_{{ $q }}_learning_competencies" class="block text-sm font-medium text-gray-700">Learning Competencies</label>
                                        <textarea id="q_{{ $q }}_learning_competencies" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="The learners..."></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label for="q_{{ $q }}_performance_standards" class="block text-sm font-medium text-gray-700">Performance Standards</label>
                                    <textarea id="q_{{ $q }}_performance_standards" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="The learners shall be able to..."></textarea>
                                </div>
                                <div>
                                    <label for="q_{{ $q }}_performance_task" class="block text-sm font-medium text-gray-700">Suggested Performance Task</label>
                                    <textarea id="q_{{ $q }}_performance_task" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Enter Suggested Performance Task..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            {{-- CHED CONTAINER --}}
            <div id="ched-container">



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
                    <div class="p-8 border rounded-2xl shadow-md mapping-grid-container">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-700">PROGRAM MAPPING GRID</h3>
                            <button id="add-program-mapping-row" type="button" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 hover:text-white transition-colors">Add Row</button>
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
                    <div class="p-8 border rounded-2xl shadow-md mapping-grid-container">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-700">COURSE MAPPING GRID</h3>
                            <button id="add-course-mapping-row" type="button" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 hover:text-white transition-colors">Add Row</button>
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


            {{-- Weekly Plan (Weeks 0-18) --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Weekly Plan (Weeks 0-18)
                </h2>
                <div class="space-y-4">
                    @for ($i = 0; $i <= 18; $i++)
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



            </div> {{-- End of CHED Container --}}

            {{-- Approval Section (Shared) --}}
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Approval
                </h2>
                <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label for="prepared_by" class="block text-sm font-medium text-gray-700">Prepared:</label>
                            <input type="text" id="prepared_by" name="prepared_by" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Cluster Leader</p>
                        </div>
                        <div>
                            <label for="reviewed_by" class="block text-sm font-medium text-gray-700">Reviewed:</label>
                            <input type="text" id="reviewed_by" name="reviewed_by" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">General Education Program Head</p>
                        </div>
                        <div>
                            <label for="approved_by" class="block text-sm font-medium text-gray-700">Approved:</label>
                            <input type="text" id="approved_by" name="approved_by" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Vice President for Academic Affairs</p>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Save/Update Button --}}
            <div class="mt-10 pt-6 border-t border-gray-200">
                <button id="saveCourseButton" type="submit" class="w-full flex items-center justify-center space-x-2 px-6 py-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>Save Course</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Save Course Confirmation Modal --}}
<div id="saveCourseConfirmModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Save Course?</h3>
            <p class="text-sm text-gray-500 mt-2">Do you want to save this course?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelSaveCourse" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">No</button>
                <button id="confirmSaveCourse" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- Curriculum Selection Modal --}}
<div id="curriculumSelectionModal" class="fixed inset-0 z-50 overflow-hidden bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden flex items-center justify-center p-4">
    <div class="relative bg-white w-full max-w-7xl h-[85vh] flex flex-col rounded-2xl shadow-2xl">
        <div class="flex justify-between items-center p-6 border-b border-gray-200 shrink-0">
            <h3 class="text-xl font-semibold text-gray-800">Select Applicable Curriculums</h3>
        </div>
        
        <div class="p-6 flex-1 flex flex-col overflow-hidden">
            <p class="text-sm text-gray-600 mb-4 shrink-0">Choose which curriculums this subject will be available for in subject mapping:</p>

            <div class="mb-4 shrink-0">
                <div class="relative">
                    <input id="curriculumSearchInput" type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search curriculum...">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-1 overflow-hidden min-h-0">
                <div class="flex flex-col h-full min-h-0">
                    <div class="flex items-center justify-between mb-2 shrink-0">
                        <h4 id="seniorHighHeader" class="text-sm font-semibold text-gray-700">Senior High</h4>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600"><input id="selectAllSeniorHigh" type="checkbox" class="w-4 h-4 text-blue-600 rounded"> <span>Select all</span></label>
                    </div>
                    <div id="seniorHighContainer" class="flex-1 overflow-y-auto space-y-3 pr-2 min-h-0"></div>
                </div>
                <div class="flex flex-col h-full min-h-0">
                    <div class="flex items-center justify-between mb-2 shrink-0">
                        <h4 id="collegeHeader" class="text-sm font-semibold text-gray-700">College</h4>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600"><input id="selectAllCollege" type="checkbox" class="w-4 h-4 text-blue-600 rounded"> <span>Select all</span></label>
                    </div>
                    <div id="collegeContainer" class="flex-1 overflow-y-auto space-y-3 pr-2 min-h-0"></div>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6 pt-4 border-t border-gray-200 shrink-0">
                <button id="cancelCurriculumSelection" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmCurriculumSelection" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    Apply Selection
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Course Success Modal --}}
<div id="courseSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 id="courseSuccessTitle" class="text-lg font-semibold text-gray-800">Course Created Successfully!</h3>
            <p id="courseSuccessMessage" class="text-sm text-gray-500 mt-2">Your new subject has been created successfully!</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="skipGradeSetup" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Skip</button>
                <button id="proceedToGradeSetup" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Set Up Grades</button>
            </div>
        </div>
    </div>
</div>

{{-- Course Update Success Modal --}}
<div id="courseUpdateSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Course Updated Successfully!</h3>
            <p class="text-sm text-gray-500 mt-2">Your subject has been updated successfully!</p>
            <div class="mt-6">
                <button id="closeCourseUpdateModal" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
            </div>
        </div>
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

function switchSyllabus(type) {
    const chedContainer = document.getElementById('ched-container');
    const depedContainer = document.getElementById('deped-container');
    const btnChed = document.getElementById('btn-ched');
    const btnDeped = document.getElementById('btn-deped');
    const syllabusTypeInput = document.getElementById('syllabus_type');
    
    // Course Info Fields
    const chedFields = document.getElementById('ched-course-info-fields');
    const depedFields = document.getElementById('deped-course-info-fields');
    const depedGrids = document.getElementById('deped-curriculum-grids');

    syllabusTypeInput.value = type;

    if (type === 'CHED') {
        chedContainer.classList.remove('hidden');
        // depedContainer.classList.add('hidden'); // Removed depedContainer
        
        // Show CHED fields, Hide DepEd fields and grids
        chedFields.classList.remove('hidden');
        depedFields.classList.add('hidden');
        depedGrids.classList.add('hidden');
        
        btnChed.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
        btnChed.classList.remove('text-gray-600');
        
        btnDeped.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
        btnDeped.classList.add('text-gray-600');
    } else {
        chedContainer.classList.add('hidden');
        // depedContainer.classList.remove('hidden'); // Removed depedContainer
        
        // Hide CHED fields, Show DepEd fields and grids
        chedFields.classList.add('hidden');
        depedFields.classList.remove('hidden');
        depedGrids.classList.remove('hidden');
        
        btnDeped.classList.add('bg-white', 'text-red-600', 'shadow-sm');
        btnDeped.classList.remove('text-gray-600');
        
        btnChed.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
        btnChed.classList.add('text-gray-600');
    }
}



document.addEventListener('DOMContentLoaded', function () {
    const courseForm = document.getElementById('courseForm');
    const subjectIdField = document.getElementById('subject_id');
    const saveButton = document.getElementById('saveCourseButton');
    const pageTitle = document.querySelector('h1.text-4xl');

    // --- DEFAULT WEEK 0 CONTENT ---
    const populateWeek0DefaultContent = () => {
        // Only populate if Week 0 fields are empty (for new courses)
        if (document.getElementById('week_0_content').value.trim() === '') {
            document.getElementById('week_0_content').value = `BCP Vision, Mission, Goals, Objectives, Philosophy and School Organizational Structure School Policies Orientation in Online and Modular Learning System`;
            
            document.getElementById('week_0_silo').value = `Recite, internalize and explain the BCP Vision, Mission, Goals, Objectives and Philosophy
Deepen knowledge on the school's policies, guidelines, rules and regulations.
Familiarize on the new normal system of education through online, modular learning system and Blended Learning Delivery Modality (BLDM)`;
            
            document.getElementById('week_0_at_onsite').value = `Identify the Vision and Mission statements of BCP.
Critically analyze these statements in 500 words, focusing on the following:
The clarity and coherence of the statements.
How these statements reflect the school's commitment to education.
The alignment of these statements with the current educational landscape.
Verify that the BCP VMGO are align with the school goals thru checklist (PMED tools)`;
            
            document.getElementById('week_0_at_offsite').value = `Navigate LMS and know the function of each feature.`;
            
            document.getElementById('week_0_tla_onsite').value = `Read and internalize the following printed materials for Students and Parents Orientation:`;
            
            document.getElementById('week_0_tla_offsite').value = `Presentation and Lecture Discussion (PowerPoint Presentation) of Students and Parents Orientation:`;
            
            document.getElementById('week_0_ltsm').value = `Instructional Materials
Video Presentation of BCP Hymn
YouTube Video Title: BESTLINK COLLEGE OF THE PHILIPPINES HYMN (BCP hymn)
https://www.youtube.com/watch?v=NuEYO11Wb4E
Student Hand Book
Learning Management System`;
            
            document.getElementById('week_0_output').value = `Check alignment report`;
        }
    };

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
        for (let i = 0; i <= 18; i++) {
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
        for (let i = 0; i <= 18; i++) {
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

        // Load curriculum relationships if available
        if (subject.curriculums && subject.curriculums.length > 0) {
            selectedCurriculums = new Set(subject.curriculums.map(c => c.id));
            updateCurriculumButtonText();
        }

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
        
        // Show confirmation modal first
        document.getElementById('saveCourseConfirmModal').classList.remove('hidden');
    });

    // Handle the actual save logic when user confirms
    const handleCourseSave = async () => {

        const payload = {
            course_title: document.getElementById('course_title').value,
            subject_code: document.getElementById('course_code').value,
            subject_type: document.getElementById('subject_type').value,
            subject_unit: document.getElementById('credit_units').value,
            contact_hours: document.getElementById('contact_hours').value,
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
            curriculum_ids: Array.from(selectedCurriculums), // Include selected curriculums
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
                // Show update success modal
                document.getElementById('courseUpdateSuccessModal').classList.remove('hidden');
            } else {
                // Show create success modal with grade setup option
                document.getElementById('courseSuccessModal').classList.remove('hidden');
                
                // Store subject data for grade setup
                window.newSubjectData = {
                    id: result.subject.id,
                    name: result.subject.subject_name,
                    code: result.subject.subject_code
                };
            }
        } catch (error) {
            showError('Save Error', `Error saving course: ${error.message}`);
        }
    };

    // --- MODAL EVENT HANDLERS ---
    // Save Course Confirmation Modal
    document.getElementById('cancelSaveCourse').addEventListener('click', () => {
        document.getElementById('saveCourseConfirmModal').classList.add('hidden');
    });
    
    document.getElementById('confirmSaveCourse').addEventListener('click', () => {
        document.getElementById('saveCourseConfirmModal').classList.add('hidden');
        handleCourseSave();
    });
    
    // Course Success Modal (Create)
    document.getElementById('skipGradeSetup').addEventListener('click', () => {
        document.getElementById('courseSuccessModal').classList.add('hidden');
        window.location.href = `/subject_mapping`;
    });
    
    document.getElementById('proceedToGradeSetup').addEventListener('click', () => {
        document.getElementById('courseSuccessModal').classList.add('hidden');
        if (window.newSubjectData) {
            const newSubjectName = encodeURIComponent(`${window.newSubjectData.name} (${window.newSubjectData.code})`);
            window.location.href = `/grade-setup?new_subject_id=${window.newSubjectData.id}&new_subject_name=${newSubjectName}`;
        }
    });
    
    // Course Update Success Modal
    document.getElementById('closeCourseUpdateModal').addEventListener('click', () => {
        document.getElementById('courseUpdateSuccessModal').classList.add('hidden');
        window.location.href = `/subject_mapping`;
    });

    // --- MAPPING GRID ROW LOGIC ---
    const createMappingTableRow = (isPilo = true) => {
        const row = document.createElement('tr');
        row.className = '';
        row.innerHTML = `<td class="py-2 px-4 border-b"><input type="text" placeholder="${isPilo ? 'PILO...' : 'CILO...'}" class="w-full p-1 border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b text-center"><button type="button" class="delete-row-btn text-red-500 hover:text-red-700 font-semibold">Delete</button></td>`;
        return row;
    };
    document.getElementById('add-program-mapping-row').addEventListener('click', () => document.getElementById('program-mapping-table-body').appendChild(createMappingTableRow(true)));
    document.getElementById('add-course-mapping-row').addEventListener('click', () => document.getElementById('course-mapping-table-body').appendChild(createMappingTableRow(false)));
    
    // Event delegation for delete buttons in both tables
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('delete-row-btn')) {
            e.target.closest('tr').remove();
        }
    });

    // --- CURRICULUM SELECTION MODAL ---
    let selectedCurriculums = new Set();
    let allCurriculums = [];

    const loadCurriculums = async () => {
        try {
            const response = await fetch('/api/curriculums');
            if (response.ok) {
                const data = await response.json();
                // Filter to match Curriculum Builder's "New" view (includes null as new)
                allCurriculums = data.filter(c => (c.version_status || 'new') === 'new');
                renderCurriculumChecklist();
            } else {
                console.error('Failed to load curriculums');
            }
        } catch (error) {
            console.error('Error loading curriculums:', error);
        }
    };

    const renderCurriculumChecklist = () => {
        const term = (document.getElementById('curriculumSearchInput')?.value || '').toLowerCase();
        const filterMatch = (c) => {
            const s = `${c.curriculum_name} ${c.program_code} ${c.academic_year}`.toLowerCase();
            return term === '' || s.includes(term);
        };

        const seniorHigh = allCurriculums.filter(c => c.year_level === 'Senior High' && filterMatch(c));
        // Match Curriculum Builder logic: anything not Senior High goes to College
        const college = allCurriculums.filter(c => c.year_level !== 'Senior High' && filterMatch(c));

        const renderGroup = (containerId, list) => {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            list.forEach(curriculum => {
                const isSelected = selectedCurriculums.has(curriculum.id);
                const card = document.createElement('div');
                card.className = `border rounded-lg p-4 cursor-pointer transition-all duration-200 ${isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'}`;
                card.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" class="curriculum-checkbox w-5 h-5 text-blue-600 rounded focus:ring-blue-500" data-curriculum-id="${curriculum.id}" ${isSelected ? 'checked' : ''}>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${curriculum.curriculum_name}</h4>
                            <p class="text-sm text-gray-600">${curriculum.program_code} - ${curriculum.academic_year}</p>
                        </div>
                    </div>`;
                card.addEventListener('click', (e) => {
                    if (e.target.tagName !== 'INPUT') {
                        const checkbox = card.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
                const checkbox = card.querySelector('input[type="checkbox"]');
                checkbox.addEventListener('change', (e) => {
                    const curriculumId = parseInt(e.target.dataset.curriculumId);
                    if (e.target.checked) {
                        selectedCurriculums.add(curriculumId);
                    } else {
                        selectedCurriculums.delete(curriculumId);
                    }
                    renderCurriculumChecklist();
                });
                container.appendChild(card);
            });
        };

        renderGroup('seniorHighContainer', seniorHigh);
        renderGroup('collegeContainer', college);

        // Update Header Counts
        const shSelectedCount = seniorHigh.filter(c => selectedCurriculums.has(c.id)).length;
        const coSelectedCount = college.filter(c => selectedCurriculums.has(c.id)).length;
        
        const shHeader = document.getElementById('seniorHighHeader');
        if (shHeader) shHeader.textContent = `Senior High (${shSelectedCount} selected)`;
        
        const coHeader = document.getElementById('collegeHeader');
        if (coHeader) coHeader.textContent = `College (${coSelectedCount} selected)`;

        const shToggle = document.getElementById('selectAllSeniorHigh');
        const coToggle = document.getElementById('selectAllCollege');
        shToggle.checked = seniorHigh.length > 0 && seniorHigh.every(c => selectedCurriculums.has(c.id));
        coToggle.checked = college.length > 0 && college.every(c => selectedCurriculums.has(c.id));
        shToggle.onchange = (e) => {
            if (e.target.checked) {
                seniorHigh.forEach(c => selectedCurriculums.add(c.id));
            } else {
                seniorHigh.forEach(c => selectedCurriculums.delete(c.id));
            }
            renderCurriculumChecklist();
        };
        coToggle.onchange = (e) => {
            if (e.target.checked) {
                college.forEach(c => selectedCurriculums.add(c.id));
            } else {
                college.forEach(c => selectedCurriculums.delete(c.id));
            }
            renderCurriculumChecklist();
        };
    };

    const updateCurriculumButtonText = () => {
        const buttonText = document.getElementById('curriculumButtonText');
        if (selectedCurriculums.size === 0) {
            buttonText.textContent = 'Select curriculums for this subject...';
            buttonText.className = 'text-gray-500';
        } else {
            buttonText.textContent = `${selectedCurriculums.size} Curriculum(s) Selected`;
            buttonText.className = 'text-gray-800';
        }
    };

    // Modal event listeners
    document.getElementById('openCurriculumModal').addEventListener('click', () => {
        document.getElementById('curriculumSelectionModal').classList.remove('hidden');
        loadCurriculums();
    });

    // Close button removed
    // document.getElementById('closeCurriculumModal').addEventListener('click', () => {
    //     document.getElementById('curriculumSelectionModal').classList.add('hidden');
    // });

    document.getElementById('cancelCurriculumSelection').addEventListener('click', () => {
        document.getElementById('curriculumSelectionModal').classList.add('hidden');
    });

    document.getElementById('confirmCurriculumSelection').addEventListener('click', () => {
        document.getElementById('curriculumSelectionModal').classList.add('hidden');
        updateCurriculumButtonText();
    });

    document.getElementById('curriculumSearchInput')?.addEventListener('input', () => {
        renderCurriculumChecklist();
    });

    // --- INITIALIZATION ---
    const urlParams = new URLSearchParams(window.location.search);
    const subjectIdToEdit = urlParams.get('subject_id');
    if (subjectIdToEdit) {
        fetchSubjectData(subjectIdToEdit);
    } else {
        // For new courses, populate Week 0 with default BCP content
        populateWeek0DefaultContent();
    }
});
</script>
@endsection