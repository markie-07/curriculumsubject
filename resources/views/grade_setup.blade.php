@extends('layouts.app')

@section('content')
<style>
    .progress-ring__circle { transition: stroke-dashoffset 0.35s; transform: rotate(-90deg); transform-origin: 50% 50%; }
    .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    .component-row input { background-color: transparent; }
    .grade-history-card { cursor: pointer; }
    #grade-modal.opacity-0 { opacity: 0; }
    #grade-modal-panel.opacity-0 { opacity: 0; }
    #grade-modal-panel.scale-95 { transform: scale(0.95); }
</style>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 sm:p-6 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Grade Scheme Setup Form --}}
        <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl p-6 md:p-8 rounded-2xl shadow-lg border border-gray-200/80">
            <form id="grade-setup-form" onsubmit="return false;">
                @csrf
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Curriculum Grade Scheme Setup</h1>
                    <p class="text-sm text-gray-600 mt-1">Design and manage grading schemes for entire curriculums with automatic minor course grading.</p>
                </div>

                {{-- Course Type Selection --}}
                <div id="course-type-section" class="border border-gray-200 bg-gray-50/50 p-6 rounded-xl">
                    <div class="flex items-center gap-3 pb-3 mb-4">
                        <div class="w-10 h-10 flex-shrink-0 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-700">Course Type</h2>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <button id="minor-courses-btn" type="button" class="p-4 border-2 border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Minor Courses</h3>
                                    <p class="text-sm text-gray-600">Auto-assign default grades</p>
                                </div>
                            </div>
                        </button>
                        <button id="major-courses-btn" type="button" class="p-4 border-2 border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Major Courses</h3>
                                    <p class="text-sm text-gray-600">Manual grade setup</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Subject Selection for Major Courses --}}
                <div id="major-subject-section" class="mt-8 border border-gray-200 bg-gray-50/50 p-6 rounded-xl hidden">
                    <div class="flex items-center gap-3 pb-3 mb-4">
                        <div class="w-10 h-10 flex-shrink-0 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-700">Select Major Subject</h2>
                    </div>
                    <div>
                        <label for="major-subject-select" class="block text-sm font-medium text-gray-600 mb-1">Major Subject</label>
                        
                        {{-- Custom Dropdown --}}
                        <div class="relative">
                            <button type="button" id="major-subject-dropdown-btn" class="w-full py-3 pl-4 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm transition-colors text-left flex items-center justify-between">
                                <span id="major-subject-selected-text" class="text-gray-500">Select a major subject...</span>
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            {{-- Dropdown Menu --}}
                            <div id="major-subject-dropdown-menu" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <div class="py-1">
                                    <!-- Options will be populated here -->
                                </div>
                            </div>
                        </div>
                        
                        {{-- Hidden select for form compatibility --}}
                        <select id="major-subject-select" class="hidden">
                            <option value="">Select a major subject...</option>
                        </select>
                    </div>
                </div>

                {{-- Grade Components --}}
                <div class="mt-8">
                    <div class="flex items-center justify-between gap-3 pb-3 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex-shrink-0 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M12 21a9 9 0 110-18 9 9 0 010 18z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-700">Semestral Grade Components</h2>
                                <p class="text-sm text-amber-600 font-medium mt-1 curriculum-reminder-text">⚠️ Please select a curriculum first to set up grades</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="update-minor-grades-btn" type="button" class="hidden flex items-center gap-2 text-sm font-semibold text-orange-600 hover:text-orange-800 transition-colors py-2 px-3 rounded-lg hover:bg-orange-50 border border-orange-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Update Minor Grades
                            </button>
                            <button id="add-grade-component-btn" type="button" class="flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors py-2 px-3 rounded-lg hover:bg-indigo-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                Add Grade Component
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4" id="semestral-grade-accordion">
                        {{-- Grade components will be dynamically inserted here --}}
                    </div>

                    <div class="mt-8 flex justify-center items-center p-4 bg-gray-100 rounded-lg border border-gray-200">
                        <div class="relative w-24 h-24">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <circle class="text-gray-200" stroke-width="10" stroke="currentColor" fill="transparent" r="45" cx="50" cy="50" />
                                <circle id="progress-circle" class="progress-ring__circle text-indigo-500" stroke-width="10" stroke-linecap="round" stroke="currentColor" fill="transparent" r="45" cx="50" cy="50" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span id="total-weight" class="text-xl font-bold text-gray-700">0%</span>
                            </div>
                        </div>
                        <p class="ml-4 font-semibold text-gray-600">Total Weight</p>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200">
                    <button id="setGradeSchemeButton" type="button" class="w-full flex items-center justify-center gap-2 bg-white hover:bg-gray-100 text-black font-bold py-3 px-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 disabled:opacity-50 disabled:cursor-not-allowed border border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6a1 1 0 10-2 0v5.586L7.707 10.293zM10 18a8 8 0 100-16 8 8 0 000 16z" /></svg>
                        Set Grade Scheme
                    </button>
                    <button id="update-grade-setup-btn" class="w-full flex items-center justify-center gap-2 bg-white hover:bg-gray-100 text-black font-bold py-3 px-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hidden border border-gray-300">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                        Update Grade Scheme
                    </button>
                </div>
            </form>
        </div>

        {{-- Grade History --}}
        <div class="lg:col-span-1 bg-white/70 backdrop-blur-xl p-6 md:p-8 rounded-2xl shadow-lg border border-gray-200/80">
            <h2 class="text-xl font-bold text-gray-700 mb-4 pb-3 border-b">Curriculum Grade History</h2>
            
            {{-- View Mode Toggle --}}
            <div class="mb-4 flex gap-2 bg-gray-100 p-1 rounded-lg">
                <button 
                    id="view-curriculum-btn" 
                    class="view-mode-btn flex-1 px-4 py-2 text-sm font-semibold rounded-md transition-colors bg-white text-indigo-600 shadow-sm"
                    data-view="curriculum"
                >
                    📚 Curriculums
                </button>
                <button 
                    id="view-subject-btn" 
                    class="view-mode-btn flex-1 px-4 py-2 text-sm font-semibold rounded-md transition-colors text-gray-600 hover:text-gray-800"
                    data-view="subject"
                >
                    📖 All Subjects
                </button>
            </div>
            
            {{-- Search Bar --}}
            <div class="mb-4">
                <div class="relative">
                    <input 
                        type="text" 
                        id="curriculum-search" 
                        placeholder="Search..." 
                        class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            {{-- Type Filter Buttons (for curriculum view) --}}
            <div id="curriculum-type-filters" class="mb-4 flex gap-2">
                <button 
                    id="filter-all-btn" 
                    class="curriculum-filter-btn flex-1 px-3 py-2 text-xs font-medium rounded-lg transition-colors bg-indigo-600 text-white"
                    data-filter="all"
                >
                    All
                </button>
                <button 
                    id="filter-college-btn" 
                    class="curriculum-filter-btn flex-1 px-3 py-2 text-xs font-medium rounded-lg transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                    data-filter="college"
                >
                    College
                </button>
                <button 
                    id="filter-seniorhigh-btn" 
                    class="curriculum-filter-btn flex-1 px-3 py-2 text-xs font-medium rounded-lg transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                    data-filter="seniorhigh"
                >
                    Senior High
                </button>
            </div>
            
            {{-- Subject Type Filter Buttons (for subject view) --}}
            <div id="subject-type-filters" class="mb-4 flex gap-2 hidden">
                <button 
                    id="subject-filter-all-btn" 
                    class="subject-filter-btn flex-1 px-3 py-2 text-xs font-medium rounded-lg transition-colors bg-indigo-600 text-white"
                    data-filter="all"
                >
                    All
                </button>
                <button 
                    id="subject-filter-major-btn" 
                    class="subject-filter-btn flex-1 px-3 py-2 text-xs font-medium rounded-lg transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                    data-filter="major"
                >
                    Major
                </button>
                <button 
                    id="subject-filter-minor-btn" 
                    class="subject-filter-btn flex-1 px-3 py-2 text-xs font-medium rounded-lg transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                    data-filter="minor"
                >
                    Minor
                </button>
            </div>
            
            <div id="grade-history-container" class="space-y-4 max-h-[600px] overflow-y-auto">
                <p id="no-history-message" class="text-gray-500">No curriculums with grade schemes yet.</p>
            </div>
        </div>
    </div>
</main>

{{-- Save Grade Scheme Confirmation Modal --}}
<div id="saveGradeSchemeModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Confirm Grade Scheme</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to save this grade scheme for the selected subject?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelSaveGradeScheme" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">No</button>
                <button id="confirmSaveGradeScheme" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- Grade Scheme Success Modal --}}
<div id="gradeSchemeSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Successfully Added!</h3>
            <p class="text-sm text-gray-500 mt-2">Your grade scheme has been saved successfully!</p>
            <div class="mt-6">
                <button id="closeGradeSchemeSuccess" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Update Grade Scheme Confirmation Modal --}}
<div id="updateGradeSchemeModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-orange-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Confirm Grade Update</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to update the grade of this subject?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelUpdateGradeScheme" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">No</button>
                <button id="confirmUpdateGradeScheme" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- Grade Scheme Update Success Modal --}}
<div id="gradeSchemeUpdateSuccessModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Successfully Updated!</h3>
            <p class="text-sm text-gray-500 mt-2">You successfully updated the grade of this subject!</p>
            <div class="mt-6">
                <button id="closeGradeSchemeUpdateSuccess" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Update Minor Grades Confirmation Modal --}}
<div id="updateMinorGradesModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-orange-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Update Minor Grades</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to update the grade for minor subjects? This will unlock the grade components for editing.</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelUpdateMinorGrades" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">No</button>
                <button id="confirmUpdateMinorGrades" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- Update Grade Scheme Confirmation Modal --}}
<div id="editGradeSchemeModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-yellow-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Update Grade Subject</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to update this grade subject?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelEditGradeScheme" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">No</button>
                <button id="confirmEditGradeScheme" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- Grade Details Modal --}}
<div id="grade-modal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl" id="grade-modal-panel">
        {{-- Modal Header --}}
        <div class="flex justify-between items-center p-5 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex-shrink-0 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M12 21a9 9 0 110-18 9 9 0 010 18z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Grade Component Details</h3>
            </div>
            <button id="close-modal-btn" class="text-gray-400 hover:text-gray-700 transition-colors rounded-full p-1 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Modal Content --}}
        <div id="modal-content" class="p-6 max-h-[70vh] overflow-y-auto bg-gray-50">
            {{-- Grade version dropdowns will be loaded here --}}
        </div>

        {{-- Modal Footer --}}
        <div class="flex justify-end p-5 bg-white border-t border-gray-200 rounded-b-2xl">
            <button id="edit-grade-setup-btn" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold py-2 px-5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Update
            </button>
        </div>
        </div>
    </div>
</div>

