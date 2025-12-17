@extends('layouts.app')

@section('content')
<style>
    /* Allow textareas to auto-resize */
    textarea {
        resize: none !important;
        overflow: hidden !important;
        min-height: 60px !important;
        box-sizing: border-box !important;
    }
    
    /* Auto-capitalize mapping grid inputs (CTPSS, ECC, EPP, GLC only - not PILO/CILO) */
    .mapping-grid-container table td.text-center input[type="text"] {
        text-transform: uppercase !important;
    }
</style>
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
                        {{-- DepEd Specific Fields --}}
                        <div id="deped-course-info-fields" class="contents hidden">
                            <div class="md:col-span-2 lg:col-span-3 mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Import Syllabus (PDF)</label>
                                <div class="flex items-center space-x-4">
                                    <button type="button" onclick="document.getElementById('syllabus_file').click()" class="px-4 py-2 bg-red-50 text-red-600 rounded-md border border-red-200 hover:bg-red-100 transition-colors flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Choose File
                                    </button>
                                    <span id="file_name_display" class="text-sm text-gray-500">No file chosen</span>
                                    <input type="file" id="syllabus_file" class="hidden" accept=".pdf" onchange="handleSyllabusUpload(this)">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    <svg class="w-4 h-4 inline mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <strong>Note:</strong> Auto-extraction works best with standard DepEd formats. Please review and manually adjust the extracted data as needed. Use the "+ Add Content Row" button to organize content into proper rows.
                                </p>
                            </div>
                            <div>
                                <label for="time_allotment" class="block text-sm font-medium text-gray-700">Time Allotment</label>
                                <input type="text" name="time_allotment" id="time_allotment" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. 80 hours / semester">
                            </div>
                            <div>
                                <label for="schedule" class="block text-sm font-medium text-gray-700">Schedule</label>
                                <input type="text" name="schedule" id="schedule" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. M-W-F 9:00-10:00">
                            </div>
                        </div>
                        {{-- CHED Specific Fields --}}
                        <div id="ched-course-info-fields" class="contents">
                            <div class="md:col-span-2 lg:col-span-3 mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Import Syllabus (PDF)</label>
                                <div class="flex items-center space-x-4">
                                    <button type="button" onclick="document.getElementById('ched_syllabus_file').click()" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-md border border-blue-200 hover:bg-blue-100 transition-colors flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Choose File
                                    </button>
                                    <span id="ched_file_name_display" class="text-sm text-gray-500">No file chosen</span>
                                    <input type="file" id="ched_syllabus_file" class="hidden" accept=".pdf" onchange="handleSyllabusUpload(this)">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    <svg class="w-4 h-4 inline mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <strong>Note:</strong> Auto-extraction works best with standard CHED formats. Please review and manually adjust the extracted data as needed.
                                </p>
                            </div>
                            <div>
                                <label for="credit_units" class="block text-sm font-medium text-gray-700">Credit Units</label>
                                <input type="number" name="credit_units" id="credit_units" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="contact_hours" class="block text-sm font-medium text-gray-700">Contact Hours</label>
                                <input type="number" name="contact_hours" id="contact_hours" class="mt-1 block w-full py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                                <div id="q_{{ $q }}_rows_container" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative group deped-row">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Content</label>
                                            <textarea name="q_{{ $q }}_content[]" id="q_{{ $q }}_content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Enter Content..."></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Content Standards</label>
                                            <textarea name="q_{{ $q }}_content_standards[]" id="q_{{ $q }}_content_standards" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="The learners demonstrate understanding of..."></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Learning Competencies</label>
                                            <textarea name="q_{{ $q }}_learning_competencies[]" id="q_{{ $q }}_learning_competencies" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="The learners..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center border-b pb-6">
                                    <button type="button" onclick="addDepEdRow({{ $q }})" class="flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-full hover:bg-red-100 transition-colors text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Add Content Row
                                    </button>
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
                    {{-- School Goals and Program Goals --}}
                    <div class="p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">School Goals:</h3>
                            <p class="text-gray-700 leading-relaxed">
                                BCP puts God in the center of all its efforts realize and operationalize its vision and missions through the following. Instruction, Research, Extension and Productivity
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Program Goals:</h3>
                            <p class="text-gray-700 leading-relaxed">
                                To cultivate a dynamic and inclusive learning environment that empowers students to become self-directed, ethical, and engaged citizens, equipped with the critical thinking, communication, and problem-solving skills necessary to thrive in a rapidly evolving world.
                            </p>
                        </div>
                    </div>

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
                                <span class="font-semibold text-lg text-gray-700">
                                    Week {{ $i }}
                                    @if($i == 6)
                                        - Prelim Exam
                                    @elseif($i == 12)
                                        - Midterm Exam
                                    @elseif($i == 18)
                                        - Final Exam
                                    @endif
                                </span>
                                <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="accordion-content bg-gray-50 p-6 border-t" style="display: none;">
                                @if(in_array($i, [6, 12, 18]))
                                    <div class="text-center py-8">
                                        <p class="text-xl font-bold text-gray-600">
                                            @if($i == 6) PRELIM EXAM
                                            @elseif($i == 12) MIDTERM EXAM
                                            @elseif($i == 18) FINAL EXAM
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500 mt-2">No additional details required for this week.</p>
                                        {{-- Hidden input to maintain data structure --}}
                                        <input type="hidden" id="week_{{ $i }}_content" value="{{ $i == 6 ? 'Prelim Exam' : ($i == 12 ? 'Midterm Exam' : 'Final Exam') }}">
                                    </div>
                                @else
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
                                @endif
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

