@extends('layouts.app')

@section('content')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 md:p-8">
        <div>
            
            {{-- Main Header with New Icon --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
                <div class="flex items-start gap-4">
                    {{-- Icon for Curriculum Builder --}}
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="flex-grow flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Curriculum Builder</h1>
                            <p class="text-sm text-slate-500 mt-1">Design and manage your academic curriculums.</p>
                        </div>
                        <button id="addCurriculumButton" class="w-full mt-4 sm:mt-0 sm:w-auto flex items-center justify-center space-x-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            <span>Add Curriculum</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Curriculums Section --}}
            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-xl font-bold text-slate-700">Existing Curriculums</h2>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        {{-- Version Filter --}}
                        <div class="relative w-full sm:w-48">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 01.628.74v2.288a2.25 2.25 0 01-.659 1.59l-4.682 4.683a2.25 2.25 0 00-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 018 18.25v-5.757a2.25 2.25 0 00-.659-1.591L2.659 6.22A2.25 2.25 0 012 4.629V2.34a.75.75 0 01.628-.74z" clip-rule="evenodd" />
                            </svg>
                            <select id="version-filter" class="w-full appearance-none pl-10 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                <option value="new" selected>New</option>
                                <option value="old">Old</option>
                            </select>
                            <svg class="w-5 h-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                        </div>
                        {{-- Search Bar --}}
                        <div class="relative w-full sm:w-72">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                            <input type="text" id="search-bar" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-10">
                    <div>
                        <h3 class="flex items-center gap-3 text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Senior High</span>
                        </h3>
                        <div id="senior-high-curriculums" class="space-y-4 pt-2">
                            <p class="text-slate-500 text-sm py-4">No Senior High curriculums found.</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="flex items-center gap-3 text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                               <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222 4 2.222V20M1 12v7a2 2 0 002 2h18a2 2 0 002-2v-7" />
                            </svg>
                           <span>College</span>
                        </h3>
                        <div id="college-curriculums" class="space-y-4 pt-2">
                           <p class="text-slate-500 text-sm py-4">No College curriculums found.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal for adding/editing a curriculum --}}
            <div id="addCurriculumModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="modal-panel">
                        <button id="closeModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        
                        <div class="text-center mb-8">
                            <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-16 w-auto mb-4">
                            <h2 id="modal-title" class="text-2xl font-bold text-slate-800">Create New Curriculum</h2>
                            <p class="text-sm text-slate-500 mt-1">Fill in the details below to add a new curriculum.</p>
                        </div>

                        <form id="curriculumForm" class="space-y-6">
                            @csrf
                            <input type="hidden" id="curriculumId" name="curriculumId">
                            
                            <div>
                                <label for="curriculum" class="block text-sm font-medium text-slate-700 mb-1">Curriculum Name</label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                    <input type="text" id="curriculum" name="curriculum" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"  required>
                                </div>
                            </div>
                            <div>
                                <label for="programCode" class="block text-sm font-medium text-slate-700 mb-1">Program Code</label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3h15a.75.75 0 01.75.75v15a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-15zM8.25 9a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5h-7.5zM8.25 12.75a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5z" /></svg>
                                    <input type="text" id="programCode" name="programCode" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                </div>
                            </div>
                            <div>
                                <label for="academicYear" class="block text-sm font-medium text-slate-700 mb-1">Academic Year</label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" /></svg>
                                    <input type="text" id="academicYear" name="academicYear" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                </div>
                            </div>
                            <div>
                                <label for="yearLevel" class="block text-sm font-medium text-slate-700 mb-1">Level</label>
                                 <div class="relative">
                                     <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5-1.5V3" /></svg>
                                    <select id="yearLevel" name="yearLevel" class="w-full appearance-none pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                        <option value="" disabled selected>Select Level</option>
                                        <option value="Senior High">Senior High</option>
                                        <option value="College">College</option>
                                    </select>
                                    <svg class="w-5 h-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                            
                            <div>
                                <label for="compliance" class="block text-sm font-medium text-slate-700 mb-1">Choose Compliance</label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c0 .621-.504 1.125-1.125 1.125H18a2.25 2.25 0 01-2.25-2.25M6.75 17.25h-.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V4.875c0-.621.504-1.125 1.125-1.125H6.75a1.125 1.125 0 011.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125z" /></svg>
                                    <select id="compliance" name="compliance" class="w-full appearance-none pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                        <option value="" disabled selected>Select Compliance</option>
                                        <option value="CHED">CHED</option>
                                        <option value="DepEd">DepEd</option>
                                    </select>
                                    <svg class="w-5 h-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                            
                            <div id="memorandumContainer" class="hidden">
                <!-- Year Selection for CHED (hidden by default) -->
                <div id="yearContainer" class="hidden mb-4">
                    <label for="memorandumYear" class="block text-sm font-medium text-slate-700 mb-1">Memorandum Year</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" /></svg>
                        <select id="memorandumYear" name="memorandumYear" class="w-full appearance-none pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="" disabled selected>Select Year</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                        </select>
                        <svg class="w-5 h-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
                
                <!-- Category Selection for DepEd (hidden by default) -->
                <div id="categoryContainer" class="hidden mb-4">
                    <label for="memorandumCategory" class="block text-sm font-medium text-slate-700 mb-1">Document Category</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25H11.182c-.397 0-.779-.158-1.06-.44z" /></svg>
                        <select id="memorandumCategory" name="memorandumCategory" class="w-full appearance-none pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Shape Paper">Shape Paper</option>
                            <option value="Curriculum Guides (Core)">Curriculum Guides (Core)</option>
                            <option value="Curriculum Guides (Academic)">Curriculum Guides (Academic)</option>
                            <option value="Curriculum Guides (TechPro)">Curriculum Guides (TechPro)</option>
                        </select>
                        <svg class="w-5 h-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
                
                <label for="memorandum" class="block text-sm font-medium text-slate-700 mb-1">Official Memorandum</label>
                <div class="relative">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c0 .621-.504 1.125-1.125 1.125H11.25a9 9 0 00-9-9V3.375c0-.621.504-1.125 1.125-1.125z" /></svg>
                    <select id="memorandum" name="memorandum" class="w-full appearance-none pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="" disabled selected>Select Memorandum</option>
                    </select>
                    <svg class="w-5 h-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                </div>
            </div>
                            
                            <div id="unitsContainer" class="hidden">
                                <label id="semesterUnitsLabel" class="block text-sm font-medium text-slate-700 mb-3">Semester Units</label>
                                <div id="semesterInputs" class="space-y-3">
                                    <!-- Dynamic semester inputs will be inserted here -->
                                </div>
                                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-blue-800">Total Units:</span>
                                        <span id="totalUnits" class="text-lg font-bold text-blue-900">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-6 flex flex-col sm:flex-row-reverse gap-3">
                                <button type="submit" id="submit-button" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                    <span>Create</span>
                                </button>
                                <button type="button" id="cancelModalButton" class="w-full sm:w-auto px-6 py-3 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Reusable Confirmation Modal --}}
            <div id="confirmationModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
                <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out" id="confirmation-modal-panel">
                    <div id="confirmation-modal-icon" class="w-12 h-12 rounded-full p-2 flex items-center justify-center mx-auto mb-4">
                        {{-- Icon will be set by JS --}}
                    </div>
                    <h3 id="confirmation-modal-title" class="text-lg font-semibold text-slate-800"></h3>
                    <p id="confirmation-modal-message" class="text-sm text-slate-500 mt-2"></p>
                    <div class="mt-6 flex justify-center gap-4">
                        <button id="cancel-confirmation-button" class="w-full px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">No</button>
                        <button id="confirm-action-button" class="w-full px-6 py-2.5 text-sm font-medium text-white rounded-lg transition-all">Yes</button>
                    </div>
                </div>
            </div>

            {{-- Reusable Success Modal --}}
            <div id="successModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
                 <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out" id="success-modal-panel">
                    <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                         <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 id="success-modal-title" class="text-lg font-semibold text-slate-800"></h3>
                    <p id="success-modal-message" class="text-sm text-slate-500 mt-2"></p>
                    <div class="mt-6">
                        <button id="closeSuccessModalButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Main elements
            const addCurriculumButton = document.getElementById('addCurriculumButton');
            const seniorHighCurriculums = document.getElementById('senior-high-curriculums');
            const collegeCurriculums = document.getElementById('college-curriculums');
            const searchBar = document.getElementById('search-bar');
            const versionFilter = document.getElementById('version-filter');
            
            // New form elements
            const complianceSelect = document.getElementById('compliance');
            const memorandumContainer = document.getElementById('memorandumContainer');
            const yearContainer = document.getElementById('yearContainer');
            const categoryContainer = document.getElementById('categoryContainer');
            const memorandumYearSelect = document.getElementById('memorandumYear');
            const memorandumCategorySelect = document.getElementById('memorandumCategory');
            const memorandumSelect = document.getElementById('memorandum');
            const yearLevelSelect = document.getElementById('yearLevel');
            const unitsContainer = document.getElementById('unitsContainer');
            const semesterInputs = document.getElementById('semesterInputs');
            const totalUnitsDisplay = document.getElementById('totalUnits');

            // Add/Edit Modal elements
            const addCurriculumModal = document.getElementById('addCurriculumModal');
            const closeModalButton = document.getElementById('closeModalButton');
            const cancelModalButton = document.getElementById('cancelModalButton');
            const modalPanel = document.getElementById('modal-panel');
            const curriculumForm = document.getElementById('curriculumForm');
            const modalTitle = document.getElementById('modal-title');
            const submitButton = document.getElementById('submit-button');
            const curriculumIdField = document.getElementById('curriculumId');

            // Confirmation Modal elements
            const confirmationModal = document.getElementById('confirmationModal');
            const confirmationModalPanel = document.getElementById('confirmation-modal-panel');
            const confirmationModalTitle = document.getElementById('confirmation-modal-title');
            const confirmationModalMessage = document.getElementById('confirmation-modal-message');
            const confirmationModalIcon = document.getElementById('confirmation-modal-icon');
            const cancelConfirmationButton = document.getElementById('cancel-confirmation-button');
            const confirmActionButton = document.getElementById('confirm-action-button');

            // Success Modal elements
            const successModal = document.getElementById('successModal');
            const successModalPanel = document.getElementById('success-modal-panel');
            const successModalTitle = document.getElementById('success-modal-title');
            const successModalMessage = document.getElementById('success-modal-message');
            const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');

            let currentAction = null; // To store the function to execute on confirmation.
            
            // Memorandum data organized by year - fetched from compliance validator structure
            const memorandumData = {
                'CHED': {
                    '2025': [
                        'CMO No. 1, series of 2025 – Guidelines for Micro-Credential Development, Approval, and Recognition in Higher Education',
                        'CMO No. 2, series of 2025 – Updated List of Private Higher Education Institutions Granted Autonomous and Deregulated Status by Evaluation',
                        'CMO No. 3, series of 2025 – Updated Guidelines for Securing Authority to Travel Abroad for State Universities and Colleges (SUCs)',
                        'CMO No. 4, series of 2025 – Revised Policies, Standards and Guidelines for Associate in Radiologictechnology Education (ART) Program',
                        'CMO No. 5, series of 2025 – Guidelines for the Accreditation of Hospitals and Primary Health Care Facilities for the Clinical Practice of Radiologic/X-RAY Technology Interns',
                        'CMO No. 6, series of 2025 – Application Process for Authority to Offer Transnational Higher Education Pursuant to Republic Act No. 11448 or The Transnational Higher Education Act',
                        'CMO No. 7, series of 2025 – Policies, Standards and Guidelines for the Implementation of the National Merchant Marine Aptitude Test (NaMMAT)',
                        'CMO No. 9, series of 2025 – Updated Guidelines for the Scholarships for Staff and Instructors\' Knowledge Advancement Program (SIKAP) for Full-Time and Part-Time Study',
                        'CMO No. 10, series of 2025 – Policies and Standards on Centers of Excellence (COE)',
                        'CMO No. 11, series of 2025 – Implementing Rules and Regulations of Republic Act No. 12124, "An Act Institutionalizing the Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP) and Providing Funds Therefor"',
                        'CMO No. 12, series of 2025 – Policies and Guidelines on Open Distance and e-Learning (ODeL)',
                        'CMO No. 13, series of 2025 – Revised Policies and Guidelines for the CHED Merit Scholarship Program (CMSP)',
                        'CMO No. 14, series of 2025 – Revised Implementing Guidelines for the CHED Scholarship Program for Future Statisticians (ESTATISKOLAR)',
                        'CMO No. 15, series of 2025 – Updated Policies and Guidelines for the Grant of Autonomous and Deregulated Status to Private Higher Education Institutions'
                    ],
                    '2024': [
                        'View all 2024 CHED Memorandum Orders on the official website'
                    ],
                    '2023': [
                        'View all 2023 CHED Memorandum Orders on the official website'
                    ],
                    '2022': [
                        'View all 2022 CHED Memorandum Orders on the official website'
                    ],
                    '2021': [
                        'View all 2021 CHED Memorandum Orders on the official website'
                    ],
                    '2020': [
                        'View all 2020 CHED Memorandum Orders on the official website'
                    ],
                    '2019': [
                        'View all 2019 CHED Memorandum Orders on the official website'
                    ],
                    '2018': [
                        'View all 2018 CHED Memorandum Orders on the official website'
                    ],
                    '2017': [
                        'View all 2017 CHED Memorandum Orders on the official website'
                    ],
                    '2016': [
                        'View all 2016 CHED Memorandum Orders on the official website'
                    ]
                },
                'DepEd': {
                    'Shape Paper': [
                        'The Strengthened Senior High School Program Shaping Paper'
                    ],
                    'Curriculum Guides (Core)': [
                        'Effective Communication - Core Subject Guide',
                        'General Mathematics - Core Subject Guide',
                        'General Science - Core Subject Guide',
                        'Life and Career Skills - Core Subject Guide',
                        'Mabisang Komunikasyon - Core Subject Guide',
                        'Pag-aaral ng Kasaysayan at Lipunang Pilipino - Core Subject Guide'
                    ],
                    'Curriculum Guides (Academic)': [
                        'Arts 1 (Creative Industries - Visual Art, Literary Art, Media Art, Applied Art, and Traditional Art)',
                        'Arts 2 (Creative Industries II – Performing Arts)',
                        'Social Science 1 (Introduction to Social Sciences)',
                        'Humanities 1 (Creative Writing)',
                        'Humanities 2 (Introduction to World Religions and Belief Systems)',
                        'Engineering 1 (Calculus)',
                        'Engineering 2 (Fundamentals of Programming)',
                        'Engineering 3 (Basic Electricity and Electronics)',
                        'Business 1 (Business Enterprise Simulation)',
                        'Economics 1 (Introduction to Economics)',
                        'Management 1 (Fundamentals of Accountancy, Business, and Management)',
                        'Health Science 1 (Introduction to Health Science)',
                        'Health Science 2 (Basic Human Anatomy and Physiology)'
                    ],
                    'Curriculum Guides (TechPro)': [
                        'Digital Tools and Productivity Applications',
                        'Multimedia Development and Design',
                        'Computer Systems and Network Administration',
                        'Web Development',
                        'Computer Programming'
                    ]
                }
            };
            
            // Function to fetch memorandums based on compliance, year, or category
            const fetchMemorandumData = async (compliance, yearOrCategory = null) => {
                try {
                    if (compliance === 'CHED' && yearOrCategory) {
                        return memorandumData[compliance][yearOrCategory] || [];
                    } else if (compliance === 'DepEd' && yearOrCategory) {
                        return memorandumData[compliance][yearOrCategory] || [];
                    }
                    return [];
                } catch (error) {
                    console.error('Error fetching memorandum data:', error);
                    return [];
                }
            };

            // --- Modal Helper Functions ---
            const showSuccessModal = (title, message) => {
                successModalTitle.textContent = title;
                successModalMessage.textContent = message;
                successModal.classList.remove('hidden');
                setTimeout(() => {
                    successModal.classList.remove('opacity-0');
                    successModalPanel.classList.remove('opacity-0', 'scale-95');
                }, 10);
            };

            const hideSuccessModal = () => {
                successModal.classList.add('opacity-0');
                successModalPanel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => successModal.classList.add('hidden'), 300);
            };

            const showConfirmationModal = (config) => {
                confirmationModalTitle.textContent = config.title;
                confirmationModalMessage.textContent = config.message;
                confirmationModalIcon.innerHTML = config.icon;
                confirmActionButton.className = `w-full px-6 py-2.5 text-sm font-medium text-white rounded-lg transition-all ${config.confirmButtonClass}`;
                currentAction = config.onConfirm;

                confirmationModal.classList.remove('hidden');
                setTimeout(() => {
                    confirmationModal.classList.remove('opacity-0');
                    confirmationModalPanel.classList.remove('opacity-0', 'scale-95');
                }, 10);
            };

            const hideConfirmationModal = () => {
                confirmationModal.classList.add('opacity-0');
                confirmationModalPanel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    confirmationModal.classList.add('hidden');
                    currentAction = null;
                }, 300);
            };

            closeSuccessModalButton.addEventListener('click', hideSuccessModal);
            cancelConfirmationButton.addEventListener('click', hideConfirmationModal);
            confirmActionButton.addEventListener('click', () => {
                if (typeof currentAction === 'function') {
                    currentAction();
                }
                hideConfirmationModal();
            });
            
            // --- Dynamic Form Logic ---
            
            // Handle compliance selection
            complianceSelect.addEventListener('change', function() {
                const selectedCompliance = this.value;
                
                if (selectedCompliance === 'CHED') {
                    // Show year selection for CHED
                    yearContainer.classList.remove('hidden');
                    categoryContainer.classList.add('hidden');
                    memorandumSelect.innerHTML = '<option value="" disabled selected>Please select a year first</option>';
                    memorandumContainer.classList.remove('hidden');
                    
                    // Manage validation
                    memorandumYearSelect.setAttribute('required', 'required');
                    memorandumCategorySelect.removeAttribute('required');
                } else if (selectedCompliance === 'DepEd') {
                    // Show category selection for DepEd
                    categoryContainer.classList.remove('hidden');
                    yearContainer.classList.add('hidden');
                    memorandumSelect.innerHTML = '<option value="" disabled selected>Please select a category first</option>';
                    memorandumContainer.classList.remove('hidden');
                    
                    // Manage validation
                    memorandumCategorySelect.setAttribute('required', 'required');
                    memorandumYearSelect.removeAttribute('required');
                } else {
                    memorandumContainer.classList.add('hidden');
                    yearContainer.classList.add('hidden');
                    categoryContainer.classList.add('hidden');
                    
                    // Remove validation when hidden
                    memorandumYearSelect.removeAttribute('required');
                    memorandumCategorySelect.removeAttribute('required');
                    memorandumSelect.removeAttribute('required');
                }
            });
            
            // Handle year selection for CHED
            memorandumYearSelect.addEventListener('change', function() {
                const selectedYear = this.value;
                const selectedCompliance = complianceSelect.value;
                
                if (selectedCompliance === 'CHED' && selectedYear) {
                    loadMemorandums(selectedCompliance, selectedYear);
                }
            });
            
            // Handle category selection for DepEd
            memorandumCategorySelect.addEventListener('change', function() {
                const selectedCategory = this.value;
                const selectedCompliance = complianceSelect.value;
                
                if (selectedCompliance === 'DepEd' && selectedCategory) {
                    loadMemorandums(selectedCompliance, selectedCategory);
                }
            });
            
            // Function to load memorandums
            const loadMemorandums = async (compliance, yearOrCategory = null) => {
                memorandumSelect.innerHTML = '<option value="" disabled selected>Loading memorandums...</option>';
                
                try {
                    const memorandums = await fetchMemorandumData(compliance, yearOrCategory);
                    memorandumSelect.innerHTML = '<option value="" disabled selected>Select Memorandum</option>';
                    
                    memorandums.forEach(memo => {
                        const option = document.createElement('option');
                        option.value = memo;
                        option.textContent = memo;
                        memorandumSelect.appendChild(option);
                    });
                    
                    // Add required validation when memorandums are loaded
                    if (memorandums.length > 0) {
                        memorandumSelect.setAttribute('required', 'required');
                    }
                } catch (error) {
                    console.error('Error loading memorandums:', error);
                    memorandumSelect.innerHTML = '<option value="" disabled selected>Error loading memorandums</option>';
                }
            };
            
            // Handle year level selection for units
            yearLevelSelect.addEventListener('change', function() {
                const selectedLevel = this.value;
                generateSemesterInputs(selectedLevel);
                
                const semesterUnitsLabel = document.getElementById('semesterUnitsLabel');
                if (selectedLevel === 'Senior High') {
                    semesterUnitsLabel.textContent = 'Quarter Units';
                } else {
                    semesterUnitsLabel.textContent = 'Semester Units';
                }

                if (selectedLevel) {
                    unitsContainer.classList.remove('hidden');
                } else {
                    unitsContainer.classList.add('hidden');
                }
            });
            
            // Generate semester inputs based on level
            function generateSemesterInputs(level) {
                semesterInputs.innerHTML = '';
                let semesters = [];
                
                if (level === 'College') {
                    semesters = [
                        '1st Year First Semester',
                        '1st Year Second Semester',
                        '2nd Year First Semester',
                        '2nd Year Second Semester',
                        '3rd Year First Semester',
                        '3rd Year Second Semester',
                        '4th Year First Semester',
                        '4th Year Second Semester'
                    ];
                } else if (level === 'Senior High') {
                    semesters = [
                        '1st Year First Quarter',
                        '1st Year Second Quarter',
                        '2nd Year Third Quarter',
                        '2nd Year Fourth Quarter'
                    ];
                }
                
                semesters.forEach((semester, index) => {
                    const inputGroup = document.createElement('div');
                    inputGroup.className = 'flex items-center gap-3';
                    inputGroup.innerHTML = `
                        <label class="text-sm font-medium text-slate-700 w-48 flex-shrink-0">${semester}:</label>
                        <div class="relative flex-grow">
                            <input type="number" 
                                   id="semester_${index}" 
                                   name="semester_units[${index}]" 
                                   class="semester-unit-input w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="0" 
                                   min="0" 
                                   step="0.5">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">units</span>
                        </div>
                    `;
                    semesterInputs.appendChild(inputGroup);
                });
                
                // Add event listeners for automatic calculation
                document.querySelectorAll('.semester-unit-input').forEach(input => {
                    input.addEventListener('input', calculateTotalUnits);
                });
                
                calculateTotalUnits();
            }
            
            // Calculate total units
            function calculateTotalUnits() {
                const inputs = document.querySelectorAll('.semester-unit-input');
                let total = 0;
                
                inputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    total += value;
                });
                
                // Format total units without .0 for whole numbers
                const formattedTotal = total % 1 === 0 ? Math.floor(total) : total.toFixed(1);
                totalUnitsDisplay.textContent = formattedTotal;
            }

            // --- Card & API Logic ---
            const createCurriculumCard = (curriculum) => {
                const card = document.createElement('div');
                card.className = 'curriculum-card group relative bg-white p-4 rounded-xl border border-slate-200 flex items-center gap-4 hover:border-blue-500 hover:shadow-lg transition-all duration-300';
                card.dataset.name = curriculum.curriculum_name.toLowerCase();
                card.dataset.code = curriculum.program_code.toLowerCase();
                card.dataset.id = curriculum.id;
                card.dataset.version = curriculum.version_status || 'new';

                const date = new Date(curriculum.created_at);
                const formattedDate = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

                // Helper function to truncate long memorandum text
                const truncateText = (text, maxLength = 60) => {
                    if (!text) return 'Not specified';
                    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
                };

                // Format compliance badge
                const complianceBadge = curriculum.compliance 
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${curriculum.compliance === 'CHED' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'}">
                        ${curriculum.compliance}
                    </span>`
                    : '';

                // Format total units display (remove .0 from whole numbers)
                const formatUnits = (units) => {
                    if (!units) return '';
                    const num = parseFloat(units);
                    return num % 1 === 0 ? Math.floor(num) : num;
                };

                const totalUnitsDisplay = curriculum.total_units 
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ${formatUnits(curriculum.total_units)} units
                    </span>`
                    : '';

                // Version status badge
                const versionBadge = curriculum.version_status === 'old'
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                        </svg>
                        Old
                    </span>`
                    : `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        New
                    </span>`;

                // Format memorandum year/category display
                const memorandumYearCategory = curriculum.memorandum_year 
                    ? curriculum.memorandum_year
                    : curriculum.memorandum_category 
                        ? curriculum.memorandum_category 
                        : '';

                card.innerHTML = `
                    <div class="flex-shrink-0 w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                        <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="flex-grow cursor-pointer min-w-0" onclick="window.location.href='/subject_mapping?curriculumId=${curriculum.id}'">
                        <div class="flex items-start justify-between">
                            <div class="flex-grow min-w-0 pr-2">
                                <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors duration-300 truncate mb-1">${curriculum.curriculum_name}</h3>
                                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                                    <span>${curriculum.program_code} • ${curriculum.academic_year}</span>
                                </div>
                                ${curriculum.memorandum ? `
                                <p class="text-xs text-slate-400 truncate" title="${curriculum.memorandum}">
                                    ${memorandumYearCategory ? `${memorandumYearCategory} ` : ''}• ${truncateText(curriculum.memorandum, 45)}
                                </p>
                                ` : memorandumYearCategory ? `
                                <p class="text-xs text-slate-400">
                                    ${memorandumYearCategory} • No memorandum selected
                                </p>
                                ` : ''}
                                <p class="text-xs text-slate-400 mt-1">
                                    Created: ${formattedDate} at ${formattedTime} • 
                                    <span class="font-medium">${curriculum.subjects_count} subject${curriculum.subjects_count !== 1 ? 's' : ''}</span>
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <div class="flex items-center gap-1">
                                    ${versionBadge}
                                    ${complianceBadge}
                                    ${totalUnitsDisplay}
                                </div>
                            </div>
                        </div>
                    </div>

                `;
                return card;
            };

            const renderCurriculums = (curriculums) => {
                seniorHighCurriculums.innerHTML = '';
                collegeCurriculums.innerHTML = '';
                let seniorHighCount = 0;
                let collegeCount = 0;

                curriculums.forEach(curriculum => {
                    const card = createCurriculumCard(curriculum);
                    if (curriculum.year_level === 'Senior High') {
                        seniorHighCurriculums.appendChild(card);
                        seniorHighCount++;
                    } else {
                        collegeCurriculums.appendChild(card);
                        collegeCount++;
                    }
                });

                if (seniorHighCount === 0) seniorHighCurriculums.innerHTML = '<p class="text-slate-500 text-sm py-4">No Senior High curriculums found.</p>';
                if (collegeCount === 0) collegeCurriculums.innerHTML = '<p class="text-slate-500 text-sm py-4">No College curriculums found.</p>';
                
                attachActionListeners();
                
                // Apply filter after rendering to hide old versions by default
                filterCurriculums();
            };
            
            const fetchCurriculums = () => {
                fetch('/api/curriculums')
                    .then(response => response.json())
                    .then(renderCurriculums)
                    .catch(error => console.error('Error fetching curriculums:', error));
            };

            const showAddEditModal = (isEdit = false, curriculum = null) => {
                curriculumForm.reset();
                 const modalSubTitle = document.querySelector('#modal-panel > div.text-center.mb-8 > p');
                if (isEdit && curriculum) {
                    modalTitle.textContent = 'Edit Curriculum';
                    modalSubTitle.textContent = 'Update the details for this curriculum.';
                    submitButton.querySelector('span').textContent = 'Update';
                    curriculumIdField.value = curriculum.id;
                    document.getElementById('curriculum').value = curriculum.curriculum;
                    document.getElementById('programCode').value = curriculum.program_code;
                    document.getElementById('academicYear').value = curriculum.academic_year;
                    document.getElementById('yearLevel').value = curriculum.year_level;
                    
                    // Set compliance and memorandum if available
                    if (curriculum.compliance) {
                        document.getElementById('compliance').value = curriculum.compliance;
                        complianceSelect.dispatchEvent(new Event('change'));
                        
                        // Set year if available and compliance is CHED
                        if (curriculum.compliance === 'CHED' && curriculum.memorandum_year) {
                            setTimeout(() => {
                                document.getElementById('memorandumYear').value = curriculum.memorandum_year;
                                memorandumYearSelect.dispatchEvent(new Event('change'));
                                
                                if (curriculum.memorandum) {
                                    setTimeout(() => {
                                        document.getElementById('memorandum').value = curriculum.memorandum;
                                    }, 200);
                                }
                            }, 100);
                        } else if (curriculum.compliance === 'DepEd' && curriculum.memorandum_category) {
                            // Set category if available and compliance is DepEd
                            setTimeout(() => {
                                document.getElementById('memorandumCategory').value = curriculum.memorandum_category;
                                memorandumCategorySelect.dispatchEvent(new Event('change'));
                                
                                if (curriculum.memorandum) {
                                    setTimeout(() => {
                                        document.getElementById('memorandum').value = curriculum.memorandum;
                                    }, 200);
                                }
                            }, 100);
                        } else if (curriculum.memorandum) {
                            setTimeout(() => {
                                document.getElementById('memorandum').value = curriculum.memorandum;
                            }, 100);
                        }
                    }
                    
                    // Generate semester inputs and populate if data exists
                    if (curriculum.year_level) {
                        generateSemesterInputs(curriculum.year_level);
                        
                        const semesterUnitsLabel = document.getElementById('semesterUnitsLabel');
                        if (curriculum.year_level === 'Senior High') {
                            semesterUnitsLabel.textContent = 'Quarter Units';
                        } else {
                            semesterUnitsLabel.textContent = 'Semester Units';
                        }
                        
                        // Populate semester units if available
                        if (curriculum.semester_units) {
                            setTimeout(() => {
                                curriculum.semester_units.forEach((units, index) => {
                                    const input = document.getElementById(`semester_${index}`);
                                    if (input) {
                                        input.value = units;
                                    }
                                });
                                calculateTotalUnits();
                            }, 100);
                        }
                    }
                } else {
                    modalTitle.textContent = 'Create New Curriculum';
                    modalSubTitle.textContent = 'Fill in the details below to add a new curriculum.';
                    submitButton.querySelector('span').textContent = 'Create';
                    curriculumIdField.value = '';
                    
                    // Reset dynamic sections
                    memorandumContainer.classList.add('hidden');
                    yearContainer.classList.add('hidden');
                    categoryContainer.classList.add('hidden');
                    unitsContainer.classList.add('hidden');
                    memorandumSelect.innerHTML = '<option value="" disabled selected>Select Memorandum</option>';
                    memorandumYearSelect.value = '';
                    memorandumCategorySelect.value = '';
                    semesterInputs.innerHTML = '';
                    totalUnitsDisplay.textContent = '0';
                }
                addCurriculumModal.classList.remove('hidden');
                setTimeout(() => {
                    addCurriculumModal.classList.remove('opacity-0');
                    modalPanel.classList.remove('opacity-0', 'scale-95');
                }, 10);
            };

            const hideAddEditModal = () => {
                addCurriculumModal.classList.add('opacity-0');
                modalPanel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => addCurriculumModal.classList.add('hidden'), 300);
            };
            
            const handleFormSubmit = (e) => {
                e.preventDefault();
                console.log('Form submit event triggered');
                
                const performSubmit = async () => {
                    console.log('Perform submit function called');
                    const id = curriculumIdField.value;
                    const method = id ? 'PUT' : 'POST';
                    const url = id ? `/api/curriculums/${id}` : '/api/curriculums';
                    
                    const formData = new FormData(curriculumForm);
                    // Collect semester units
                    const semesterUnits = [];
                    document.querySelectorAll('.semester-unit-input').forEach(input => {
                        semesterUnits.push(parseFloat(input.value) || 0);
                    });
                    
                    const payload = {
                        curriculum: formData.get('curriculum'),
                        programCode: formData.get('programCode'),
                        academicYear: formData.get('academicYear'),
                        yearLevel: formData.get('yearLevel'),
                        compliance: formData.get('compliance'),
                        memorandumYear: formData.get('memorandumYear'),
                        memorandumCategory: formData.get('memorandumCategory'),
                        memorandum: formData.get('memorandum'),
                        semesterUnits: semesterUnits,
                        totalUnits: parseFloat(totalUnitsDisplay.textContent) || 0
                    };
                    
                    try {
                        console.log('Submitting payload:', payload);
                        console.log('URL:', url);
                        console.log('Method:', method);
                        
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload)
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Server error response:', errorData);
                            console.error('Response status:', response.status);
                            throw errorData;
                        }
                        
                        const result = await response.json();
                        hideAddEditModal();
                        fetchCurriculums();
                        
                        // Use helper function to handle response
                        handleAjaxResponse(result, () => {
                            // Fallback notification if none provided by server
                            if (!result.notification) {
                                notificationManager.success(
                                    `Curriculum ${id ? 'Updated' : 'Created'}!`,
                                    `The curriculum has been successfully ${id ? 'updated' : 'created'}.`
                                );
                            }
                        });
                    } catch (error) {
                        console.error('Form submission error:', error);
                        handleAjaxError(error);
                    }
                };

                const isUpdating = !!curriculumIdField.value;
                console.log('Showing confirmation modal, isUpdating:', isUpdating);
                showConfirmationModal({
                    title: isUpdating ? 'Update Curriculum?' : 'Create Curriculum?',
                    message: `Are you sure you want to ${isUpdating ? 'update' : 'create'} this curriculum?`,
                    icon: `<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                    confirmButtonClass: 'bg-blue-600 hover:bg-blue-700',
                    onConfirm: performSubmit
                });
            };
            
            const attachActionListeners = () => {
                // Edit and delete functionality removed
            };

            searchBar.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                filterCurriculums();
            });

            versionFilter.addEventListener('change', (e) => {
                filterCurriculums();
            });

            function filterCurriculums() {
                const searchTerm = searchBar.value.toLowerCase();
                const versionStatus = versionFilter.value;
                
                document.querySelectorAll('.curriculum-card').forEach(card => {
                    const name = card.dataset.name;
                    const code = card.dataset.code;
                    const version = card.dataset.version;
                    
                    const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm);
                    const matchesVersion = version === versionStatus;
                    
                    card.style.display = (matchesSearch && matchesVersion) ? 'flex' : 'none';
                });
            }

            addCurriculumButton.addEventListener('click', () => showAddEditModal());
            closeModalButton.addEventListener('click', hideAddEditModal);
            cancelModalButton.addEventListener('click', hideAddEditModal);
            curriculumForm.addEventListener('submit', handleFormSubmit);

            fetchCurriculums();
        });
    </script>
@endsection