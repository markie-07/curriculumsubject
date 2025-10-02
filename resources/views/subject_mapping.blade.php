@extends('layouts.app')

@section('content')
<style>
    .assigned-card {
        background-color: #e0e7ff; 
        border-color: #a5b4fc;
    }
    .assigned-card .subject-name {
        color: #4338ca;
    }
</style>
<main class="flex-1 overflow-hidden bg-gray-100 p-6 flex flex-col">
    <div class="bg-white rounded-2xl shadow-xl p-8 flex-1 flex flex-col">
        
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-800">Subject Mapping</h1>
                <p class="text-sm text-gray-500 mt-1">Drag and drop subjects to build the curriculum.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-0">
            
            <div class="lg:col-span-1 bg-gray-50 border border-gray-200 rounded-xl p-6 flex flex-col h-[calc(100vh-200px)]">
                <div class="pb-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Available Subjects</h2>
                    <p class="text-sm text-gray-500">Find and select subjects to add to the curriculum.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 my-4">
                    <div class="relative flex-grow">
                        <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Search subject...">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <select id="typeFilter" class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="All Types">All Types</option>
                        <option value="Major">Major</option>
                        <option value="Minor">Minor</option>
                        <option value="Elective">Elective</option>
                        <option value="GE">GE</option>
                    </select>
                </div>

                <div id="availableSubjects" class="flex-1 overflow-y-auto pr-2 -mr-2 space-y-3">
                    <p class="text-gray-500 text-center mt-4">Select a curriculum to view subjects.</p>
                </div>
            </div>

            <div class="lg:col-span-2 bg-gray-50 border border-gray-200 rounded-xl p-6 flex flex-col h-[calc(100vh-200px)]">
                <div class="pb-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2 sm:mb-0">Curriculum Overview</h2>
                    <select id="curriculumSelector" class="w-full sm:w-auto border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Select a Curriculum</option>
                    </select>
                </div>

                <div id="grand-total-container" class="hidden mt-4 p-4 bg-gray-100 border border-gray-200 text-gray-800 rounded-lg flex justify-between items-center">
                    <span class="text-lg font-bold">Total Curriculum Units:</span>
                    <span id="grand-total-units" class="text-2xl font-extrabold">0</span>
                </div>

                <div id="curriculumOverview" class="mt-4 space-y-6 flex-1 overflow-y-auto">
                    <p class="text-gray-500 text-center mt-4">Select a curriculum from the dropdown to start mapping subjects.</p>
                </div>
                
                <div class="mt-auto pt-6 border-t border-gray-200 flex justify-end gap-2">
                    <button id="editCurriculumButton" class="px-6 py-3 rounded-lg text-sm font-semibold text-blue-700 bg-white border border-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-md hidden">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                        Edit
                    </button>
                    <button id="saveCurriculumButton" class="px-6 py-3 rounded-lg text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors shadow-md" disabled>
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v6a2 2 0 002 2h6m4-4H9m0 0V9m0 0V5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2h-3m-4-4V9"></path></svg>
                        Save the Mapping
                    </button>
                </div>
            </div>
        </div>
    </div>
    
 {{-- Subject Details Modal (COMPREHENSIVE) --}}