{{-- Extraction Success Modal --}}
<div id="extractionSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Syllabus Extracted Successfully!</h3>
            <p class="text-sm text-gray-500 mt-2">The syllabus data has been extracted and populated into the form fields.</p>
            <div class="mt-6">
                <button id="closeExtractionModal" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
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
    
    // Clear shared fields when switching (unless editing existing subject)
    const isEditing = document.getElementById('subject_id').value !== '';
    if (!isEditing) {
        // Clear Course Information fields
        document.getElementById('course_title').value = '';
        document.getElementById('course_code').value = '';
        document.getElementById('subject_type').value = '';
        document.getElementById('course_description').value = '';
        
        // Clear Approval fields
        document.getElementById('prepared_by').value = '';
        document.getElementById('reviewed_by').value = '';
        document.getElementById('approved_by').value = '';
        
        // Clear curriculum selection if available
        if (typeof selectedCurriculums !== 'undefined') {
            selectedCurriculums.clear();
            // Update button text if the function exists
            if (typeof updateCurriculumButtonText === 'function') {
                updateCurriculumButtonText();
            }
        }
    }

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
        
        // Clear DepEd-specific fields if not editing
        if (!isEditing) {
            document.getElementById('time_allotment').value = '';
            document.getElementById('schedule').value = '';
            
            // Clear quarter grids
            for (let q = 1; q <= 2; q++) {
                const container = document.getElementById(`q_${q}_rows_container`);
                if (container) {
                    // Keep only the first row and clear it
                    const rows = container.querySelectorAll('.deped-row');
                    rows.forEach((row, index) => {
                        if (index === 0) {
                            row.querySelectorAll('textarea').forEach(ta => ta.value = '');
                        } else {
                            row.remove();
                        }
                    });
                }
                
                const perfStd = document.getElementById(`q_${q}_performance_standards`);
                const perfTask = document.getElementById(`q_${q}_performance_task`);
                if (perfStd) perfStd.value = '';
                if (perfTask) perfTask.value = '';
            }
        }
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
        
        // Clear CHED-specific fields if not editing
        if (!isEditing) {
            document.getElementById('credit_units').value = '';
            document.getElementById('contact_hours').value = '';
            document.getElementById('pilo_outcomes').value = '';
            document.getElementById('cilo_outcomes').value = '';
            document.getElementById('learning_outcomes').value = '';
            document.getElementById('basic_readings').value = '';
            document.getElementById('extended_readings').value = '';
            document.getElementById('course_assessment').value = '';
            document.getElementById('committee_members').value = '';
            document.getElementById('consultation_schedule').value = '';
            
            // Clear mapping grids
            document.getElementById('program-mapping-table-body').innerHTML = '';
            document.getElementById('course-mapping-table-body').innerHTML = '';
            
            // Clear weekly plan
            for (let i = 0; i <= 18; i++) {
                const contentEl = document.getElementById(`week_${i}_content`);
                const siloEl = document.getElementById(`week_${i}_silo`);
                const atOnsiteEl = document.getElementById(`week_${i}_at_onsite`);
                const atOffsiteEl = document.getElementById(`week_${i}_at_offsite`);
                const tlaOnsiteEl = document.getElementById(`week_${i}_tla_onsite`);
                const tlaOffsiteEl = document.getElementById(`week_${i}_tla_offsite`);
                const ltsmEl = document.getElementById(`week_${i}_ltsm`);
                const outputEl = document.getElementById(`week_${i}_output`);
                
                if (contentEl) contentEl.value = '';
                if (siloEl) siloEl.value = '';
                if (atOnsiteEl) atOnsiteEl.value = '';
                if (atOffsiteEl) atOffsiteEl.value = '';
                if (tlaOnsiteEl) tlaOnsiteEl.value = '';
                if (tlaOffsiteEl) tlaOffsiteEl.value = '';
                if (ltsmEl) ltsmEl.value = '';
                if (outputEl) outputEl.value = '';
            }
        }
    }
}





