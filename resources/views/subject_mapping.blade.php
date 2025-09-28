@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-hidden bg-gray-100 p-6 flex flex-col">
    <div class="bg-white rounded-2xl shadow-xl p-8 flex-1 flex flex-col">
        
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-800"><i class="fas fa-sitemap mr-2"></i>Subject Mapping</h1>
                <p class="text-sm text-gray-500 mt-1"><i class="fas fa-hand-rock mr-2"></i>Drag and drop subjects to build the curriculum.</p>
            </div>
            <button id="createSubjectButton" class="w-full sm:w-auto flex items-center justify-center space-x-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add New Subject</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-0">
            
            <div class="lg:col-span-1 bg-gray-50 border border-gray-200 rounded-xl p-6 flex flex-col h-[calc(100vh-200px)]">
                <div class="pb-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800"><i class="fas fa-book mr-2"></i>Available Subjects</h2>
                    <p class="text-sm text-gray-500"><i class="fas fa-search mr-2"></i>Find and select subjects to add to the curriculum.</p>
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
                    </select>
                </div>

                <div id="availableSubjects" class="flex-1 overflow-y-auto pr-2 -mr-2 space-y-2">
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

    {{-- Add/Edit Subject Modal --}}
    <div id="addSubjectModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 transition-opacity duration-300 ease-out hidden">
        <div class="flex items-start justify-center min-h-screen p-4 pt-8">
            <form id="subjectForm" class="relative bg-white w-11/12 rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col" id="modal-subject-panel">
                <div class="flex justify-between items-center p-5 border-b border-gray-200">
                    <h2 id="modal-title" class="text-xl font-bold text-gray-800">Create New Subject</h2>
                    <button id="closeSubjectModalButton" type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- BODY (Scrollable) --}}
                <div class="p-6 h-[80vh] overflow-y-auto space-y-6">
                    <input type="hidden" id="subjectId" name="subjectId">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="subjectName" class="block text-sm font-semibold text-gray-700 mb-1">Subject Name</label>
                            <input type="text" id="subjectName" name="subjectName" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                        </div>
                        <div class="md:col-span-1">
                            <label for="subjectCode" class="block text-sm font-semibold text-gray-700 mb-1">Subject Code</label>
                            <input type="text" id="subjectCode" name="subjectCode" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"  required>
                        </div>
                        <div class="md:col-span-1">
                            <label for="subjectType" class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                            <select id="subjectType" name="subjectType" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Major">Major</option>
                                <option value="Minor">Minor</option>
                                <option value="Elective">Elective</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="subjectUnit" class="block text-sm font-semibold text-gray-700 mb-1">Unit</label>
                        <input type="number" id="subjectUnit" name="subjectUnit" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 pt-4 border-t border-gray-200">Weekly Topics</h3>
                            <button type="button" id="generateTopicsButton" class="px-4 py-2 text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors">
                                AI Topic Generator
                            </button>
                        </div>
                         <div id="topicSpinner" class="hidden text-center py-4">
                            <div role="status">
                                <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Generating weekly topics, please wait...</p>
                        </div>
                        @for ($i = 1; $i <= 15; $i++)
                        <div class="border border-gray-200 rounded-lg">
                            <button type="button" class="week-toggle-btn w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition-colors" data-week="{{ $i }}">
                                <span class="font-sans font-semibold text-gray-700">Week {{ $i }}</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="week-content hidden p-5 border-t border-gray-200 bg-white">
                                <textarea id="week-{{ $i }}-lessons" name="week-{{ $i }}-lessons" class="w-full h-24 p-3 border border-gray-300 rounded-lg resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors font-mono text-sm" style="min-height: 5rem;" placeholder="Topic for Week {{ $i }}..."></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="button" class="generate-lesson-btn px-3 py-1.5 text-xs font-semibold text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors" data-week="{{ $i }}">
                                        Generate Detailed Lesson
                                    </button>
                                </div>
                                <div id="lesson-spinner-{{ $i }}" class="hidden text-center py-2">
                                    <p class="text-xs text-gray-500">Generating detailed lesson...</p>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="flex justify-between items-center p-5 mt-auto border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <div id="createdTimestamp" class="text-sm text-gray-500 self-center"></div>
                    <div class="flex items-center gap-4">
                        <button type="button" id="cancelSubjectModalButton" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 rounded-lg text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Create Subject
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Subject Details Modal --}}
    <div id="subjectDetailsModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 transition-opacity duration-300 ease-out hidden">
        <div class="flex items-start justify-center min-h-screen p-4 pt-8">
            <div class="relative bg-white w-11/12 rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col" id="modal-details-panel">
                <div class="flex justify-between items-center p-5 border-b border-gray-200">
                    <h2 id="detailsSubjectName" class="text-xl font-bold text-gray-800"></h2>
                    <button id="closeDetailsModalButton" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 h-[80vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Subject Code</p>
                            <p id="detailsSubjectCode" class="text-base font-semibold text-gray-800"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Type</p>
                            <p id="detailsSubjectType" class="text-base font-semibold text-gray-800"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Unit</p>
                            <p id="detailsSubjectUnit" class="text-base font-semibold text-gray-800"></p>
                        </div>
                    </div>

                    <div class="space-y-2" id="detailsLessonsContainer">
                        <h3 class="text-xl font-bold text-gray-800 pt-4 border-t border-gray-200">Lessons</h3>
                    </div>
                </div>
                
                {{-- FOOTER --}}
                <div class="flex justify-between items-center p-5 mt-auto border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <div id="detailsCreatedAtContainer">
                        <p class="text-sm font-medium text-gray-500">Created At</p>
                        <p id="detailsCreatedAt" class="text-base font-semibold text-gray-800"></p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button id="importSubjectDetailsButton" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Import
                        </button>
                        <button id="editSubjectDetailsButton" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                            Edit
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
                <h3 class="text-lg font-semibold text-gray-800">Import Subject</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to import this subject as a PDF file?</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button id="cancelImportButton" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
                    <button id="confirmImportButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">Yes, Import</button>
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

    {{-- ... (your other existing modals like addSubjectModal, subjectDetailsModal, etc.) ... --}}

    {{-- Save Mapping Confirmation Modal --}}
    <div id="saveMappingModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
                {{-- ICON ADDED --}}
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
                {{-- ICON ADDED --}}
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
                {{-- ICON ADDED --}}
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
        
        // Existing elements...
        const createSubjectButton = document.getElementById('createSubjectButton');
        const addSubjectModal = document.getElementById('addSubjectModal');
        const closeSubjectModalButton = document.getElementById('closeSubjectModalButton');
        const cancelSubjectModalButton = document.getElementById('cancelSubjectModalButton');
        const subjectForm = document.getElementById('subjectForm');
        const availableSubjectsContainer = document.getElementById('availableSubjects');
        const generateTopicsButton = document.getElementById('generateTopicsButton');
        const topicSpinner = document.getElementById('topicSpinner');
        const createdTimestamp = document.getElementById('createdTimestamp');

        // Modals for creating subjects
        const createConfirmationModal = document.getElementById('createConfirmationModal');
        const cancelCreateBtn = document.getElementById('cancelCreateBtn');
        const confirmCreateBtn = document.getElementById('confirmCreateBtn');
        const gradeSetupConfirmationModal = document.getElementById('gradeSetupConfirmationModal');
        const declineGradeSetupBtn = document.getElementById('declineGradeSetupBtn');
        const confirmGradeSetupBtn = document.getElementById('confirmGradeSetupBtn');
        const creationSuccessModal = document.getElementById('creationSuccessModal');
        const successOkBtn = document.getElementById('successOkBtn');
        let createdSubjectData = null;

        // *** START: NEW MODAL ELEMENTS FOR SAVING MAPPING ***
        const saveCurriculumButton = document.getElementById('saveCurriculumButton');
        const saveMappingModal = document.getElementById('saveMappingModal');
        const cancelSaveMapping = document.getElementById('cancelSaveMapping');
        const confirmSaveMapping = document.getElementById('confirmSaveMapping');
        const proceedToPrerequisitesModal = document.getElementById('proceedToPrerequisitesModal');
        const declineProceedToPrerequisites = document.getElementById('declineProceedToPrerequisites');
        const confirmProceedToPrerequisites = document.getElementById('confirmProceedToPrerequisites');
        const mappingSuccessModal = document.getElementById('mappingSuccessModal');
        const closeMappingSuccessModal = document.getElementById('closeMappingSuccessModal');
        // *** END: NEW MODAL ELEMENTS FOR SAVING MAPPING ***

        const showSubjectModal = (subjectToEdit = null) => {
            subjectForm.reset();
            const modalTitle = document.getElementById('modal-title');
            const submitButton = subjectForm.querySelector('button[type="submit"]');
            const subjectIdInput = document.getElementById('subjectId');
            
            document.querySelectorAll('.week-content').forEach(content => content.classList.add('hidden'));
            document.querySelectorAll('.week-toggle-btn svg').forEach(svg => svg.classList.remove('rotate-180'));

            if (subjectToEdit) {
                modalTitle.textContent = 'Edit Subject';
                submitButton.textContent = 'Update Subject';
                subjectIdInput.value = subjectToEdit.id;
                document.getElementById('subjectName').value = subjectToEdit.subject_name;
                document.getElementById('subjectCode').value = subjectToEdit.subject_code;
                document.getElementById('subjectType').value = subjectToEdit.subject_type;
                document.getElementById('subjectUnit').value = subjectToEdit.subject_unit;
                createdTimestamp.textContent = ''; 

                for (let i = 1; i <= 15; i++) {
                    const weekKey = `Week ${i}`;
                    const weekTextarea = document.getElementById(`week-${i}-lessons`);
                    if (subjectToEdit.lessons && subjectToEdit.lessons[weekKey]) {
                        weekTextarea.value = subjectToEdit.lessons[weekKey];
                    } else {
                        weekTextarea.value = '';
                    }
                }
            } else {
                modalTitle.textContent = 'Create New Subject';
                submitButton.textContent = 'Create Subject';
                subjectIdInput.value = '';
                const now = new Date();
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true };
                createdTimestamp.textContent = `Created: ${now.toLocaleDateString('en-US', options)}`;
            }

            addSubjectModal.classList.remove('hidden');
            setTimeout(() => {
                addSubjectModal.classList.remove('opacity-0');
                subjectForm.classList.remove('opacity-0', 'scale-95');
            }, 10);
        };

        const hideSubjectModal = () => {
            addSubjectModal.classList.add('opacity-0');
            subjectForm.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                addSubjectModal.classList.add('hidden');
                subjectForm.reset();
            }, 300);
        };

        createSubjectButton.addEventListener('click', () => showSubjectModal());
        closeSubjectModalButton.addEventListener('click', hideSubjectModal);
        cancelSubjectModalButton.addEventListener('click', hideSubjectModal);
        addSubjectModal.addEventListener('click', (e) => {
            if (e.target.id === 'addSubjectModal') {
                hideSubjectModal();
            }
        });
        
        const hideCreateConfirmation = () => createConfirmationModal.classList.add('hidden');
        const showCreateConfirmation = () => createConfirmationModal.classList.remove('hidden');

        const hideGradeSetupConfirmation = () => gradeSetupConfirmationModal.classList.add('hidden');
        const showGradeSetupConfirmation = () => gradeSetupConfirmationModal.classList.remove('hidden');

        const hideSuccess = () => creationSuccessModal.classList.add('hidden');
        const showSuccess = () => creationSuccessModal.classList.remove('hidden');

        subjectForm.addEventListener('submit', (e) => {
            e.preventDefault();
            showCreateConfirmation();
        });

        cancelCreateBtn.addEventListener('click', hideCreateConfirmation);

        confirmCreateBtn.addEventListener('click', () => {
            hideCreateConfirmation();

            const subjectId = document.getElementById('subjectId').value;
            const isUpdating = !!subjectId;

            const subjectData = {
                subjectName: document.getElementById('subjectName').value,
                subjectCode: document.getElementById('subjectCode').value,
                subjectType: document.getElementById('subjectType').value,
                subjectUnit: document.getElementById('subjectUnit').value,
                lessons: {}
            };

            for (let i = 1; i <= 15; i++) {
                const weekLessons = document.getElementById(`week-${i}-lessons`).value;
                if (weekLessons.trim() !== '') {
                    subjectData.lessons[`Week ${i}`] = weekLessons.trim();
                }
            }

            const url = isUpdating ? `/api/subjects/${subjectId}` : '/api/subjects';
            const method = isUpdating ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify(subjectData)
            })
            .then(async response => {
                if (!response.ok) {
                    const errorData = await response.json();
                    throw errorData;
                }
                return response.json();
            })
            .then(data => {
                hideSubjectModal();
                createdSubjectData = data;
                showGradeSetupConfirmation();
            })
            .catch(error => {
                console.error('Error submitting subject:', error);
                let errorMessage = 'An unknown error occurred. Please try again.';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).map(messages => messages.join('\n')).join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                alert('Could not save subject:\n\n' + errorMessage);
            });
        });

        confirmGradeSetupBtn.addEventListener('click', () => {
            hideGradeSetupConfirmation();

            // Get the subject details from the data you received after creation
            const subject = createdSubjectData.subject;
            const subjectId = subject.id;
            const subjectName = `${subject.subject_name} (${subject.subject_code})`;

            // Redirect to the correct URL with the new parameters
            window.location.href = `/grade-setup?new_subject_id=${subjectId}&new_subject_name=${encodeURIComponent(subjectName)}`;
        });

        declineGradeSetupBtn.addEventListener('click', () => {
            hideGradeSetupConfirmation();
            showSuccess();
        });
        successOkBtn.addEventListener('click', () => {
            hideSuccess();
            location.reload();
        });

        document.querySelectorAll('.week-toggle-btn').forEach(button => {
            button.addEventListener('click', () => {
                const parent = button.parentElement;
                const contentDiv = parent.querySelector('.week-content');
                const svg = button.querySelector('svg');
                contentDiv.classList.toggle('hidden');
                svg.classList.toggle('rotate-180');
            });
        });

        generateTopicsButton.addEventListener('click', () => {
            const subjectName = document.getElementById('subjectName').value;
            if (!subjectName) {
                alert('Please enter a Subject Name to generate topics.');
                return;
            }

            topicSpinner.classList.remove('hidden');

            fetch('/api/generate-lesson-topics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ subjectName })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => { throw errorData; });
                }
                return response.json();
            })
            .then(data => {
                for (let i = 1; i <= 15; i++) {
                    const weekKey = `Week ${i}`;
                    const weekTextarea = document.getElementById(`week-${i}-lessons`);
                    if (data[weekKey]) {
                        weekTextarea.value = data[weekKey];
                    }
                }
            })
            .catch(error => {
                console.error('Error generating topics:', error);
                let errorMessage = 'An error occurred while generating topics. Check the browser console for more details.';
                if (error && error.message) {
                    errorMessage = `Error: ${error.message}`;
                }
                alert(errorMessage);
            })
            .finally(() => {
                topicSpinner.classList.add('hidden');
            });
        });

        document.querySelectorAll('.generate-lesson-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const week = e.target.dataset.week;
                const weekTextarea = document.getElementById(`week-${week}-lessons`);
                const topic = weekTextarea.value;
                const subjectName = document.getElementById('subjectName').value;
                const subjectUnit = document.getElementById('subjectUnit').value;

                if (!topic) {
                    alert(`Please enter a topic for Week ${week} or generate topics first.`);
                    return;
                }
                 if (!subjectName) {
                    alert('Please ensure the Subject Name is filled out.');
                    return;
                }

                const lessonSpinner = document.getElementById(`lesson-spinner-${week}`);
                lessonSpinner.classList.remove('hidden');

                fetch('/api/generate-lesson-plan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ subjectName, topic, subjectUnit })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => { throw errorData; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.lessonPlan) {
                        weekTextarea.value = data.lessonPlan;
                    } else {
                        weekTextarea.value = 'Could not generate a detailed lesson plan. Please try again.';
                    }
                })
                .catch(error => {
                    console.error('Error generating detailed lesson:', error);
                    let errorMessage = 'An error occurred. Check console for details.';
                    if (error && error.message) {
                        errorMessage = `Error: ${error.message}`;
                    }
                    alert(errorMessage);
                })
                .finally(() => {
                    lessonSpinner.classList.add('hidden');
                });
            });
        });

        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');
        const filterSubjects = () => {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = typeFilter.value;
            const subjectCards = availableSubjectsContainer.querySelectorAll('.subject-card');

            subjectCards.forEach(card => {
                const subjectData = JSON.parse(card.dataset.subjectData);
                const searchMatch = subjectData.subject_name.toLowerCase().includes(searchTerm) || subjectData.subject_code.toLowerCase().includes(searchTerm);
                const typeMatch = (selectedType === 'All Types' || subjectData.subject_type === selectedType);
                card.style.display = (searchMatch && typeMatch) ? 'flex' : 'none';
            });
        };
        searchInput.addEventListener('input', filterSubjects);
        typeFilter.addEventListener('change', filterSubjects);

        const subjectDetailsModal = document.getElementById('subjectDetailsModal');
        const closeDetailsModalButton = document.getElementById('closeDetailsModalButton');
        const modalDetailsPanel = document.getElementById('modal-details-panel');
        const editSubjectDetailsButton = document.getElementById('editSubjectDetailsButton');
        const importSubjectDetailsButton = document.getElementById('importSubjectDetailsButton');
        const importConfirmationModal = document.getElementById('importConfirmationModal');
        const importModalPanel = document.getElementById('import-modal-panel');
        const cancelImportButton = document.getElementById('cancelImportButton');
        const confirmImportButton = document.getElementById('confirmImportButton');
        let subjectToImport = null;

        importSubjectDetailsButton.addEventListener('click', () => {
            subjectToImport = JSON.parse(importSubjectDetailsButton.dataset.subjectData);
            importConfirmationModal.classList.remove('hidden');
            setTimeout(() => {
                importConfirmationModal.classList.remove('opacity-0');
                importModalPanel.classList.remove('opacity-0', 'scale-95');
            }, 10);
        });

        const hideImportConfirmationModal = () => {
            importConfirmationModal.classList.add('opacity-0');
            importModalPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                importConfirmationModal.classList.add('hidden');
                subjectToImport = null;
            }, 300);
        };
        cancelImportButton.addEventListener('click', hideImportConfirmationModal);
        
        confirmImportButton.addEventListener('click', () => {
            if (subjectToImport) {
                generatePDF(subjectToImport);
            }
            hideImportConfirmationModal();
        });

        const generatePDF = (subject) => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(20);
            doc.setFont("helvetica", "bold");
            doc.text("Subject Syllabus", 105, 20, null, null, "center");

            const detailsData = [
                ['Subject Name:', subject.subject_name],
                ['Subject Code:', subject.subject_code],
                ['Subject Type:', subject.subject_type],
                ['Units:', subject.subject_unit.toString()]
            ];
            
            doc.autoTable({
                startY: 30,
                head: [['Attribute', 'Value']],
                body: detailsData,
                theme: 'grid',
                headStyles: { fillColor: [22, 160, 133] },
                styles: { fontSize: 12 },
            });

            const lessonsData = [];
            if (subject.lessons) {
                for (let i = 1; i <= 15; i++) {
                    const week = `Week ${i}`;
                    lessonsData.push([week, subject.lessons[week] || 'N/A']);
                }
            }
            
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 15,
                head: [['Week', 'Lesson / Topics']],
                body: lessonsData,
                theme: 'grid',
                headStyles: { fillColor: [44, 62, 80] },
                styles: { fontSize: 10, cellPadding: 3 },
                columnStyles: {
                    0: { cellWidth: 25 },
                    1: { cellWidth: 'auto' }
                }
            });

            doc.save(`${subject.subject_code}_${subject.subject_name}_Syllabus.pdf`);
        };

        const showDetailsModal = (data) => {
            document.getElementById('detailsSubjectName').textContent = `${data.subject_name} (${data.subject_code})`;
            document.getElementById('detailsSubjectCode').textContent = data.subject_code;
            document.getElementById('detailsSubjectType').textContent = data.subject_type;
            document.getElementById('detailsSubjectUnit').textContent = data.subject_unit;
            editSubjectDetailsButton.dataset.subjectData = JSON.stringify(data);
            importSubjectDetailsButton.dataset.subjectData = JSON.stringify(data);

            const createdAtContainer = document.getElementById('detailsCreatedAtContainer');
            if (data.created_at) {
                const date = new Date(data.created_at);
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
                document.getElementById('detailsCreatedAt').textContent = date.toLocaleDateString('en-US', options);
                createdAtContainer.style.display = 'block';
            } else {
                createdAtContainer.style.display = 'none';
            }
            
            const lessonsContainer = document.getElementById('detailsLessonsContainer');
            lessonsContainer.innerHTML = '<h3 class="text-xl font-bold text-gray-800 pt-4 border-t border-gray-200">Lessons</h3>';

            if (data.lessons && Object.keys(data.lessons).length > 0) {
                const sortedWeeks = Object.keys(data.lessons).sort((a, b) => parseInt(a.replace('Week ', '')) - parseInt(b.replace('Week ', '')));

                sortedWeeks.forEach(week => {
                    const lessonContent = data.lessons[week];
                    const accordionItem = document.createElement('div');
                    accordionItem.className = 'border border-gray-200 rounded-lg';

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition-colors';
                    button.innerHTML = `
                        <span class="font-semibold text-gray-700">${week}</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    `;

                    const contentDiv = document.createElement('div');
                    contentDiv.className = 'p-5 border-t border-gray-200 hidden bg-white';
                    
                    let formattedContent = lessonContent.replace(/^(Learning Objectives|Detailed Lesson Content|Activities|Assessment|Total Duration):/gm, '<strong class="block mt-3 mb-1 text-gray-800">$1:</strong>');
                    contentDiv.innerHTML = `<div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line">${formattedContent}</div>`;
                    
                    button.addEventListener('click', () => {
                        contentDiv.classList.toggle('hidden');
                        button.querySelector('svg').classList.toggle('rotate-180');
                    });

                    accordionItem.appendChild(button);
                    accordionItem.appendChild(contentDiv);
                    lessonsContainer.appendChild(accordionItem);
                });

            } else {
                lessonsContainer.innerHTML += '<p class="text-sm text-gray-500 mt-2">No lessons recorded for this subject.</p>';
            }

            subjectDetailsModal.classList.remove('hidden');
            setTimeout(() => {
                subjectDetailsModal.classList.remove('opacity-0');
                modalDetailsPanel.classList.remove('opacity-0', 'scale-95');
            }, 10);
        };

        const hideDetailsModal = () => {
            subjectDetailsModal.classList.add('opacity-0');
            modalDetailsPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => subjectDetailsModal.classList.add('hidden'), 300);
        };
        closeDetailsModalButton.addEventListener('click', hideDetailsModal);
        subjectDetailsModal.addEventListener('click', (e) => { if (e.target.id === 'subjectDetailsModal') hideDetailsModal(); });

        editSubjectDetailsButton.addEventListener('click', () => {
            const subjectData = JSON.parse(editSubjectDetailsButton.dataset.subjectData);
            hideDetailsModal();
            setTimeout(() => {
                showSubjectModal(subjectData);
            }, 300);
        });

        let draggedItem = null;
        let subjectTagToRemove = null;
        
        const removeConfirmationModal = document.getElementById('removeConfirmationModal');
        const removeModalPanel = document.getElementById('remove-modal-panel');
        const cancelRemoveButton = document.getElementById('cancelRemoveButton');
        const confirmRemoveButton = document.getElementById('confirmRemoveButton');

        const showRemoveConfirmationModal = () => {
            removeConfirmationModal.classList.remove('hidden');
            setTimeout(() => {
                removeConfirmationModal.classList.remove('opacity-0');
                removeModalPanel.classList.remove('opacity-0', 'scale-95');
            }, 10);
        };

        const hideRemoveConfirmationModal = () => {
            removeConfirmationModal.classList.add('opacity-0');
            removeModalPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                removeConfirmationModal.classList.add('hidden');
                subjectTagToRemove = null;
            }, 300);
        };

        cancelRemoveButton.addEventListener('click', hideRemoveConfirmationModal);
        
        confirmRemoveButton.addEventListener('click', async () => {
            if (!subjectTagToRemove) return;

            const subjectData = JSON.parse(subjectTagToRemove.dataset.subjectData);
            const curriculumId = curriculumSelector.value;
            const dropzone = subjectTagToRemove.closest('.semester-dropzone');
            const year = dropzone.dataset.year;
            const semester = dropzone.dataset.semester;

            try {
                const response = await fetch('/api/curriculum/remove-subject', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        curriculumId: curriculumId,
                        subjectId: subjectData.id,
                        year: year,
                        semester: semester
                    })
                });

                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message || 'Failed to remove the subject.');
                }

                subjectTagToRemove.remove();

                const originalSubjectCard = document.getElementById(`subject-${subjectData.subject_code.toLowerCase()}`);
                if (originalSubjectCard) {
                    originalSubjectCard.classList.add('opacity-50', 'cursor-not-allowed', 'removed-subject-card');
                    originalSubjectCard.setAttribute('draggable', 'false');
                    originalSubjectCard.dataset.status = 'removed';
                    
                    const statusIndicator = originalSubjectCard.querySelector('div:last-child');
                    if (statusIndicator) {
                        statusIndicator.innerHTML = `<span class="text-xs font-semibold text-red-500 bg-red-100 px-2 py-1 rounded-full">Removed</span>`;
                    }
                }

                updateUnitTotals();
                alert('Subject removed successfully and logged to history!');

            } catch (error) {
                console.error('Error removing subject:', error);
                alert('Error: ' + error.message);
            } finally {
                hideRemoveConfirmationModal();
            }
        });

        const createSubjectCard = (subject, isMapped = false, status = '') => {
            const newSubjectCard = document.createElement('div');
            newSubjectCard.id = `subject-${subject.subject_code.toLowerCase()}`;
            newSubjectCard.dataset.subjectData = JSON.stringify(subject);
            newSubjectCard.dataset.status = status;

            let cardClasses = 'subject-card flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg transition-all';
            let statusHTML = '<i class="fas fa-bars text-gray-400"></i>';
            let isDraggable = true;

            if (isMapped) {
                cardClasses += ' opacity-50 cursor-not-allowed mapped-subject-card';
                statusHTML = '<span class="text-xs font-semibold text-blue-500 bg-blue-100 px-2 py-1 rounded-full">In Use</span>';
                isDraggable = false;
            } else if (status === 'removed') {
                cardClasses += ' opacity-50 cursor-not-allowed removed-subject-card';
                statusHTML = '<span class="text-xs font-semibold text-red-500 bg-red-100 px-2 py-1 rounded-full">Removed</span>';
                isDraggable = false;
            } else {
                cardClasses += ' hover:bg-blue-50 cursor-grab';
            }
            
            newSubjectCard.className = cardClasses;
            newSubjectCard.setAttribute('draggable', isDraggable);
            
            newSubjectCard.innerHTML = `<div><p class="font-semibold text-gray-700">${subject.subject_name}</p><p class="text-xs text-gray-500">${subject.subject_code}</p><p class="text-xs text-gray-500">Unit: ${subject.subject_unit}</p></div><div class="flex items-center space-x-2">${statusHTML}</div>`;
            
            if(isDraggable) {
                addDraggableEvents(newSubjectCard);
            }
            addDoubleClickEvents(newSubjectCard);
            
            return newSubjectCard;
        };

        const addDraggableEvents = (item) => {
            item.addEventListener('dragstart', (e) => {
                if (!isEditing || item.dataset.status === 'removed') {
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
        const addDoubleClickEvents = (item) => {
            item.addEventListener('dblclick', () => showDetailsModal(JSON.parse(item.dataset.subjectData)));
        };

        const createSubjectTag = (subjectData, isEditing = false) => {
            const subjectTag = document.createElement('div');
            subjectTag.className = 'subject-tag bg-white border border-gray-300 shadow-sm rounded-lg p-2 flex items-center justify-between w-full transition-all hover:shadow-md hover:border-blue-500';
            subjectTag.setAttribute('draggable', isEditing);
            subjectTag.dataset.subjectData = JSON.stringify(subjectData);

            let typeColorClass = 'bg-gray-400';
            switch (subjectData.subject_type) {
                case 'Major': typeColorClass = 'bg-blue-500'; break;
                case 'Minor': typeColorClass = 'bg-green-500'; break;
                case 'Elective': typeColorClass = 'bg-yellow-500'; break;
            }

            subjectTag.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full ${typeColorClass}"></span>
                    <div>
                        <p class="font-bold text-sm text-gray-800">${subjectData.subject_code}</p>
                        <p class="text-xs text-gray-600">${subjectData.subject_name}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-700">${subjectData.subject_unit} units</span>
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
        
        let isEditing = false;
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
                    draggedItem.classList.add('opacity-50', 'cursor-not-allowed', 'mapped-subject-card');
                    draggedItem.setAttribute('draggable', 'false');
                } else if (draggedItem.classList.contains('subject-tag')) {
                    draggedItem.parentNode.removeChild(draggedItem);
                    const subjectTag = createSubjectTag(droppedSubjectData, isEditing);
                    targetContainer.appendChild(subjectTag);
                }
                updateUnitTotals();
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
                        originalSubjectCard.classList.remove('opacity-50', 'cursor-not-allowed', 'mapped-subject-card');
                        originalSubjectCard.setAttribute('draggable', 'true');
                    }
                    draggedItem.remove();
                    updateUnitTotals();
                }
            });
        };
        
        document.getElementById('editCurriculumButton').addEventListener('click', () => toggleEditMode(!isEditing));
        
        // *** START: UPDATED SAVE CURRICULUM LOGIC ***
        saveCurriculumButton.addEventListener('click', () => {
            if (!curriculumSelector.value) {
                alert('Please select a curriculum to save.');
                return;
            }
            saveMappingModal.classList.remove('hidden');
        });

        cancelSaveMapping.addEventListener('click', () => {
            saveMappingModal.classList.add('hidden');
        });

        confirmSaveMapping.addEventListener('click', () => {
            saveMappingModal.classList.add('hidden');

            const curriculumId = curriculumSelector.value;
            const curriculumData = [];
            document.querySelectorAll('#curriculumOverview .semester-dropzone').forEach(dropzone => {
                const year = parseInt(dropzone.dataset.year, 10);
                const semester = parseInt(dropzone.dataset.semester, 10);
                const subjects = [];
                dropzone.querySelectorAll('.subject-tag').forEach(tag => {
                    const sData = JSON.parse(tag.dataset.subjectData);
                    subjects.push({
                        subjectCode: sData.subject_code,
                        subjectName: sData.subject_name,
                        subjectType: sData.subject_type,
                        subjectUnit: sData.subject_unit,
                        lessons: sData.lessons || {}
                    });
                });
                if (subjects.length > 0) {
                    curriculumData.push({ year, semester, subjects });
                }
            });

            fetch('/api/curriculums/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ curriculumId, curriculumData })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to save the curriculum mapping.');
                }
                return response.json();
            })
            .then(data => {
                // On success, show the next modal instead of alerting
                proceedToPrerequisitesModal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Save Error:', error);
                alert('An error occurred while saving the curriculum.');
            });
        });

        declineProceedToPrerequisites.addEventListener('click', () => {
            proceedToPrerequisitesModal.classList.add('hidden');
            mappingSuccessModal.classList.remove('hidden');
        });

        confirmProceedToPrerequisites.addEventListener('click', () => {
            const curriculumId = curriculumSelector.value;
            window.location.href = `/pre_requisite?curriculumId=${curriculumId}`;
        });

        closeMappingSuccessModal.addEventListener('click', () => {
            mappingSuccessModal.classList.add('hidden');
        });
        // *** END: UPDATED SAVE CURRICULUM LOGIC ***

        const curriculumSelector = document.getElementById('curriculumSelector');
        const curriculumOverview = document.getElementById('curriculumOverview');

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
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data || !data.curriculum || !data.allSubjects) {
                        throw new Error('Invalid data structure from server.');
                    }
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

        fetchCurriculums();
        fetchAllSubjects();
    });
</script>
@endsection