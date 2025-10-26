@extends('layouts.app')

@section('content')
<style>
    /* Icon background styles */
    .icon-bg-default { background-color: #F3F4F6; border: 2px solid #E5E7EB; } /* Default Gray */
    .icon-bg-major { background-color: #DBEAFE; border: 2px solid #BFDBFE; }   /* Blue */
    .icon-bg-minor { background-color: #E9D5FF; border: 2px solid #D8B4FE; }   /* Violet */
    .icon-bg-elective { background-color: #FEE2E2; border: 2px solid #FECACA; } /* Red */
    .icon-bg-general { background-color: #FFEDD5; border: 2px solid #FED7AA; } /* Orange */

    .icon-major { color: #3B82F6; }
    .icon-minor { color: #8B5CF6; }
    .icon-elective { color: #EF4444; }
    .icon-general { color: #F97316; }
    .assigned-major { background-color: #DBEAFE; border-color: #BFDBFE; }
    .assigned-minor { background-color: #E9D5FF; border-color: #D8B4FE; }
    .assigned-elective { background-color: #FEE2E2; border-color: #FECACA; }
    .assigned-general { background-color: #FFEDD5; border-color: #FED7AA; }

    .assigned-card {
        background-color: #e0e7ff; 
        border-color: #a5b4fc;
    }
    .assigned-card .subject-name {
        color: #4338ca;
    }
    
    /* Complete semester styling */
    .semester-complete {
        background-color: #f9fafb !important;
        border-color: #d1d5db !important;
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .semester-complete .add-subject-btn {
        display: none !important;
    }
    
    .semester-complete::before {
        content: "✓ Complete";
        position: absolute;
        top: 8px;
        right: 8px;
        background: #10b981;
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 4px;
        z-index: 10;
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
                        <option value="GE">General Education</option>
                    </select>
                </div>

                <div id="availableSubjects" class="flex-1 overflow-y-auto pr-2 -mr-2 space-y-3 pt-2.5">
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
    
 {{-- Subject Details Modal --}}
<div id="subjectDetailsModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-2">
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
                <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Weekly Plan (Weeks 0-18)</h3>
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                        Edit Subject
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    
{{-- Remove Subject Confirmation Modal --}}
    <div id="removeConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 ease-out hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-500 ease-out" id="remove-modal-panel">
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
    <div id="importConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 ease-out hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-500 ease-out" id="import-modal-panel">
                <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Export Subject</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to download a PDF file of this subject's details and lessons?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="cancelImportButton" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button id="confirmImportButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Yes, Export</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Mapping Confirmation Modal --}}
    <div id="saveMappingModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
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
    <div id="proceedToPrerequisitesModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
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

    {{-- Save Success Modal --}}
    <div id="saveSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Successfully Added!</h3>
                <p class="text-sm text-gray-500 mt-2">Your curriculum mapping has been saved successfully!</p>
                <div class="mt-6">
                    <button id="closeSaveSuccessModal" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mapping Success Modal --}}
    <div id="mappingSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
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
    
    {{-- Remove Success Modal --}}
    <div id="removeSuccessModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Success!</h3>
            <p class="text-sm text-gray-500 mt-2">Subject removed successfully and moved to history!</p>
            <div class="mt-6">
                <button id="closeRemoveSuccessModal" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
            </div>
        </div>
    </div>

    </main>

    {{-- edit --}}
    <div id="editConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 transition-all duration-500 ease-out">
                <div class="w-12 h-12 rounded-full bg-blue-100 p-2 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Enable Editing?</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to edit this curriculum? This will allow you to drag, drop, and remove subjects.</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="cancelEditBtn" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button id="confirmEditBtn" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Yes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reassign Subject Confirmation Modal --}}
    <div id="reassignConfirmationModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-yellow-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Reassign Subject</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to move this subject to a different semester?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelReassignBtn" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button id="confirmReassignBtn" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">Yes, Reassign</button>
            </div>
        </div>
    </div>

    {{-- Reassign Success Modal --}}
    <div id="reassignSuccessModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Success!</h3>
            <p class="text-sm text-gray-500 mt-2">Subject has been successfully reassigned.</p>
            <div class="mt-6">
                <button id="closeReassignSuccessBtn" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
            </div>
        </div>
    </div>

    {{-- Subject Version Tracker Modal --}}
    <div id="subjectVersionTrackerModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-4xl rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out" id="version-tracker-modal-panel">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <div>
                        <h2 id="versionTrackerModalTitle" class="text-2xl font-bold text-gray-800">Subject Version Tracker</h2>
                        <p id="versionTrackerModalSubtitle" class="text-sm text-gray-500 mt-1">Compare old and new versions</p>
                    </div>
                    <button id="closeVersionTrackerModalBtn" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <!-- Loading State -->
                    <div id="versionTrackerLoading" class="text-center py-12 hidden">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-500">Loading version history...</p>
                    </div>
                    
                    <!-- Version Comparison -->
                    <div id="versionTrackerContent" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Current Version Card -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Current Version</h3>
                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">CURRENT</span>
                            </div>
                            <div id="newVersionCard" class="cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg">
                                <!-- Current version subject card will be populated here -->
                            </div>
                        </div>
                        
                        <!-- Version History List -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Version History</h3>
                                <span id="versionCount" class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">0 versions</span>
                            </div>
                            <div id="versionHistoryList" class="space-y-3 max-h-96 overflow-y-auto">
                                <!-- Version history will be populated here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- No Previous Version State -->
                    <div id="noOldVersion" class="text-center py-12 hidden">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Previous Version</h3>
                        <p class="text-gray-500">This subject hasn't been modified yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete modals removed --}}
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- MODAL ELEMENTS ---
        const subjectDetailsModal = document.getElementById('subjectDetailsModal');
        const closeDetailsModalButtonTop = document.getElementById('closeDetailsModalButtonTop');
        const modalDetailsPanel = document.getElementById('modal-details-panel');
        const editSubjectDetailsButton = document.getElementById('editSubjectDetailsButton');
        const importSubjectDetailsButton = document.getElementById('importSubjectDetailsButton');
        const editConfirmationModal = document.getElementById('editConfirmationModal');

        // Version Tracker Modal Elements
        const subjectVersionTrackerModal = document.getElementById('subjectVersionTrackerModal');
        const closeVersionTrackerModalBtn = document.getElementById('closeVersionTrackerModalBtn');
        const versionTrackerModalPanel = document.getElementById('version-tracker-modal-panel');
        const versionTrackerLoading = document.getElementById('versionTrackerLoading');
        const versionTrackerContent = document.getElementById('versionTrackerContent');
        const newVersionCard = document.getElementById('newVersionCard');
        const versionHistoryList = document.getElementById('versionHistoryList');
        const versionCount = document.getElementById('versionCount');
        const noOldVersion = document.getElementById('noOldVersion');
        const versionTrackerModalTitle = document.getElementById('versionTrackerModalTitle');
        const versionTrackerModalSubtitle = document.getElementById('versionTrackerModalSubtitle');

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

        const removeSuccessModal = document.getElementById('removeSuccessModal');
        const closeRemoveSuccessModal = document.getElementById('closeRemoveSuccessModal');
        
        // --- STATE VARIABLES ---
        let isAddingSubjectsMode = false;
        let activeSemesterForAdding = null;
        let itemToReassign = null;
        let reassignTargetContainer = null;


        // --- SUBJECT DETAIL MODAL FUNCTIONS ---

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

        const showDetailsModal = (data, showEditButton = true) => {
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
            
            // Show/hide edit button based on parameter
            if (showEditButton) {
                editSubjectDetailsButton.classList.remove('hidden');
            } else {
                editSubjectDetailsButton.classList.add('hidden');
            }

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
                            const match = part.match(/Assessment: ONSITE:\s*(.*)OFFSITE:\s*(.*)/s);
                            if (match) { lessonData.at_onsite = match[1]; lessonData.at_offsite = match[2]; }
                        }
                        if (part.startsWith('Activities:')) {
                            const match = part.match(/Activities: ON-SITE:\s*(.*)OFF-SITE:\s*(.*)/s);
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

        // --- VERSION TRACKER MODAL FUNCTIONS ---
        
        const showVersionTrackerModal = async (subjectData) => {
            // Set modal title
            versionTrackerModalTitle.textContent = `${subjectData.subject_name} Version History`;
            versionTrackerModalSubtitle.textContent = `${subjectData.subject_code} - Compare versions`;
            
            // Show modal with loading state
            subjectVersionTrackerModal.classList.remove('hidden');
            setTimeout(() => {
                subjectVersionTrackerModal.classList.remove('opacity-0');
                versionTrackerModalPanel.classList.remove('opacity-0', 'scale-95');
            }, 10);
            
            // Show loading state
            versionTrackerLoading.classList.remove('hidden');
            versionTrackerContent.classList.add('hidden');
            noOldVersion.classList.add('hidden');
            
            try {
                // Fetch subject version history
                const response = await fetch(`/api/subjects/${subjectData.id}/versions`);
                if (!response.ok) {
                    throw new Error('Failed to fetch version history');
                }
                
                const versionData = await response.json();
                
                // Hide loading state
                versionTrackerLoading.classList.add('hidden');
                
                // Always show the current version
                versionTrackerContent.classList.remove('hidden');
                
                // Create current version card (always available)
                const newCard = createVersionCard(versionData.currentVersion, 'current');
                newVersionCard.innerHTML = '';
                newVersionCard.appendChild(newCard);
                
                // Add click handler for current version
                newCard.addEventListener('click', () => {
                    hideVersionTrackerModal();
                    showDetailsModal(versionData.currentVersion, true); // true = show edit button
                });
                
                // Update version count
                versionCount.textContent = `${versionData.totalVersions} version${versionData.totalVersions !== 1 ? 's' : ''}`;
                
                if (versionData.hasOldVersion && versionData.previousVersions.length > 0) {
                    // Clear version history list
                    versionHistoryList.innerHTML = '';
                    
                    // Create version history items
                    versionData.previousVersions.forEach((version, index) => {
                        const versionItem = createVersionHistoryItem(version, index + 1);
                        versionHistoryList.appendChild(versionItem);
                    });
                } else {
                    // Show no versions message
                    versionHistoryList.innerHTML = `
                        <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">No previous versions available</p>
                            <p class="text-xs text-gray-400 mt-1">This is the original version</p>
                        </div>
                    `;
                }
                
            } catch (error) {
                console.error('Error fetching version history:', error);
                versionTrackerLoading.classList.add('hidden');
                
                // Still show current version even if API fails
                versionTrackerContent.classList.remove('hidden');
                
                // Create current version card from the original subject data
                const newCard = createVersionCard(subjectData, 'current');
                newVersionCard.innerHTML = '';
                newVersionCard.appendChild(newCard);
                
                // Add click handler for current version
                newCard.addEventListener('click', () => {
                    hideVersionTrackerModal();
                    showDetailsModal(subjectData, true); // true = show edit button
                });
                
                // Update version count to show error
                versionCount.textContent = 'Error loading';
                versionCount.className = 'px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full';
                
                // Show error message in version history area
                versionHistoryList.innerHTML = `
                    <div class="text-center py-8 border-2 border-dashed border-red-300 rounded-xl bg-red-50">
                        <svg class="w-8 h-8 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm text-red-600">Error loading version history</p>
                        <p class="text-xs text-red-400 mt-1">Could not fetch previous versions</p>
                    </div>
                `;
            }
        };
        
        const hideVersionTrackerModal = () => {
            subjectVersionTrackerModal.classList.add('opacity-0');
            versionTrackerModalPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => subjectVersionTrackerModal.classList.add('hidden'), 300);
        };
        
        const createVersionCard = (subjectData, version) => {
            const isCurrent = version === 'current' || version === 'new';
            const card = document.createElement('div');
            card.className = `bg-white border-2 ${isCurrent ? 'border-green-200 hover:border-green-300' : 'border-gray-200 hover:border-gray-300'} rounded-xl p-4 transition-all duration-200 hover:shadow-md`;
            
            // Determine subject type styling
            let iconBgClass = 'bg-gray-100';
            let iconTextClass = 'text-gray-600';
            
            const subjectType = subjectData.subject_type.toLowerCase();
            if (subjectType.includes('major')) {
                iconBgClass = 'bg-blue-100';
                iconTextClass = 'text-blue-600';
            } else if (subjectType.includes('minor')) {
                iconBgClass = 'bg-purple-100';
                iconTextClass = 'text-purple-600';
            } else if (subjectType.includes('elective')) {
                iconBgClass = 'bg-red-100';
                iconTextClass = 'text-red-600';
            } else {
                iconBgClass = 'bg-orange-100';
                iconTextClass = 'text-orange-600';
            }
            
            card.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-12 h-12 ${iconBgClass} rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 ${iconTextClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 truncate">${subjectData.subject_name}</h4>
                        <p class="text-xs text-gray-500">${subjectData.subject_code}</p>
                        <div class="flex items-center mt-1 space-x-2">
                            <span class="text-xs px-2 py-1 rounded-full ${isCurrent ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">${subjectData.subject_type}</span>
                            <span class="text-xs text-gray-400">${subjectData.units} units</span>
                        </div>
                        ${subjectData.updated_at ? `<p class="text-xs text-gray-400 mt-1">Updated: ${new Date(subjectData.updated_at).toLocaleDateString()}</p>` : ''}
                    </div>
                </div>
            `;
            
            return card;
        };

        const createVersionHistoryItem = (versionData, displayNumber) => {
            const item = document.createElement('div');
            item.className = 'bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer hover:border-blue-300';
            
            // Format date
            const versionDate = new Date(versionData.created_at);
            const formattedDate = versionDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            item.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">
                                v${versionData.version_number}
                            </span>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-sm font-medium text-gray-900 truncate">${versionData.subject_name}</h5>
                                <p class="text-xs text-gray-500">${versionData.subject_code}</p>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-xs text-gray-400">${formattedDate}</span>
                                ${versionData.change_reason ? `<span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">${versionData.change_reason}</span>` : ''}
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">${versionData.subject_type}</span>
                        </div>
                    </div>
                    <div class="ml-3 flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            `;
            
            // Add click handler
            item.addEventListener('click', () => {
                hideVersionTrackerModal();
                showDetailsModal(versionData, false); // false = no edit button for old versions
            });
            
            return item;
        };

        // --- CORE EVENT LISTENERS ---
        closeDetailsModalButtonTop.addEventListener('click', hideDetailsModal);
        subjectDetailsModal.addEventListener('click', (e) => { if (e.target.id === 'subjectDetailsModal') hideDetailsModal(); });
        
        // Version Tracker Modal Event Listeners
        closeVersionTrackerModalBtn.addEventListener('click', hideVersionTrackerModal);
        subjectVersionTrackerModal.addEventListener('click', (e) => { if (e.target.id === 'subjectVersionTrackerModal') hideVersionTrackerModal(); });
        
        // --- EDIT SUBJECT BUTTON FUNCTIONALITY ---
        editSubjectDetailsButton.addEventListener('click', () => {
            const subjectDataString = editSubjectDetailsButton.dataset.subjectData;
            if (subjectDataString) {
                try {
                    const subjectData = JSON.parse(subjectDataString);
                    window.location.href = `/course-builder?subject_id=${subjectData.id}`;
                } catch (e) {
                    console.error('Failed to parse subject data for editing:', e);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Could not open subject for editing due to a data error.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#EF4444'
                    });
                }
            } else {
                console.error('No subject data found on the edit button.');
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred. Subject data is missing.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#EF4444'
                });
            }
        });

        const addDoubleClickEvents = (item) => {
            item.addEventListener('dblclick', () => showVersionTrackerModal(JSON.parse(item.dataset.subjectData)));
        };

        const addDraggableEvents = (item) => {
            item.addEventListener('dragstart', (e) => {
                if (!isEditing || (item.classList.contains('subject-card') && item.classList.contains('assigned-card'))) {
                    e.preventDefault();
                    return;
                }
                
                // Check if the subject tag is in a complete semester
                if (item.classList.contains('subject-tag')) {
                    const semesterDropzone = item.closest('.semester-dropzone');
                    if (semesterDropzone && semesterDropzone.dataset.isComplete === 'true') {
                        e.preventDefault();
                        return;
                    }
                }
                
                draggedItem = item;
                e.dataTransfer.setData('text/plain', item.dataset.subjectData);
                setTimeout(() => item.classList.add('opacity-50', 'bg-gray-200'), 0);
            });
            item.addEventListener('dragend', () => {
                if (draggedItem) {
                    draggedItem.classList.remove('opacity-50', 'bg-gray-200');
                }
                draggedItem = null;
            });
        };

    const createSubjectCard = (subject, isMapped = false, status = '') => {
        const newSubjectCard = document.createElement('div');
        newSubjectCard.id = `subject-${subject.subject_code.toLowerCase()}`;
        newSubjectCard.dataset.subjectData = JSON.stringify(subject);
        newSubjectCard.dataset.status = status;

        let cardClasses = 'subject-card p-4 border border-gray-200 rounded-xl shadow-sm transition-all duration-200 flex items-center gap-4';
        let statusHTML = '';
        let isDraggable = true;
        
        let iconContainerClasses = 'icon-bg-default';
        let iconSvgClasses = 'text-gray-500';

        if (isMapped) {
            const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
            let assignedClass = 'assigned-card'; 

            switch (true) {
                case subject.subject_type === 'Major':
                    assignedClass = 'assigned-major';
                    iconContainerClasses = 'icon-bg-major';
                    iconSvgClasses = 'icon-major';
                    break;
                case subject.subject_type === 'Minor':
                    assignedClass = 'assigned-minor';
                    iconContainerClasses = 'icon-bg-minor';
                    iconSvgClasses = 'icon-minor';
                    break;
                case subject.subject_type === 'Elective':
                    assignedClass = 'assigned-elective';
                    iconContainerClasses = 'icon-bg-elective';
                    iconSvgClasses = 'icon-elective';
                    break;
                case geIdentifiers.map(id => id.toLowerCase()).includes(subject.subject_type.toLowerCase()):
                    assignedClass = 'assigned-general';
                    iconContainerClasses = 'icon-bg-general';
                    iconSvgClasses = 'icon-general';
                    break;
            }
            cardClasses += ` ${assignedClass} cursor-not-allowed`;
            statusHTML = `<span class="status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-200 text-gray-700">Assigned</span>`;
            isDraggable = false;
        } else {
            cardClasses += ' bg-white hover:shadow-md hover:border-blue-400 cursor-grab active:cursor-grabbing';
            statusHTML = '<span class="status-badge text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">Available</span>';
        }
        
        newSubjectCard.className = cardClasses;
        newSubjectCard.setAttribute('draggable', isDraggable);
        
        newSubjectCard.innerHTML = `
            <div class="flex-shrink-0 w-12 h-12 ${iconContainerClasses} rounded-lg flex items-center justify-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ${iconSvgClasses} transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="flex-grow">
                <p class="subject-name font-bold text-gray-800">${subject.subject_name}</p>
                <p class="text-sm text-gray-500">${subject.subject_code}</p>
                <p class="text-sm font-semibold text-gray-600 mt-1">Units: ${subject.subject_unit}</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full">${subject.subject_type}</span>
                ${statusHTML}
            </div>
            <div class="add-subject-checkbox hidden ml-2">
                <input type="checkbox" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            </div>`;
        
        if(isDraggable) {
            addDraggableEvents(newSubjectCard);
        }
        addDoubleClickEvents(newSubjectCard);
        
        return newSubjectCard;
    };
        
        const createSubjectTag = (subjectData, isEditing = false) => {
            const subjectTag = document.createElement('div');
            subjectTag.setAttribute('draggable', isEditing);
            subjectTag.dataset.subjectData = JSON.stringify(subjectData);

            let baseClasses = 'subject-tag shadow-sm rounded-lg p-3 flex items-center justify-between w-full transition-all border';
            let colorClasses = '';
            let textClasses = '';
            let unitClasses = '';
            let iconClasses = '';
            let codeClasses = '';
            let deleteBtnClasses = 'text-red-500 hover:text-red-700';

            const subjectType = subjectData.subject_type;
            const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];

            if (subjectType === 'Major') {
                colorClasses = 'bg-blue-100 border-blue-200 hover:bg-blue-200';
                textClasses = 'text-blue-800 font-bold';
                unitClasses = 'bg-blue-200 text-blue-800';
                iconClasses = 'text-blue-500';
                codeClasses = 'text-blue-700';
            } else if (subjectType === 'Minor') {
                colorClasses = 'bg-purple-100 border-purple-200 hover:bg-purple-200';
                textClasses = 'text-purple-800 font-bold';
                unitClasses = 'bg-purple-200 text-purple-800';
                iconClasses = 'text-purple-500';
                codeClasses = 'text-purple-700';
            } else if (subjectType === 'Elective') {
                colorClasses = 'bg-red-100 border-red-200 hover:bg-red-200';
                textClasses = 'text-red-800 font-bold';
                unitClasses = 'bg-red-200 text-red-800';
                iconClasses = 'text-red-500';
                codeClasses = 'text-red-700';
            } else if (geIdentifiers.map(id => id.toLowerCase()).includes(subjectType.toLowerCase())) {
                colorClasses = 'bg-orange-50 text-orange-700 hover:bg-orange-100 border-orange-200';
                textClasses = 'text-orange-700 font-bold';
                unitClasses = 'bg-orange-200 text-orange-700';
                iconClasses = 'text-orange-500';
                codeClasses = 'text-orange-700';
            } else {
                colorClasses = 'bg-white border-gray-200 hover:shadow-md hover:border-blue-500';
                textClasses = 'text-gray-800 font-bold';
                unitClasses = 'bg-gray-200 text-gray-700';
                iconClasses = 'text-gray-400';
                codeClasses = 'text-gray-500';
            }

            subjectTag.className = `${baseClasses} ${colorClasses}`;

            subjectTag.innerHTML = `
                <div class="flex items-center gap-3 flex-grow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ${iconClasses}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <div class="flex-grow">
                        <p class="text-sm leading-tight ${textClasses}">${subjectData.subject_name}</p>
                        <p class="text-xs font-mono ${codeClasses}">${subjectData.subject_code}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 ml-2 flex-shrink-0">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full ${unitClasses}">${subjectData.subject_unit} units</span>
                    <button class="delete-subject-tag ${isEditing ? '' : 'hidden'} ${deleteBtnClasses} transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
            
            subjectTag.querySelector('.delete-subject-tag').onclick = (e) => {
                e.stopPropagation();
                const subjectTag = e.currentTarget.closest('.subject-tag');
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
            // Count all subjects (both confirmed and pending) in unit totals
            const subjectData = JSON.parse(tag.dataset.subjectData);
            semesterTotal += parseInt(subjectData.subject_unit, 10) || 0;
        });
        
        const unitLimit = parseFloat(dropzone.dataset.unitLimit) || 0;
        const formatUnits = (units) => {
            const num = parseFloat(units);
            return num % 1 === 0 ? Math.floor(num) : num;
        };
        
        // Update unit total display
        const unitTotalElement = dropzone.querySelector('.semester-unit-total');
        if (unitLimit > 0) {
            const isOverLimit = semesterTotal > unitLimit;
            unitTotalElement.textContent = `Units: ${formatUnits(semesterTotal)}/${formatUnits(unitLimit)}`;
            unitTotalElement.className = `semester-unit-total text-sm font-bold ${isOverLimit ? 'text-red-600' : 'text-gray-700'}`;
            
            // Update progress bar
            const progressBar = dropzone.querySelector('.unit-progress');
            if (progressBar) {
                const percentage = Math.min((semesterTotal / unitLimit) * 100, 100);
                progressBar.style.width = `${percentage}%`;
                
                // Change color based on usage
                const isComplete = semesterTotal >= unitLimit;
                if (semesterTotal > unitLimit) {
                    progressBar.className = 'unit-progress bg-red-500 h-full transition-all duration-300';
                } else if (isComplete) {
                    progressBar.className = 'unit-progress bg-green-500 h-full transition-all duration-300';
                } else {
                    progressBar.className = 'unit-progress bg-blue-500 h-full transition-all duration-300';
                }
            }
            
            // Update dropzone border color and add complete status
            const isComplete = semesterTotal >= unitLimit;
            dropzone.dataset.isComplete = isComplete;
            
            if (isOverLimit) {
                dropzone.classList.add('border-red-400');
                dropzone.classList.remove('border-gray-300', 'border-green-400', 'semester-complete');
            } else if (isComplete) {
                dropzone.classList.add('border-green-400', 'semester-complete');
                dropzone.classList.remove('border-gray-300', 'border-red-400');
                dropzone.style.position = 'relative'; // Needed for the ::before pseudo-element
            } else {
                dropzone.classList.add('border-gray-300');
                dropzone.classList.remove('border-red-400', 'border-green-400', 'semester-complete');
                dropzone.style.position = '';
            }
            
            // Hide/show add subject button based on completion
            const addSubjectBtn = dropzone.querySelector('.add-subject-btn-placeholder');
            if (addSubjectBtn) {
                if (isComplete) {
                    addSubjectBtn.classList.add('hidden');
                } else if (isEditing) {
                    addSubjectBtn.classList.remove('hidden');
                }
            }
        } else {
            unitTotalElement.textContent = `Units: ${formatUnits(semesterTotal)}`;
        }
        
        grandTotal += semesterTotal;
    });
    
    const grandTotalSpan = document.getElementById('grand-total-units');
    grandTotalSpan.textContent = grandTotal;
    grandTotalContainer.classList.remove('hidden');
    updateAllTotals(); 
}; 

const updateAllTotals = () => {
    document.querySelectorAll('.semester-dropzone').forEach(dropzone => {
        const typeCounts = {
            Major: 0,
            Minor: 0,
            Elective: 0,
            General: 0,
        };
        const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
        const subjectTags = dropzone.querySelectorAll('.subject-tag');
        const totalSubjectCount = subjectTags.length;

        subjectTags.forEach(tag => {
            const subjectData = JSON.parse(tag.dataset.subjectData);
            const subjectType = subjectData.subject_type;

            if (geIdentifiers.map(id => id.toLowerCase()).includes(subjectType.toLowerCase())) {
                typeCounts.General++;
            } else if (typeCounts.hasOwnProperty(subjectType)) {
                typeCounts[subjectType]++;
            }
        });

        const totalsContainer = dropzone.querySelector('.semester-type-totals');
        if (!totalsContainer) return;

        // Clear all previous badges
        totalsContainer.innerHTML = '';

        const typeStyles = {
            Major: 'bg-blue-100 text-blue-800',
            Minor: 'bg-purple-100 text-purple-800',
            Elective: 'bg-red-100 text-red-800',
            General: 'bg-orange-100 text-orange-800',
        };

        // Add the type-specific badges first
        ['Major', 'Minor', 'Elective', 'General'].forEach(type => {
            if (typeCounts[type] > 0) {
                const badge = document.createElement('span');
                badge.className = `px-2 py-1 rounded-full font-semibold ${typeStyles[type]}`;
                badge.textContent = `${type}: ${typeCounts[type]}`;
                totalsContainer.appendChild(badge);
            }
        });

        // Add the "Total Subjects" badge at the end
        if (totalSubjectCount > 0) {
            const totalBadge = document.createElement('span');
            totalBadge.className = 'px-2 py-1 rounded-full font-semibold bg-gray-200 text-gray-800';
            totalBadge.textContent = `Total Subjects: ${totalSubjectCount}`;
            totalsContainer.appendChild(totalBadge);
        }
    });
};

        const toggleEditMode = (enableEdit) => {
            isEditing = enableEdit;
            const dropzones = document.querySelectorAll('.semester-dropzone');
            const deleteButtons = document.querySelectorAll('.delete-subject-tag');
            const saveButton = document.getElementById('saveCurriculumButton');
            const editButton = document.getElementById('editCurriculumButton');
            const subjectTags = document.querySelectorAll('.subject-tag');
            const addSubjectButtons = document.querySelectorAll('.add-subject-btn-placeholder');


            if (isEditing) {
                dropzones.forEach(dropzone => {
                    dropzone.classList.remove('locked');
                    addDragAndDropListeners(dropzone);
                });
                deleteButtons.forEach(button => button.classList.remove('hidden'));
                addSubjectButtons.forEach(button => button.classList.remove('hidden'));
                saveButton.removeAttribute('disabled');
                editButton.innerHTML = `<svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Cancel`;
                
                subjectTags.forEach(tag => tag.setAttribute('draggable', 'true'));

            } else {
                dropzones.forEach(dropzone => {
                    dropzone.classList.add('locked');
                    removeDragAndDropListeners(dropzone);
                });
                deleteButtons.forEach(button => button.classList.add('hidden'));
                addSubjectButtons.forEach(button => button.classList.add('hidden'));

                saveButton.setAttribute('disabled', 'disabled');
                editButton.innerHTML = `<svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg> Edit`;

                subjectTags.forEach(tag => tag.setAttribute('draggable', 'false'));
                
                // Ensure adding subjects mode is turned off
                if (isAddingSubjectsMode) {
                    toggleAddSubjectsMode(null);
                }
            }
        };

        const dragOverHandler = (e) => {
            e.preventDefault();
            const dropzone = e.currentTarget;
            
            // Check if semester is complete
            const isComplete = dropzone.dataset.isComplete === 'true';
            if (isComplete) {
                dropzone.classList.add('border-red-400', 'bg-red-50');
                return;
            }
            
            dropzone.classList.add('border-blue-500', 'bg-blue-50');
        };

        const dragLeaveHandler = (e) => {
            const dropzone = e.currentTarget;
            dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'border-red-400', 'bg-red-50');
        };

    const dropHandler = (e) => {
        e.preventDefault();
        const dropzone = e.currentTarget;
        dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'border-red-400', 'bg-red-50');
        if (!draggedItem) return;
        
        // Check if semester is complete
        const isComplete = dropzone.dataset.isComplete === 'true';
        if (isComplete) {
            const year = dropzone.dataset.year;
            const semester = dropzone.dataset.semester;
            const semesterName = semester === '1' ? 'First' : 'Second';
            
            Swal.fire({
                title: 'Semester Complete!',
                html: `
                    <div class="text-center">
                        <div class="mb-3">
                            <svg class="w-16 h-16 mx-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <p class="text-gray-700">Year ${year}, ${semesterName} Semester has reached its unit limit.</p>
                        <p class="text-sm text-gray-500 mt-2">You cannot add more subjects to this semester.</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10B981'
            });
            return;
        }
        
        const droppedSubjectData = JSON.parse(e.dataTransfer.getData('text/plain'));
        const targetContainer = dropzone.querySelector('.flex-wrap');
        
        // Check unit limit validation
        const unitLimit = parseFloat(dropzone.dataset.unitLimit) || 0;
        if (unitLimit > 0) {
            let currentTotal = 0;
            targetContainer.querySelectorAll('.subject-tag').forEach(tag => {
                // Only count non-pending subjects in unit limit validation
                if (tag.dataset.isPending !== 'true') {
                    const subjectData = JSON.parse(tag.dataset.subjectData);
                    currentTotal += parseInt(subjectData.subject_unit, 10) || 0;
                }
            });
            
            const newSubjectUnits = parseInt(droppedSubjectData.subject_unit, 10) || 0;
            const wouldExceedLimit = (currentTotal + newSubjectUnits) > unitLimit;
            
            if (wouldExceedLimit) {
                const year = dropzone.dataset.year;
                const semester = dropzone.dataset.semester;
                const formatUnits = (units) => {
                    const num = parseFloat(units);
                    return num % 1 === 0 ? Math.floor(num) : num;
                };
                
                Swal.fire({
                    title: 'Unit Limit Exceeded!',
                    html: `
                        <div class="text-left">
                            <p class="mb-2">Cannot add <strong>"${droppedSubjectData.subject_name}"</strong> (${formatUnits(newSubjectUnits)} units) to Year ${year}, Semester ${semester}.</p>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="text-sm text-red-700">
                                    <strong>Current:</strong> ${formatUnits(currentTotal)} units<br>
                                    <strong>Adding:</strong> ${formatUnits(newSubjectUnits)} units<br>
                                    <strong>Total would be:</strong> ${formatUnits(currentTotal + newSubjectUnits)} units<br>
                                    <strong>Semester limit:</strong> ${formatUnits(unitLimit)} units
                                </p>
                            </div>
                        </div>
                    `,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#EF4444',
                    customClass: {
                        popup: 'text-sm'
                    }
                });
                return;
            }
        }
        
        const isDuplicateInSameSemester = Array.from(targetContainer.querySelectorAll('.subject-tag')).some(tag => JSON.parse(tag.dataset.subjectData).subject_code === droppedSubjectData.subject_code);
        
        if (!isDuplicateInSameSemester) {
            if (draggedItem.classList.contains('subject-card')) {
                const subjectTag = createSubjectTag(droppedSubjectData, isEditing);
                subjectTag.dataset.isNew = 'true'; // Mark as new
                targetContainer.appendChild(subjectTag);
                draggedItem.setAttribute('draggable', 'false');
                
                draggedItem.classList.remove('bg-white', 'hover:shadow-md', 'hover:border-blue-400', 'cursor-grab', 'active:cursor-grabbing');
                
                const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
                let assignedClass = 'assigned-card';
                let iconBgClass = 'bg-gray-100';
                let iconSvgClass = 'text-gray-500';

                switch (true) {
                    case droppedSubjectData.subject_type === 'Major':
                        assignedClass = 'assigned-major';
                        iconBgClass = 'icon-bg-major';
                        iconSvgClass = 'icon-major';
                        break;
                    case droppedSubjectData.subject_type === 'Minor':
                        assignedClass = 'assigned-minor';
                        iconBgClass = 'icon-bg-minor';
                        iconSvgClass = 'icon-minor';
                        break;
                    case droppedSubjectData.subject_type === 'Elective':
                        assignedClass = 'assigned-elective';
                        iconBgClass = 'icon-bg-elective';
                        iconSvgClass = 'icon-elective';
                        break;
                    case geIdentifiers.map(id => id.toLowerCase()).includes(droppedSubjectData.subject_type.toLowerCase()):
                        assignedClass = 'assigned-general';
                        iconBgClass = 'icon-bg-general';
                        iconSvgClass = 'icon-general';
                        break;
                }
                
                draggedItem.classList.add(assignedClass, 'cursor-not-allowed');

                const iconContainer = draggedItem.querySelector('.flex-shrink-0');
                const iconSvg = iconContainer.querySelector('svg');
                
                iconContainer.classList.remove('bg-gray-100');
                iconSvg.classList.remove('text-gray-500');
                
                iconContainer.classList.add(iconBgClass);
                iconSvg.classList.add(iconSvgClass);

                const statusBadge = draggedItem.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.textContent = 'Assigned';
                    statusBadge.className = 'status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-200 text-gray-700';
                }
                updateUnitTotals();
                
                // Show SweetAlert for successful subject mapping
                const year = dropzone.dataset.year;
                const semester = dropzone.dataset.semester;
                Swal.fire({
                    title: 'Subject Added Successfully!',
                    text: `"${droppedSubjectData.subject_name}" (${droppedSubjectData.subject_code}) has been added to Year ${year}, Semester ${semester}.`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10B981',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInRight'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutRight'
                    }
                });
            } else if (draggedItem.classList.contains('subject-tag')) {
                itemToReassign = draggedItem;
                reassignTargetContainer = targetContainer; 
                
                document.getElementById('reassignConfirmationModal').classList.remove('hidden');
            }
        } else {
            // Show SweetAlert for duplicate subject
            const year = dropzone.dataset.year;
            const semester = dropzone.dataset.semester;
            Swal.fire({
                title: 'Duplicate Subject!',
                text: `"${droppedSubjectData.subject_name}" (${droppedSubjectData.subject_code}) is already assigned to Year ${year}, Semester ${semester}.`,
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#F59E0B',
                showClass: {
                    popup: 'animate__animated animate__headShake'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
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
        
        // --- THIS IS THE FIXED FUNCTION ---
        const toggleAddSubjectsMode = (semesterDropzone) => {
            // If we are turning the mode on
            if (semesterDropzone && !isAddingSubjectsMode) {
                // Check if semester is complete
                const isComplete = semesterDropzone.dataset.isComplete === 'true';
                if (isComplete) {
                    const year = semesterDropzone.dataset.year;
                    const semester = semesterDropzone.dataset.semester;
                    const semesterName = semester === '1' ? 'First' : 'Second';
                    
                    Swal.fire({
                        title: 'Semester Complete!',
                        html: `
                            <div class="text-center">
                                <div class="mb-3">
                                    <svg class="w-16 h-16 mx-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-700">Year ${year}, ${semesterName} Semester has reached its unit limit.</p>
                                <p class="text-sm text-gray-500 mt-2">You cannot add more subjects to this semester.</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10B981'
                    });
                    return;
                }
                
                isAddingSubjectsMode = true;
                activeSemesterForAdding = semesterDropzone;
                
                // Show checkboxes ONLY for available subjects
                availableSubjectsContainer.querySelectorAll('.subject-card').forEach(card => {
                    if (card.getAttribute('draggable') === 'true') {
                        const checkboxDiv = card.querySelector('.add-subject-checkbox');
                        if (checkboxDiv) {
                            checkboxDiv.classList.remove('hidden');
                        }
                    }
                });

                semesterDropzone.querySelector('.add-subject-btn-placeholder').classList.add('hidden');

            // If we are turning the mode off
            } else {
                isAddingSubjectsMode = false;
                
                // Hide ALL checkboxes and uncheck them
                document.querySelectorAll('.add-subject-checkbox').forEach(cb => {
                    cb.classList.add('hidden');
                    cb.querySelector('input').checked = false;
                });

                if (activeSemesterForAdding) {
                    activeSemesterForAdding.querySelector('.add-subject-btn-placeholder').classList.remove('hidden');
                }
                document.querySelectorAll('.add-subject-btn-placeholder').forEach(div => {
                    if (isEditing) div.classList.remove('hidden');
                });
                
                activeSemesterForAdding = null;
            }
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
        
        // --- REMOVE SUBJECT CONFIRMATION ---
        document.getElementById('confirmRemoveButton').addEventListener('click', async () => {
            if (!subjectTagToRemove) return;

            const subjectData = JSON.parse(subjectTagToRemove.dataset.subjectData);
            const dropzone = subjectTagToRemove.closest('.semester-dropzone');
            const year = dropzone.dataset.year;
            const semester = dropzone.dataset.semester;
            const isNewSubject = subjectTagToRemove.dataset.isNew === 'true';

            // Helper function to reset subject card appearance
            const resetSubjectCard = (originalSubjectCard) => {
                if (!originalSubjectCard) return;
                
                // Reset subject to Available status
                originalSubjectCard.dataset.status = '';
                originalSubjectCard.setAttribute('draggable', 'true');
                originalSubjectCard.classList.remove('assigned-card', 'assigned-major', 'assigned-minor', 'assigned-elective', 'assigned-general', 'bg-white', 'opacity-60', 'cursor-not-allowed', 'removed-subject-card');
                originalSubjectCard.classList.add('bg-white', 'hover:shadow-md', 'hover:border-blue-400', 'cursor-grab');

                // Reset icon styling to original available state
                const iconContainer = originalSubjectCard.querySelector('.flex-shrink-0');
                const iconSvg = iconContainer?.querySelector('svg');
                const subjectType = subjectData.subject_type.toLowerCase();
                
                // Restore original icon styling based on subject type
                if (subjectType.includes('major')) {
                    if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                    if (iconSvg) iconSvg.className = 'h-6 w-6 text-blue-600 transition-colors duration-300';
                } else if (subjectType.includes('minor')) {
                    if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                    if (iconSvg) iconSvg.className = 'h-6 w-6 text-purple-600 transition-colors duration-300';
                } else if (subjectType.includes('elective')) {
                    if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                    if (iconSvg) iconSvg.className = 'h-6 w-6 text-red-600 transition-colors duration-300';
                } else {
                    if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                    if (iconSvg) iconSvg.className = 'h-6 w-6 text-orange-600 transition-colors duration-300';
                }
                
                // Reset status badge to Available
                const statusBadge = originalSubjectCard.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.textContent = 'Available';
                    statusBadge.className = 'status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700';
                }
                
                // Uncheck checkbox if it exists and is checked
                const checkbox = originalSubjectCard.querySelector('.add-subject-checkbox input');
                if (checkbox && checkbox.checked) {
                    checkbox.checked = false;
                }
            };

            if (isNewSubject) {
                // Handle new subjects (added via checkbox) - no API call needed
                const isPending = subjectTagToRemove.dataset.isPending === 'true';
                subjectTagToRemove.remove();
                const originalSubjectCard = document.getElementById(`subject-${subjectData.subject_code.toLowerCase()}`);
                resetSubjectCard(originalSubjectCard);
                
                // If it was a pending subject, don't need to update unit totals since it wasn't counted
                if (!isPending) {
                    updateUnitTotals();
                }
                hideRemoveConfirmationModal();
                return;
            }

            // Handle existing subjects - need API call
            const curriculumId = curriculumSelector.value;
            try {
                const response = await fetch('/api/curriculum/remove-subject', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        curriculumId: curriculumId,
                        subjectId: subjectData.id,
                        year: year,
                        semester: semester
                    }),
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to remove the subject.');
                }

                subjectTagToRemove.remove();
                const originalSubjectCard = document.getElementById(`subject-${subjectData.subject_code.toLowerCase()}`);
                resetSubjectCard(originalSubjectCard);
                updateUnitTotals();
                
                // Use SweetAlert for success
                Swal.fire({
                    title: 'Subject Removed Successfully!',
                    text: `"${subjectData.subject_name}" (${subjectData.subject_code}) has been removed from Year ${year}, Semester ${semester} and is now available for assignment.`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10B981',
                    timer: 5000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });

            } catch (error) {
                console.error('Error removing subject:', error);
                Swal.fire({
                    title: 'Failed to Remove Subject!',
                    text: 'An error occurred while trying to remove the subject: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#EF4444',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            } finally {
                hideRemoveConfirmationModal();
            }
        });

        closeRemoveSuccessModal.addEventListener('click', () => {
            removeSuccessModal.classList.add('hidden');
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
                // Redirect to the export route
                window.location.href = `/subjects/${subjectToImport.id}/export-pdf`;
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
                Swal.fire({
                    title: 'No Curriculum Selected!',
                    text: 'Please select a curriculum from the dropdown before saving.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#F59E0B'
                });
                return;
            }
            
            // Show traditional modal for save confirmation
            document.getElementById('saveMappingModal').classList.remove('hidden');
        });

        // Add back modal event handlers
        document.getElementById('cancelSaveMapping').addEventListener('click', () => {
            document.getElementById('saveMappingModal').classList.add('hidden');
        });

        document.getElementById('confirmSaveMapping').addEventListener('click', async () => {
            document.getElementById('saveMappingModal').classList.add('hidden');
            
            const saveResult = await saveCurriculumData();
            
            if (saveResult) {
                console.log('Save successful:', saveResult.message);
                
                // Exit edit mode after successful save
                toggleEditMode(false);
                
                // Show traditional modal for prerequisites setup
                document.getElementById('proceedToPrerequisitesModal').classList.remove('hidden');
            } else {
                console.log('Save failed.');
            }
        });

        const saveCurriculumData = async () => {
            const curriculumId = curriculumSelector.value;
            if (!curriculumId) {
                Swal.fire({
                    title: 'No Curriculum Selected!',
                    text: 'Please select a curriculum from the dropdown first.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#F59E0B'
                });
                return null; 
            }

            const curriculumData = [];
            document.querySelectorAll('.semester-dropzone').forEach(dropzone => {
                const year = dropzone.dataset.year;
                const semester = dropzone.dataset.semester;
                const subjects = [];
                
                dropzone.querySelectorAll('.subject-tag').forEach(tag => {
                    subjects.push(JSON.parse(tag.dataset.subjectData));
                });
                
                curriculumData.push({ year, semester, subjects });
            });

            try {
                const response = await fetch('/api/curriculums/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ curriculumId, curriculumData }),
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to save the curriculum mapping.');
                }
                
                return await response.json();

            } catch (error) {
                console.error('Error during save:', error);
                Swal.fire({
                    title: 'Save Failed!',
                    text: 'An error occurred while saving the curriculum mapping: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#EF4444',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                return null;
            }
        };


        document.getElementById('declineProceedToPrerequisites').addEventListener('click', () => {
            document.getElementById('proceedToPrerequisitesModal').classList.add('hidden');
            document.getElementById('saveSuccessModal').classList.remove('hidden');
        });

        document.getElementById('confirmProceedToPrerequisites').addEventListener('click', () => {
            const curriculumId = curriculumSelector.value;
            window.location.href = `/pre_requisite?curriculumId=${curriculumId}`;
        });

        document.getElementById('closeSaveSuccessModal').addEventListener('click', () => {
            document.getElementById('saveSuccessModal').classList.add('hidden');
        });

        document.getElementById('closeMappingSuccessModal').addEventListener('click', () => {
            document.getElementById('mappingSuccessModal').classList.add('hidden');
            toggleEditMode(false); 
        });


        function filterSubjects() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = typeFilter.value;
            const subjectCards = availableSubjectsContainer.querySelectorAll('.subject-card');
            const geIdentifiers = ["ge", "general education", "gen ed"];

            subjectCards.forEach(card => {
                const subjectData = JSON.parse(card.dataset.subjectData);
                const subjectType = subjectData.subject_type.toLowerCase();
                
                const searchMatch = subjectData.subject_name.toLowerCase().includes(searchTerm) || subjectData.subject_code.toLowerCase().includes(searchTerm);
                
                let typeMatch = false;
                if (selectedType === 'All Types') {
                    typeMatch = true;
                } else if (selectedType === 'GE') {
                    typeMatch = geIdentifiers.some(id => subjectType.includes(id));
                } else {
                    typeMatch = (subjectType === selectedType.toLowerCase());
                }

                card.style.display = (searchMatch && typeMatch) ? 'flex' : 'none';
            });
        }
        searchInput.addEventListener('input', filterSubjects);
        typeFilter.addEventListener('change', filterSubjects);


function renderCurriculumOverview(yearLevel, semesterUnits = []) {
    let html = '';
    const isSeniorHigh = yearLevel === 'Senior High';
    const maxYear = isSeniorHigh ? 2 : 4;

    const getYearSuffix = (year) => {
        if (year === 1) return 'st';
        if (year === 2) return 'nd';
        if (year === 3) return 'rd';
        return 'th';
    };

    // Helper function to get semester unit limit
    const getSemesterLimit = (year, semester) => {
        const semesterIndex = (year - 1) * 2 + (semester - 1);
        return semesterUnits[semesterIndex] || 0;
    };

    // Helper function to format units without .0
    const formatUnits = (units) => {
        const num = parseFloat(units);
        return num % 1 === 0 ? Math.floor(num) : num;
    };

    for (let i = 1; i <= maxYear; i++) {
        const yearTitle = `${i}${getYearSuffix(i)} Year`;
        const firstSemLimit = getSemesterLimit(i, 1);
        const secondSemLimit = getSemesterLimit(i, 2);
        
        html += `
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">${yearTitle}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="semester-dropzone bg-white border-2 border-solid border-gray-300 rounded-lg p-4 transition-colors" data-year="${i}" data-semester="1" data-unit-limit="${firstSemLimit}">
                        <div class="border-b border-gray-200 pb-2 mb-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-600">First Semester</h4>
                                    ${firstSemLimit > 0 ? `<p class="text-xs text-blue-600 font-medium">Limit: ${formatUnits(firstSemLimit)} units</p>` : ''}
                                </div>
                                <div class="semester-unit-display">
                                    <div class="semester-unit-total text-sm font-bold text-gray-700">Units: 0</div>
                                    ${firstSemLimit > 0 ? `<div class="unit-limit-bar mt-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="unit-progress bg-blue-500 h-full transition-all duration-300" style="width: 0%"></div>
                                    </div>` : ''}
                                </div>
                            </div>
                            <div class="semester-type-totals mt-2 flex gap-x-3 gap-y-1 text-xs"></div>
                        </div>
                        <div class="flex-wrap space-y-2 min-h-[80px]"></div>
                         <div class="add-subject-btn-placeholder mt-2 text-center hidden">
                            <button class="add-subject-btn text-blue-600 hover:text-blue-800 font-semibold text-sm py-2 px-4 rounded-lg hover:bg-blue-100 transition-all">+ Add Subject</button>
                        </div>
                        <div class="add-all-btn-container mt-2 text-center hidden">
                            <button class="add-all-btn bg-green-500 hover:bg-green-600 text-white font-semibold text-sm py-2 px-4 rounded-lg transition-all">Add All</button>
                        </div>
                    </div>
                    <div class="semester-dropzone bg-white border-2 border-solid border-gray-300 rounded-lg p-4 transition-colors" data-year="${i}" data-semester="2" data-unit-limit="${secondSemLimit}">
                        <div class="border-b border-gray-200 pb-2 mb-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-600">Second Semester</h4>
                                    ${secondSemLimit > 0 ? `<p class="text-xs text-blue-600 font-medium">Limit: ${formatUnits(secondSemLimit)} units</p>` : ''}
                                </div>
                                <div class="semester-unit-display">
                                    <div class="semester-unit-total text-sm font-bold text-gray-700">Units: 0</div>
                                    ${secondSemLimit > 0 ? `<div class="unit-limit-bar mt-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="unit-progress bg-blue-500 h-full transition-all duration-300" style="width: 0%"></div>
                                    </div>` : ''}
                                </div>
                            </div>
                            <div class="semester-type-totals mt-2 flex gap-x-3 gap-y-1 text-xs"></div>
                        </div>
                        <div class="flex-wrap space-y-2 min-h-[80px]"></div>
                         <div class="add-subject-btn-placeholder mt-2 text-center hidden">
                            <button class="add-subject-btn text-blue-600 hover:text-blue-800 font-semibold text-sm py-2 px-4 rounded-lg hover:bg-blue-100 transition-all">+ Add Subject</button>
                        </div>
                        <div class="add-all-btn-container mt-2 text-center hidden">
                            <button class="add-all-btn bg-green-500 hover:bg-green-600 text-white font-semibold text-sm py-2 px-4 rounded-lg transition-all">Add All</button>
                        </div>
                    </div>
                </div>
            </div>`;
    }
            curriculumOverview.innerHTML = html;
             // Add event listeners for add subject buttons
            curriculumOverview.querySelectorAll('.add-subject-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const semesterDropzone = e.target.closest('.semester-dropzone');
                    toggleAddSubjectsMode(semesterDropzone);
                });
            });

            // Add event listeners for Add All buttons
            curriculumOverview.querySelectorAll('.add-all-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const semesterDropzone = e.target.closest('.semester-dropzone');
                    confirmAllPendingSubjects(semesterDropzone);
                });
            });

            // Add automatic checkbox functionality
            availableSubjectsContainer.addEventListener('change', (e) => {
                if (e.target.type === 'checkbox' && e.target.closest('.add-subject-checkbox') && isAddingSubjectsMode) {
                    const checkbox = e.target;
                    const subjectCard = checkbox.closest('.subject-card');
                    const subjectData = JSON.parse(subjectCard.dataset.subjectData);
                    const targetContainer = activeSemesterForAdding.querySelector('.flex-wrap');
                    
                    if (checkbox.checked) {
                        // Adding subject - check validation
                        const unitLimit = parseFloat(activeSemesterForAdding.dataset.unitLimit) || 0;
                        const isDuplicate = Array.from(targetContainer.querySelectorAll('.subject-tag')).some(tag => JSON.parse(tag.dataset.subjectData).subject_code === subjectData.subject_code);
                        
                        if (isDuplicate) {
                            checkbox.checked = false;
                            const year = activeSemesterForAdding.dataset.year;
                            const semester = activeSemesterForAdding.dataset.semester;
                            Swal.fire({
                                title: 'Duplicate Subject!',
                                text: `"${subjectData.subject_name}" is already assigned to Year ${year}, Semester ${semester}.`,
                                icon: 'warning',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#F59E0B'
                            });
                            return;
                        }
                        
                        if (unitLimit > 0) {
                            let currentTotal = 0;
                            // Count all subjects in the semester (both confirmed and pending)
                            targetContainer.querySelectorAll('.subject-tag').forEach(tag => {
                                const existingSubjectData = JSON.parse(tag.dataset.subjectData);
                                currentTotal += parseInt(existingSubjectData.subject_unit, 10) || 0;
                            });
                            
                            const newSubjectUnits = parseInt(subjectData.subject_unit, 10) || 0;
                            const wouldExceedLimit = (currentTotal + newSubjectUnits) > unitLimit;
                            
                            if (wouldExceedLimit) {
                                checkbox.checked = false;
                                const year = activeSemesterForAdding.dataset.year;
                                const semester = activeSemesterForAdding.dataset.semester;
                                const formatUnits = (units) => {
                                    const num = parseFloat(units);
                                    return num % 1 === 0 ? Math.floor(num) : num;
                                };
                                
                                Swal.fire({
                                    title: 'Unit Limit Exceeded!',
                                    html: `
                                        <div class="text-left">
                                            <p class="mb-2">Cannot add <strong>"${subjectData.subject_name}"</strong> (${formatUnits(newSubjectUnits)} units) to Year ${year}, Semester ${semester}.</p>
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                                <p class="text-sm text-red-700">
                                                    <strong>Current:</strong> ${formatUnits(currentTotal)} units<br>
                                                    <strong>Adding:</strong> ${formatUnits(newSubjectUnits)} units<br>
                                                    <strong>Total would be:</strong> ${formatUnits(currentTotal + newSubjectUnits)} units<br>
                                                    <strong>Semester limit:</strong> ${formatUnits(unitLimit)} units
                                                </p>
                                            </div>
                                        </div>
                                    `,
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#EF4444'
                                });
                                return;
                            }
                        }
                        
                        // Add subject as pending (blurry) initially
                        const subjectTag = createSubjectTag(subjectData, true);
                        subjectTag.dataset.isNew = 'true';
                        subjectTag.dataset.isPending = 'true';
                        subjectTag.classList.add('opacity-50', 'pending-subject');
                        targetContainer.appendChild(subjectTag);
                        
                        // Update subject card appearance to show it's selected (pending)
                        const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
                        let pendingClass = 'assigned-card';
                        let iconBgClass = 'icon-bg-default';
                        let iconSvgClass = 'text-gray-500';

                        switch (true) {
                            case subjectData.subject_type === 'Major':
                                pendingClass = 'assigned-major';
                                iconBgClass = 'icon-bg-major';
                                iconSvgClass = 'icon-major';
                                break;
                            case subjectData.subject_type === 'Minor':
                                pendingClass = 'assigned-minor';
                                iconBgClass = 'icon-bg-minor';
                                iconSvgClass = 'icon-minor';
                                break;
                            case subjectData.subject_type === 'Elective':
                                pendingClass = 'assigned-elective';
                                iconBgClass = 'icon-bg-elective';
                                iconSvgClass = 'icon-elective';
                                break;
                            case geIdentifiers.map(id => id.toLowerCase()).includes(subjectData.subject_type.toLowerCase()):
                                pendingClass = 'assigned-general';
                                iconBgClass = 'icon-bg-general';
                                iconSvgClass = 'icon-general';
                                break;
                        }
                        
                        // Apply pending styling to subject card
                        subjectCard.classList.remove('bg-white', 'hover:shadow-md', 'hover:border-blue-400', 'cursor-grab');
                        subjectCard.classList.add(pendingClass, 'opacity-70', 'cursor-not-allowed');
                        subjectCard.setAttribute('draggable', false);
                        
                        // Update icon styling
                        const iconContainer = subjectCard.querySelector('.flex-shrink-0');
                        const iconSvg = iconContainer?.querySelector('svg');
                        if (iconContainer && iconSvg) {
                            // Remove old classes
                            iconContainer.classList.remove('icon-bg-default', 'bg-gray-100');
                            iconSvg.classList.remove('text-gray-500');
                            // Add new classes
                            iconContainer.classList.add(iconBgClass);
                            iconSvg.classList.add(iconSvgClass);
                        }
                        
                        // Update status badge
                        const statusBadge = subjectCard.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.textContent = 'Pending';
                            statusBadge.className = 'status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700';
                        }
                        
                        // Show the "Add All" button for this semester if there are pending subjects
                        showAddAllButton(activeSemesterForAdding);
                        
                        // Update unit totals to include pending subjects
                        updateUnitTotals();
                    } else {
                        // Removing subject - find and remove from semester
                        const subjectTagToRemove = Array.from(targetContainer.querySelectorAll('.subject-tag')).find(tag => {
                            const tagData = JSON.parse(tag.dataset.subjectData);
                            return tagData.subject_code === subjectData.subject_code;
                        });
                        
                        if (subjectTagToRemove) {
                            subjectTagToRemove.remove();
                            
                            // Reset subject card to normal appearance
                            subjectCard.classList.remove('assigned-card', 'assigned-major', 'assigned-minor', 'assigned-elective', 'assigned-general', 'cursor-not-allowed');
                            subjectCard.classList.add('bg-white', 'hover:shadow-md', 'hover:border-blue-400', 'cursor-grab');
                            subjectCard.setAttribute('draggable', true);
                            
                            // Reset icon background and color to original subject type
                            const iconContainer = subjectCard.querySelector('.flex-shrink-0');
                            const iconSvg = iconContainer?.querySelector('svg');
                            const subjectType = subjectData.subject_type.toLowerCase();
                            
                            // Restore original icon styling based on subject type
                            if (subjectType.includes('major')) {
                                if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                                if (iconSvg) iconSvg.className = 'h-6 w-6 text-blue-600 transition-colors duration-300';
                            } else if (subjectType.includes('minor')) {
                                if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                                if (iconSvg) iconSvg.className = 'h-6 w-6 text-purple-600 transition-colors duration-300';
                            } else if (subjectType.includes('elective')) {
                                if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                                if (iconSvg) iconSvg.className = 'h-6 w-6 text-red-600 transition-colors duration-300';
                            } else {
                                if (iconContainer) iconContainer.className = 'flex-shrink-0 w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center transition-colors duration-300';
                                if (iconSvg) iconSvg.className = 'h-6 w-6 text-orange-600 transition-colors duration-300';
                            }
                            
                            // Reset status badge
                            subjectCard.querySelector('.status-badge').textContent = 'Available';
                            subjectCard.querySelector('.status-badge').className = 'status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700';
                            
                            updateUnitTotals();
                            
                            // Check if we need to hide Add All button
                            if (activeSemesterForAdding) {
                                showAddAllButton(activeSemesterForAdding);
                            }
                        }
                    }
                }
            });
            
            // Function to show/hide Add All button based on pending subjects
            window.showAddAllButton = (semesterDropzone) => {
                const pendingSubjects = semesterDropzone.querySelectorAll('.subject-tag[data-is-pending="true"]');
                const addAllContainer = semesterDropzone.querySelector('.add-all-btn-container');
                
                if (pendingSubjects.length > 0) {
                    addAllContainer.classList.remove('hidden');
                } else {
                    addAllContainer.classList.add('hidden');
                }
            };
            
            // Function to confirm all pending subjects in a semester
            window.confirmAllPendingSubjects = (semesterDropzone) => {
                const pendingSubjects = semesterDropzone.querySelectorAll('.subject-tag[data-is-pending="true"]');
                
                pendingSubjects.forEach(subjectTag => {
                    // Make subject solid and remove pending state
                    subjectTag.classList.remove('opacity-50', 'pending-subject');
                    subjectTag.dataset.isPending = 'false';
                    
                    // Update corresponding subject card appearance
                    const subjectData = JSON.parse(subjectTag.dataset.subjectData);
                    const subjectCard = document.getElementById(`subject-${subjectData.subject_code.toLowerCase()}`);
                    
                    if (subjectCard) {
                        // Apply proper styling based on subject type
                        const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
                        let assignedClass = 'assigned-card';
                        let iconBgClass = 'icon-bg-default';
                        let iconSvgClass = 'text-gray-500';

                        switch (true) {
                            case subjectData.subject_type === 'Major':
                                assignedClass = 'assigned-major';
                                iconBgClass = 'icon-bg-major';
                                iconSvgClass = 'icon-major';
                                break;
                            case subjectData.subject_type === 'Minor':
                                assignedClass = 'assigned-minor';
                                iconBgClass = 'icon-bg-minor';
                                iconSvgClass = 'icon-minor';
                                break;
                            case subjectData.subject_type === 'Elective':
                                assignedClass = 'assigned-elective';
                                iconBgClass = 'icon-bg-elective';
                                iconSvgClass = 'icon-elective';
                                break;
                            case geIdentifiers.map(id => id.toLowerCase()).includes(subjectData.subject_type.toLowerCase()):
                                assignedClass = 'assigned-general';
                                iconBgClass = 'icon-bg-general';
                                iconSvgClass = 'icon-general';
                                break;
                        }
                        
                        subjectCard.classList.remove('opacity-70'); // Remove pending opacity
                        subjectCard.classList.add(assignedClass, 'cursor-not-allowed');
                        subjectCard.setAttribute('draggable', false);
                        
                        // Update icon styling
                        const iconContainer = subjectCard.querySelector('.flex-shrink-0');
                        const iconSvg = iconContainer?.querySelector('svg');
                        if (iconContainer && iconSvg) {
                            // Remove old classes
                            iconContainer.classList.remove('icon-bg-default', 'icon-bg-major', 'icon-bg-minor', 'icon-bg-elective', 'icon-bg-general');
                            iconSvg.classList.remove('text-gray-500', 'icon-major', 'icon-minor', 'icon-elective', 'icon-general');
                            // Add new classes
                            iconContainer.classList.add(iconBgClass);
                            iconSvg.classList.add(iconSvgClass);
                        }
                        
                        subjectCard.querySelector('.status-badge').textContent = 'Assigned';
                        subjectCard.querySelector('.status-badge').className = 'status-badge text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-200 text-gray-700';
                        
                        // Hide and uncheck the checkbox
                        const checkboxDiv = subjectCard.querySelector('.add-subject-checkbox');
                        const checkbox = subjectCard.querySelector('.add-subject-checkbox input');
                        if (checkboxDiv && checkbox) {
                            checkboxDiv.classList.add('hidden');
                            checkbox.checked = false;
                        }
                    }
                });
                
                // Hide the Add All button
                const addAllContainer = semesterDropzone.querySelector('.add-all-btn-container');
                addAllContainer.classList.add('hidden');
                
                // Exit adding subjects mode to hide all remaining checkboxes
                toggleAddSubjectsMode(null);
                
                // Update unit totals now that subjects are confirmed
                updateUnitTotals();
            };
            
            initDragAndDrop();
        }

        function renderAvailableSubjects(subjects, mappedSubjects = []) {
            availableSubjectsContainer.innerHTML = '';
            const mappedSubjectCodes = new Set(mappedSubjects.map(s => s.subject_code));

            if (subjects.length === 0) {
                availableSubjectsContainer.innerHTML = '<p class="text-gray-500 text-center mt-4">No available subjects found.</p>';
            } else {
                subjects.forEach(subject => {
                    const isMapped = mappedSubjectCodes.has(subject.subject_code);
                    
                    const newSubjectCard = createSubjectCard(subject, isMapped);
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
                        option.dataset.semesterUnits = JSON.stringify(curriculum.semester_units || []);
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

        // Load all subjects initially
        function loadAllSubjects() {
            fetch('/api/subjects')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (!data || !Array.isArray(data)) throw new Error('Invalid subjects data from server.');
                    
                    // Show all subjects as available (none are mapped yet)
                    renderAvailableSubjects(data, []);
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                    availableSubjectsContainer.innerHTML = '<p class="text-red-500 text-center mt-4">Error loading subjects. Please refresh the page.</p>';
                });
        }

        function fetchCurriculumData(id) {
            const selectedOption = curriculumSelector.querySelector(`option[value="${id}"]`);
            if (!selectedOption || !selectedOption.dataset.yearLevel) {
                curriculumOverview.innerHTML = '<p class="text-red-500 text-center mt-4">Could not determine year level. Please reload.</p>';
                return;
            }

            const yearLevel = selectedOption.dataset.yearLevel;
            const semesterUnits = JSON.parse(selectedOption.dataset.semesterUnits || '[]');
            renderCurriculumOverview(yearLevel, semesterUnits);

            fetch(`/api/curriculums/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (!data || !data.curriculum || !data.allSubjects) throw new Error('Invalid data structure from server.');
                    
                    renderAvailableSubjects(data.allSubjects, data.curriculum.subjects);
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
                    Swal.fire({
                        title: 'Failed to Load Curriculum!',
                        text: 'Could not load curriculum data from the server. Please check your connection and try again.',
                        icon: 'error',
                        confirmButtonText: 'Retry',
                        confirmButtonColor: '#EF4444',
                        showClass: {
                            popup: 'animate__animated animate__shakeX'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                    availableSubjectsContainer.innerHTML = '<p class="text-red-500 text-center mt-4">Could not load subjects.</p>';
                });
        }
        
        document.getElementById('editCurriculumButton').addEventListener('click', (e) => {
            if (isEditing) {
                toggleEditMode(false);
            } else {
                editConfirmationModal.classList.remove('hidden');
            }
        });

        document.getElementById('cancelEditBtn').addEventListener('click', () => {
            editConfirmationModal.classList.add('hidden');
        });

        document.getElementById('confirmEditBtn').addEventListener('click', () => {
            editConfirmationModal.classList.add('hidden');
            toggleEditMode(true);
        });

        const reassignModal = document.getElementById('reassignConfirmationModal');
        const confirmReassignBtn = document.getElementById('confirmReassignBtn');
        const cancelReassignBtn = document.getElementById('cancelReassignBtn');

        confirmReassignBtn.addEventListener('click', () => {
            if (!itemToReassign || !reassignTargetContainer) return;

            const droppedSubjectData = JSON.parse(itemToReassign.dataset.subjectData);
            
            itemToReassign.parentNode.removeChild(itemToReassign);
            const subjectTag = createSubjectTag(droppedSubjectData, isEditing);
            reassignTargetContainer.appendChild(subjectTag);
            updateUnitTotals();
            
            reassignModal.classList.add('hidden');
            
            // Use SweetAlert for reassign success
            Swal.fire({
                title: 'Subject Reassigned Successfully!',
                text: `"${droppedSubjectData.subject_name}" (${droppedSubjectData.subject_code}) has been successfully moved to the new semester.`,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10B981',
                timer: 4000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });

            itemToReassign = null;
            reassignTargetContainer = null;
        });

        cancelReassignBtn.addEventListener('click', () => {
            reassignModal.classList.add('hidden');
            itemToReassign = null;
            reassignTargetContainer = null;
        });


        document.getElementById('closeReassignSuccessBtn').addEventListener('click', () => {
            document.getElementById('reassignSuccessModal').classList.add('hidden');
        });



        curriculumSelector.addEventListener('change', (e) => {
            const curriculumId = e.target.value;
            if (curriculumId) {
                fetchCurriculumData(curriculumId);
            } else {
                curriculumOverview.innerHTML = '<p class="text-gray-500 text-center mt-4">Select a curriculum from the dropdown to start mapping subjects.</p>';
                // Keep showing all subjects even when no curriculum is selected
                loadAllSubjects();
                updateUnitTotals();
                document.getElementById('editCurriculumButton').classList.add('hidden');
                toggleEditMode(false);
            }
        });
        
        // Load all subjects initially when page loads
        loadAllSubjects();
        fetchCurriculums();
    });
</script>
@endsection