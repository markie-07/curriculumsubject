@extends('layouts.app')

@section('content')
<style>
    /* Custom scrollbar for dropdown */
    #curriculum-options::-webkit-scrollbar {
        width: 6px;
    }
    
    #curriculum-options::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #curriculum-options::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    #curriculum-options::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Ensure dropdown stays within viewport */
    #curriculum-dropdown-menu {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Panel: Export Configuration --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
            <div class="pb-6 border-b border-gray-200 flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Curriculum Export Tool</h1>
                    <p class="mt-1 text-sm text-gray-500">Select a curriculum and export its data as a PDF document.</p>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800">Export Configuration</h2>
                <div class="space-y-6 mt-4">
                    {{-- Curriculum Status Filter --}}
                    <div>
                        <label for="curriculum-status-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Curriculum Status</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </div>
                            <select id="curriculum-status-filter" name="curriculum_status" class="block w-full pl-10 pr-10 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="" selected disabled>-- Select Status --</option>
                                <option value="processing">Processing</option>
                                <option value="new">New</option>
                                <option value="old">Old</option>
                            </select>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Filter curriculums by their approval status.</p>
                    </div>
                    
                    <div>
                        <label for="curriculum-select" class="block text-sm font-medium text-gray-700 mb-2">Select Curriculum</label>
                        <div class="relative">
                            <!-- Custom Dropdown Button -->
                            <button type="button" id="curriculum-dropdown-btn" class="relative w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm pl-4 pr-10 py-3 text-left cursor-not-allowed opacity-60 focus:outline-none transition-all duration-200" disabled>
                                <span id="curriculum-selected-text" class="block truncate text-gray-500">-- Please select a status first --</span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg id="dropdown-arrow" class="h-5 w-5 text-gray-400 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="curriculum-dropdown-menu" class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg max-h-screen rounded-lg text-base ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-200 overflow-hidden" style="max-height: 500px;">
                                <div class="sticky top-0 bg-white p-2 border-b border-gray-100">
                                    <input type="text" id="curriculum-search" placeholder="Search curriculums..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div id="curriculum-options" class="py-1 overflow-y-auto" style="max-height: 450px;">
                                    @foreach($curriculums as $curriculum)
                                        <div class="curriculum-option cursor-pointer select-none relative py-3 pl-4 pr-9 hover:bg-blue-50 hover:text-blue-900 transition-colors duration-150" 
                                             data-value="{{ $curriculum->id }}" 
                                             data-text="{{ $curriculum->curriculum }} ({{ $curriculum->program_code }}) - {{ $curriculum->academic_year }}" 
                                             data-version-status="{{ $curriculum->version_status ?? 'new' }}"
                                             data-approval-status="{{ $curriculum->approval_status ?? 'processing' }}">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $curriculum->curriculum }}</div>
                                                    <div class="text-sm text-gray-500">{{ $curriculum->program_code }} • {{ $curriculum->academic_year }}</div>
                                                </div>
                                                <div class="hidden check-icon text-blue-600">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="no-results" class="hidden px-4 py-3 text-center text-gray-500 text-sm">
                                    No curriculums found
                                </div>
                            </div>

                            <!-- Hidden input to store selected value -->
                            <input type="hidden" id="curriculum-select" name="curriculum_id" value="">
                        </div>
                    </div>

                    {{-- Course Type Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Filter by Course Type (Optional)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="course_types" value="Major" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">Major</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="course_types" value="Minor" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">Minor</span>
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Leave unchecked to include all course types in the export.</p>
                        <div id="filter-summary" class="mt-3 hidden">
                            <div class="flex items-center gap-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                <span class="text-sm text-blue-800 font-medium">Filters applied: <span id="filter-list"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Curriculum Preview Section --}}
            <div id="curriculum-preview" class="mt-8 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Preview: Export Content</h2>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <div id="preview-loading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-sm text-gray-500">Loading curriculum data...</p>
                    </div>
                    <div id="preview-content" class="hidden">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                            <div>
                                <h3 id="preview-curriculum-name" class="text-lg font-semibold text-gray-800"></h3>
                                <p id="preview-curriculum-code" class="text-sm text-gray-500"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total Subjects</p>
                                <p id="preview-subject-count" class="text-2xl font-bold text-blue-600">0</p>
                            </div>
                        </div>
                        <div id="preview-subjects" class="space-y-3 max-h-64 overflow-y-auto">
                            <!-- Subjects will be populated here -->
                        </div>
                        <div id="preview-empty" class="hidden text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500">No subjects match the selected filters</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <button id="export-curriculum-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg flex items-center justify-center gap-2" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                    </svg>
                    Export Curriculum as PDF
                </button>
            </div>
        </div>

        {{-- Right Panel: Export History --}}
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200 flex flex-col">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Export History</h2>
            
            <div class="relative mb-4">
                <input type="text" id="history-search" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Search history...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            
            <div id="export-history" class="space-y-4 flex-1 overflow-y-auto pr-2 min-h-0">
                @forelse ($exportHistories as $history)
                    <div class="history-item flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="bg-gray-200 p-2 rounded-full">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $history->curriculum->curriculum ?? 'Unknown' }}</h3>
                                <p class="text-sm text-gray-500">{{ $history->format }} • {{ $history->created_at->format('M d, Y, g:i A') }}</p>
                                @if($history->exported_by_name || $history->exported_by_email)
                                    <p class="text-xs text-gray-400 mt-1">
                                        Exported by: {{ $history->exported_by_name ?? $history->exported_by_email }}
                                        @if($history->exported_by_name && $history->exported_by_email)
                                            ({{ $history->exported_by_email }})
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-10" id="no-history-msg">No export history yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</main>

{{-- Export Confirmation Modal --}}
<div id="exportConfirmationModal" class="fixed inset-0 z-[60] overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="export-confirmation-panel">
            <button id="closeExportConfirmationButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Export Curriculum to PDF</h2>
                <p class="text-sm text-slate-500 mt-1">Are you sure you want to export this curriculum?</p>
            </div>

            <div class="bg-slate-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-slate-700 mb-2">Export Details:</h3>
                <div id="export-confirmation-summary" class="text-sm text-slate-600">
                    <!-- Summary will be populated by JavaScript -->
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" id="cancelExportConfirmationButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                <button type="button" id="confirmExportConfirmationButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Yes, Export PDF</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Export Success Modal --}}
<div id="exportSuccessModal" class="fixed inset-0 z-[70] overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out" id="export-success-panel">
            <div class="w-16 h-16 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Export Successful!</h3>
            <p class="text-sm text-slate-500 mb-6">Your curriculum PDF has been generated and will download shortly.</p>
            <button id="closeExportSuccessButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">OK</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const exportButton = document.getElementById('export-curriculum-btn');
    const curriculumSelect = document.getElementById('curriculum-select');
    const exportHistoryContainer = document.getElementById('export-history');
    const noHistoryMessage = document.getElementById('no-history-msg');
    const historySearchInput = document.getElementById('history-search');

    // Custom Dropdown Elements
    const dropdownBtn = document.getElementById('curriculum-dropdown-btn');
    const dropdownMenu = document.getElementById('curriculum-dropdown-menu');
    const dropdownArrow = document.getElementById('dropdown-arrow');
    const selectedText = document.getElementById('curriculum-selected-text');
    const searchInput = document.getElementById('curriculum-search');
    const curriculumOptions = document.querySelectorAll('.curriculum-option');
    const noResults = document.getElementById('no-results');

    // Custom Dropdown Functionality
    let isDropdownOpen = false;

    function toggleDropdown() {
        isDropdownOpen = !isDropdownOpen;
        if (isDropdownOpen) {
            dropdownMenu.classList.remove('hidden');
            dropdownArrow.style.transform = 'rotate(180deg)';
            dropdownBtn.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
            searchInput.focus();
        } else {
            dropdownMenu.classList.add('hidden');
            dropdownArrow.style.transform = 'rotate(0deg)';
            dropdownBtn.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
            searchInput.value = '';
            filterOptions('');
        }
    }

    function selectOption(option) {
        const value = option.dataset.value;
        const text = option.dataset.text;
        
        // Update hidden input
        curriculumSelect.value = value;
        
        // Update display text
        selectedText.textContent = text;
        selectedText.classList.remove('text-gray-500');
        selectedText.classList.add('text-gray-900');
        
        // Update check icons
        curriculumOptions.forEach(opt => {
            const checkIcon = opt.querySelector('.check-icon');
            if (opt === option) {
                checkIcon.classList.remove('hidden');
                opt.classList.add('bg-blue-50');
            } else {
                checkIcon.classList.add('hidden');
                opt.classList.remove('bg-blue-50');
            }
        });
        
        // Close dropdown
        toggleDropdown();
        
        // Trigger change event
        const changeEvent = new Event('change', { bubbles: true });
        curriculumSelect.dispatchEvent(changeEvent);
    }

    function filterOptions(searchTerm = '') {
        const statusFilter = document.getElementById('curriculum-status-filter');
        const selectedStatus = statusFilter ? statusFilter.value : '';
        let visibleCount = 0;
        
        // If no status selected, hide all options
        if (!selectedStatus) {
            curriculumOptions.forEach(option => {
                option.style.display = 'none';
            });
            noResults.classList.remove('hidden');
            return;
        }
        
        curriculumOptions.forEach(option => {
            const text = option.dataset.text.toLowerCase();
            const versionStatus = option.dataset.versionStatus || 'new';
            const approvalStatus = option.dataset.approvalStatus || 'processing';
            
            // Check search match
            const searchMatch = text.includes(searchTerm.toLowerCase());
            
            // Check status match based on selected filter
            let statusMatch = false;
            
            if (selectedStatus === 'processing') {
                // For processing, check approval status
                statusMatch = approvalStatus === 'processing';
            } else if (selectedStatus === 'new') {
                // For new, check version status AND approved
                statusMatch = versionStatus === 'new' && approvalStatus === 'approved';
            } else if (selectedStatus === 'old') {
                // For old, check version status AND approved
                statusMatch = versionStatus === 'old' && approvalStatus === 'approved';
            }
            
            // Show option if both search and status match
            if (searchMatch && statusMatch) {
                option.style.display = 'block';
                visibleCount++;
            } else {
                option.style.display = 'none';
            }
        });
        
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    // Status filter event listener
    const statusFilter = document.getElementById('curriculum-status-filter');
    
    statusFilter.addEventListener('change', () => {
        const selectedStatus = statusFilter.value;
        
        // Enable or disable curriculum dropdown based on status selection
        if (selectedStatus) {
            // Enable dropdown
            dropdownBtn.disabled = false;
            dropdownBtn.classList.remove('cursor-not-allowed', 'opacity-60', 'bg-gray-100');
            dropdownBtn.classList.add('cursor-pointer', 'bg-white', 'hover:border-gray-400', 'hover:shadow-md');
            selectedText.textContent = '-- Please select a curriculum --';
        } else {
            // Disable dropdown
            dropdownBtn.disabled = true;
            dropdownBtn.classList.add('cursor-not-allowed', 'opacity-60', 'bg-gray-100');
            dropdownBtn.classList.remove('cursor-pointer', 'bg-white', 'hover:border-gray-400', 'hover:shadow-md');
            selectedText.textContent = '-- Please select a status first --';
        }
        
        // Reset curriculum selection when status changes
        curriculumSelect.value = '';
        selectedText.classList.add('text-gray-500');
        selectedText.classList.remove('text-gray-900');
        
        // Reset check icons
        curriculumOptions.forEach(opt => {
            const checkIcon = opt.querySelector('.check-icon');
            checkIcon.classList.add('hidden');
            opt.classList.remove('bg-blue-50');
        });
        
        // Hide preview
        hideCurriculumPreview();
        exportButton.disabled = true;
        
        // Apply filter
        filterOptions(searchInput.value);
    });

    // Event Listeners
    dropdownBtn.addEventListener('click', toggleDropdown);

    curriculumOptions.forEach(option => {
        option.addEventListener('click', () => selectOption(option));
    });

    searchInput.addEventListener('input', (e) => {
        filterOptions(e.target.value);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            if (isDropdownOpen) {
                toggleDropdown();
            }
        }
    });

    // Handle keyboard navigation
    dropdownBtn.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleDropdown();
        }
    });

    // Original change handler for curriculum selection
    curriculumSelect.addEventListener('change', function() {
        exportButton.disabled = !this.value;
        
        if (this.value) {
            loadCurriculumPreview(this.value);
        } else {
            hideCurriculumPreview();
        }
    });

    // Handle filter summary display
    const filterCheckboxes = document.querySelectorAll('input[name="course_types"]');
    const filterSummary = document.getElementById('filter-summary');
    const filterList = document.getElementById('filter-list');

    function updateFilterSummary() {
        const selectedTypes = Array.from(filterCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedTypes.length > 0) {
            filterList.textContent = selectedTypes.join(', ');
            filterSummary.classList.remove('hidden');
        } else {
            filterSummary.classList.add('hidden');
        }
        
        // Update preview when filters change
        if (curriculumSelect.value) {
            updatePreviewWithFilters();
        }
    }

    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateFilterSummary);
    });

    // Preview functions
    let currentCurriculumData = null;

    async function loadCurriculumPreview(curriculumId) {
        const previewSection = document.getElementById('curriculum-preview');
        const previewLoading = document.getElementById('preview-loading');
        const previewContent = document.getElementById('preview-content');
        
        // Show preview section and loading state
        previewSection.classList.remove('hidden');
        previewLoading.classList.remove('hidden');
        previewContent.classList.add('hidden');
        
        try {
            // Fetch curriculum data
            const response = await fetch(`/api/curriculum/${curriculumId}/subjects`);
            if (!response.ok) throw new Error('Failed to fetch curriculum data');
            
            currentCurriculumData = await response.json();
            displayCurriculumPreview(currentCurriculumData);
        } catch (error) {
            console.error('Error loading curriculum preview:', error);
            previewLoading.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-500">Failed to load curriculum data</p>
                    <p class="text-sm text-gray-500 mt-1">Please try selecting the curriculum again</p>
                </div>
            `;
        }
    }

    function displayCurriculumPreview(data) {
        const previewLoading = document.getElementById('preview-loading');
        const previewContent = document.getElementById('preview-content');
        const previewCurriculumName = document.getElementById('preview-curriculum-name');
        const previewCurriculumCode = document.getElementById('preview-curriculum-code');
        
        // Hide loading and show content
        previewLoading.classList.add('hidden');
        previewContent.classList.remove('hidden');
        
        // Set curriculum info
        previewCurriculumName.textContent = data.curriculum_name || 'Unknown Curriculum';
        previewCurriculumCode.textContent = data.program_code || 'No Code';
        
        // Update subjects with current filters
        updatePreviewWithFilters();
    }

    function updatePreviewWithFilters() {
        if (!currentCurriculumData) return;
        
        const selectedTypes = Array.from(filterCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        
        const previewSubjects = document.getElementById('preview-subjects');
        const previewSubjectCount = document.getElementById('preview-subject-count');
        const previewEmpty = document.getElementById('preview-empty');
        
        // Filter subjects based on selected types
        let filteredSubjects = currentCurriculumData.subjects || [];
        
        // Exclude subjects with invalid year/semester (unmapped subjects)
        filteredSubjects = filteredSubjects.filter(subject => 
            subject.year !== 'N/A' && subject.semester !== 'N/A' && 
            subject.year != null && subject.semester != null
        );

        if (selectedTypes.length > 0) {
            const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
            
            filteredSubjects = filteredSubjects.filter(subject => {
                const subjectType = subject.subject_type;
                
                return selectedTypes.some(selectedType => {
                    if (selectedType === 'General Education') {
                        // Handle General Education with flexible matching
                        return geIdentifiers.some(id => 
                            subjectType.toLowerCase().includes(id.toLowerCase())
                        );
                    } else {
                        // Exact match for other types
                        return subjectType === selectedType;
                    }
                });
            });
        }
        
        // Update subject count
        previewSubjectCount.textContent = filteredSubjects.length;
        
        if (filteredSubjects.length === 0) {
            previewSubjects.classList.add('hidden');
            previewEmpty.classList.remove('hidden');
        } else {
            previewSubjects.classList.remove('hidden');
            previewEmpty.classList.add('hidden');
            
            // Display subjects
            previewSubjects.innerHTML = filteredSubjects.map(subject => `
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full ${getSubjectTypeColor(subject.subject_type)}"></div>
                        <div>
                            <h4 class="font-medium text-gray-800">${subject.subject_name}</h4>
                            <p class="text-sm text-gray-500">${subject.subject_code}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getSubjectTypeBadge(subject.subject_type)}">
                            ${subject.subject_type}
                        </span>
                        <p class="text-sm text-gray-500 mt-1">${subject.subject_unit} units</p>
                    </div>
                </div>
            `).join('');
        }
    }

    function getSubjectTypeColor(type) {
        const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
        
        if (type === 'Major') return 'bg-blue-500';
        if (type === 'Minor') return 'bg-purple-500';
        if (type === 'Elective') return 'bg-red-500';
        if (geIdentifiers.some(id => type.toLowerCase().includes(id.toLowerCase()))) {
            return 'bg-orange-500';
        }
        return 'bg-gray-500';
    }

    function getSubjectTypeBadge(type) {
        const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
        
        if (type === 'Major') return 'bg-blue-100 text-blue-800';
        if (type === 'Minor') return 'bg-purple-100 text-purple-800';
        if (type === 'Elective') return 'bg-red-100 text-red-800';
        if (geIdentifiers.some(id => type.toLowerCase().includes(id.toLowerCase()))) {
            return 'bg-orange-100 text-orange-800';
        }
        return 'bg-gray-100 text-gray-800';
    }

    function hideCurriculumPreview() {
        const previewSection = document.getElementById('curriculum-preview');
        previewSection.classList.add('hidden');
        currentCurriculumData = null;
    }

    // Modal elements
    const exportConfirmationModal = document.getElementById('exportConfirmationModal');
    const exportConfirmationPanel = document.getElementById('export-confirmation-panel');
    const exportConfirmationSummary = document.getElementById('export-confirmation-summary');
    const exportSuccessModal = document.getElementById('exportSuccessModal');
    const exportSuccessPanel = document.getElementById('export-success-panel');

    // Modal functions
    const showExportConfirmationModal = () => {
        const curriculumId = curriculumSelect.value;
        const curriculumName = selectedText.textContent;
        const selectedTypes = Array.from(document.querySelectorAll('input[name="course_types"]:checked'))
                                   .map(checkbox => checkbox.value);
        
        // Populate summary
        const subjectCount = currentCurriculumData ? currentCurriculumData.subjects.length : 'Unknown';
        const filterText = selectedTypes.length > 0 ? selectedTypes.join(', ') : 'All subject types';
        
        exportConfirmationSummary.innerHTML = `
            <p><strong>Curriculum:</strong> ${curriculumName}</p>
            <p><strong>Subjects:</strong> ${subjectCount} subjects</p>
            <p><strong>Filters:</strong> ${filterText}</p>
            <p><strong>Format:</strong> PDF Document</p>
        `;
        
        exportConfirmationModal.classList.remove('hidden');
        setTimeout(() => {
            exportConfirmationModal.classList.remove('opacity-0');
            exportConfirmationPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hideExportConfirmationModal = () => {
        exportConfirmationModal.classList.add('opacity-0');
        exportConfirmationPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => exportConfirmationModal.classList.add('hidden'), 300);
    };

    const showExportSuccessModal = () => {
        exportSuccessModal.classList.remove('hidden');
        setTimeout(() => {
            exportSuccessModal.classList.remove('opacity-0');
            exportSuccessPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hideExportSuccessModal = () => {
        exportSuccessModal.classList.add('opacity-0');
        exportSuccessPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => exportSuccessModal.classList.add('hidden'), 300);
    };

    // Export button click - show confirmation modal
    exportButton.addEventListener('click', function () {
        const curriculumId = curriculumSelect.value;
        if (!curriculumId) {
            alert('Please select a curriculum to export.');
            return;
        }
        showExportConfirmationModal();
    });

    // Modal event listeners
    document.getElementById('closeExportConfirmationButton').addEventListener('click', hideExportConfirmationModal);
    document.getElementById('cancelExportConfirmationButton').addEventListener('click', hideExportConfirmationModal);
    document.getElementById('closeExportSuccessButton').addEventListener('click', hideExportSuccessModal);

    // Confirm export button
    document.getElementById('confirmExportConfirmationButton').addEventListener('click', async function () {
        const curriculumId = curriculumSelect.value;
        
        // Get selected course types from checkboxes
        const selectedTypes = Array.from(document.querySelectorAll('input[name="course_types"]:checked'))
                                   .map(checkbox => checkbox.value);

        // Build export URL with filters
        let exportUrl = `/curriculum/${curriculumId}/export-pdf`;
        if (selectedTypes.length > 0) {
            const queryParams = new URLSearchParams();
            selectedTypes.forEach(type => queryParams.append('course_types[]', type));
            exportUrl += `?${queryParams.toString()}`;
        }

        // Hide confirmation modal
        hideExportConfirmationModal();

        // Open PDF in new tab with filters applied
        window.open(exportUrl, '_blank');

        // Show success modal
        showExportSuccessModal();

        try {
            const curriculumName = selectedText.textContent;
            const filterText = selectedTypes.length > 0 ? ` (${selectedTypes.join(', ')})` : '';
            const fileName = `${curriculumName}${filterText}.pdf`;
            const newHistory = await saveExportHistory(curriculumId, fileName, 'PDF');
            
            // Use SweetAlert for success
            Swal.fire({
                title: 'Export Successful!',
                text: 'Curriculum has been exported successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            
            addHistoryItemToDOM(newHistory.data || newHistory);
        } catch (error) {
            console.error('Error saving export history:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while saving the export history: ' + error.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    // Close modals when clicking outside
    exportConfirmationModal.addEventListener('click', function(e) {
        if (e.target === this) hideExportConfirmationModal();
    });

    exportSuccessModal.addEventListener('click', function(e) {
        if (e.target === this) hideExportSuccessModal();
    });

    historySearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const historyItems = exportHistoryContainer.querySelectorAll('.history-item');
        historyItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    async function saveExportHistory(curriculumId, fileName, format) {
        const response = await fetch('{{ route('curriculum_export_tool.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ curriculum_id: curriculumId, file_name: fileName, format: format })
        });
        if (!response.ok) throw new Error('Failed to save export history.');
        return await response.json();
    }

    function addHistoryItemToDOM(historyItem) {
        if (noHistoryMessage) noHistoryMessage.remove();
        const item = document.createElement('div');
        item.className = 'history-item flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-shadow';
        const formattedDate = new Date(historyItem.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit'});
        
        // Build exported by text
        let exportedByText = '';
        if (historyItem.exported_by_name || historyItem.exported_by_email) {
            const name = historyItem.exported_by_name || historyItem.exported_by_email;
            const email = historyItem.exported_by_name && historyItem.exported_by_email ? ` (${historyItem.exported_by_email})` : '';
            exportedByText = `<p class="text-xs text-gray-400 mt-1">Exported by: ${name}${email}</p>`;
        }
        
        item.innerHTML = `
            <div class="flex items-center gap-4">
                <div class="bg-gray-200 p-2 rounded-full">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">${historyItem.curriculum.curriculum}</h3>
                    <p class="text-sm text-gray-500">${historyItem.format} • ${formattedDate}</p>
                    ${exportedByText}
                </div>
            </div>
        `;
        exportHistoryContainer.prepend(item);
    }
});
</script>
@endsection