document.addEventListener('DOMContentLoaded', function () {
    const courseForm = document.getElementById('courseForm');
    const subjectIdField = document.getElementById('subject_id');
    const saveButton = document.getElementById('saveCourseButton');
    const pageTitle = document.querySelector('h1.text-4xl');

    // --- DEFAULT WEEK 0 CONTENT ---
    // --- DEFAULT CONTENT ---
    const populateDefaultContent = () => {
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

        // Default Exams
        if (document.getElementById('week_6_content') && document.getElementById('week_6_content').value.trim() === '') {
            document.getElementById('week_6_content').value = 'Prelim Exam';
        }
        if (document.getElementById('week_12_content') && document.getElementById('week_12_content').value.trim() === '') {
            document.getElementById('week_12_content').value = 'Midterm Exam';
        }
        if (document.getElementById('week_18_content') && document.getElementById('week_18_content').value.trim() === '') {
            document.getElementById('week_18_content').value = 'Final Exam';
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
            const contentEl = document.getElementById(`week_${i}_content`);
            const siloEl = document.getElementById(`week_${i}_silo`);
            const atOnsiteEl = document.getElementById(`week_${i}_at_onsite`);
            const atOffsiteEl = document.getElementById(`week_${i}_at_offsite`);
            const tlaOnsiteEl = document.getElementById(`week_${i}_tla_onsite`);
            const tlaOffsiteEl = document.getElementById(`week_${i}_tla_offsite`);
            const ltsmEl = document.getElementById(`week_${i}_ltsm`);
            const outputEl = document.getElementById(`week_${i}_output`);

            const content = contentEl ? contentEl.value : '';
            const silo = siloEl ? siloEl.value : '';
            const atOnsite = atOnsiteEl ? atOnsiteEl.value : '';
            const atOffsite = atOffsiteEl ? atOffsiteEl.value : '';
            const tlaOnsite = tlaOnsiteEl ? tlaOnsiteEl.value : '';
            const tlaOffsite = tlaOffsiteEl ? tlaOffsiteEl.value : '';
            const ltsm = ltsmEl ? ltsmEl.value : '';
            const output = outputEl ? outputEl.value : '';

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
                const contentEl = document.getElementById(`week_${i}_content`);
                if (contentEl) contentEl.value = contentMatch ? contentMatch[1].trim() : '';

                const siloMatch = lessonString.match(/Student Intended Learning Outcomes:\n(.*?)(?=,, |$)/s);
                const siloEl = document.getElementById(`week_${i}_silo`);
                if (siloEl) siloEl.value = siloMatch ? siloMatch[1].trim() : '';

                const atMatch = lessonString.match(/Assessment: ONSITE: (.*?) OFFSITE: (.*?)(?=,, |$)/s);
                if (atMatch) {
                    const atOnsiteEl = document.getElementById(`week_${i}_at_onsite`);
                    if (atOnsiteEl) atOnsiteEl.value = atMatch[1].trim();
                    const atOffsiteEl = document.getElementById(`week_${i}_at_offsite`);
                    if (atOffsiteEl) atOffsiteEl.value = atMatch[2].trim();
                }

                const tlaMatch = lessonString.match(/Activities: ON-SITE: (.*?) OFF-SITE: (.*?)(?=,, |$)/s);
                if (tlaMatch) {
                    const tlaOnsiteEl = document.getElementById(`week_${i}_tla_onsite`);
                    if (tlaOnsiteEl) tlaOnsiteEl.value = tlaMatch[1].trim();
                    const tlaOffsiteEl = document.getElementById(`week_${i}_tla_offsite`);
                    if (tlaOffsiteEl) tlaOffsiteEl.value = tlaMatch[2].trim();
                }

                const ltsmMatch = lessonString.match(/Learning and Teaching Support Materials:\n(.*?)(?=,, |$)/s);
                const ltsmEl = document.getElementById(`week_${i}_ltsm`);
                if (ltsmEl) ltsmEl.value = ltsmMatch ? ltsmMatch[1].trim() : '';

                const outputMatch = lessonString.match(/Output Materials:\n(.*?)(?=,, |$)/s);
                const outputEl = document.getElementById(`week_${i}_output`);
                if (outputEl) outputEl.value = outputMatch ? outputMatch[1].trim() : '';
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

    // --- MAPPING GRID ROW LOGIC (Event Listeners) ---
    document.getElementById('add-program-mapping-row').addEventListener('click', () => document.getElementById('program-mapping-table-body').appendChild(createMappingTableRow(true)));
    document.getElementById('add-course-mapping-row').addEventListener('click', () => document.getElementById('program-mapping-table-body').appendChild(createMappingTableRow(false)));
    
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
        // For new courses, populate defaults
        populateDefaultContent();
    }

});

// --- MAPPING GRID ROW LOGIC ---
const createMappingTableRow = (isPilo = true) => {
    const row = document.createElement('tr');
    row.className = '';
    row.innerHTML = `<td class="py-2 px-4 border-b"><input type="text" placeholder="${isPilo ? 'PILO...' : 'CILO...'}" class="w-full p-1 border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b"><input type="text" class="w-full p-1 text-center border-gray-300 rounded"></td><td class="py-2 px-4 border-b text-center"><button type="button" class="delete-row-btn text-red-500 hover:text-red-700 font-semibold">Delete</button></td>`;
    return row;
};

// Function to handle Syllabus PDF Upload
// Function to handle Syllabus PDF Upload
function handleSyllabusUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        // Update display name based on which input triggered this
        if (input.id === 'ched_syllabus_file') {
            document.getElementById('ched_file_name_display').textContent = file.name;
        } else {
            document.getElementById('file_name_display').textContent = file.name;
        }

        const formData = new FormData();
        formData.append('syllabus_file', file);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        // Show loading state
        const btn = input.previousElementSibling.previousElementSibling; // The button
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Uploading...';
        btn.disabled = true;

        // Determine API endpoint based on input ID or syllabus type
        const isChed = input.id === 'ched_syllabus_file';
        const endpoint = isChed ? '/api/extract-ched-syllabus' : '/api/extract-syllabus';

        fetch(endpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Debug: Log the full response
            console.log('Extraction Response:', data);
            console.log('Extraction Method:', data.extraction_method);
            
            if (data.success) {
                // Common fields
                if (data.data.course_title) document.getElementById('course_title').value = data.data.course_title;
                if (data.data.course_description) document.getElementById('course_description').value = data.data.course_description;
                
                if (isChed) {
                    // CHED Specific Fields
                    if (data.data.course_code) document.getElementById('course_code').value = data.data.course_code;
                    if (data.data.credit_units) document.getElementById('credit_units').value = data.data.credit_units;
                    if (data.data.contact_hours) document.getElementById('contact_hours').value = data.data.contact_hours;
                    
                    if (data.data.pilo_outcomes) document.getElementById('pilo_outcomes').value = data.data.pilo_outcomes;
                    if (data.data.cilo_outcomes) document.getElementById('cilo_outcomes').value = data.data.cilo_outcomes;
                    if (data.data.learning_outcomes) document.getElementById('learning_outcomes').value = data.data.learning_outcomes;
                    
                    if (data.data.basic_readings) document.getElementById('basic_readings').value = data.data.basic_readings;
                    if (data.data.extended_readings) document.getElementById('extended_readings').value = data.data.extended_readings;
                    if (data.data.course_assessment) document.getElementById('course_assessment').value = data.data.course_assessment;
                    if (data.data.committee_members) document.getElementById('committee_members').value = data.data.committee_members;
                    if (data.data.consultation_schedule) document.getElementById('consultation_schedule').value = data.data.consultation_schedule;
                    
                    if (data.data.prepared_by) document.getElementById('prepared_by').value = data.data.prepared_by;
                    if (data.data.reviewed_by) document.getElementById('reviewed_by').value = data.data.reviewed_by;
                    if (data.data.approved_by) document.getElementById('approved_by').value = data.data.approved_by;

                    // Mapping Grids
                    if (data.data.program_mapping && data.data.program_mapping.length > 0) {
                        const tbody = document.getElementById('program-mapping-table-body');
                        tbody.innerHTML = ''; // Clear existing
                        data.data.program_mapping.forEach(row => {
                            const tr = createMappingTableRow(true);
                            const inputs = tr.querySelectorAll('input');
                            inputs[0].value = row.pilo || '';
                            inputs[1].value = row.ctpss || '';
                            inputs[2].value = row.ecc || '';
                            inputs[3].value = row.epp || '';
                            inputs[4].value = row.glc || '';
                            tbody.appendChild(tr);
                        });
                    }

                    if (data.data.course_mapping && data.data.course_mapping.length > 0) {
                        const tbody = document.getElementById('course-mapping-table-body');
                        tbody.innerHTML = ''; // Clear existing
                        data.data.course_mapping.forEach(row => {
                            const tr = createMappingTableRow(false);
                            const inputs = tr.querySelectorAll('input');
                            inputs[0].value = row.cilo || '';
                            inputs[1].value = row.ctpss || '';
                            inputs[2].value = row.ecc || '';
                            inputs[3].value = row.epp || '';
                            inputs[4].value = row.glc || '';
                            tbody.appendChild(tr);
                        });
                    }

                    // Weekly Plan
                    if (data.data.weekly_plan) {
                        for (const [week, lesson] of Object.entries(data.data.weekly_plan)) {
                            // week is index (0, 1, 2...)
                            if (document.getElementById(`week_${week}_content`)) {
                                if (lesson.content) document.getElementById(`week_${week}_content`).value = lesson.content;
                                if (lesson.silo) document.getElementById(`week_${week}_silo`).value = lesson.silo;
                                if (lesson.at_onsite) document.getElementById(`week_${week}_at_onsite`).value = lesson.at_onsite;
                                if (lesson.at_offsite) document.getElementById(`week_${week}_at_offsite`).value = lesson.at_offsite;
                                if (lesson.tla_onsite) document.getElementById(`week_${week}_tla_onsite`).value = lesson.tla_onsite;
                                if (lesson.tla_offsite) document.getElementById(`week_${week}_tla_offsite`).value = lesson.tla_offsite;
                                if (lesson.ltsm) document.getElementById(`week_${week}_ltsm`).value = lesson.ltsm;
                                if (lesson.output) document.getElementById(`week_${week}_output`).value = lesson.output;
                            }
                        }
                    }

                } else {
                    // DepEd Specific Fields
                    if (data.data.time_allotment) document.getElementById('time_allotment').value = data.data.time_allotment;
                    if (data.data.schedule) document.getElementById('schedule').value = data.data.schedule;

                    // Populate Quarter 1
                    if (data.data.q_1_rows && data.data.q_1_rows.length > 0) {
                        // Populate first row
                        const firstRow = data.data.q_1_rows[0];
                        if (firstRow.content) document.getElementById('q_1_content').value = firstRow.content;
                        if (firstRow.content_standards) document.getElementById('q_1_content_standards').value = firstRow.content_standards;
                        if (firstRow.learning_competencies) document.getElementById('q_1_learning_competencies').value = firstRow.learning_competencies;
                        
                        // Add additional rows
                        for (let i = 1; i < data.data.q_1_rows.length; i++) {
                            addDepEdRow(1);
                            const row = data.data.q_1_rows[i];
                            const container = document.getElementById('q_1_rows_container');
                            const lastRow = container.lastElementChild;
                            const textareas = lastRow.querySelectorAll('textarea');
                            if (row.content) textareas[0].value = row.content;
                            if (row.content_standards) textareas[1].value = row.content_standards;
                            if (row.learning_competencies) textareas[2].value = row.learning_competencies;
                        }
                    }
                    if (data.data.q_1_performance_standards) document.getElementById('q_1_performance_standards').value = data.data.q_1_performance_standards;
                    if (data.data.q_1_performance_task) document.getElementById('q_1_performance_task').value = data.data.q_1_performance_task;

                    // Populate Quarter 2
                    if (data.data.q_2_rows && data.data.q_2_rows.length > 0) {
                        // Populate first row
                        const firstRow = data.data.q_2_rows[0];
                        if (firstRow.content) document.getElementById('q_2_content').value = firstRow.content;
                        if (firstRow.content_standards) document.getElementById('q_2_content_standards').value = firstRow.content_standards;
                        if (firstRow.learning_competencies) document.getElementById('q_2_learning_competencies').value = firstRow.learning_competencies;
                        
                        // Add additional rows
                        for (let i = 1; i < data.data.q_2_rows.length; i++) {
                            addDepEdRow(2);
                            const row = data.data.q_2_rows[i];
                            const container = document.getElementById('q_2_rows_container');
                            const lastRow = container.lastElementChild;
                            const textareas = lastRow.querySelectorAll('textarea');
                            if (row.content) textareas[0].value = row.content;
                            if (row.content_standards) textareas[1].value = row.content_standards;
                            if (row.learning_competencies) textareas[2].value = row.learning_competencies;
                        }
                    }
                    if (data.data.q_2_performance_standards) document.getElementById('q_2_performance_standards').value = data.data.q_2_performance_standards;
                    if (data.data.q_2_performance_task) document.getElementById('q_2_performance_task').value = data.data.q_2_performance_task;
                }

                // Show success modal instead of alert
                document.getElementById('extractionSuccessModal').classList.remove('hidden');
            } else {
                alert('Failed to extract data: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the file. Please check the console for details.');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
}

// Extraction Success Modal Close Handler
document.getElementById('closeExtractionModal').addEventListener('click', () => {
    document.getElementById('extractionSuccessModal').classList.add('hidden');
});

function addDepEdRow(quarter) {
    const container = document.getElementById(`q_${quarter}_rows_container`);
    const newRow = document.createElement('div');
    newRow.className = 'grid grid-cols-1 md:grid-cols-3 gap-6 relative group deped-row pt-6 border-t border-dashed';
    
    newRow.innerHTML = `
        <div>
            <textarea name="q_${quarter}_content[]" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Enter Content..."></textarea>
        </div>
        <div>
            <textarea name="q_${quarter}_content_standards[]" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="The learners demonstrate understanding of..."></textarea>
        </div>
        <div>
            <textarea name="q_${quarter}_learning_competencies[]" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="The learners..."></textarea>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="absolute -top-3 right-0 bg-white text-red-500 hover:text-red-700 rounded-full p-1 shadow-sm border border-gray-200" title="Remove Row">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    
    container.appendChild(newRow);
    
    // Auto-resize the newly added textareas
    newRow.querySelectorAll('textarea').forEach(textarea => {
        setupAutoResize(textarea);
    });
}

// Setup auto-resize for a textarea element
function setupAutoResize(textarea) {
    // Initial resize
    autoResize(textarea);
    
    // Add event listener for future changes
    textarea.addEventListener('input', function() {
        autoResize(this);
    });
}

// Simple auto-resize function
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = (textarea.scrollHeight) + 'px';
}

// Resize all textareas
function resizeAllTextareas() {
    document.querySelectorAll('textarea').forEach(function(textarea) {
        autoResize(textarea);
    });
}

// Initialize all textareas on page load
window.addEventListener('load', function() {
    document.querySelectorAll('textarea').forEach(function(textarea) {
        // Set initial size
        autoResize(textarea);
        
        // Add oninput attribute if not already present
        if (!textarea.hasAttribute('oninput')) {
            textarea.setAttribute('oninput', 'autoResize(this)');
        }
        
        // Also add event listener as backup
        textarea.addEventListener('input', function() {
            autoResize(this);
        });
    });
    
    // Watch for value changes (for programmatic updates like PDF extraction)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'characterData') {
                resizeAllTextareas();
            }
        });
    });
    
    // Observe the entire form for changes
    const form = document.getElementById('courseForm');
    if (form) {
        observer.observe(form, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }
});



</script>
@endsection