<div id="subjectDetailsModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 transition-opacity duration-300 ease-out hidden">
    {{-- MODIFIED: Reduced padding to `p-2` to make the modal closer to the screen edges --}}
    <div class="flex items-center justify-center min-h-screen p-2">
        {{-- MODIFIED: Width and height are now set to 98% of the viewport width (vw) and height (vh) --}}
        <div class="relative bg-white w-[98vw] h-[98vh] rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col" id="modal-details-panel">
            
            {{-- Modal Header (Sticky) --}}
            <div class="flex justify-between items-center p-5 border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-2xl">
                <h2 id="detailsSubjectName" class="text-2xl font-bold text-gray-800"></h2>
                <button id="closeDetailsModalButtonTop" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            {{-- Modal Content Scrollable Area (Your existing content goes here) --}}
            <div class="p-6 flex-1 overflow-y-auto">

                {{-- 1. Course Information --}}
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Course Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 bg-gray-50 p-4 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Course Title</p>
                        <p id="detailsCourseTitle" class="text-base font-semibold text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Course Code</p>
                        <p id="detailsSubjectCode" class="text-base font-semibold text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Course Type</p>
                        <p id="detailsSubjectType" class="text-base font-semibold text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Credit Units</p>
                        <p id="detailsSubjectUnit" class="text-base font-semibold text-gray-800"></p>
                    </div>
                     <div>
                        <p class="text-sm font-medium text-gray-500">Contact Hours</p>
                        <p id="detailsContactHours" class="text-base font-semibold text-gray-800">N/A</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Credit Prerequisites</p>
                        <p id="detailsPrerequisites" class="text-base font-semibold text-gray-800">N/A</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pre-requisite to</p>
                        <p id="detailsPrereqTo" class="text-base font-semibold text-gray-800">N/A</p>
                    </div>
                    <div class="md:col-span-4">
                        <p class="text-sm font-medium text-gray-500">Course Description</p>
                        <div id="detailsCourseDescription" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                </div>
                
                {{-- 2. Mapping Grids --}}
                <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Mapping Grids</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                         <p class="text-sm font-medium text-gray-500">PROGRAM MAPPING GRID</p>
                        <div id="detailsProgramMapping" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">Mapping Grid data is stored in the Course Builder.</div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">COURSE MAPPING GRID</p>
                        <div id="detailsCourseMapping" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">Mapping Grid data is stored in the Course Builder.</div>
                    </div>
                </div>

                {{-- 3. Learning Outcomes --}}
                <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Learning Outcomes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                         <p class="text-sm font-medium text-gray-500">PROGRAM INTENDED LEARNING OUTCOMES (PILO)</p>
                        <div id="detailsPILO" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Course Intended Learning Outcomes (CILO)</p>
                        <div id="detailsCILO" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                    <div class="md:col-span-2">
                         <p class="text-sm font-medium text-gray-500">Learning Outcomes</p>
                        <div id="detailsLearningOutcomes" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                </div>

                {{-- 4. Weekly Plan (Lessons) --}}
                <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Weekly Plan (Weeks 1-15)</h3>
                <div class="space-y-3" id="detailsLessonsContainer">
                    <p class="text-sm text-gray-500 mt-2">Loading weekly plan...</p>
                </div>

                {{-- 5. Course Requirements and Policies --}}
                <h3 class="text-xl font-bold text-gray-800 mb-4 pt-8 pb-2 border-b">Course Requirements and Policies</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Basic Readings / Textbooks</p>
                        <div id="detailsBasicReadings" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Extended Readings / References</p>
                        <div id="detailsExtendedReadings" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Course Assessment</p>
                        <div id="detailsCourseAssessment" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                </div>
                
                {{-- 6. Committee and Approval --}}
                 <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Committee and Approval</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                     <div class="md:col-span-3">
                        <p class="text-sm font-medium text-gray-500">Committee Members</p>
                        <div id="detailsCommitteeMembers" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                    <div class="md:col-span-3">
                        <p class="text-sm font-medium text-gray-500">Consultation Schedule</p>
                        <div id="detailsConsultationSchedule" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                    <div>
                         <p class="text-sm font-medium text-gray-500">Prepared By</p>
                        <div id="detailsPreparedBy" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">N/A</div>
                    </div>
                     <div>
                         <p class="text-sm font-medium text-gray-500">Reviewed By</p>
                        <div id="detailsReviewedBy" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">N/A</div>
                    </div>
                     <div>
                         <p class="text-sm font-medium text-gray-500">Approved By</p>
                        <div id="detailsApprovedBy" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">N/A</div>
                    </div>
                </div>

            </div>
            
            {{-- Modal Footer (Sticky & Modified) --}}
            <div class="flex justify-between items-center p-5 mt-auto border-t border-gray-200 bg-gray-50 rounded-b-2xl sticky bottom-0 z-10">
                <div class="text-sm text-gray-500">
                    <span class="font-semibold">Created:</span>
                    <span id="detailsCreatedAt"></span>
                </div>
                <div class="flex items-center gap-4">
                    <button id="importSubjectDetailsButton" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm flex items-center gap-2" data-subject-data="">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export to PDF
                    </button>
                    <button id="editSubjectDetailsButton" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm flex items-center gap-2" data-subject-data="">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                        Edit Subject
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    
    {{-- Remove Subject Confirmation Modal --}}
    <div id="removeConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 transition-opacity duration-300 ease-out hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out" id="remove-modal-panel">
                <div class="w-12 h-12 rounded-full bg-red-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Remove Subject</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to remove this subject from the semester?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="cancelRemoveButton" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
                    <button id="confirmRemoveButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all">Yes, Remove</button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Import Confirmation Modal --}}
    <div id="importConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 transition-opacity duration-300 ease-out hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out" id="import-modal-panel">
                <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Export Subject</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to download a PDF file of this subject's details and lessons?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="cancelImportButton" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
                    <button id="confirmImportButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">Yes, Export</button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Create Subject Confirmation Modal --}}
    <div id="createConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Create Subject</h3>
                <p class="text-sm text-gray-500 mt-2">Do you want to create this new subject?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="cancelCreateBtn" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button id="confirmCreateBtn" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Yes, create it!</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Grade Setup Confirmation Modal --}}
    <div id="gradeSetupConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-indigo-100 p-2 flex items-center justify-center mx-auto mb-4">
                     <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Set Up Grades?</h3>
                <p class="text-sm text-gray-500 mt-2">Do you want to set up the grade components for this subject now?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="declineGradeSetupBtn" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">No</button>
                    <button id="confirmGradeSetupBtn" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Yes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Creation Success Modal --}}
    <div id="creationSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Success!</h3>
                <p class="text-sm text-gray-500 mt-2">You successfully created a new subject.</p>
                <div class="mt-6">
                    <button id="successOkBtn" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Mapping Confirmation Modal --}}
    <div id="saveMappingModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Save Mapping</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to save the mapping?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="cancelSaveMapping" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button id="confirmSaveMapping" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Yes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Proceed to Prerequisites Confirmation Modal --}}
    <div id="proceedToPrerequisitesModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Proceed to Prerequisites</h3>
                <p class="text-sm text-gray-500 mt-2">Do you want to go to Pre-requisite Configuration to set up the Pre-requisite?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="declineProceedToPrerequisites" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">No</button>
                    <button id="confirmProceedToPrerequisites" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Yes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mapping Success Modal --}}
    <div id="mappingSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Success!</h3>
                <p class="text-sm text-gray-500 mt-2">You have successfully mapped the subject.</p>
                <div class="mt-6">
                    <button id="closeMappingSuccessModal" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- MODAL ELEMENTS ---
        const subjectDetailsModal = document.getElementById('subjectDetailsModal');
        const closeDetailsModalButtonTop = document.getElementById('closeDetailsModalButtonTop');
        const modalDetailsPanel = document.getElementById('modal-details-panel');
        const editSubjectDetailsButton = document.getElementById('editSubjectDetailsButton');
        const importSubjectDetailsButton = document.getElementById('importSubjectDetailsButton');

        // Detailed Content Elements
        const detailsCourseTitle = document.getElementById('detailsCourseTitle');
        const detailsContactHours = document.getElementById('detailsContactHours');
        const detailsPrerequisites = document.getElementById('detailsPrerequisites');
        const detailsPrereqTo = document.getElementById('detailsPrereqTo');
        const detailsCourseDescription = document.getElementById('detailsCourseDescription');
        const detailsProgramMapping = document.getElementById('detailsProgramMapping');
        const detailsCourseMapping = document.getElementById('detailsCourseMapping');
        const detailsPILO = document.getElementById('detailsPILO');
        const detailsCILO = document.getElementById('detailsCILO');
        const detailsLearningOutcomes = document.getElementById('detailsLearningOutcomes');
        const detailsLessonsContainer = document.getElementById('detailsLessonsContainer');
        const detailsBasicReadings = document.getElementById('detailsBasicReadings');
        const detailsExtendedReadings = document.getElementById('detailsExtendedReadings');
        const detailsCourseAssessment = document.getElementById('detailsCourseAssessment');
        const detailsCommitteeMembers = document.getElementById('detailsCommitteeMembers');
        const detailsConsultationSchedule = document.getElementById('detailsConsultationSchedule');
        const detailsPreparedBy = document.getElementById('detailsPreparedBy');
        const detailsReviewedBy = document.getElementById('detailsReviewedBy');
        const detailsApprovedBy = document.getElementById('detailsApprovedBy');
        const detailsCreatedAt = document.getElementById('detailsCreatedAt');

        // --- CORE ELEMENTS & STATE ---
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');
        const availableSubjectsContainer = document.getElementById('availableSubjects');
        const curriculumSelector = document.getElementById('curriculumSelector');
        const curriculumOverview = document.getElementById('curriculumOverview');
        let draggedItem = null;
        let subjectTagToRemove = null;
        let isEditing = false;
        let subjectToImport = null;

        // --- SUBJECT DETAIL MODAL FUNCTIONS (UPDATED) ---

        const hideDetailsModal = () => {
            subjectDetailsModal.classList.add('opacity-0');
            modalDetailsPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => subjectDetailsModal.classList.add('hidden'), 300);
        };
        
        const createMappingGridHtml = (gridData, mainHeader) => {
            if (!gridData || !Array.isArray(gridData) || gridData.length === 0) {
                return '<p class="text-xs text-gray-500">No mapping grid data available.</p>';
            }

            const headers = [mainHeader, 'CTPSS', 'ECC', 'EPP', 'GLC'];
            
            let tableHtml = `<div class="overflow-x-auto border rounded-md">
                                <table class="min-w-full divide-y divide-gray-200 text-xs">
                                    <thead class="bg-gray-50">
                                        <tr>${headers.map(h => `<th scope="col" class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">${h}</th>`).join('')}</tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">`;
            
            gridData.forEach(row => {
                const mainCellData = row[mainHeader.toLowerCase()] || '';
                tableHtml += `<tr>
                                <td class="px-3 py-2 whitespace-normal">${mainCellData}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">${row.ctpss || ''}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">${row.ecc || ''}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">${row.epp || ''}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">${row.glc || ''}</td>
                              </tr>`;
            });

            tableHtml += `</tbody></table></div>`;
            return tableHtml;
        };

        const showDetailsModal = (data) => {
            const setText = (element, value) => {
                if (element) {
                    element.textContent = value || 'N/A';
                }
            };

            // Set text content for all details
            setText(document.getElementById('detailsSubjectName'), `${data.subject_name} (${data.subject_code})`);
            setText(detailsCourseTitle, data.subject_name);
            setText(document.getElementById('detailsSubjectCode'), data.subject_code);
            setText(document.getElementById('detailsSubjectType'), data.subject_type);
            setText(document.getElementById('detailsSubjectUnit'), data.subject_unit);
            setText(detailsContactHours, data.contact_hours);
            setText(detailsPrerequisites, data.prerequisites);
            setText(detailsPrereqTo, data.pre_requisite_to);
            setText(detailsCourseDescription, data.course_description);
            setText(detailsPILO, data.pilo_outcomes);
            setText(detailsCILO, data.cilo_outcomes);
            setText(detailsLearningOutcomes, data.learning_outcomes);
            setText(detailsBasicReadings, data.basic_readings);
            setText(detailsExtendedReadings, data.extended_readings);
            setText(detailsCourseAssessment, data.course_assessment);
            setText(detailsCommitteeMembers, data.committee_members);
            setText(detailsConsultationSchedule, data.consultation_schedule);
            setText(detailsPreparedBy, data.prepared_by);
            setText(detailsReviewedBy, data.reviewed_by);
            setText(detailsApprovedBy, data.approved_by);
            
            // Format and set the creation date
            const createdAtDate = new Date(data.created_at);
            const formattedDate = createdAtDate.toLocaleString('en-US', {
                year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            setText(detailsCreatedAt, formattedDate);

            // Store subject data on footer buttons
            const subjectDataString = JSON.stringify(data);
            editSubjectDetailsButton.dataset.subjectData = subjectDataString;
            importSubjectDetailsButton.dataset.subjectData = subjectDataString;

            // Render mapping grids
            detailsProgramMapping.innerHTML = createMappingGridHtml(data.program_mapping_grid, 'PILO');
            detailsCourseMapping.innerHTML = createMappingGridHtml(data.course_mapping_grid, 'CILO');

            // Render lessons
            detailsLessonsContainer.innerHTML = '';
            if (data.lessons && typeof data.lessons === 'object' && Object.keys(data.lessons).length > 0) {
                Object.keys(data.lessons).sort((a, b) => parseInt(a.replace('Week ', '')) - parseInt(b.replace('Week ', ''))).forEach(week => {
                    const lessonString = data.lessons[week];
                    const lessonData = {};
                    const parts = lessonString.split(',, ');
                    parts.forEach(part => {
                        if (part.startsWith('Detailed Lesson Content:')) lessonData.content = part.replace('Detailed Lesson Content:\n', '');
                        if (part.startsWith('Student Intended Learning Outcomes:')) lessonData.silo = part.replace('Student Intended Learning Outcomes:\n', '');
                        if (part.startsWith('Assessment:')) {
                            const match = part.match(/ONSITE: (.*) OFFSITE: (.*)/);
                            if (match) { lessonData.at_onsite = match[1]; lessonData.at_offsite = match[2]; }
                        }
                        if (part.startsWith('Activities:')) {
                            const match = part.match(/ON-SITE: (.*) OFF-SITE: (.*)/);
                            if (match) { lessonData.tla_onsite = match[1]; lessonData.tla_offsite = match[2]; }
                        }
                        if (part.startsWith('Learning and Teaching Support Materials:')) lessonData.ltsm = part.replace('Learning and Teaching Support Materials:\n', '');
                        if (part.startsWith('Output Materials:')) lessonData.output = part.replace('Output Materials:\n', '');
                    });

                    const weekHTML = `
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition-colors week-toggle">
                                <span class="font-semibold text-gray-700">${week}</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="p-5 border-t border-gray-200 bg-white hidden week-content space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div><label class="block text-sm font-semibold text-gray-600 mb-2">Content</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.content || ''}</div></div>
                                    <div><label class="block text-sm font-semibold text-gray-600 mb-2">Student Intended Learning Outcomes</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.silo || ''}</div></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Assessment Tasks (ATs)</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border rounded-md bg-gray-50">
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1">ONSITE</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.at_onsite || ''}</div></div>
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1">OFFSITE</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.at_offsite || ''}</div></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Suggested Teaching/Learning Activities (TLAs)</label>
                                    <div class="p-4 border rounded-md bg-gray-50">
                                        <p class="text-xs font-bold text-gray-500 mb-2">Blended Learning Delivery Modality (BLDM)</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-bold text-gray-500 mb-1">Face to Face (On-Site)</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.tla_onsite || ''}</div></div>
                                            <div><label class="block text-xs font-bold text-gray-500 mb-1">Online (Off-Site)</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.tla_offsite || ''}</div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div><label class="block text-sm font-semibold text-gray-600 mb-2">Learning and Teaching Support Materials (LTSM)</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.ltsm || ''}</div></div>
                                    <div><label class="block text-sm font-semibold text-gray-600 mb-2">Output Materials</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.output || ''}</div></div>
                                </div>
                            </div>
                        </div>`;
                    detailsLessonsContainer.innerHTML += weekHTML;
                });
            } else {
                detailsLessonsContainer.innerHTML = '<p class="text-sm text-gray-500 mt-2">No lessons recorded for this subject.</p>';
            }
            
            document.querySelectorAll('.week-toggle').forEach(button => {
                button.addEventListener('click', () => {
                    const content = button.nextElementSibling;
                    content.classList.toggle('hidden');
                    button.querySelector('svg').classList.toggle('rotate-180');
                });
            });

            subjectDetailsModal.classList.remove('hidden');
            setTimeout(() => {
                subjectDetailsModal.classList.remove('opacity-0');
                modalDetailsPanel.classList.remove('opacity-0', 'scale-95');
            }, 10);
        };

        // --- CORE EVENT LISTENERS ---
        closeDetailsModalButtonTop.addEventListener('click', hideDetailsModal);
        subjectDetailsModal.addEventListener('click', (e) => { if (e.target.id === 'subjectDetailsModal') hideDetailsModal(); });
        
        // --- EDIT SUBJECT BUTTON FUNCTIONALITY ---
        editSubjectDetailsButton.addEventListener('click', () => {
            const subjectDataString = editSubjectDetailsButton.dataset.subjectData;
            if (subjectDataString) {
                try {
                    const subjectData = JSON.parse(subjectDataString);
                    // Redirect to the course builder page with the subject ID as a query parameter
                    window.location.href = `/course-builder?subject_id=${subjectData.id}`;
                } catch (e) {
                    console.error('Failed to parse subject data for editing:', e);
                    alert('Could not open subject for editing due to a data error.');
                }
            } else {
                console.error('No subject data found on the edit button.');
                alert('An error occurred. Subject data is missing.');
            }
        });

        const addDoubleClickEvents = (item) => {
            item.addEventListener('dblclick', () => showDetailsModal(JSON.parse(item.dataset.subjectData)));
        };

        const addDraggableEvents = (item) => {
            item.addEventListener('dragstart', (e) => {
                if (!isEditing || item.dataset.status === 'removed' || item.classList.contains('assigned-card')) {
                    e.preventDefault();
                    return;
                }
                draggedItem = item;
                e.dataTransfer.setData('text/plain', item.dataset.subjectData);
                setTimeout(() => item.classList.add('opacity-50', 'bg-gray-200'), 0);
            });
            item.addEventListener('dragend', () => {
                item.classList.remove('opacity-50', 'bg-gray-200');
                draggedItem = null;
            });
        };

        // UPDATED: createSubjectCard function
        const createSubjectCard = (subject, isMapped = false, status = '') => {
            const newSubjectCard = document.createElement('div');
            newSubjectCard.id = `subject-${subject.subject_code.toLowerCase()}`;
            newSubjectCard.dataset.subjectData = JSON.stringify(subject);
            newSubjectCard.dataset.status = status;

            let cardClasses = 'subject-card p-4 bg-white border border-gray-200 rounded-xl shadow-sm transition-all duration-200 flex items-center gap-4';
            let statusHTML = '';
            let isDraggable = true;
            let typeColorClass, typeTextColorClass, iconSVG;

            switch(subject.subject_type) {
                case 'Major':
                    typeColorClass = 'bg-blue-100'; typeTextColorClass = 'text-blue-700';
                    iconSVG = `<div class="w-12 h-12 flex-shrink-0 bg-blue-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.747-5.747H17.747"></path></svg></div>`;
                    break;
                case 'Minor':
                    typeColorClass = 'bg-green-100'; typeTextColorClass = 'text-green-700';
                    iconSVG = `<div class="w-12 h-12 flex-shrink-0 bg-green-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>`;
                    break;
                case 'Elective':
                    typeColorClass = 'bg-yellow-100'; typeTextColorClass = 'text-yellow-700';
                    iconSVG = `<div class="w-12 h-12 flex-shrink-0 bg-yellow-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2zm0 0V3m0 0h.01"></path></svg></div>`;
                    break;
                default: // GE and others
                    typeColorClass = 'bg-indigo-100'; typeTextColorClass = 'text-indigo-700';
                    iconSVG = `<div class="w-12 h-12 flex-shrink-0 bg-indigo-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.747-5.747H17.747"></path></svg></div>`;
                    break;
            }

            if (isMapped) {
                cardClasses += ' assigned-card cursor-not-allowed';
                statusHTML = `<span class="status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-200 text-gray-700">Assigned</span>`;
                isDraggable = false;
            } else if (status === 'removed') {
                cardClasses += ' opacity-60 cursor-not-allowed removed-subject-card';
                statusHTML = '<span class="text-xs font-semibold text-red-700 bg-red-100 px-2.5 py-1 rounded-full">Removed</span>';
                isDraggable = false;
            } else {
                cardClasses += ' hover:shadow-md hover:border-blue-400 cursor-grab active:cursor-grabbing';
                statusHTML = '<span class="status-badge text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">Available</span>';
            }
            
            newSubjectCard.className = cardClasses;
            newSubjectCard.setAttribute('draggable', isDraggable);
            
            newSubjectCard.innerHTML = `
                ${iconSVG}
                <div class="flex-grow">
                    <p class="subject-name font-bold text-gray-800">${subject.subject_name}</p>
                    <p class="text-sm text-gray-500">${subject.subject_code}</p>
                    <p class="text-sm font-semibold text-gray-600 mt-1">Units: ${subject.subject_unit}</p>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full ${typeColorClass} ${typeTextColorClass}">${subject.subject_type}</span>
                    ${statusHTML}
                </div>`;
            
            if(isDraggable) {
                addDraggableEvents(newSubjectCard);
            }
            addDoubleClickEvents(newSubjectCard);
            
            return newSubjectCard;
        };
        
        // UPDATED: createSubjectTag function
        const createSubjectTag = (subjectData, isEditing = false) => {
            const subjectTag = document.createElement('div');
            subjectTag.className = 'subject-tag bg-white border border-gray-200 shadow-sm rounded-lg p-3 flex items-center justify-between w-full transition-all hover:shadow-md hover:border-blue-500';
            subjectTag.setAttribute('draggable', isEditing);
            subjectTag.dataset.subjectData = JSON.stringify(subjectData);

            let iconSVG;
            switch (subjectData.subject_type) {
                case 'Major': iconSVG = `<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.747-5.747H17.747"></path></svg>`; break;
                case 'Minor': iconSVG = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>`; break;
                case 'Elective': iconSVG = `<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2zm0 0V3m0 0h.01"></path></svg>`; break;
                default: iconSVG = `<svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.747-5.747H17.747"></path></svg>`; break;
            }

            subjectTag.innerHTML = `
                <div class="flex items-center gap-3 flex-grow">
                    ${iconSVG}
                    <div class="flex-grow">
                        <p class="font-bold text-sm text-gray-800 leading-tight">${subjectData.subject_name}</p>
                        <p class="text-xs text-gray-500 font-mono">${subjectData.subject_code}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 ml-2 flex-shrink-0">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-200 text-gray-700">${subjectData.subject_unit} units</span>
                    <button class="delete-subject-tag ${isEditing ? '' : 'hidden'} text-gray-400 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
            
            subjectTag.querySelector('.delete-subject-tag').onclick = (e) => {
                e.stopPropagation();
                subjectTagToRemove = subjectTag;
                showRemoveConfirmationModal();
            };

            addDraggableEvents(subjectTag);
            addDoubleClickEvents(subjectTag);
            return subjectTag;
        };

        const updateUnitTotals = () => {
            let grandTotal = 0;
            const curriculumId = curriculumSelector.value;
            const grandTotalContainer = document.getElementById('grand-total-container');

            if (!curriculumId) {
                grandTotalContainer.classList.add('hidden');
                return;
            }

            document.querySelectorAll('.semester-dropzone').forEach(dropzone => {
                let semesterTotal = 0;
                dropzone.querySelectorAll('.subject-tag').forEach(tag => {
                    const subjectData = JSON.parse(tag.dataset.subjectData);
                    semesterTotal += parseInt(subjectData.subject_unit, 10) || 0;
                });
                dropzone.querySelector('.semester-unit-total').textContent = `Units: ${semesterTotal}`;
                grandTotal += semesterTotal;
            });
            
            const grandTotalSpan = document.getElementById('grand-total-units');
            grandTotalSpan.textContent = grandTotal;
            grandTotalContainer.classList.remove('hidden');
        };

        const toggleEditMode = (enableEdit) => {
            isEditing = enableEdit;
            const dropzones = document.querySelectorAll('.semester-dropzone');
            const deleteButtons = document.querySelectorAll('.delete-subject-tag');
            const saveButton = document.getElementById('saveCurriculumButton');
            const editButton = document.getElementById('editCurriculumButton');

            if (isEditing) {
                dropzones.forEach(dropzone => {
                    dropzone.classList.remove('locked');
                    addDragAndDropListeners(dropzone);
                });
                deleteButtons.forEach(button => button.classList.remove('hidden'));
                saveButton.removeAttribute('disabled');
                editButton.innerHTML = `<svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Cancel`;
            } else {
                dropzones.forEach(dropzone => {
                    dropzone.classList.add('locked');
                    removeDragAndDropListeners(dropzone);
                });
                deleteButtons.forEach(button => button.classList.add('hidden'));
                saveButton.setAttribute('disabled', 'disabled');
                editButton.innerHTML = `<svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg> Edit`;
            }
        };

        const dragOverHandler = (e) => {
            e.preventDefault();
            e.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
        };

        const dragLeaveHandler = (e) => {
            e.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
        };

        const dropHandler = (e) => {
            e.preventDefault();
            const dropzone = e.currentTarget;
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
            if (!draggedItem) return;
            
            const droppedSubjectData = JSON.parse(e.dataTransfer.getData('text/plain'));
            const targetContainer = dropzone.querySelector('.flex-wrap');
            
            const isDuplicateInSameSemester = Array.from(targetContainer.querySelectorAll('.subject-tag')).some(tag => JSON.parse(tag.dataset.subjectData).subject_code === droppedSubjectData.subject_code);
            
            if (!isDuplicateInSameSemester) {
                if (draggedItem.classList.contains('subject-card')) {
                    const subjectTag = createSubjectTag(droppedSubjectData, isEditing);
                    targetContainer.appendChild(subjectTag);
                    draggedItem.setAttribute('draggable', 'false');
                    draggedItem.classList.add('assigned-card', 'cursor-not-allowed');
                    draggedItem.classList.remove('hover:shadow-md', 'hover:border-blue-400', 'cursor-grab', 'active:cursor-grabbing');
        
                    const statusBadge = draggedItem.querySelector('.status-badge');
                    if (statusBadge) {
                        statusBadge.textContent = 'Assigned';
                        statusBadge.className = 'status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-200 text-gray-700';
                    }
                } else if (draggedItem.classList.contains('subject-tag')) {
                    draggedItem.parentNode.removeChild(draggedItem);
                    const subjectTag = createSubjectTag(droppedSubjectData, isEditing);
                    targetContainer.appendChild(subjectTag);
                }
                updateUnitTotals();
            }
        };
        
        const addDragAndDropListeners = (dropzone) => {
            dropzone.addEventListener('dragover', dragOverHandler);
            dropzone.addEventListener('dragleave', dragLeaveHandler);
            dropzone.addEventListener('drop', dropHandler);
        };
        
        const removeDragAndDropListeners = (dropzone) => {
            dropzone.removeEventListener('dragover', dragOverHandler);
            dropzone.removeEventListener('dragleave', dragLeaveHandler);
            dropzone.removeEventListener('drop', dropHandler);
        };

        const initDragAndDrop = () => {
            document.querySelectorAll('.semester-dropzone').forEach(dropzone => {
                addDragAndDropListeners(dropzone);
            });

            document.body.addEventListener('dragover', e => e.preventDefault());
            document.body.addEventListener('drop', e => {
                e.preventDefault();
                if (isEditing && draggedItem && draggedItem.classList.contains('subject-tag') && !e.target.closest('.semester-dropzone')) {
                    const subjectData = JSON.parse(draggedItem.dataset.subjectData);
                    const originalSubjectCard = document.getElementById(`subject-${subjectData.subject_code.toLowerCase()}`);
                    if (originalSubjectCard) {
                        originalSubjectCard.setAttribute('draggable', 'true');
                        originalSubjectCard.classList.remove('assigned-card', 'cursor-not-allowed');
                        originalSubjectCard.classList.add('hover:shadow-md', 'hover:border-blue-400', 'cursor-grab', 'active:cursor-grabbing');
            
                        const statusBadge = originalSubjectCard.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.textContent = 'Available';
                            statusBadge.className = 'status-badge text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full';
                        }
                    }
                    draggedItem.remove();
                    updateUnitTotals();
                }
            });
        };
        
        const showRemoveConfirmationModal = () => {
            const removeConfirmationModal = document.getElementById('removeConfirmationModal');
            const removeModalPanel = document.getElementById('remove-modal-panel');
            removeConfirmationModal.classList.remove('hidden');
            setTimeout(() => {
                removeConfirmationModal.classList.remove('opacity-0');
                removeModalPanel.classList.remove('opacity-0', 'scale-95');
            }, 10);
        };

        const hideRemoveConfirmationModal = () => {
            const removeConfirmationModal = document.getElementById('removeConfirmationModal');
            const removeModalPanel = document.getElementById('remove-modal-panel');
            removeConfirmationModal.classList.add('opacity-0');
            removeModalPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                removeConfirmationModal.classList.add('hidden');
                subjectTagToRemove = null;
            }, 300);
        };

        document.getElementById('cancelRemoveButton').addEventListener('click', hideRemoveConfirmationModal);
        
        document.getElementById('confirmRemoveButton').addEventListener('click', async () => {
            if (!subjectTagToRemove) return;

            const subjectData = JSON.parse(subjectTagToRemove.dataset.subjectData);
            const curriculumId = curriculumSelector.value;
            const dropzone = subjectTagToRemove.closest('.semester-dropzone');
            const year = dropzone.dataset.year;
            const semester = dropzone.dataset.semester;

            try {
                await new Promise(resolve => setTimeout(resolve, 300));

                subjectTagToRemove.remove();

                const originalSubjectCard = document.getElementById(`subject-${subjectData.subject_code.toLowerCase()}`);
                if (originalSubjectCard) {
                    originalSubjectCard.setAttribute('draggable', 'true');
                    originalSubjectCard.classList.remove('assigned-card', 'cursor-not-allowed');
                    originalSubjectCard.classList.add('hover:shadow-md', 'hover:border-blue-400', 'cursor-grab', 'active:cursor-grabbing');
        
                    const statusBadge = originalSubjectCard.querySelector('.status-badge');
                    if (statusBadge) {
                        statusBadge.textContent = 'Available';
                        statusBadge.className = 'status-badge text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full';
                    }
                }

                updateUnitTotals();
                alert('Subject removed successfully!');

            } catch (error) {
                console.error('Error removing subject:', error);
                alert('Error: ' + error.message);
            } finally {
                hideRemoveConfirmationModal();
            }
        });
        
        const hideImportConfirmationModal = () => {
            const importConfirmationModal = document.getElementById('importConfirmationModal');
            const importModalPanel = document.getElementById('import-modal-panel');
            importConfirmationModal.classList.add('opacity-0');
            importModalPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                importConfirmationModal.classList.add('hidden');
                subjectToImport = null;
            }, 300);
        };
        document.getElementById('cancelImportButton').addEventListener('click', hideImportConfirmationModal);
        
        document.getElementById('confirmImportButton').addEventListener('click', () => {
            if (subjectToImport) {
                alert(`Preparing to generate PDF for ${subjectToImport.subject_name}... (PDF logic placeholder)`);
            }
            hideImportConfirmationModal();
        });

        importSubjectDetailsButton.addEventListener('click', () => {
            subjectToImport = JSON.parse(importSubjectDetailsButton.dataset.subjectData);
            const importConfirmationModal = document.getElementById('importConfirmationModal');
            importConfirmationModal.classList.remove('hidden');
            setTimeout(() => {
                importConfirmationModal.classList.remove('opacity-0');
                document.getElementById('import-modal-panel').classList.remove('opacity-0', 'scale-95');
            }, 10);
            hideDetailsModal(); 
        });

        document.getElementById('saveCurriculumButton').addEventListener('click', () => {
            if (!curriculumSelector.value) {
                alert('Please select a curriculum to save.');
                return;
            }
            document.getElementById('saveMappingModal').classList.remove('hidden');
        });

        document.getElementById('cancelSaveMapping').addEventListener('click', () => {
            document.getElementById('saveMappingModal').classList.add('hidden');
        });

        document.getElementById('confirmSaveMapping').addEventListener('click', () => {
            document.getElementById('saveMappingModal').classList.add('hidden');
            document.getElementById('proceedToPrerequisitesModal').classList.remove('hidden');
        });

        document.getElementById('declineProceedToPrerequisites').addEventListener('click', () => {
            document.getElementById('proceedToPrerequisitesModal').classList.add('hidden');
            document.getElementById('mappingSuccessModal').classList.remove('hidden');
        });

        document.getElementById('confirmProceedToPrerequisites').addEventListener('click', () => {
            const curriculumId = curriculumSelector.value;
            window.location.href = `/pre_requisite?curriculumId=${curriculumId}`;
        });

        document.getElementById('closeMappingSuccessModal').addEventListener('click', () => {
            document.getElementById('mappingSuccessModal').classList.add('hidden');
        });


        function filterSubjects() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = typeFilter.value;
            const subjectCards = availableSubjectsContainer.querySelectorAll('.subject-card');

            subjectCards.forEach(card => {
                const subjectData = JSON.parse(card.dataset.subjectData);
                const searchMatch = subjectData.subject_name.toLowerCase().includes(searchTerm) || subjectData.subject_code.toLowerCase().includes(searchTerm);
                const typeMatch = (selectedType === 'All Types' || subjectData.subject_type === selectedType);
                card.style.display = (searchMatch && typeMatch) ? 'block' : 'none';
            });
        }
        searchInput.addEventListener('input', filterSubjects);
        typeFilter.addEventListener('change', filterSubjects);


        function renderCurriculumOverview(yearLevel) {
            let html = '';
            const isSeniorHigh = yearLevel === 'Senior High';
            const maxYear = isSeniorHigh ? 2 : 4;

            const getYearSuffix = (year) => {
                if (year === 1) return 'st';
                if (year === 2) return 'nd';
                if (year === 3) return 'rd';
                return 'th';
            };

            for (let i = 1; i <= maxYear; i++) {
                const yearTitle = `${i}${getYearSuffix(i)} Year`;
                html += `
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">${yearTitle}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="semester-dropzone bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 transition-colors" data-year="${i}" data-semester="1">
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-3">
                                    <h4 class="font-semibold text-gray-600">First Semester</h4>
                                    <div class="semester-unit-total text-sm font-bold text-gray-700">Units: 0</div>
                                </div>
                                <div class="flex-wrap space-y-2 min-h-[80px]"></div>
                            </div>
                            <div class="semester-dropzone bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 transition-colors" data-year="${i}" data-semester="2">
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-3">
                                    <h4 class="font-semibold text-gray-600">Second Semester</h4>
                                    <div class="semester-unit-total text-sm font-bold text-gray-700">Units: 0</div>
                                </div>
                                <div class="flex-wrap space-y-2 min-h-[80px]"></div>
                            </div>
                        </div>
                    </div>`;
            }
            curriculumOverview.innerHTML = html;
            initDragAndDrop();
        }

        function renderAvailableSubjects(subjects, mappedSubjects = [], removedSubjectCodes = []) {
            availableSubjectsContainer.innerHTML = '';
            const mappedSubjectCodes = new Set(mappedSubjects.map(s => s.subject_code));
            const removedCodesSet = new Set(removedSubjectCodes);

            if (subjects.length === 0) {
                availableSubjectsContainer.innerHTML = '<p class="text-gray-500 text-center mt-4">No available subjects found.</p>';
            } else {
                subjects.forEach(subject => {
                    const isMapped = mappedSubjectCodes.has(subject.subject_code);
                    const isRemoved = removedCodesSet.has(subject.subject_code);
                    const status = isRemoved ? 'removed' : '';
                    
                    const newSubjectCard = createSubjectCard(subject, isMapped, status);
                    availableSubjectsContainer.appendChild(newSubjectCard);
                });
            }
        }

        function populateMappedSubjects(subjects) {
            if (!subjects) {
                updateUnitTotals();
                return;
            };
            document.querySelectorAll('.semester-dropzone .flex-wrap').forEach(el => el.innerHTML = '');

            subjects.forEach(subject => {
                const dropzone = document.querySelector(`#curriculumOverview .semester-dropzone[data-year="${subject.pivot.year}"][data-semester="${subject.pivot.semester}"] .flex-wrap`);
                if (dropzone) {
                    const subjectTag = createSubjectTag(subject, isEditing);
                    dropzone.appendChild(subjectTag);
                }
            });
            updateUnitTotals();
        }

        function fetchCurriculums() {
            fetch('/api/curriculums')
                .then(response => response.json())
                .then(curriculums => {
                    curriculumSelector.innerHTML = '<option value="">Select a Curriculum</option>';
                    curriculums.forEach(curriculum => {
                        const optionText = `${curriculum.year_level}: ${curriculum.program_code} ${curriculum.curriculum_name} (${curriculum.academic_year})`;
                        const option = new Option(optionText, curriculum.id);
                        option.dataset.yearLevel = curriculum.year_level;
                        option.dataset.academicYear = curriculum.academic_year;
                        curriculumSelector.appendChild(option);
                    });
                    const urlParams = new URLSearchParams(window.location.search);
                    const newCurriculumId = urlParams.get('curriculumId');
                    if (newCurriculumId) {
                        curriculumSelector.value = newCurriculumId;
                        setTimeout(() => fetchCurriculumData(newCurriculumId), 100);
                    }
                });
        }

        function fetchCurriculumData(id) {
            const selectedOption = curriculumSelector.querySelector(`option[value="${id}"]`);
            if (!selectedOption || !selectedOption.dataset.yearLevel) {
                curriculumOverview.innerHTML = '<p class="text-red-500 text-center mt-4">Could not determine year level. Please reload.</p>';
                return;
            }

            const yearLevel = selectedOption.dataset.yearLevel;
            renderCurriculumOverview(yearLevel);

            fetch(`/api/curriculums/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (!data || !data.curriculum || !data.allSubjects) throw new Error('Invalid data structure from server.');
                    
                    renderAvailableSubjects(data.allSubjects, data.curriculum.subjects, data.removedSubjectCodes);
                    populateMappedSubjects(data.curriculum.subjects);
                    
                    const hasMappedSubjects = data.curriculum.subjects.length > 0;
                    
                    if (hasMappedSubjects) {
                        toggleEditMode(false);
                        document.getElementById('editCurriculumButton').classList.remove('hidden');
                    } else {
                        toggleEditMode(true);
                        document.getElementById('editCurriculumButton').classList.add('hidden');
                    }
                    
                    document.getElementById('editCurriculumButton').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching curriculum data:', error);
                    availableSubjectsContainer.innerHTML = '<p class="text-red-500 text-center mt-4">Could not load subjects.</p>';
                });
        }

        function fetchAllSubjects() {
             availableSubjectsContainer.innerHTML = '<p class="text-gray-500 text-center mt-4">Loading subjects...</p>';
            fetch('/api/subjects')
                .then(response => response.json())
                .then(subjects => {
                    renderAvailableSubjects(subjects);
                })
                .catch(error => {
                    console.error('Error fetching all subjects:', error);
                    availableSubjectsContainer.innerHTML = '<p class="text-red-500 text-center mt-4">Could not load subjects.</p>';
                });
        }

        curriculumSelector.addEventListener('change', (e) => {
            const curriculumId = e.target.value;
            if (curriculumId) {
                fetchCurriculumData(curriculumId);
            } else {
                curriculumOverview.innerHTML = '<p class="text-gray-500 text-center mt-4">Select a curriculum from the dropdown to start mapping subjects.</p>';
                availableSubjectsContainer.innerHTML = '<p class="text-gray-500 text-center mt-4">Select a curriculum to view subjects.</p>';
                updateUnitTotals();
                document.getElementById('editCurriculumButton').classList.add('hidden');
                toggleEditMode(false);
            }
        });

        fetchCurriculums();
        fetchAllSubjects();
    });
</script>
@endsection