{{-- Curriculum Grade Details Modal --}}
<div id="curriculum-grade-modal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-500 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white w-full max-w-6xl rounded-2xl shadow-2xl" id="curriculum-grade-modal-panel">
            {{-- Modal Header --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex-shrink-0 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800" id="curriculum-modal-title">Curriculum Grade Schemes</h3>
                            <p class="text-sm text-gray-600" id="curriculum-modal-subtitle">View all subjects and their grade configurations</p>
                        </div>
                    </div>
                    <button id="close-curriculum-modal-btn" class="text-gray-400 hover:text-gray-700 transition-colors rounded-full p-1 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                {{-- Search Bar --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="subject-search-input" placeholder="Search subjects by name or code..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Minor Subjects --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800">Minor Subjects</h4>
                            <span class="text-sm text-gray-500">(Auto-assigned grades)</span>
                        </div>
                        <div id="minor-subjects-container" class="space-y-3">
                            <p class="text-gray-500 text-sm">No minor subjects found.</p>
                        </div>
                    </div>

                    {{-- Major Subjects --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800">Major Subjects</h4>
                            <span class="text-sm text-gray-500">(Custom grades)</span>
                        </div>
                        <div id="major-subjects-container" class="space-y-3">
                            <p class="text-gray-500 text-sm">No major subjects found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    let curriculums = [];
    let currentCurriculumSubjects = [];
    // Default structure for initial grade components setup
    let minorGradesUnlocked = false;

    // Main form elements
    const accordionContainer = document.getElementById('semestral-grade-accordion');
    const totalWeightSpan = document.getElementById('total-weight');
    const progressCircle = document.getElementById('progress-circle');
    const addGradeBtn = document.getElementById('setGradeSchemeButton');
    const updateGradeSetupBtn = document.getElementById('update-grade-setup-btn');
    const curriculumSelect = document.getElementById('curriculum-select');
    const courseTypeSection = document.getElementById('course-type-section');
    const majorSubjectSection = document.getElementById('major-subject-section');
    const majorSubjectSelect = document.getElementById('major-subject-select');
    const gradeHistoryContainer = document.getElementById('grade-history-container');
    const addGradeComponentBtn = document.getElementById('add-grade-component-btn');
    const updateMinorGradesBtn = document.getElementById('update-minor-grades-btn');
    const minorCoursesBtn = document.getElementById('minor-courses-btn');
    const majorCoursesBtn = document.getElementById('major-courses-btn');

    // Modal elements
    const gradeModal = document.getElementById('grade-modal');
    const curriculumGradeModal = document.getElementById('curriculum-grade-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const closeCurriculumModalBtn = document.getElementById('close-curriculum-modal-btn');
    const modalContent = document.getElementById('modal-content');
    const editGradeSetupBtn = document.getElementById('edit-grade-setup-btn');
    
    // State
    let isEditMode = false;
    let currentCurriculumId = null;
    let currentSubjectId = null;
    let currentCourseType = null; // 'minor' or 'major'
    let componentCounter = 0;

    const createGradeComponent = (period = `component${++componentCounter}`, weight = 0, components = []) => {
        const componentContainer = document.createElement('div');
        componentContainer.className = 'period-container border border-gray-200/80 bg-white rounded-xl shadow-sm overflow-hidden';
        componentContainer.dataset.period = period;
        
        componentContainer.innerHTML = `
            <div class="accordion-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                <div class="flex items-center gap-4">
                    <input type="text" value="${period.startsWith('component') ? '' : period}" placeholder="Component Name (e.g., Midterm)" class="component-name-input font-semibold text-lg text-gray-700 capitalize border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="flex items-center">
                        <input type="number" value="${weight}" class="semestral-input w-20 text-center font-bold border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="ml-2 text-lg text-gray-600">%</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Sub-total: <span class="sub-total font-bold text-gray-700">100%</span></span>
                     <button type="button" class="remove-component-btn flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full transition-colors" title="Remove Component">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                    </button>
                    <svg class="w-6 h-6 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            <div class="accordion-content bg-gray-50/50 border-t border-gray-200/80">
                <div class="p-4">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr>
                                <th class="p-2 text-left font-semibold text-gray-600">Sub-Component</th>
                                <th class="p-2 text-center font-semibold text-gray-600 w-28">Weight (%)</th>
                                <th class="p-2 text-center font-semibold text-gray-600 w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="component-tbody"></tbody>
                    </table>
                    <div class="mt-4 flex justify-end">
                        <button type="button" class="add-component-btn inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors py-2 px-3 rounded-lg hover:bg-indigo-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            Add Main Component
                        </button>
                    </div>
                </div>
            </div>`;
            
        const tbody = componentContainer.querySelector('.component-tbody');
        components.forEach(comp => {
            const mainRow = createRow(false, period, comp);
            tbody.appendChild(mainRow);
            (comp.sub_components || []).forEach(sub => {
                const subRow = createRow(true, period, sub);
                tbody.appendChild(subRow);
            });
        });
        
        return componentContainer;
    };

    addGradeComponentBtn.addEventListener('click', () => {
        // Check if course type is selected
        if (!currentCourseType) {
            Swal.fire({
                title: 'Select Course Type First',
                text: 'Please select a course type (Minor or Major) before adding grade components.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        
        // For major courses, check if a subject is selected
        if (currentCourseType === 'major' && !currentSubjectId) {
            Swal.fire({
                title: 'Select a Major Subject First',
                text: 'Please select a major subject before adding grade components.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        
        // For minor courses, check if curriculum is selected (since minor courses work with curriculum-based workflow)
        if (currentCourseType === 'minor' && !currentCurriculumId) {
            Swal.fire({
                title: 'Select a Curriculum First',
                text: 'Please select a curriculum before adding grade components for minor courses.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        
        // Add the grade component
        accordionContainer.appendChild(createGradeComponent());
        calculateAndUpdateTotals();
    });

    const createRow = (isSub, period, component = { name: '', weight: 0 }) => {
        const tr = document.createElement('tr');
        tr.className = `component-row ${isSub ? 'sub-component-row border-l-4 border-gray-200' : 'main-component-row'} hover:bg-gray-50`;
        const namePlaceholder = isSub ? "Sub-component Name" : "Main Component Name";
        const inputClass = isSub ? 'sub-input' : 'main-input';
        
        tr.innerHTML = `
            <td class="p-2 ${isSub ? 'pl-6' : 'pl-4'} align-middle">
                <input type="text" placeholder="${namePlaceholder}" value="${component.name}" class="component-name-input w-full border-0 focus:ring-0 p-1 rounded bg-transparent">
            </td>
            <td class="p-2 w-28 align-middle">
                <input type="number" value="${component.weight}" class="${inputClass} w-full text-center font-semibold border-gray-300 rounded-lg p-2 shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
            </td>
            <td class="p-2 w-28 text-center align-middle">
                <div class="flex items-center justify-center gap-1">
                    ${!isSub ? `<button type="button" class="add-sub-btn flex items-center justify-center w-8 h-8 text-gray-400 hover:text-blue-600 hover:bg-blue-100 rounded-full transition-colors" title="Add Sub-component"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg></button>` : '<span class="w-8 h-8"></span>'}
                    <button type="button" class="remove-row-btn flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full transition-colors" title="Remove Row"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg></button>
                </div>
            </td>
        `;
        return tr;
    };

    const resizeOpenAccordion = (element) => {
        const content = element.closest('.accordion-content');
        if (content && content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    };

    const handleDynamicEvents = (e) => {
        const target = e.target;
        if (target.closest('.add-component-btn')) {
            const tbody = target.closest('.accordion-content').querySelector('.component-tbody');
            tbody.appendChild(createRow(false, tbody.closest('.period-container').dataset.period));
            resizeOpenAccordion(tbody);
        } else if (target.closest('.add-sub-btn')) {
            const parentRow = target.closest('tr');
            const newSubRow = createRow(true, parentRow.closest('.period-container').dataset.period);
            parentRow.insertAdjacentElement('afterend', newSubRow);
            resizeOpenAccordion(parentRow);
        } else if (target.closest('.remove-row-btn')) {
            const rowToRemove = target.closest('tr');
            const accordionContent = rowToRemove.closest('.accordion-content');
            if (rowToRemove.classList.contains('main-component-row')) {
                let nextRow = rowToRemove.nextElementSibling;
                while (nextRow && nextRow.classList.contains('sub-component-row')) {
                    const toRemove = nextRow;
                    nextRow = nextRow.nextElementSibling;
                    toRemove.remove();
                }
            }
            rowToRemove.remove();
            if (accordionContent && accordionContent.style.maxHeight && accordionContent.style.maxHeight !== '0px') {
                accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
            }
        } else if (target.closest('.remove-component-btn')) {
            target.closest('.period-container').remove();
        }
        calculateAndUpdateTotals();
    };
    
    const calculateAndUpdateTotals = () => {
        let semestralTotal = 0;
        document.querySelectorAll('.semestral-input').forEach(input => semestralTotal += Number(input.value) || 0);
        
        totalWeightSpan.textContent = `${semestralTotal}%`;
        const radius = progressCircle.r.baseVal.value;
        const circumference = 2 * Math.PI * radius;
        progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
        const offset = circumference - (Math.min(semestralTotal, 100) / 100) * circumference;
        progressCircle.style.strokeDashoffset = offset;
        progressCircle.classList.toggle('text-red-500', semestralTotal !== 100);
        progressCircle.classList.toggle('text-indigo-500', semestralTotal === 100);

        let allSubTotalsCorrect = true;
        document.querySelectorAll('.period-container').forEach(container => {
            let periodSubTotal = 0;
            container.querySelectorAll('.main-input').forEach(input => periodSubTotal += Number(input.value) || 0);

            const subTotalSpan = container.querySelector('.sub-total');
            subTotalSpan.textContent = `${periodSubTotal}%`;
            subTotalSpan.classList.toggle('text-red-500', periodSubTotal !== 100);
            subTotalSpan.classList.toggle('text-gray-700', periodSubTotal === 100);
            if (periodSubTotal !== 100) allSubTotalsCorrect = false;

            container.querySelectorAll('.main-component-row').forEach(mainRow => {
                let subComponentTotal = 0;
                let nextRow = mainRow.nextElementSibling;
                let hasSubComponents = false;
                while (nextRow && nextRow.classList.contains('sub-component-row')) {
                    hasSubComponents = true;
                    subComponentTotal += Number(nextRow.querySelector('.sub-input').value) || 0;
                    nextRow = nextRow.nextElementSibling;
                }
                
                if (hasSubComponents && subComponentTotal !== 100) {
                    mainRow.classList.add('bg-red-100');
                    allSubTotalsCorrect = false;
                } else {
                    mainRow.classList.remove('bg-red-100');
                }
            });
        });
        
        // Check curriculum-based validation
        const hasValidSelection = currentCourseType && 
            (currentCourseType === 'minor' || (currentCourseType === 'major' && currentSubjectId));
        
        // For minor courses, also check if grades are unlocked
        const minorGradesReady = currentCourseType !== 'minor' || minorGradesUnlocked;
        
        // Enable Set Grade Scheme button when totals are correct, valid selection exists, and minor grades are ready
        const saveButtonIsDisabled = semestralTotal !== 100 || !allSubTotalsCorrect || !hasValidSelection || !minorGradesReady;
        addGradeBtn.disabled = saveButtonIsDisabled;
        updateGradeSetupBtn.disabled = saveButtonIsDisabled;
    };

    const getGradeDataFromDOM = () => {
        const data = {};
        document.querySelectorAll('.period-container').forEach(container => {
            const periodName = container.querySelector('.component-name-input').value.trim() || container.dataset.period;
            data[periodName] = {
                weight: Number(container.querySelector('.semestral-input').value) || 0,
                components: []
            };
            container.querySelectorAll('.main-component-row').forEach(mainRow => {
                const mainComponent = {
                    name: mainRow.querySelector('.component-name-input').value,
                    weight: Number(mainRow.querySelector('.main-input').value) || 0,
                    sub_components: []
                };
                let nextRow = mainRow.nextElementSibling;
                while (nextRow && nextRow.classList.contains('sub-component-row')) {
                    mainComponent.sub_components.push({
                        name: nextRow.querySelector('.component-name-input').value,
                        weight: Number(nextRow.querySelector('.sub-input').value) || 0,
                    });
                    nextRow = nextRow.nextElementSibling;
                }
                data[periodName].components.push(mainComponent);
            });
        });
        return data;
    };

    const loadGradeDataToDOM = (componentsData) => {
        accordionContainer.innerHTML = ''; // Clear existing components
        const dataToLoad = componentsData && Object.keys(componentsData).length > 0 ? componentsData : {};
        
        Object.keys(dataToLoad).forEach(period => {
            const periodData = dataToLoad[period];
            const newComponent = createGradeComponent(period, periodData.weight, periodData.components);
            accordionContainer.appendChild(newComponent);
        });
        calculateAndUpdateTotals();
    };

    const toggleGradeComponents = (disabled) => {
        // Disable input fields and action buttons
        document.querySelectorAll('.semestral-input, .main-input, .sub-input, .component-name-input, .add-sub-btn, .remove-row-btn, .add-component-btn, .remove-component-btn, #add-grade-component-btn').forEach(el => {
            el.disabled = disabled;
            
            // Add visual styling for disabled state
            if (disabled) {
                el.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                el.style.pointerEvents = 'none';
            } else {
                el.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                el.style.pointerEvents = '';
            }
        });
        
        // Handle accordion container - keep accordion toggles functional but disable editing
        const accordionContainer = document.getElementById('semestral-grade-accordion');
        if (accordionContainer) {
            if (disabled) {
                // Add visual indication that it's locked but keep accordion functionality
                accordionContainer.classList.add('opacity-80');
                accordionContainer.style.userSelect = 'none';
                
                // Keep accordion toggle buttons functional
                document.querySelectorAll('.accordion-toggle').forEach(toggle => {
                    toggle.style.pointerEvents = 'auto';
                    toggle.style.cursor = 'pointer';
                });
            } else {
                accordionContainer.classList.remove('opacity-80');
                accordionContainer.style.userSelect = '';
                
                // Reset accordion toggle styling
                document.querySelectorAll('.accordion-toggle').forEach(toggle => {
                    toggle.style.pointerEvents = '';
                    toggle.style.cursor = '';
                });
            }
        }
    };
    
    const showModal = (modalId) => {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
    };

    const hideModal = (modalId) => {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
    };

    const fetchAPI = async (url, options = {}) => {
        try {
            const apiUrl = `/api/${url}`;
            console.log(`Making API request to: ${apiUrl}`);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                            document.querySelector('input[name="_token"]')?.value;
            
            options.headers = { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json', 
                ...options.headers 
            };
            
            const response = await fetch(apiUrl, options);
            console.log(`API Response status: ${response.status} ${response.statusText}`);
            
            if (!response.ok) {
                let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    // If response is not JSON, use status text
                }
                throw new Error(errorMessage);
            }
            
            const data = await response.json();
            console.log('API Response data:', data);
            return data;
        } catch (error) {
            console.error('API Error:', error);
            // Don't show Swal here, let the calling function handle it
            throw error;
        }
    };
    
    // Old subject fetching function removed - now using curriculum-based workflow

    const fetchGradeSetupForSubject = (subjectId) => {
        if (!subjectId) {
            loadGradeDataToDOM({});
            toggleGradeComponents(true);
            return;
        }
        currentSubjectId = subjectId;
        isEditMode = false; 
        updateGradeSetupBtn.classList.add('hidden'); 
        addGradeBtn.classList.remove('hidden');

        fetchAPI(`grades/${subjectId}`).then(data => {
            if (data && data.components && Object.keys(data.components).length > 0) {
                 loadGradeDataToDOM(data.components);
                 toggleGradeComponents(true); // Disable form if grade exists
                 addGradeBtn.disabled = true;
            } else {
                 loadGradeDataToDOM({});
                 toggleGradeComponents(false); // Enable form if no grade exists
                 addGradeBtn.disabled = false;
            }
            calculateAndUpdateTotals();
        }).catch(() => {
            loadGradeDataToDOM({});
            toggleGradeComponents(false);
            addGradeBtn.disabled = false;
            calculateAndUpdateTotals();
        });
    };
    
    const addSubjectToHistory = (subject) => {
            const noHistoryMessage = document.getElementById('no-history-message');
            if (noHistoryMessage) noHistoryMessage.remove();
            if (document.querySelector(`.grade-history-card[data-subject-id="${subject.id}"]`)) return;
            const card = document.createElement('div');
            card.className = 'grade-history-card p-4 border rounded-lg hover:bg-gray-50 transition-colors duration-200';
            card.dataset.subjectId = subject.id;
            card.innerHTML = `<p class="font-semibold text-gray-800">${subject.subject_name}</p><p class="text-sm text-gray-500">${subject.subject_code}</p>`;
            gradeHistoryContainer.appendChild(card);
        };
        
        accordionContainer.addEventListener('click', (e) => {
            // Check for remove button first to prevent accordion toggle
            if (e.target.closest('.remove-component-btn')) {
                handleDynamicEvents(e);
                return;
            }
            
            const toggleButton = e.target.closest('.accordion-toggle');
            if (toggleButton) {
                const content = toggleButton.nextElementSibling;
                const icon = toggleButton.querySelector('svg:last-child');
                const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';
                
                // This allows only one accordion to be open at a time.
                // If you want multiple, remove this block.
                document.querySelectorAll('.accordion-content').forEach(c => { 
                    if (c !== content) c.style.maxHeight = null;
                });
                document.querySelectorAll('.accordion-toggle svg:last-child').forEach(i => {
                    if (i !== icon) i.classList.remove('rotate-180');
                });

                if (!isOpen) {
                    content.style.maxHeight = content.scrollHeight + "px";
                    icon.classList.add('rotate-180');
                } else {
                    content.style.maxHeight = null;
                    icon.classList.remove('rotate-180');
                }
            } else {
                handleDynamicEvents(e);
            }
        });

        accordionContainer.addEventListener('input', calculateAndUpdateTotals);
        // Old subject selection event listener removed - now using curriculum-based workflow
        
        const updateSubjectSelectionUI = (subjectId) => {
            const reminderText = document.querySelector('.subject-reminder-text');
            const addButton = document.getElementById('add-grade-component-btn');
            
            if (subjectId) {
                // Subject is selected
                if (reminderText) {
                    reminderText.textContent = '✓ Subject selected. You can now add grade components.';
                    reminderText.className = 'text-sm text-green-600 font-medium mt-1 subject-reminder-text';
                }
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                addButton.classList.add('hover:bg-indigo-50');
            } else {
                // No subject selected
                if (reminderText) {
                    reminderText.textContent = '⚠️ Please select a subject first before adding grade components';
                    reminderText.className = 'text-sm text-amber-600 font-medium mt-1 subject-reminder-text';
                }
                addButton.classList.add('opacity-50', 'cursor-not-allowed');
                addButton.classList.remove('hover:bg-indigo-50');
            }
        };
        
        addGradeBtn.addEventListener('click', () => {
            // Show confirmation modal
            document.getElementById('saveGradeSchemeModal').classList.remove('hidden');
        });

        // Handle the actual save logic when user confirms
        const handleGradeSchemeSave = async () => {
            if (currentCourseType === 'minor') {
                try {
                    const minorSubjects = currentCurriculumSubjects.filter(subject => subject.subject_type === 'Minor');
                    const components = getGradeDataFromDOM();
                    let savedCount = 0;
                    for (const subject of minorSubjects) {
                        try {
                            const payload = {
                                subject_id: subject.id,
                                components,
                                course_type: 'minor'
                            };
                            await fetchAPI('grades', { method: 'POST', body: JSON.stringify(payload) });
                            savedCount++;
                        } catch (err) {
                            console.error(`Failed to save minor subject ${subject.id}`, err);
                        }
                    }
                    console.log(`Saved grade scheme for ${savedCount} minor subject(s)`);
                    // Show success modal
                    document.getElementById('gradeSchemeSuccessModal').classList.remove('hidden');
                    
                    // For minor grades, lock components again and show update button
                    if (currentCourseType === 'minor') {
                        minorGradesUnlocked = false;
                        toggleGradeComponents(true);
                        addGradeBtn.disabled = true;
                        addGradeComponentBtn.style.display = 'none';
                        updateMinorGradesBtn.classList.remove('hidden');
                        document.querySelector('.curriculum-reminder-text').textContent = '✅ Grade scheme updated for minor courses - Grade components are locked';
                        
                        // Close all open accordion sections
                        closeAllAccordions();
                    } else {
                        // Reset form for major courses
                        resetForm();
                    }
                } catch (e) {
                    console.error('Failed to save minor grade scheme:', e);
                    Swal.fire('Error!', 'Failed to save grade scheme: ' + e.message, 'error');
                }
                return;
            }

            // Handle major course grades (individual subject)
            if (currentCourseType === 'major' && currentSubjectId) {
                try {
                    const gradeData = getGradeDataFromDOM();
                    const payload = {
                        subject_id: currentSubjectId,
                        components: gradeData,
                        course_type: 'major'
                    };
                    
                    const response = await fetchAPI('grades', {
                        method: 'POST',
                        body: JSON.stringify(payload)
                    });
                    
                    console.log('Major subject grade scheme saved successfully:', response);
                    
                    // Check if this was an update (by checking if we have currentSubjectData)
                    const isUpdate = currentSubjectData && currentSubjectData.subjectId === currentSubjectId;
                    
                    // Show success modal with specific message for major subjects
                    Swal.fire({
                        icon: 'success',
                        title: isUpdate ? 'Grade Scheme Updated!' : 'Grade Scheme Saved!',
                        text: isUpdate ? 'Successfully updated grade scheme for this major subject. The previous version has been saved to history.' : 'Successfully added grade scheme for this major subject.',
                        timer: 3000,
                        showConfirmButton: true
                    });
                    
                    // Clear currentSubjectData after successful update
                    if (isUpdate) {
                        currentSubjectData = null;
                        
                        // For updates, maintain the major courses state but reset the grade components
                        loadGradeDataToDOM({});
                        toggleGradeComponents(true);
                        addGradeBtn.disabled = true;
                        addGradeComponentBtn.style.display = 'none';
                        
                        // Reset major subject selection
                        majorSubjectSelect.value = '';
                        const selectedText = document.getElementById('major-subject-selected-text');
                        if (selectedText) {
                            selectedText.textContent = 'Select a major subject...';
                            selectedText.classList.remove('text-gray-900');
                            selectedText.classList.add('text-gray-500');
                        }
                        
                        // Update reminder text
                        document.querySelector('.curriculum-reminder-text').textContent = '⚠️ Please select a major subject to set up grades';
                        
                        // Refresh major subjects to show updated status
                        await populateMajorSubjects();
                    } else {
                        // For new grades, do full reset
                        resetForm();
                        await populateMajorSubjects();
                    }
                    
                } catch (e) {
                    console.error('Failed to save major subject grade scheme:', e);
                    Swal.fire('Error!', 'Failed to save grade scheme: ' + e.message, 'error');
                }
                return;
            }

            // This section is for curriculum-based workflows (if needed)
            let payload;
            if (currentCourseType === 'major' && currentCurriculumId) {
                payload = {
                    curriculum_id: currentCurriculumId,
                    course_type: 'major',
                    subjects: [{
                        subject_id: currentSubjectId,
                        components: getGradeDataFromDOM()
                    }]
                };
                
                try {
                    console.log('Saving curriculum grade scheme with payload:', payload);
                    const data = await fetchAPI('curriculum-grades', { method: 'POST', body: JSON.stringify(payload) });
                    console.log('Curriculum grade scheme saved successfully:', data);
                    
                    // Show success modal
                    document.getElementById('gradeSchemeSuccessModal').classList.remove('hidden');
                    
                    // Add curriculum to history
                    addCurriculumToHistory(data.curriculum);
                    
                    // Reset form
                    resetForm();
                    
                } catch(e) { 
                    console.error('Failed to save curriculum grade scheme:', e);
                    Swal.fire('Error!', 'Failed to save grade scheme: ' + e.message, 'error');
                }
            }
        };

        // Modal event handlers
        document.getElementById('cancelSaveGradeScheme').addEventListener('click', () => {
            document.getElementById('saveGradeSchemeModal').classList.add('hidden');
        });
        
        document.getElementById('confirmSaveGradeScheme').addEventListener('click', () => {
            document.getElementById('saveGradeSchemeModal').classList.add('hidden');
            handleGradeSchemeSave();
        });
        
        document.getElementById('closeGradeSchemeSuccess').addEventListener('click', () => {
            document.getElementById('gradeSchemeSuccessModal').classList.add('hidden');
        });
        
        // Update modal event handlers
        document.getElementById('cancelUpdateGradeScheme').addEventListener('click', () => {
            document.getElementById('updateGradeSchemeModal').classList.add('hidden');
        });
        
        document.getElementById('confirmUpdateGradeScheme').addEventListener('click', () => {
            document.getElementById('updateGradeSchemeModal').classList.add('hidden');
            handleGradeSchemeUpdate();
        });
        
        // Update Minor Grades button and modal handlers
        updateMinorGradesBtn.addEventListener('click', () => {
            document.getElementById('updateMinorGradesModal').classList.remove('hidden');
        });
        
        document.getElementById('cancelUpdateMinorGrades').addEventListener('click', () => {
            document.getElementById('updateMinorGradesModal').classList.add('hidden');
        });
        
        document.getElementById('confirmUpdateMinorGrades').addEventListener('click', () => {
            document.getElementById('updateMinorGradesModal').classList.add('hidden');
            unlockMinorGrades();
        });
        
        document.getElementById('closeGradeSchemeUpdateSuccess').addEventListener('click', () => {
            document.getElementById('gradeSchemeUpdateSuccessModal').classList.add('hidden');
        });
        
        // Edit modal event handlers
        document.getElementById('cancelEditGradeScheme').addEventListener('click', () => {
            document.getElementById('editGradeSchemeModal').classList.add('hidden');
        });
        
        document.getElementById('confirmEditGradeScheme').addEventListener('click', () => {
            document.getElementById('editGradeSchemeModal').classList.add('hidden');
            handleGradeSchemeEdit();
        });

        editGradeSetupBtn.addEventListener('click', () => {
            // Close the grade details modal immediately
            hideModal('grade-modal');
            
            // Then show edit confirmation modal
            document.getElementById('editGradeSchemeModal').classList.remove('hidden');
        });

        // Function to load grade data for editing
        const loadGradeDataForUpdate = async (subjectId, components) => {
            try {
                console.log('Loading grade data for update:', { subjectId, components });
                
                // Fetch subject details to get subject name and code
                const subjectData = await fetchAPI(`subjects/${subjectId}`);
                console.log('Subject data received:', subjectData);
                
                // Switch to major courses mode
                currentCourseType = 'major';
                updateCourseTypeButtons('major');
                
                // Show major subject section
                majorSubjectSection.classList.remove('hidden');
                
                // Populate major subjects dropdown
                await populateMajorSubjects();
                
                // Select the subject
                majorSubjectSelect.value = subjectId;
                currentSubjectId = subjectId;
                
                // Update dropdown button text
                const selectedText = document.getElementById('major-subject-selected-text');
                if (selectedText) {
                    selectedText.textContent = `${subjectData.subject_name} (${subjectData.subject_code})`;
                    selectedText.classList.remove('text-gray-500');
                    selectedText.classList.add('text-gray-900');
                }
                
                // Close dropdown
                document.getElementById('major-subject-dropdown-menu').classList.add('hidden');
                
                // Load the grade components into the form
                loadGradeDataToDOM(components);
                
                // Enable grade components for editing
                toggleGradeComponents(false);
                addGradeComponentBtn.style.display = '';
                addGradeComponentBtn.disabled = false;
                
                // Update UI text
                document.querySelector('.curriculum-reminder-text').textContent = `✏️ Editing grades for ${subjectData.subject_name} - Make changes and click "Set Grade Scheme" to save`;
                
                // Calculate totals to enable/disable save button
                calculateAndUpdateTotals();
                
                console.log('Grade data loaded successfully for editing');
                
            } catch (error) {
                console.error('Error loading grade data for update:', error);
                throw error;
            }
        };

        // Handle the actual edit logic when user confirms
        const handleGradeSchemeEdit = async () => {
            if (!currentSubjectData) {
                console.error('No subject data available for editing');
                return;
            }
            
            try {
                console.log('Starting form population with data:', currentSubjectData);
                
                // Load the grade data for editing
                await loadGradeDataForUpdate(
                    currentSubjectData.subjectId,
                    currentSubjectData.components
                );
                
                console.log('Form successfully populated and ready for editing');
                
            } catch (error) {
                console.error('Error populating form:', error);
                Swal.fire('Error!', 'Failed to load subject data: ' + error.message, 'error');
            }
        };

    updateGradeSetupBtn.addEventListener('click', () => {
        // Show update confirmation modal
        document.getElementById('updateGradeSchemeModal').classList.remove('hidden');
    });

    // Handle the actual update logic when user confirms
    const handleGradeSchemeUpdate = async () => {
        const payload = { 
            subject_id: currentSubjectId, 
            components: getGradeDataFromDOM(),
            curriculum_id: currentCurriculumId,
            course_type: currentCourseType
        };
        try {
            const data = await fetchAPI('grades', { method: 'POST', body: JSON.stringify(payload) });
            
            // Show update success modal instead of SweetAlert
            document.getElementById('gradeSchemeUpdateSuccessModal').classList.remove('hidden');
            
            isEditMode = false;
            toggleGradeComponents(true);
            addGradeBtn.classList.remove('hidden');
            updateGradeSetupBtn.classList.add('hidden');
            addGradeBtn.disabled = true;
            
            // Re-enable form elements after successful update
            if (curriculumSelect) {
                curriculumSelect.disabled = false;
                curriculumSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
            }
            minorCoursesBtn.disabled = false;
            minorCoursesBtn.classList.remove('cursor-not-allowed', 'opacity-60');
            majorCoursesBtn.disabled = false;
            majorCoursesBtn.classList.remove('cursor-not-allowed', 'opacity-60');
            majorSubjectSelect.disabled = false;
            majorSubjectSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
        } catch (e) { 
            Swal.fire('Error!', 'Failed to update grade scheme: ' + e.message, 'error');
        }
    };

    gradeHistoryContainer.addEventListener('click', async (e) => {
        const card = e.target.closest('.grade-history-card');
        if (card) {
            const subjectId = card.dataset.subjectId;
            // Updated to work with curriculum-based workflow
            // This functionality needs to be updated for curriculum-based grade history
            try {
                const gradeData = await fetchAPI(`grades/${subjectId}`);
                
                let contentHtml = '<div class="space-y-6">';
                for (const [period, data] of Object.entries(gradeData.components)) {
                    contentHtml += `
                        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                            <h4 class="font-bold text-lg capitalize text-gray-800 flex items-center gap-3 border-b pb-3 mb-3">
                                ${period} <span class="text-sm font-bold text-gray-500 ml-auto">${data.weight}%</span>
                            </h4>
                            <div class="flow-root">
                                <div class="-my-2 divide-y divide-gray-100">`;

                    if (!data.components || data.components.length === 0) {
                        contentHtml += '<p class="text-sm text-gray-500 py-3">No components for this period.</p>';
                    } else {
                        (data.components || []).forEach(comp => {
                            contentHtml += `
                                <div class="py-3">
                                    <div class="flex items-center justify-between gap-4">
                                        <p class="font-medium text-gray-700">${comp.name}</p>
                                        <p class="font-mono text-base text-gray-900">${comp.weight}%</p>
                                    </div>`;
                            if (comp.sub_components && comp.sub_components.length > 0) {
                                contentHtml += '<div class="mt-2 pl-6 border-l-2 border-gray-200 space-y-2">';
                                comp.sub_components.forEach(sub => {
                                    contentHtml += `
                                        <div class="flex items-center justify-between text-sm">
                                            <p class="text-gray-600">${sub.name}</p>
                                            <p class="font-mono text-gray-600">${sub.weight}%</p>
                                        </div>`;
                                });
                                contentHtml += '</div>';
                            }
                            contentHtml += '</div>';
                        });
                    }
                    contentHtml += `</div></div></div>`;
                }
                contentHtml += '</div>';

                modalContent.innerHTML = contentHtml;
                showModal('grade-modal');

            } catch (error) { 
                console.error('Error fetching grade details:', error);
                console.log('Could not fetch grade details. This might be because no grades are set for this subject.');
            }
        }
    });
    
    closeModalBtn.addEventListener('click', () => hideModal('grade-modal'));
    gradeModal.addEventListener('click', (e) => { if (e.target.id === 'grade-modal') hideModal('grade-modal'); });

    // Fetch all subjects globally (not per curriculum)
    const fetchAllSubjects = async () => {
        try {
            console.log('Fetching all subjects from API...');
            const allSubjects = await fetchAPI('subjects');
            console.log('All subjects received:', allSubjects);
            
            // Store subjects globally
            currentCurriculumSubjects = allSubjects;
            
            // Update reminder text
            document.querySelector('.curriculum-reminder-text').textContent = '⚠️ Please select a course type to set up grades';
            
        } catch (error) {
            console.error('Error fetching subjects:', error);
            Swal.fire('Error!', 'Failed to fetch subjects', 'error');
        }
    };

    const handleCourseTypeSelection = async (courseType) => {
        currentCourseType = courseType;
        updateCourseTypeButtons(courseType);
        
        if (courseType === 'minor') {
            // Load current grade structure for minor courses
            majorSubjectSection.classList.add('hidden');
            await loadMinorGradeStructure();
            toggleGradeComponents(true); // Lock components initially
            addGradeComponentBtn.style.display = 'none'; // Hide add component button initially
            updateMinorGradesBtn.classList.remove('hidden'); // Show update minor grades button
            minorGradesUnlocked = false; // Set as locked initially
            document.querySelector('.curriculum-reminder-text').textContent = '✅ Default grades already applied to all minor subject(s) - Grade components are locked';
        } else if (courseType === 'major') {
            // Show major subject selection
            majorSubjectSection.classList.remove('hidden');
            document.querySelector('.curriculum-reminder-text').textContent = '⏳ Loading major subjects...';
            await populateMajorSubjects();
            loadGradeDataToDOM({});
            toggleGradeComponents(true);
            addGradeBtn.disabled = true;
            addGradeComponentBtn.style.display = ''; // Show add component button for major courses
            updateMinorGradesBtn.classList.add('hidden'); // Hide update minor grades button for major courses
            document.querySelector('.curriculum-reminder-text').textContent = '⚠️ Please select a major subject to set up grades';
        }
    };
    

    const closeAllAccordions = () => {
        // Close all accordion sections
        document.querySelectorAll('.accordion-content').forEach(content => {
            content.style.maxHeight = null;
        });
        
        // Reset all chevron icons to closed state
        document.querySelectorAll('.accordion-toggle svg:last-child').forEach(icon => {
            icon.classList.remove('rotate-180');
        });
        
        console.log('All accordion sections closed');
    };

    const loadMinorGradeStructure = async () => {
        try {
            // Find a minor subject to get the current grade structure
            const minorSubjects = currentCurriculumSubjects.filter(subject => subject.subject_type === 'Minor');
            
            if (minorSubjects.length > 0) {
                // Try to get grade data from the first minor subject
                try {
                    const firstMinorSubject = minorSubjects[0];
                    const gradeData = await fetchAPI(`grades/${firstMinorSubject.id}`);
                    
                    if (gradeData && gradeData.components && Object.keys(gradeData.components).length > 0) {
                        // Load the existing grade structure
                        loadGradeDataToDOM(gradeData.components);
                        console.log('Loaded existing minor grade structure:', gradeData.components);
                        return;
                    }
                } catch (error) {
                    console.log('No existing grades found for minor subjects, loading default structure');
                }
            }
            
            // If no existing grades found, load default structure
            const defaultStructure = {
                'Prelim': { 
                    weight: 30, 
                    components: [
                        {
                            name: 'A.1 CLASS STANDING',
                            weight: 40,
                            sub_components: [
                                { name: 'Attendance', weight: 10 },
                                { name: 'Written Works', weight: 50 },
                                { name: 'Performance Task', weight: 40 }
                            ]
                        },
                        {
                            name: 'A.2 PROJECT',
                            weight: 25,
                            sub_components: [
                                { name: 'Course-Based Output Off-Campus Requirements', weight: 100 }
                            ]
                        },
                        {
                            name: 'A.3 EXAMINATION',
                            weight: 35,
                            sub_components: [
                                { name: 'Written Examination', weight: 100 }
                            ]
                        }
                    ]
                },
                'Midterm': { 
                    weight: 30, 
                    components: [
                        {
                            name: 'A.1 CLASS STANDING',
                            weight: 40,
                            sub_components: [
                                { name: 'Attendance', weight: 10 },
                                { name: 'Written Works', weight: 50 },
                                { name: 'Performance Task', weight: 40 }
                            ]
                        },
                        {
                            name: 'A.2 PROJECT',
                            weight: 25,
                            sub_components: [
                                { name: 'Course-Based Output Off-Campus Requirements', weight: 100 }
                            ]
                        },
                        {
                            name: 'A.3 EXAMINATION',
                            weight: 35,
                            sub_components: [
                                { name: 'Written Examination', weight: 100 }
                            ]
                        }
                    ]
                },
                'Finals': { 
                    weight: 40, 
                    components: [
                        {
                            name: 'A.1 CLASS STANDING',
                            weight: 40,
                            sub_components: [
                                { name: 'Attendance', weight: 10 },
                                { name: 'Written Works', weight: 50 },
                                { name: 'Performance Task', weight: 40 }
                            ]
                        },
                        {
                            name: 'A.2 PROJECT',
                            weight: 25,
                            sub_components: [
                                { name: 'Course-Based Output Off-Campus Requirements', weight: 100 }
                            ]
                        },
                        {
                            name: 'A.3 EXAMINATION',
                            weight: 35,
                            sub_components: [
                                { name: 'Written Examination', weight: 100 }
                            ]
                        }
                    ]
                }
            };
            
            loadGradeDataToDOM(defaultStructure);
            console.log('Loaded default minor grade structure');
            
        } catch (error) {
            console.error('Error loading minor grade structure:', error);
            // Load basic structure as fallback
            const basicStructure = {
                'Prelim': { weight: 30, components: [] },
                'Midterm': { weight: 30, components: [] },
                'Finals': { weight: 40, components: [] }
            };
            loadGradeDataToDOM(basicStructure);
        }
    };

    const unlockMinorGrades = () => {
        // Set unlock flag
        minorGradesUnlocked = true;
        
        // Unlock grade components for editing
        toggleGradeComponents(false);
        
        // Show Add Grade Component button
        addGradeComponentBtn.style.display = '';
        
        // Hide Update Minor Grades button (since it's now unlocked)
        updateMinorGradesBtn.classList.add('hidden');
        
        // Update reminder text
        document.querySelector('.curriculum-reminder-text').textContent = '✏️ Minor grades unlocked - You can now edit the grade components';
        
        // Calculate totals to determine if Set Grade Scheme button should be enabled
        calculateAndUpdateTotals();
        
        // Show success notification
        Swal.fire({
            icon: 'success',
            title: 'Unlocked!',
            text: 'Minor grade components are now editable. Make your changes and click "Set Grade Scheme" to save.',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    };

    const populateMajorSubjects = async () => {
        const majorSubjects = currentCurriculumSubjects.filter(subject => subject.subject_type === 'Major');
        
        // Get dropdown elements
        const dropdownMenu = document.getElementById('major-subject-dropdown-menu');
        const dropdownContainer = dropdownMenu.querySelector('.py-1');
        
        // Clear existing options
        dropdownContainer.innerHTML = '';
        majorSubjectSelect.innerHTML = '<option value="">Select a major subject...</option>';
        
        // Check grade status for each subject
        for (const subject of majorSubjects) {
            // Check if subject has grades
            let hasGrades = false;
            try {
                const gradeData = await fetchAPI(`grades/${subject.id}/version-history`);
                hasGrades = gradeData && gradeData.current_version;
            } catch (error) {
                hasGrades = false;
            }
            
            // Create custom dropdown option with badge
            const optionDiv = document.createElement('div');
            
            // Apply different styles based on graded status
            if (hasGrades) {
                // Graded subjects - disabled style
                optionDiv.className = 'px-4 py-2 cursor-not-allowed flex items-center justify-between bg-gray-50 opacity-60';
                optionDiv.title = 'This subject already has grades set up';
            } else {
                // Available subjects - clickable
                optionDiv.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center justify-between transition-colors';
            }
            
            optionDiv.dataset.subjectId = subject.id;
            optionDiv.dataset.subjectName = subject.subject_name;
            optionDiv.dataset.subjectCode = subject.subject_code;
            
            // Create badge if graded
            const badge = hasGrades 
                ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Graded</span>'
                : '';
            
            const textColor = hasGrades ? 'text-gray-500' : 'text-gray-900';
            optionDiv.innerHTML = `
                <span class="text-sm ${textColor}">${subject.subject_name} <span class="text-gray-400">(${subject.subject_code})</span></span>
                ${badge}
            `;
            
            // Add click handler - different behavior for graded vs non-graded subjects
            if (!hasGrades) {
                // Non-graded subjects: single click to select
                optionDiv.addEventListener('click', () => {
                    selectMajorSubject(subject.id, subject.subject_name, subject.subject_code);
                });
            } else {
                // Graded subjects: double click to view grade details
                let clickCount = 0;
                optionDiv.addEventListener('click', () => {
                    clickCount++;
                    if (clickCount === 1) {
                        setTimeout(() => {
                            if (clickCount === 2) {
                                // Double click detected - show grade details
                                showGradeComponentDetails(subject.id, subject.subject_name, subject.subject_code);
                            }
                            clickCount = 0;
                        }, 300);
                    }
                });
            }
            
            dropdownContainer.appendChild(optionDiv);
            
            // Also add to hidden select for compatibility
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = `${subject.subject_name} (${subject.subject_code})`;
            majorSubjectSelect.appendChild(option);
        }
    };
    
    // Function to show grade component details for graded subjects
    const showGradeComponentDetails = async (subjectId, subjectName, subjectCode) => {
        try {
            // Fetch grade details with version history
            const gradeData = await fetchAPI(`grades/${subjectId}/version-history`);
            
            if (!gradeData || !gradeData.current_version) {
                Swal.fire('Error!', 'No grade data found for this subject.', 'error');
                return;
            }
            
            // Show grade details modal (you can create this modal or use SweetAlert)
            let detailsHTML = `
                <div style="text-align: left;">
                    <h3 style="margin-bottom: 10px; color: #1F2937; font-weight: bold;">${subjectName} (${subjectCode})</h3>
                    <div style="margin-bottom: 15px;">
                        <span style="background: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                            Current Version - Updated: ${new Date(gradeData.current_version.updated_at).toLocaleDateString()}
                        </span>
                    </div>
            `;
            
            // Display current grade components
            const components = gradeData.current_version.components;
            Object.keys(components).forEach(period => {
                const periodData = components[period];
                detailsHTML += `
                    <div style="margin-bottom: 15px;">
                        <h4 style="color: #059669; font-weight: bold; margin-bottom: 8px;">${period} (${periodData.weight}%)</h4>
                `;
                
                if (periodData.components && periodData.components.length > 0) {
                    periodData.components.forEach(comp => {
                        detailsHTML += `
                            <div style="margin-left: 15px; margin-bottom: 8px;">
                                <strong>${comp.name}</strong> - ${comp.weight}%
                        `;
                        
                        if (comp.sub_components && comp.sub_components.length > 0) {
                            comp.sub_components.forEach(sub => {
                                detailsHTML += `<br><span style="margin-left: 20px; color: #6B7280;">${sub.name}: ${sub.weight}%</span>`;
                            });
                        }
                        
                        detailsHTML += `</div>`;
                    });
                }
                
                detailsHTML += `</div>`;
            });
            
            // Show previous versions if any
            if (gradeData.previous_versions && gradeData.previous_versions.length > 0) {
                detailsHTML += `
                    <hr style="margin: 20px 0; border-color: #E5E7EB;">
                    <h4 style="color: #6B7280; margin-bottom: 10px;">Previous Versions:</h4>
                `;
                
                gradeData.previous_versions.forEach((version, index) => {
                    detailsHTML += `
                        <div style="margin-bottom: 8px; color: #6B7280; font-size: 14px;">
                            Version ${gradeData.previous_versions.length - index} - ${new Date(version.updated_at).toLocaleDateString()}
                        </div>
                    `;
                });
            }
            
            detailsHTML += `</div>`;
            
            Swal.fire({
                title: 'Grade Component Details',
                html: detailsHTML,
                width: '600px',
                showCancelButton: false,
                confirmButtonText: 'Close',
                confirmButtonColor: '#4F46E5'
            });
            
        } catch (error) {
            console.error('Error fetching grade details:', error);
            Swal.fire('Error!', 'Failed to load grade details.', 'error');
        }
    };

    // Function to handle major subject selection from custom dropdown
    const selectMajorSubject = (subjectId, subjectName, subjectCode) => {
        // Update hidden select
        majorSubjectSelect.value = subjectId;
        
        // Update button text
        const selectedText = document.getElementById('major-subject-selected-text');
        selectedText.textContent = `${subjectName} (${subjectCode})`;
        selectedText.classList.remove('text-gray-500');
        selectedText.classList.add('text-gray-900');
        
        // Close dropdown
        document.getElementById('major-subject-dropdown-menu').classList.add('hidden');
        
        // Trigger the selection handler
        handleMajorSubjectSelection();
    };

    const handleMajorSubjectSelection = () => {
        const subjectId = majorSubjectSelect.value;
        if (!subjectId) {
            loadGradeDataToDOM({});
            toggleGradeComponents(true);
            addGradeBtn.disabled = true;
            addGradeComponentBtn.style.display = 'none';
            document.querySelector('.curriculum-reminder-text').textContent = '⚠️ Please select a major subject to set up grades';
            return;
        }

        currentSubjectId = subjectId;
        loadGradeDataToDOM({});
        toggleGradeComponents(false);
        addGradeBtn.disabled = false;
        addGradeComponentBtn.style.display = ''; // Show add component button
        addGradeComponentBtn.disabled = false; // Ensure it's enabled
        document.querySelector('.curriculum-reminder-text').textContent = '✅ Major subject selected - ready to set up grades';
        calculateAndUpdateTotals(); // Recalculate to update button states
    };

    const updateCourseTypeButtons = (selectedType) => {
        resetCourseTypeButtons();
        
        if (selectedType === 'minor') {
            minorCoursesBtn.classList.add('border-blue-500', 'bg-blue-50');
            minorCoursesBtn.classList.remove('border-gray-300');
        } else if (selectedType === 'major') {
            majorCoursesBtn.classList.add('border-green-500', 'bg-green-50');
            majorCoursesBtn.classList.remove('border-gray-300');
        }
    };

    const resetCourseTypeButtons = () => {
        minorCoursesBtn.classList.remove('border-blue-500', 'bg-blue-50');
        minorCoursesBtn.classList.add('border-gray-300');
        majorCoursesBtn.classList.remove('border-green-500', 'bg-green-50');
        majorCoursesBtn.classList.add('border-gray-300');
    };

    const updateCurriculumSelectionUI = () => {
        courseTypeSection.classList.add('hidden');
        majorSubjectSection.classList.add('hidden');
        document.querySelector('.curriculum-reminder-text').textContent = '⚠️ Please select a curriculum first to set up grades';
    };

    const addCurriculumToHistory = (curriculum) => {
        const noHistoryMessage = document.getElementById('no-history-message');
        if (noHistoryMessage) noHistoryMessage.remove();
        
        // Check if curriculum already exists in history
        if (document.querySelector(`.grade-history-card[data-curriculum-id="${curriculum.id}"]`)) return;
        
        // Determine curriculum type based on multiple fields
        const programCode = (curriculum.program_code || '').toLowerCase();
        const curriculumName = (curriculum.curriculum_name || '').toLowerCase();
        
        // Check if it's Senior High based on various indicators
        const isSeniorHigh = programCode.includes('shs') || 
                            programCode.includes('senior') ||
                            programCode.includes('sh-') ||
                            programCode.includes('_sh') ||
                            curriculumName.includes('senior high') ||
                            curriculumName.includes('senior-high') ||
                            curriculumName.includes('shs') ||
                            // Common Senior High program codes
                            programCode.includes('humss') ||
                            programCode.includes('stem') ||
                            programCode.includes('abm') ||
                            programCode.includes('gas') ||
                            programCode.includes('tvl') ||
                            programCode.includes('ict-cp') ||
                            programCode.includes('he') ||
                            programCode.includes('ia');
        
        const curriculumType = curriculum.curriculum_type || (isSeniorHigh ? 'seniorhigh' : 'college');
        
        const typeBadge = curriculumType === 'seniorhigh' 
            ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-full">Senior High</span>'
            : '<span class="inline-block px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">College</span>';
        
        const card = document.createElement('div');
        card.className = 'grade-history-card p-4 border rounded-lg hover:bg-gray-50 transition-colors duration-200 cursor-pointer';
        card.dataset.curriculumId = curriculum.id;
        card.dataset.curriculumType = curriculumType;
        card.dataset.curriculumName = curriculum.curriculum_name.toLowerCase();
        card.dataset.programCode = (curriculum.program_code || '').toLowerCase();
        card.innerHTML = `
            <div class="flex items-start justify-between gap-2 mb-2">
                <p class="font-semibold text-gray-800 flex-1">${curriculum.curriculum_name}</p>
                ${typeBadge}
            </div>
            <p class="text-sm text-gray-500">${curriculum.program_code} - ${curriculum.academic_year}</p>
        `;
        
        // Add click event to show curriculum grade modal
        card.addEventListener('click', () => showCurriculumGradeModal(curriculum.id));
        
        gradeHistoryContainer.appendChild(card);
    };

    const resetForm = () => {
        if (curriculumSelect) {
            curriculumSelect.value = '';
        }
        courseTypeSection.classList.add('hidden');
        majorSubjectSection.classList.add('hidden');
        majorSubjectSelect.innerHTML = '<option value="">Select a major subject...</option>';
        currentCurriculumId = null;
        currentSubjectId = null;
        currentCourseType = null;
        currentCurriculumSubjects = [];
        minorGradesUnlocked = false; // Reset minor grades unlock status
        resetCourseTypeButtons();
        loadGradeDataToDOM({});
        toggleGradeComponents(true);
        addGradeBtn.disabled = true;
        updateCurriculumSelectionUI();
        
        // Re-enable form elements
        if (curriculumSelect) {
            curriculumSelect.disabled = false;
            curriculumSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
        }
        minorCoursesBtn.disabled = false;
        minorCoursesBtn.classList.remove('cursor-not-allowed', 'opacity-60');
        majorCoursesBtn.disabled = false;
        majorCoursesBtn.classList.remove('cursor-not-allowed', 'opacity-60');
        majorSubjectSelect.disabled = false;
        majorSubjectSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
    };

    const showCurriculumGradeModal = async (curriculumId) => {
        try {
            // Fetch curriculum details and subjects with grades
            console.log('Fetching curriculum grade details for:', curriculumId);
            let data = await fetchAPI(`curriculum-grades/${curriculumId}`);
            console.log('Curriculum grade details received:', data);
            
            // No auto-assignment - manual grade setup required through Update Minor Grades
            
            // Update modal title
            document.getElementById('curriculum-modal-title').textContent = data.curriculum.curriculum_name;
            document.getElementById('curriculum-modal-subtitle').textContent = `${data.curriculum.program_code} - Grade Schemes Overview`;
            
            // Populate minor subjects
            const minorContainer = document.getElementById('minor-subjects-container');
            const minorSubjects = data.subjects.filter(subject => subject.subject_type === 'Minor');
            
            if (minorSubjects.length > 0) {
                minorContainer.innerHTML = minorSubjects.map(subject => {
                    // All minor subjects should have grades now (auto-assigned)
                    const hasGrades = subject.has_grades;
                    const cardClass = 'subject-card p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors';
                    const statusText = 'Default grades applied';
                    const statusColor = 'text-blue-600';
                    
                    return `
                        <div class="${cardClass}" data-subject-id="${subject.id}" data-has-grades="true" data-subject-name="${subject.subject_name.toLowerCase()}" data-subject-code="${subject.subject_code.toLowerCase()}">
                            <p class="font-medium text-gray-800">${subject.subject_name}</p>
                            <p class="text-sm text-gray-500">${subject.subject_code}</p>
                            <p class="text-xs ${statusColor} mt-1">${statusText}</p>
                        </div>
                    `;
                }).join('');
            } else {
                minorContainer.innerHTML = '<p class="text-gray-500 text-sm" id="no-minor-subjects">No minor subjects found.</p>';
            }
            
            // Populate major subjects
            const majorContainer = document.getElementById('major-subjects-container');
            const majorSubjects = data.subjects.filter(subject => subject.subject_type === 'Major');
            
            if (majorSubjects.length > 0) {
                // Check grades for each major subject individually
                const majorSubjectCards = await Promise.all(majorSubjects.map(async (subject) => {
                    let hasGrades = false;
                    
                    try {
                        // Check if subject has grades by trying to fetch them
                        const gradeData = await fetchAPI(`grades/${subject.id}`);
                        hasGrades = gradeData && gradeData.components && Object.keys(gradeData.components).length > 0;
                        console.log(`Subject ${subject.subject_name} (ID: ${subject.id}) has grades:`, hasGrades);
                    } catch (error) {
                        // If fetch fails, subject doesn't have grades
                        hasGrades = false;
                        console.log(`Subject ${subject.subject_name} (ID: ${subject.id}) has no grades (fetch failed)`);
                    }
                    
                    const cardClass = hasGrades ? 'subject-card p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors' : 'subject-card p-3 border rounded-lg cursor-not-allowed transition-colors bg-gray-200 opacity-60';
                    const statusText = hasGrades ? 'Custom grades set' : 'No grades set';
                    const statusColor = hasGrades ? 'text-green-600' : 'text-gray-400';
                    
                    return `
                        <div class="${cardClass}" data-subject-id="${subject.id}" data-has-grades="${hasGrades}" data-subject-name="${subject.subject_name.toLowerCase()}" data-subject-code="${subject.subject_code.toLowerCase()}">
                            <p class="font-medium text-gray-800">${subject.subject_name}</p>
                            <p class="text-sm text-gray-500">${subject.subject_code}</p>
                            <p class="text-xs ${statusColor} mt-1">${statusText}</p>
                        </div>
                    `;
                }));
                
                majorContainer.innerHTML = majorSubjectCards.join('');
            } else {
                majorContainer.innerHTML = '<p class="text-gray-500 text-sm" id="no-major-subjects">No major subjects found.</p>';
            }
            
            // Add click events to subject cards (only for subjects with grades)
            document.querySelectorAll('.subject-card').forEach(card => {
                card.addEventListener('click', () => {
                    const hasGrades = card.dataset.hasGrades === 'true';
                    if (hasGrades) {
                        const subjectId = card.dataset.subjectId;
                        showSubjectGradeDetails(subjectId);
                    }
                });
            });
            
            // Add search functionality
            const searchInput = document.getElementById('subject-search-input');
            searchInput.value = ''; // Clear search input when modal opens
            
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase().trim();
                const allSubjectCards = document.querySelectorAll('.subject-card');
                
                let minorVisible = 0;
                let majorVisible = 0;
                
                allSubjectCards.forEach(card => {
                    const subjectName = card.dataset.subjectName || '';
                    const subjectCode = card.dataset.subjectCode || '';
                    const isMinor = card.closest('#minor-subjects-container') !== null;
                    
                    // Check if search term matches name or code
                    const matches = subjectName.includes(searchTerm) || subjectCode.includes(searchTerm);
                    
                    if (matches || searchTerm === '') {
                        card.style.display = '';
                        if (isMinor) minorVisible++;
                        else majorVisible++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Show/hide "no results" messages
                const noMinorMsg = document.getElementById('no-minor-subjects');
                const noMajorMsg = document.getElementById('no-major-subjects');
                
                if (minorVisible === 0 && !noMinorMsg) {
                    minorContainer.innerHTML = '<p class="text-gray-500 text-sm" id="no-minor-results">No matching minor subjects found.</p>';
                } else if (minorVisible > 0) {
                    const noResultsMsg = document.getElementById('no-minor-results');
                    if (noResultsMsg) noResultsMsg.remove();
                }
                
                if (majorVisible === 0 && !noMajorMsg) {
                    majorContainer.innerHTML = '<p class="text-gray-500 text-sm" id="no-major-results">No matching major subjects found.</p>';
                } else if (majorVisible > 0) {
                    const noResultsMsg = document.getElementById('no-major-results');
                    if (noResultsMsg) noResultsMsg.remove();
                }
            });
            
            // Show modal
            showModal('curriculum-grade-modal');
            
        } catch (error) {
            console.error('Error fetching curriculum grade details:', error);
            // Remove the SweetAlert error and just log it
            console.log('Could not fetch curriculum grade details. This might be because the API endpoint needs to be implemented.');
        }
    };

    // Store current subject data for editing
    let currentSubjectData = null;
    
    const showSubjectGradeDetails = async (subjectId) => {
        try {
            console.log('Fetching subject grade version history for:', subjectId);
            const versionData = await fetchAPI(`grades/${subjectId}/version-history`);
            console.log('Subject grade version history received:', versionData);
            
            // Fetch subject details to get subject_type
            const subjectData = await fetchAPI(`subjects/${subjectId}`);
            
            // Store the subject data for later use when editing
            currentSubjectData = {
                subjectId: subjectId,
                curriculumId: versionData.current_version.curriculum_id,
                courseType: versionData.current_version.course_type, // 'minor' or 'major'
                subjectType: subjectData.subject_type, // 'Minor' or 'Major'
                components: versionData.current_version.components
            };
            
            // Build the grade details HTML with dropdown/accordion style
            let contentHtml = '<div class="space-y-3">';
            
            // Current Version Dropdown
            if (versionData.current_version && versionData.current_version.components) {
                const updatedDate = versionData.current_version.updated_at ? new Date(versionData.current_version.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';
                
                contentHtml += `
                    <div class="border border-gray-200 rounded-lg bg-white shadow-sm overflow-hidden">
                        <button type="button" class="grade-version-toggle w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors" data-target="current-version-content">
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">In Use</span>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800">Current Version</p>
                                    ${updatedDate ? `<p class="text-xs text-gray-500">Updated: ${updatedDate}</p>` : ''}
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: rotate(180deg);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="current-version-content" class="version-content border-t border-gray-200 bg-gray-50">
                            <div class="p-4 space-y-3">`;
                
                const components = versionData.current_version.components;
                for (const [period, periodData] of Object.entries(components)) {
                    contentHtml += `
                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                            <h4 class="font-bold text-sm capitalize text-gray-800 flex items-center justify-between border-b pb-2 mb-2">
                                <span>${period}</span>
                                <span class="text-green-600">${periodData.weight}%</span>
                            </h4>`;

                    if (!periodData.components || periodData.components.length === 0) {
                        contentHtml += '<p class="text-xs text-gray-500 py-2">No components for this period.</p>';
                    } else {
                        periodData.components.forEach(comp => {
                            contentHtml += `
                                <div class="py-2 border-b border-gray-100 last:border-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="font-medium text-xs text-gray-700">${comp.name}</p>
                                        <p class="font-mono text-xs text-gray-900">${comp.weight}%</p>
                                    </div>`;
                            
                            if (comp.sub_components && comp.sub_components.length > 0) {
                                contentHtml += '<div class="mt-2 pl-3 border-l-2 border-gray-200 space-y-1">';
                                comp.sub_components.forEach(sub => {
                                    contentHtml += `
                                        <div class="flex items-center justify-between text-xs">
                                            <p class="text-gray-600">${sub.name}</p>
                                            <p class="font-mono text-gray-600">${sub.weight}%</p>
                                        </div>`;
                                });
                                contentHtml += '</div>';
                            }
                            contentHtml += '</div>';
                        });
                    }
                    contentHtml += `</div>`;
                }
                
                contentHtml += `
                            </div>
                        </div>
                    </div>`;
            }
            
            // Previous Versions (show all)
            const versions = Array.isArray(versionData.versions) ? versionData.versions : (versionData.previous_version ? [versionData.previous_version] : []);
            if (versions.length > 0) {
                versions.forEach(ver => {
                    const createdDate = ver.created_at ? new Date(ver.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';
                    const targetId = `previous-version-content-${ver.version_number}`;
                    contentHtml += `
                        <div class="border border-gray-200 rounded-lg bg-white shadow-sm overflow-hidden">
                            <button type="button" class="grade-version-toggle w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors" data-target="${targetId}">
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded">Previous</span>
                                    <div class="text-left">
                                        <p class="text-sm font-semibold text-gray-800">Version ${ver.version_number}</p>
                                        ${createdDate ? `<p class="text-xs text-gray-500">${createdDate}</p>` : ''}
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="${targetId}" class="version-content hidden border-t border-gray-200 bg-gray-50">
                                <div class="p-4 space-y-3">`;
                    
                    if (ver.change_reason || ver.changed_by) {
                        contentHtml += `<div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-3">`;
                        if (ver.change_reason) {
                            contentHtml += `<p class="text-xs text-amber-800"><strong>Change Reason:</strong> ${ver.change_reason}</p>`;
                        }
                        if (ver.changed_by) {
                            contentHtml += `<p class="text-xs text-amber-700 mt-1"><strong>Changed By:</strong> ${ver.changed_by}</p>`;
                        }
                        contentHtml += `</div>`;
                    }
                    
                    const prevComponents = ver.components || {};
                    for (const [period, periodData] of Object.entries(prevComponents)) {
                        contentHtml += `
                            <div class="bg-white border border-gray-200 rounded-lg p-3">
                                <h4 class="font-bold text-sm capitalize text-gray-800 flex items-center justify-between border-b pb-2 mb-2">
                                    <span>${period}</span>
                                    <span class="text-amber-600">${periodData.weight}%</span>
                                </h4>`;
                        
                        if (!periodData.components || periodData.components.length === 0) {
                            contentHtml += '<p class="text-xs text-gray-500 py-2">No components for this period.</p>';
                        } else {
                            periodData.components.forEach(comp => {
                                contentHtml += `
                                    <div class="py-2 border-b border-gray-100 last:border-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="font-medium text-xs text-gray-700">${comp.name}</p>
                                            <p class="font-mono text-xs text-gray-900">${comp.weight}%</p>
                                        </div>`;
                                
                                if (comp.sub_components && comp.sub_components.length > 0) {
                                    contentHtml += '<div class="mt-2 pl-3 border-l-2 border-gray-200 space-y-1">';
                                    comp.sub_components.forEach(sub => {
                                        contentHtml += `
                                            <div class="flex items-center justify-between text-xs">
                                                <p class="text-gray-600">${sub.name}</p>
                                                <p class="font-mono text-gray-600">${sub.weight}%</p>
                                            </div>`;
                                    });
                                    contentHtml += '</div>';
                                }
                                contentHtml += '</div>';
                            });
                        }
                        contentHtml += `</div>`;
                    }
                    
                    contentHtml += `
                                </div>
                            </div>
                        </div>`;
                });
            } else {
                contentHtml += `
                    <div class="border border-gray-200 rounded-lg bg-white shadow-sm overflow-hidden">
                        <div class="px-4 py-3 flex items-center gap-3 bg-gray-50">
                            <span class="px-2 py-1 bg-gray-200 text-gray-600 text-xs font-semibold rounded">Previous</span>
                            <p class="text-sm text-gray-600">No previous version available - This is the original version</p>
                        </div>
                    </div>`;
            }
            
            contentHtml += '</div>';
            
            // Populate the modal content
            modalContent.innerHTML = contentHtml;
            
            // Add click event listeners to toggle dropdowns
            document.querySelectorAll('.grade-version-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const chevron = this.querySelector('.chevron-icon');
                    
                    if (targetContent.classList.contains('hidden')) {
                        targetContent.classList.remove('hidden');
                        chevron.style.transform = 'rotate(180deg)';
                    } else {
                        targetContent.classList.add('hidden');
                        chevron.style.transform = 'rotate(0deg)';
                    }
                });
            });
            
            // Close curriculum modal first
            hideModal('curriculum-grade-modal');
            
            // Show the grade details modal
            showModal('grade-modal');
            
            // Hide Update button for minor subjects (since their grades are locked)
            const updateButton = document.getElementById('edit-grade-setup-btn');
            if (updateButton) {
                if (subjectData.subject_type === 'Minor') {
                    updateButton.style.display = 'none';
                    console.log('Update button hidden for minor subject');
                } else {
                    updateButton.style.display = '';
                    console.log('Update button shown for major subject');
                }
            }
            
        } catch (error) {
            console.error('Error fetching subject grade version history:', error);
            // Don't show error modal, just log it
            console.log('Could not fetch subject grade version history. This might be because no grades are set for this subject.');
        }
    };

    // Function to fetch and display all curriculums in history
    const fetchAndPopulateGradeHistory = async () => {
        try {
            console.log('Fetching all curriculums for history...');
            
            // Clear the container first
            gradeHistoryContainer.innerHTML = '';
            
            const allCurriculums = await fetchAPI('curriculums');
            console.log('All curriculums received:', allCurriculums);
            
            if (allCurriculums && allCurriculums.length > 0) {
                console.log('Found', allCurriculums.length, 'curriculums');
                
                // Process each curriculum to ensure minor subjects have grades
                for (const curriculum of allCurriculums) {
                    console.log(`Processing curriculum:`, curriculum);
                    console.log(`Curriculum properties:`, {
                        id: curriculum.id,
                        curriculum: curriculum.curriculum,
                        curriculum_name: curriculum.curriculum_name,
                        program_code: curriculum.program_code,
                        academic_year: curriculum.academic_year,
                        year: curriculum.year
                    });
                    
                    try {
                        // Fetch subjects for this curriculum
                        const subjects = await fetchAPI(`curriculums/${curriculum.id}/subjects`);
                        const minorSubjects = subjects.filter(s => s.subject_type === 'Minor');
                        
                        // No auto-assignment logic - grades must be set manually
                    } catch (error) {
                        console.error(`Error processing curriculum ${curriculum.id}:`, error);
                    }
                    
                    // Add curriculum to history
                    addCurriculumToHistory({
                        id: curriculum.id,
                        curriculum_name: curriculum.curriculum || curriculum.curriculum_name || 'Unknown Curriculum',
                        program_code: curriculum.program_code || 'N/A',
                        academic_year: curriculum.academic_year || curriculum.year || 'N/A',
                        curriculum_type: curriculum.curriculum_type || curriculum.type || null
                    });
                }
            } else {
                console.log('No curriculums found');
                if (!noHistoryMessage) {
                    const message = document.createElement('p');
                    message.id = 'no-history-message';
                    message.className = 'text-gray-500';
                    message.textContent = 'No curriculums available.';
                    gradeHistoryContainer.appendChild(message);
                }
            }
        } catch (error) {
            console.error('Error fetching curriculums for history:', error);
            console.error('Error details:', error.message);
            const noHistoryMessage = document.getElementById('no-history-message');
            if (!noHistoryMessage) {
                const message = document.createElement('p');
                message.id = 'no-history-message';
                message.className = 'text-gray-500';
                message.textContent = 'Error loading curriculums.';
                gradeHistoryContainer.appendChild(message);
            }
        }
    };

    // View mode and filter functionality
    const curriculumSearchInput = document.getElementById('curriculum-search');
    const viewModeButtons = document.querySelectorAll('.view-mode-btn');
    const curriculumTypeFilters = document.getElementById('curriculum-type-filters');
    const subjectTypeFilters = document.getElementById('subject-type-filters');
    const curriculumFilterButtons = document.querySelectorAll('.curriculum-filter-btn');
    const subjectFilterButtons = document.querySelectorAll('.subject-filter-btn');
    let currentViewMode = 'curriculum';
    let currentFilter = 'all';
    
    const filterAndSearchCurriculums = () => {
        const searchTerm = curriculumSearchInput.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.grade-history-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const curriculumName = card.dataset.curriculumName || '';
            const programCode = card.dataset.programCode || '';
            const curriculumType = card.dataset.curriculumType || '';
            
            // Check filter match
            const filterMatch = currentFilter === 'all' || curriculumType === currentFilter;
            
            // Check search match
            const searchMatch = searchTerm === '' || 
                               curriculumName.includes(searchTerm) || 
                               programCode.includes(searchTerm);
            
            if (filterMatch && searchMatch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        const noHistoryMessage = document.getElementById('no-history-message');
        if (visibleCount === 0 && cards.length > 0) {
            if (!noHistoryMessage) {
                const message = document.createElement('p');
                message.id = 'no-history-message';
                message.className = 'text-gray-500';
                message.textContent = 'No curriculums match your search or filter.';
                gradeHistoryContainer.appendChild(message);
            }
        } else if (noHistoryMessage && visibleCount > 0) {
            noHistoryMessage.remove();
        }
    };
    
    // Function to display all subjects
    const displayAllSubjects = async () => {
        try {
            gradeHistoryContainer.innerHTML = '';
            const allSubjects = await fetchAPI('subjects');
            
            if (allSubjects && allSubjects.length > 0) {
                // Check grade status for each subject
                for (const subject of allSubjects) {
                    // Check if subject has grades
                    let hasGrades = false;
                    try {
                        const gradeData = await fetchAPI(`grades/${subject.id}/version-history`);
                        hasGrades = gradeData && gradeData.current_version;
                    } catch (error) {
                        hasGrades = false;
                    }
                    
                    const card = document.createElement('div');
                    
                    // Apply different styles based on grade status
                    if (hasGrades) {
                        card.className = 'subject-history-card p-4 border rounded-lg hover:bg-gray-50 transition-colors duration-200 cursor-pointer';
                    } else {
                        card.className = 'subject-history-card p-4 border rounded-lg transition-colors duration-200 cursor-not-allowed bg-gray-50 opacity-60';
                        card.title = 'This subject has no grades set up yet';
                    }
                    
                    card.dataset.subjectId = subject.id;
                    card.dataset.subjectType = subject.subject_type.toLowerCase();
                    card.dataset.subjectName = subject.subject_name.toLowerCase();
                    card.dataset.subjectCode = (subject.subject_code || '').toLowerCase();
                    card.dataset.hasGrades = hasGrades;
                    
                    const typeBadge = subject.subject_type === 'Major'
                        ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Major</span>'
                        : '<span class="inline-block px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Minor</span>';
                    
                    const gradeStatusBadge = hasGrades
                        ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full ml-1">Graded</span>'
                        : '<span class="inline-block px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-200 rounded-full ml-1">Not Graded</span>';
                    
                    const textColor = hasGrades ? 'text-gray-800' : 'text-gray-500';
                    
                    card.innerHTML = `
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <p class="font-semibold ${textColor} flex-1">${subject.subject_name}</p>
                            <div class="flex gap-1">
                                ${typeBadge}
                                ${gradeStatusBadge}
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">${subject.subject_code} - ${subject.subject_unit} units</p>
                    `;
                    
                    // Add double-click event to show subject grade details (only for graded subjects)
                    if (hasGrades) {
                        card.addEventListener('dblclick', () => showSubjectGradeDetails(subject.id));
                    }
                    
                    gradeHistoryContainer.appendChild(card);
                }
            } else {
                gradeHistoryContainer.innerHTML = '<p class="text-gray-500">No subjects found.</p>';
            }
        } catch (error) {
            console.error('Error fetching subjects:', error);
            gradeHistoryContainer.innerHTML = '<p class="text-gray-500">Error loading subjects.</p>';
        }
    };
    
    // Function to filter subjects
    const filterAndSearchSubjects = () => {
        const searchTerm = curriculumSearchInput.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.subject-history-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const subjectName = card.dataset.subjectName || '';
            const subjectCode = card.dataset.subjectCode || '';
            const subjectType = card.dataset.subjectType || '';
            
            // Check filter match
            const filterMatch = currentFilter === 'all' || subjectType === currentFilter;
            
            // Check search match
            const searchMatch = searchTerm === '' || 
                               subjectName.includes(searchTerm) || 
                               subjectCode.includes(searchTerm);
            
            if (filterMatch && searchMatch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0 && cards.length > 0) {
            if (!document.getElementById('no-results-message')) {
                const message = document.createElement('p');
                message.id = 'no-results-message';
                message.className = 'text-gray-500';
                message.textContent = 'No subjects match your search or filter.';
                gradeHistoryContainer.appendChild(message);
            }
        } else {
            const noResultsMsg = document.getElementById('no-results-message');
            if (noResultsMsg) noResultsMsg.remove();
        }
    };
    
    // View mode switching
    viewModeButtons.forEach(button => {
        button.addEventListener('click', async () => {
            // Update active button styling
            viewModeButtons.forEach(btn => {
                btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                btn.classList.add('text-gray-600', 'hover:text-gray-800');
            });
            button.classList.remove('text-gray-600', 'hover:text-gray-800');
            button.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
            
            // Update current view mode
            currentViewMode = button.dataset.view;
            currentFilter = 'all'; // Reset filter
            curriculumSearchInput.value = ''; // Clear search
            
            if (currentViewMode === 'curriculum') {
                // Show curriculum filters, hide subject filters
                curriculumTypeFilters.classList.remove('hidden');
                subjectTypeFilters.classList.add('hidden');
                
                // Reset curriculum filter buttons
                curriculumFilterButtons.forEach(btn => {
                    btn.classList.remove('bg-indigo-600', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                });
                document.getElementById('filter-all-btn').classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                document.getElementById('filter-all-btn').classList.add('bg-indigo-600', 'text-white');
                
                // Display curriculums
                await fetchAndPopulateGradeHistory();
            } else {
                // Show subject filters, hide curriculum filters
                curriculumTypeFilters.classList.add('hidden');
                subjectTypeFilters.classList.remove('hidden');
                
                // Reset subject filter buttons
                subjectFilterButtons.forEach(btn => {
                    btn.classList.remove('bg-indigo-600', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                });
                document.getElementById('subject-filter-all-btn').classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                document.getElementById('subject-filter-all-btn').classList.add('bg-indigo-600', 'text-white');
                
                // Display all subjects
                await displayAllSubjects();
            }
        });
    });
    
    // Search input event listener
    curriculumSearchInput.addEventListener('input', () => {
        if (currentViewMode === 'curriculum') {
            filterAndSearchCurriculums();
        } else {
            filterAndSearchSubjects();
        }
    });
    
    // Curriculum filter button event listeners
    curriculumFilterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Update active button styling
            curriculumFilterButtons.forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            });
            button.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            button.classList.add('bg-indigo-600', 'text-white');
            
            // Update current filter
            currentFilter = button.dataset.filter;
            
            // Apply filter
            filterAndSearchCurriculums();
        });
    });
    
    // Subject filter button event listeners
    subjectFilterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Update active button styling
            subjectFilterButtons.forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            });
            button.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            button.classList.add('bg-indigo-600', 'text-white');
            
            // Update current filter
            currentFilter = button.dataset.filter;
            
            // Apply filter
            filterAndSearchSubjects();
        });
    });

    // Custom dropdown toggle
    const dropdownBtn = document.getElementById('major-subject-dropdown-btn');
    const dropdownMenu = document.getElementById('major-subject-dropdown-menu');
    
    dropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
    
    // Initialize global subject-based workflow
    fetchAllSubjects(); // Fetch all subjects globally
    fetchAndPopulateGradeHistory(); // Keep curriculum history on the right side
    loadGradeDataToDOM({});
    toggleGradeComponents(true);
    
    // Event Listeners for course type selection
    minorCoursesBtn.addEventListener('click', () => handleCourseTypeSelection('minor'));
    majorCoursesBtn.addEventListener('click', () => handleCourseTypeSelection('major'));
    majorSubjectSelect.addEventListener('change', handleMajorSubjectSelection);
    closeCurriculumModalBtn.addEventListener('click', () => hideModal('curriculum-grade-modal'));
    curriculumGradeModal.addEventListener('click', (e) => { 
        if (e.target.id === 'curriculum-grade-modal') hideModal('curriculum-grade-modal'); 
    });
});
</script>


@endsection