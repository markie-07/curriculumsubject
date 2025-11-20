@extends('layouts.app')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Add styles for the subject cards inside the modal, similar to subject_mapping.blade.php */
    .subject-tag {
        transition: all 0.2s ease-in-out;
    }
    .subject-tag-major { background-color: #DBEAFE; border-color: #BFDBFE; }
    .subject-tag-major .text-main { color: #1E40AF; font-weight: bold; }
    .subject-tag-major .text-code { color: #1D4ED8; }
    .subject-tag-major .icon { color: #3B82F6; }
    .subject-tag-major .unit-badge { background-color: #BFDBFE; color: #1E40AF; }

    .subject-tag-minor { background-color: #E9D5FF; border-color: #D8B4FE; }
    .subject-tag-minor .text-main { color: #5B21B6; font-weight: bold; }
    .subject-tag-minor .text-code { color: #6D28D9; }
    .subject-tag-minor .icon { color: #8B5CF6; }
    .subject-tag-minor .unit-badge { background-color: #D8B4FE; color: #5B21B6; }

    .subject-tag-elective { background-color: #FEE2E2; border-color: #FECACA; }
    .subject-tag-elective .text-main { color: #991B1B; font-weight: bold; }
    .subject-tag-elective .text-code { color: #B91C1C; }
    .subject-tag-elective .icon { color: #EF4444; }
    .subject-tag-elective .unit-badge { background-color: #FECACA; color: #991B1B; }

    .subject-tag-general { background-color: #FFEDD5; border-color: #FED7AA; }
    .subject-tag-general .text-main { color: #9A3412; font-weight: bold; }
    .subject-tag-general .text-code { color: #C2410C; }
    .subject-tag-general .icon { color: #F97316; }
    .subject-tag-general .unit-badge { background-color: #FED7AA; color: #9A3412; }
</style>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 md:p-8">
    <div>
        {{-- Main Header --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
             <div class="flex items-start gap-4">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Subject Mapping History</h1>
                    <p class="text-sm text-slate-500 mt-1">Review previously mapped subject for any curriculum.</p>
                </div>
            </div>
        </div>

        {{-- Curriculums Display Section --}}
        <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-xl font-bold text-slate-700">Mapped Curriculums</h2>
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
                        <span>Senior High</span>
                    </h3>
                    <div id="senior-high-curriculums" class="space-y-4 pt-2">
                        <p class="text-slate-500 text-sm py-4">No Senior High curriculums found.</p>
                    </div>
                </div>
                <div>
                    <h3 class="flex items-center gap-3 text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                       <span>College</span>
                    </h3>
                    <div id="college-curriculums" class="space-y-4 pt-2">
                       <p class="text-slate-500 text-sm py-4">No College curriculums found.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Curriculum History Modal --}}
        <div id="subjectsModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gray-50 border border-gray-200 w-full max-w-7xl rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col h-[90vh]" id="modal-panel-subjects">
                    <div class="p-6 border-b border-gray-200 bg-white rounded-t-2xl flex justify-between items-center">
                        <h2 id="modal-curriculum-title" class="text-2xl font-bold text-slate-800">Subjects</h2>
                        <button id="closeSubjectsModal" class="text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div id="modal-subjects-content" class="p-6 space-y-4 flex-1 overflow-y-auto"></div>
                </div>
            </div>
        </div>

        {{-- [MODAL 2] Subject Details Modal --}}
        <div id="subjectDetailsModal" class="fixed inset-0 z-[60] overflow-y-auto bg-black bg-opacity-60 transition-opacity duration-300 ease-out hidden">
            <div class="flex items-center justify-center min-h-screen p-2">
                <div class="relative bg-white w-[98vw] h-[98vh] rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col" id="modal-panel-details">
                    <div class="flex justify-between items-center p-5 border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-2xl">
                        <h2 id="detailsSubjectName" class="text-2xl font-bold text-gray-800"></h2>
                        <button id="closeSubjectDetailsModal" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div id="subject-details-content" class="p-6 flex-1 overflow-y-auto">
                        {{-- Subject details will be dynamically loaded here --}}
                    </div>
                    <div class="flex justify-between items-center p-5 mt-auto border-t border-gray-200 bg-gray-50 rounded-b-2xl sticky bottom-0 z-10">
                        <div class="text-sm text-gray-500">
                            <span class="font-semibold">Created:</span>
                            <span id="detailsCreatedAt"></span>
                        </div>
                        <div class="flex items-center gap-4">
                            <button id="exportSubjectDetailsButton" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm flex items-center gap-2" data-subject-data="">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Export to PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- [MODAL 3] Version History Modal --}}
        <div id="versionHistoryModal" class="fixed inset-0 z-[70] overflow-y-auto bg-black bg-opacity-60 transition-opacity duration-300 ease-out hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white w-full max-w-4xl max-h-[90vh] rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col" id="modal-panel-versions">
                    <div class="flex justify-between items-center p-6 border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-2xl">
                        <div>
                            <h2 id="versionHistoryTitle" class="text-2xl font-bold text-gray-800">Version History</h2>
                            <p id="versionHistorySubtitle" class="text-sm text-gray-500 mt-1"></p>
                        </div>
                        <button id="closeVersionHistoryModal" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div id="version-history-content" class="p-6 flex-1 overflow-y-auto">
                        <div id="version-loading" class="flex items-center justify-center py-12">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <span class="ml-3 text-gray-600">Loading versions...</span>
                        </div>
                        <div id="version-list" class="space-y-4 hidden"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- [MODAL 4] Version Details Modal --}}
        <div id="versionDetailsModal" class="fixed inset-0 z-[80] overflow-y-auto bg-black bg-opacity-60 transition-opacity duration-300 ease-out hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white w-full max-w-6xl max-h-[90vh] rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col" id="modal-panel-version-details">
                    <div class="flex justify-between items-center p-6 border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-2xl">
                        <div>
                            <h2 id="versionDetailsTitle" class="text-2xl font-bold text-gray-800">Version Details</h2>
                            <p id="versionDetailsSubtitle" class="text-sm text-gray-500 mt-1"></p>
                        </div>
                        <button id="closeVersionDetailsModal" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div id="version-details-content" class="p-6 flex-1 overflow-y-auto">
                        <div id="version-details-loading" class="flex items-center justify-center py-12">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <span class="ml-3 text-gray-600">Loading version details...</span>
                        </div>
                        <div id="version-details-data" class="hidden"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Containers
    const seniorHighList = document.getElementById('senior-high-curriculums');
    const collegeList = document.getElementById('college-curriculums');
    const searchBar = document.getElementById('search-bar');
    const versionFilter = document.getElementById('version-filter');
    
    // Modal 1 (Curriculum Overview)
    const subjectsModal = document.getElementById('subjectsModal');
    const subjectsModalPanel = document.getElementById('modal-panel-subjects');
    const closeSubjectsModalBtn = document.getElementById('closeSubjectsModal');
    const modalCurriculumTitle = document.getElementById('modal-curriculum-title');
    const modalSubjectsContent = document.getElementById('modal-subjects-content');

    // Modal 2 (Subject Details)
    const subjectDetailsModal = document.getElementById('subjectDetailsModal');
    const subjectDetailsModalPanel = document.getElementById('modal-panel-details');
    const closeSubjectDetailsModalBtn = document.getElementById('closeSubjectDetailsModal');
    const subjectDetailsContent = document.getElementById('subject-details-content');
    const exportSubjectDetailsButton = document.getElementById('exportSubjectDetailsButton');
    const detailsCreatedAt = document.getElementById('detailsCreatedAt');

    // --- Modal 1 Functions ---
    const showSubjectsModal = () => {
        subjectsModal.classList.remove('hidden');
        setTimeout(() => {
            subjectsModal.classList.remove('opacity-0');
            subjectsModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };
    const hideSubjectsModal = () => {
        subjectsModal.classList.add('opacity-0');
        subjectsModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => subjectsModal.classList.add('hidden'), 300);
    };
    closeSubjectsModalBtn.addEventListener('click', hideSubjectsModal);
    subjectsModal.addEventListener('click', (e) => { if (e.target === subjectsModal) hideSubjectsModal(); });

    // --- Modal 2 Functions ---
    const showSubjectDetailsModal = async (subject) => {
        // Show modal with loading state
        subjectDetailsModal.classList.remove('hidden');
        setTimeout(() => {
            subjectDetailsModal.classList.remove('opacity-0');
            subjectDetailsModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);

        // Show loading state
        subjectDetailsContent.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-500">Loading current subject data...</p>
                </div>
            </div>
        `;

        try {
            // Fetch current/live subject data from the subjects API
            const response = await fetch(`/api/subjects/${subject.id}`);
            if (!response.ok) {
                throw new Error('Failed to fetch subject data');
            }
            
            const currentSubjectData = await response.json();
            
            // Populate modal with current data
            populateDetailsModal(currentSubjectData);
            
        } catch (error) {
            console.error('Error fetching current subject data:', error);
            
            // Fallback to historical data if current data fetch fails
            subjectDetailsContent.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-yellow-600 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Unable to load current data</h3>
                    <p class="text-sm text-gray-500 mb-4">Showing historical data instead. The current subject data may have been updated since this snapshot was created.</p>
                    <button id="show-historical-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                        Show Historical Data
                    </button>
                </div>
            `;
            
            // Add event listener for historical data button
            setTimeout(() => {
                const historicalBtn = document.getElementById('show-historical-btn');
                if (historicalBtn) {
                    historicalBtn.addEventListener('click', () => {
                        populateDetailsModalWithHistoricalIndicator(subject);
                    });
                }
            }, 100);
        }
    };

    // Function to populate modal with historical data and indicator
    const populateDetailsModalWithHistoricalIndicator = (subject) => {
        populateDetailsModal(subject);
        
        // Add historical data indicator at the top
        setTimeout(() => {
            const indicator = document.createElement('div');
            indicator.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6';
            indicator.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">Showing Historical Data</span>
                    <span class="text-xs text-yellow-600 ml-2">(This data may be outdated - current updates not reflected)</span>
                </div>
            `;
            const content = subjectDetailsContent;
            content.insertBefore(indicator, content.firstChild);
        }, 50);
    };
    const hideSubjectDetailsModal = () => {
        subjectDetailsModal.classList.add('opacity-0');
        subjectDetailsModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => subjectDetailsModal.classList.add('hidden'), 300);
    };
    closeSubjectDetailsModalBtn.addEventListener('click', hideSubjectDetailsModal);
    subjectDetailsModal.addEventListener('click', (e) => { if (e.target === subjectDetailsModal) hideSubjectDetailsModal(); });

    // Export button event listener moved to the second script block where modal functions are defined

    // --- Card Creation Functions ---
    const createCurriculumCard = (curriculum) => {
        const card = document.createElement('div');
        card.className = 'curriculum-history-card group bg-white p-4 rounded-xl border border-slate-200 flex items-center gap-4 hover:border-blue-500 hover:shadow-lg transition-all duration-300 cursor-pointer';
        card.dataset.id = curriculum.id;
        card.dataset.name = curriculum.curriculum_name.toLowerCase();
        card.dataset.code = curriculum.program_code.toLowerCase();
        card.dataset.yearLevel = curriculum.year_level;
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
            <div class="flex-shrink-0 w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                <svg class="w-6 h-6 text-slate-500 group-hover:text-blue-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between">
                    <div class="flex-grow min-w-0 pr-2">
                        <h3 class="font-bold text-slate-800 group-hover:text-blue-600 truncate">${curriculum.curriculum_name}</h3>
                        <p class="text-sm text-slate-500">${curriculum.program_code} &middot; ${curriculum.academic_year}</p>
                        
                        ${curriculum.memorandum ? `
                        <p class="text-xs text-slate-400 truncate mt-1" title="${curriculum.memorandum}">
                            ${memorandumYearCategory ? `${memorandumYearCategory} ` : ''}• ${truncateText(curriculum.memorandum, 45)}
                        </p>
                        ` : memorandumYearCategory ? `
                        <p class="text-xs text-slate-400 mt-1">
                            ${memorandumYearCategory} • No memorandum selected
                        </p>
                        ` : ''}

                        <p class="text-xs text-slate-400 mt-1">
                            Created: ${formattedDate} at ${formattedTime} • 
                            <span class="font-medium">${curriculum.subjects_count || 0} subject${curriculum.subjects_count !== 1 ? 's' : ''}</span>
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
            
        // Add version history button (hidden but functional if needed)
        const versionButton = document.createElement('button');
        versionButton.className = 'hidden'; // Keeping it hidden as per design, or remove if not needed
        
        card.addEventListener('click', async () => {
            modalCurriculumTitle.textContent = `${curriculum.curriculum_name} (${curriculum.program_code})` ;
            modalSubjectsContent.innerHTML = '<p class="text-gray-500 text-center">Loading history...</p>';
            showSubjectsModal();
            try {
                const response = await fetch(`/api/curriculum-history/${curriculum.id}/versions`);
                const data = await response.json();
                
                if (data.success && data.versions && data.versions.length > 0) {
                     renderVersionHistoryInModal(data.versions, curriculum.year_level);
                } else {
                    modalSubjectsContent.innerHTML = '<p class="text-gray-500 text-center">No version history found for this curriculum.</p>';
                }
            } catch (error) {
                console.error('Failed to fetch version history:', error);
                modalSubjectsContent.innerHTML = '<p class="text-red-500 text-center">Could not load version history.</p>';
            }
        });
        return card;
    };
    
    const getSubjectTagClass = (subjectType) => {
        const geIdentifiers = ["GE", "General Education", "Gen Ed", "General"];
        if (subjectType === 'Major') return 'subject-tag-major';
        if (subjectType === 'Minor') return 'subject-tag-minor';
        if (subjectType === 'Elective') return 'subject-tag-elective';
        if (geIdentifiers.map(id => id.toLowerCase()).includes(subjectType.toLowerCase())) return 'subject-tag-general';
        return 'subject-tag-default';
    };
    
    const createSubjectTagForModal = (subject) => {
        const tag = document.createElement('div');
        const tagClass = getSubjectTagClass(subject.subject_type);
        const isRemoved = subject._isRemoved;
        
        // Apply different styling for removed subjects
        let className = `subject-tag shadow-sm rounded-lg p-3 flex items-center justify-between w-full border ${tagClass}`;
        if (isRemoved) {
            className += ' opacity-60 bg-red-50 border-red-200';
        } else {
            className += ' cursor-pointer';
        }
        
        tag.className = className;
        
        const removedIcon = isRemoved ? `
            <svg class="h-4 w-4 text-red-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        ` : '';
        
        const textStyle = isRemoved ? 'line-through text-red-600' : '';
        
        tag.innerHTML = `
            <div class="flex items-center gap-3 flex-grow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <div class="flex-grow">
                    <p class="text-sm leading-tight text-main ${textStyle}">${subject.subject_name}</p>
                    <p class="text-xs font-mono text-code ${textStyle}">${subject.subject_code}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 ml-2 flex-shrink-0">
                <span class="text-xs font-semibold px-2 py-1 rounded-full unit-badge ${isRemoved ? 'bg-red-100 text-red-700' : ''}">${subject.subject_unit} units</span>
                ${removedIcon}
                ${isRemoved ? '<span class="text-xs text-red-600 font-medium ml-2">REMOVED</span>' : ''}
            </div>`;
            
        if (!isRemoved) {
            tag.addEventListener('dblclick', () => {
                showSubjectDetailsModal(subject);
            });
        }
        return tag;
    };

    // --- NEW: Comprehensive modal populator function ---
    const populateDetailsModal = (subject) => {
        const setText = (element, value) => {
            if (element) element.textContent = value || 'N/A';
        };

        const setHtml = (element, value) => {
            if(element) element.innerHTML = value || 'N/A';
        }

        const createMappingGridHtml = (gridData, mainHeader) => {
            if (!gridData || !Array.isArray(gridData) || gridData.length === 0) {
                return '<p class="text-xs text-gray-500">No mapping grid data available.</p>';
            }
            const headers = [mainHeader, 'CTPSS', 'ECC', 'EPP', 'GLC'];
            let tableHtml = `<div class="overflow-x-auto border rounded-md"><table class="min-w-full divide-y divide-gray-200 text-xs"><thead class="bg-gray-50"><tr>${headers.map(h => `<th scope="col" class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">${h}</th>`).join('')}</tr></thead><tbody class="bg-white divide-y divide-gray-200">`;
            gridData.forEach(row => {
                const mainCellData = row[mainHeader.toLowerCase()] || '';
                tableHtml += `<tr><td class="px-3 py-2 whitespace-normal">${mainCellData}</td><td class="px-3 py-2 text-center whitespace-nowrap">${row.ctpss || ''}</td><td class="px-3 py-2 text-center whitespace-nowrap">${row.ecc || ''}</td><td class="px-3 py-2 text-center whitespace-nowrap">${row.epp || ''}</td><td class="px-3 py-2 text-center whitespace-nowrap">${row.glc || ''}</td></tr>`;
            });
            tableHtml += `</tbody></table></div>`;
            return tableHtml;
        };

        subjectDetailsContent.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-800">Showing Current Data</span>
                    <span class="text-xs text-green-600 ml-2">(Last updated: ${subject.updated_at ? new Date(subject.updated_at).toLocaleString() : 'Unknown'})</span>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Course Information</h3>
            <div id="details-course-info" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 bg-gray-50 p-4 rounded-lg"></div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Mapping Grids</h3>
            <div id="details-mapping-grids" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"></div>

            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Learning Outcomes</h3>
            <div id="details-learning-outcomes-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"></div>

            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Weekly Plan (Weeks 0-18)</h3>
            <div class="space-y-3" id="details-lessons-container"></div>

            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-8 pb-2 border-b">Course Requirements and Policies</h3>
            <div id="details-requirements-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"></div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Committee and Approval</h3>
            <div id="details-approval-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8"></div>
        `;

        // Populate Course Information
        document.getElementById('details-course-info').innerHTML = `
            <div><p class="text-sm font-medium text-gray-500">Course Title</p><p class="text-base font-semibold text-gray-800">${subject.subject_name || 'N/A'}</p></div>
            <div><p class="text-sm font-medium text-gray-500">Course Code</p><p class="text-base font-semibold text-gray-800">${subject.subject_code || 'N/A'}</p></div>
            <div><p class="text-sm font-medium text-gray-500">Course Type</p><p class="text-base font-semibold text-gray-800">${subject.subject_type || 'N/A'}</p></div>
            <div><p class="text-sm font-medium text-gray-500">Credit Units</p><p class="text-base font-semibold text-gray-800">${subject.subject_unit || 'N/A'}</p></div>
            <div><p class="text-sm font-medium text-gray-500">Contact Hours</p><p class="text-base font-semibold text-gray-800">${subject.contact_hours || 'N/A'}</p></div>
            <div><p class="text-sm font-medium text-gray-500">Credit Prerequisites</p><p class="text-base font-semibold text-gray-800">${subject.prerequisites || 'None'}</p></div>
            <div><p class="text-sm font-medium text-gray-500">Pre-requisite to</p><p class="text-base font-semibold text-gray-800">${subject.pre_requisite_to || 'None'}</p></div>
            <div class="md:col-span-4"><p class="text-sm font-medium text-gray-500">Course Description</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">${subject.course_description || 'N/A'}</div></div>
        `;

        // Populate Mapping Grids
        document.getElementById('details-mapping-grids').innerHTML = `
            <div><p class="text-sm font-medium text-gray-500">PROGRAM MAPPING GRID</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">${createMappingGridHtml(subject.program_mapping_grid, 'PILO')}</div></div>
            <div><p class="text-sm font-medium text-gray-500">COURSE MAPPING GRID</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">${createMappingGridHtml(subject.course_mapping_grid, 'CILO')}</div></div>
        `;

        // Populate Learning Outcomes
        document.getElementById('details-learning-outcomes-grid').innerHTML = `
            <div><p class="text-sm font-medium text-gray-500">PROGRAM INTENDED LEARNING OUTCOMES (PILO)</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">${subject.pilo_outcomes || 'N/A'}</div></div>
            <div><p class="text-sm font-medium text-gray-500">Course Intended Learning Outcomes (CILO)</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">${subject.cilo_outcomes || 'N/A'}</div></div>
            <div class="md:col-span-2"><p class="text-sm font-medium text-gray-500">Learning Outcomes</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">${subject.learning_outcomes || 'N/A'}</div></div>
        `;
        
        // Populate Weekly Plan
        const lessonsContainer = document.getElementById('details-lessons-container');
        if (subject.lessons && typeof subject.lessons === 'object' && Object.keys(subject.lessons).length > 0) {
            lessonsContainer.innerHTML = '';
             Object.keys(subject.lessons).sort((a, b) => parseInt(a.replace('Week ', '')) - parseInt(b.replace('Week ', ''))).forEach(week => {
                const lessonString = subject.lessons[week];
                const lessonData = {};
                const parts = lessonString.split(',, ');
                parts.forEach(part => {
                    if (part.startsWith('Detailed Lesson Content:')) lessonData.content = part.replace('Detailed Lesson Content:\\n', '').replace('Detailed Lesson Content:', '');
                    if (part.startsWith('Student Intended Learning Outcomes:')) lessonData.silo = part.replace('Student Intended Learning Outcomes:\\n', '').replace('Student Intended Learning Outcomes:', '');
                    if (part.startsWith('Assessment:')) { const match = part.match(/ONSITE: (.*) OFFSITE: (.*)/s); if(match){ lessonData.at_onsite = match[1]; lessonData.at_offsite = match[2]; }}
                    if (part.startsWith('Activities:')) { const match = part.match(/ON-SITE: (.*) OFF-SITE: (.*)/s); if(match){ lessonData.tla_onsite = match[1]; lessonData.tla_offsite = match[2]; }}
                    if (part.startsWith('Learning and Teaching Support Materials:')) lessonData.ltsm = part.replace('Learning and Teaching Support Materials:\\n', '').replace('Learning and Teaching Support Materials:', '');
                    if (part.startsWith('Output Materials:')) lessonData.output = part.replace('Output Materials:\\n', '').replace('Output Materials:', '');
                });

                const weekHTML = `<div class="border border-gray-200 rounded-lg overflow-hidden"><button type="button" class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition-colors week-toggle"><span class="font-semibold text-gray-700">${week}</span><svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button><div class="p-5 border-t border-gray-200 bg-white hidden week-content space-y-6"><div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div><label class="block text-sm font-semibold text-gray-600 mb-2">Content</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.content || ''}</div></div><div><label class="block text-sm font-semibold text-gray-600 mb-2">Student Intended Learning Outcomes</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.silo || ''}</div></div></div><div><label class="block text-sm font-semibold text-gray-600 mb-2">Assessment Tasks (ATs)</label><div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border rounded-md bg-gray-50"><div><label class="block text-xs font-bold text-gray-500 mb-1">ONSITE</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.at_onsite || ''}</div></div><div><label class="block text-xs font-bold text-gray-500 mb-1">OFFSITE</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.at_offsite || ''}</div></div></div></div><div><label class="block text-sm font-semibold text-gray-600 mb-2">Suggested Teaching/Learning Activities (TLAs)</label><div class="p-4 border rounded-md bg-gray-50"><p class="text-xs font-bold text-gray-500 mb-2">Blended Learning Delivery Modality (BLDM)</p><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-gray-500 mb-1">Face to Face (On-Site)</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.tla_onsite || ''}</div></div><div><label class="block text-xs font-bold text-gray-500 mb-1">Online (Off-Site)</label><div class="p-2 bg-white border rounded-md min-h-[80px] text-sm whitespace-pre-wrap">${lessonData.tla_offsite || ''}</div></div></div></div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div><label class="block text-sm font-semibold text-gray-600 mb-2">Learning and Teaching Support Materials (LTSM)</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.ltsm || ''}</div></div><div><label class="block text-sm font-semibold text-gray-600 mb-2">Output Materials</label><div class="p-3 bg-gray-50 border rounded-md min-h-[100px] text-sm whitespace-pre-wrap">${lessonData.output || ''}</div></div></div></div></div>`;
                lessonsContainer.innerHTML += weekHTML;
            });
             lessonsContainer.querySelectorAll('.week-toggle').forEach(button => {
                button.addEventListener('click', () => {
                    const content = button.nextElementSibling;
                    content.classList.toggle('hidden');
                    button.querySelector('svg').classList.toggle('rotate-180');
                });
            });
        } else {
            lessonsContainer.innerHTML = '<p class="text-sm text-gray-500 mt-2">No weekly plan recorded for this subject.</p>';
        }

        // Populate Requirements
        document.getElementById('details-requirements-grid').innerHTML = `
            <div><p class="text-sm font-medium text-gray-500">Basic Readings / Textbooks</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">${subject.basic_readings || 'N/A'}</div></div>
            <div><p class="text-sm font-medium text-gray-500">Extended Readings / References</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">${subject.extended_readings || 'N/A'}</div></div>
            <div class="md:col-span-2"><p class="text-sm font-medium text-gray-500">Course Assessment</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">${subject.course_assessment || 'N/A'}</div></div>
        `;

        // Populate Approval
        document.getElementById('details-approval-grid').innerHTML = `
            <div><p class="text-sm font-medium text-gray-500">Prepared By</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">${subject.prepared_by || 'N/A'}</div></div>
            <div><p class="text-sm font-medium text-gray-500">Reviewed By</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">${subject.reviewed_by || 'N/A'}</div></div>
            <div><p class="text-sm font-medium text-gray-500">Approved By</p><div class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">${subject.approved_by || 'N/A'}</div></div>
        `;

        // Populate Modal Header and Footer
        setText(document.getElementById('detailsSubjectName'), `${subject.subject_name} (${subject.subject_code})`);
        const createdAtDate = new Date(subject.created_at);
        const formattedDate = createdAtDate.toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
        setText(detailsCreatedAt, formattedDate);
        exportSubjectDetailsButton.dataset.subjectData = JSON.stringify(subject);
    };

    const renderVersionHistoryInModal = (versions, yearLevel) => {
        modalSubjectsContent.innerHTML = '';

        versions.forEach((version, index) => {
            const historyEntry = document.createElement('div');
            historyEntry.className = 'border border-gray-200 rounded-lg overflow-hidden';

            // Use the already formatted date from the API, or format the raw date if available
            const formattedDate = version.created_at || 'Unknown date';

            const isCurrent = index === 0;
            const statusText = isCurrent ? 'In Use' : 'Previous';
            const statusBgColor = isCurrent ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';

            const button = document.createElement('button');
            button.className = 'w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition-colors';
            
            // Show change description if available
            const changeDescription = version.change_description || '';
            const isRemovalVersion = changeDescription.includes('removed from curriculum');
            const isRetrievalVersion = changeDescription.includes('retrieved from history');
            const isAdditionVersion = changeDescription.includes('added to curriculum') && !isRetrievalVersion;
            
            let changeIcon = '';
            if (isRemovalVersion) {
                changeIcon = `
                    <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                `;
            } else if (isRetrievalVersion) {
                changeIcon = `
                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                `;
            } else if (isAdditionVersion) {
                changeIcon = `
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                `;
            }

            button.innerHTML = `
                <div class="flex flex-col items-start">
                    <span class="font-semibold text-gray-800">Version from: ${formattedDate}</span>
                    ${changeDescription ? `<span class="text-xs text-gray-600 mt-1 flex items-center">${changeIcon}${changeDescription}</span>` : ''}
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold px-2 py-1 rounded-full ${statusBgColor}">${statusText}</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            `;

            const content = document.createElement('div');
            content.className = 'accordion-content p-4 border-t border-gray-200 bg-white space-y-4';
            if (!isCurrent) {
                content.classList.add('hidden');
            } else {
                 button.querySelector('svg').classList.add('rotate-180');
            }

            // Get the snapshot data (already parsed as object)
            const snapshotData = version.snapshot_data;
            const subjects = snapshotData.subjects || [];
            const removedSubject = snapshotData.removed_subject;
            
            // If there's a removed subject, add it to the subjects list with a special flag
            if (removedSubject) {
                subjects.push({
                    ...removedSubject,
                    _isRemoved: true // Special flag to mark as removed
                });
            }
            
            const maxYear = yearLevel === 'Senior High' ? 2 : 4;
            for (let i = 1; i <= maxYear; i++) {
                const yearSuffix = (i === 1) ? 'st' : (i === 2 ? 'nd' : (i === 3 ? 'rd' : 'th'));
                const yearSection = document.createElement('div');
                yearSection.innerHTML = `<h4 class="text-md font-semibold text-gray-600 my-2">${i}${yearSuffix} Year</h4><div class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>`;
                const semesterGrid = yearSection.querySelector('.grid');

                for (let j = 1; j <= 2; j++) {
                    // Handle both pivot structure and direct year/semester properties
                    const semesterSubjects = subjects.filter(s => {
                        const year = s.pivot ? s.pivot.year : s.year;
                        const semester = s.pivot ? s.pivot.semester : s.semester;
                        return year == i && semester == j;
                    });
                    const semesterBox = document.createElement('div');
                    semesterBox.className = 'bg-gray-50 border-2 border-solid border-gray-200 rounded-lg p-4';
                    
                    let semesterTitle = j === 1 ? 'First Semester' : 'Second Semester';
                    if (yearLevel === 'Senior High') {
                         if (i === 1) {
                            semesterTitle = j === 1 ? 'First Quarter' : 'Second Quarter';
                        } else if (i === 2) {
                            semesterTitle = j === 1 ? 'Third Quarter' : 'Fourth Quarter';
                        }
                    }

                    let totalUnits = 0;
                    semesterSubjects.forEach(s => {
                        // Don't count removed subjects in the total units
                        if (!s._isRemoved) {
                            totalUnits += parseInt(s.subject_unit || s.units || 0, 10);
                        }
                    });
                    
                    semesterBox.innerHTML = `<div class="border-b border-gray-300 pb-2 mb-3 flex justify-between items-center"><h5 class="font-semibold text-gray-700">${semesterTitle}</h5><div class="text-sm font-bold text-gray-700">Units: ${totalUnits}</div></div><div class="space-y-2 min-h-[50px]"></div>`;

                    const subjectContainer = semesterBox.querySelector('.space-y-2');
                    if (semesterSubjects.length > 0) {
                        semesterSubjects.forEach(subject => {
                            subjectContainer.appendChild(createSubjectTagForModal(subject));
                        });
                    } else {
                        subjectContainer.innerHTML = '<p class="text-xs text-center text-gray-400 pt-4">No subjects mapped.</p>';
                    }
                    semesterGrid.appendChild(semesterBox);
                }
                content.appendChild(yearSection);
            }

            button.addEventListener('click', () => {
                content.classList.toggle('hidden');
                button.querySelector('svg').classList.toggle('rotate-180');
            });

            historyEntry.appendChild(button);
            historyEntry.appendChild(content);
            modalSubjectsContent.appendChild(historyEntry);
        });
    };

    const fetchAndDisplayCurriculums = async () => {
        try {
            const response = await fetch('/api/curriculums');
            const curriculums = await response.json();
            let seniorHighCount = 0;
            let collegeCount = 0;
            seniorHighList.innerHTML = '';
            collegeList.innerHTML = '';
            curriculums.forEach(c => {
                const card = createCurriculumCard(c);
                if (c.year_level === 'Senior High') {
                    seniorHighList.appendChild(card);
                    seniorHighCount++;
                } else if (c.year_level === 'College') {
                    collegeList.appendChild(card);
                    collegeCount++;
                }
            });
            if (seniorHighCount === 0) seniorHighList.innerHTML = '<p class="text-slate-500 text-sm py-4">No Senior High curriculums found.</p>';
            if (collegeCount === 0) collegeList.innerHTML = '<p class="text-slate-500 text-sm py-4">No College curriculums found.</p>';
            
            // Apply initial filter
            filterCurriculums();
        } catch (error) {
            console.error('Failed to fetch curriculums:', error);
            seniorHighList.innerHTML = '<p class="text-red-500">Failed to load curriculums.</p>';
            collegeList.innerHTML = '<p class="text-red-500">Failed to load curriculums.</p>';
        }
    };
    
    fetchAndDisplayCurriculums();

    // Filter Logic
    function filterCurriculums() {
        const searchTerm = searchBar.value.toLowerCase();
        const versionStatus = versionFilter.value;
        
        document.querySelectorAll('.curriculum-history-card').forEach(card => {
            const name = card.dataset.name;
            const code = card.dataset.code;
            const version = card.dataset.version;
            
            const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm);
            const matchesVersion = version === versionStatus;
            
            card.style.display = (matchesSearch && matchesVersion) ? 'flex' : 'none';
        });
    }

    searchBar.addEventListener('input', filterCurriculums);
    versionFilter.addEventListener('change', filterCurriculums);
});
</script>

{{-- Export Confirmation Modal --}}
<div id="exportConfirmationModal" class="fixed inset-0 z-[60] overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="export-modal-panel">
            <button id="closeExportModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="text-center mb-8">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-16 w-auto mb-4">
                <h2 class="text-2xl font-bold text-slate-800">Export Subject to PDF</h2>
                <p class="text-sm text-slate-500 mt-1">Generate a comprehensive PDF document with subject details.</p>
            </div>

            <div class="bg-slate-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-slate-700 mb-2">Export Details:</h3>
                <div id="export-subject-summary" class="text-sm text-slate-600">
                    <!-- Summary will be populated by JavaScript -->
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" id="cancelExportButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                <button type="button" id="confirmExportButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Export PDF</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Success Modal --}}
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Modal elements
    const exportButton = document.getElementById('exportSubjectDetailsButton');
    const exportModal = document.getElementById('exportConfirmationModal');
    const exportModalPanel = document.getElementById('export-modal-panel');
    const closeExportModalButton = document.getElementById('closeExportModalButton');
    const cancelExportButton = document.getElementById('cancelExportButton');
    const confirmExportButton = document.getElementById('confirmExportButton');
    const exportSubjectSummary = document.getElementById('export-subject-summary');
    
    // Success modal elements
    const successModal = document.getElementById('successModal');
    const successModalPanel = document.getElementById('success-modal-panel');
    const successModalTitle = document.getElementById('success-modal-title');
    const successModalMessage = document.getElementById('success-modal-message');
    const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');

    let currentSubjectData = null;

    // Modal helper functions
    const showExportModal = (subjectData) => {
        currentSubjectData = subjectData;
        
        // Populate summary
        exportSubjectSummary.innerHTML = `
            <p><strong>Subject:</strong> ${subjectData?.subject_name || 'Selected Subject'}</p>
            <p><strong>Code:</strong> ${subjectData?.subject_code || 'N/A'}</p>
            <p><strong>Format:</strong> PDF Document</p>
            <p><strong>Content:</strong> Complete subject details and lesson plans</p>
        `;
        
        exportModal.classList.remove('hidden');
        setTimeout(() => {
            exportModal.classList.remove('opacity-0');
            exportModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hideExportModal = () => {
        exportModal.classList.add('opacity-0');
        exportModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => exportModal.classList.add('hidden'), 300);
    };

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

    // Event listeners
    if (exportButton) {
        exportButton.addEventListener('click', function() {
            const subjectData = JSON.parse(this.dataset.subjectData || '{}');
            showExportModal(subjectData);
        });
    }
    
    // Also handle the export button from the subject details modal
    const exportSubjectDetailsButton = document.getElementById('exportSubjectDetailsButton');
    if (exportSubjectDetailsButton) {
        exportSubjectDetailsButton.addEventListener('click', function() {
            const subjectDataString = this.dataset.subjectData;
            if (subjectDataString) {
                const subject = JSON.parse(subjectDataString);
                showExportModal(subject);
            }
        });
    }

    // Modal close event listeners
    closeExportModalButton.addEventListener('click', hideExportModal);
    cancelExportButton.addEventListener('click', hideExportModal);
    closeSuccessModalButton.addEventListener('click', hideSuccessModal);

    // Confirm export
    confirmExportButton.addEventListener('click', async function() {
        try {
            if (currentSubjectData) {
                // Trigger the actual PDF export
                window.open(`/subjects/${currentSubjectData.id}/export-pdf`, '_blank');
                hideExportModal();
                showSuccessModal('Export Started!', 'Your PDF export is being generated and will download shortly.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while exporting the subject.');
        }
    });

    // Close modals when clicking outside
    exportModal.addEventListener('click', function(e) {
        if (e.target === this) hideExportModal();
    });

    successModal.addEventListener('click', function(e) {
        if (e.target === this) hideSuccessModal();
    });

    // --- Version History Modal Functions ---
    const versionHistoryModal = document.getElementById('versionHistoryModal');
    const versionHistoryModalPanel = document.getElementById('modal-panel-versions');
    const closeVersionHistoryModalBtn = document.getElementById('closeVersionHistoryModal');
    const versionHistoryTitle = document.getElementById('versionHistoryTitle');
    const versionHistorySubtitle = document.getElementById('versionHistorySubtitle');
    const versionHistoryContent = document.getElementById('version-history-content');
    const versionLoading = document.getElementById('version-loading');
    const versionList = document.getElementById('version-list');

    const versionDetailsModal = document.getElementById('versionDetailsModal');
    const versionDetailsModalPanel = document.getElementById('modal-panel-version-details');
    const closeVersionDetailsModalBtn = document.getElementById('closeVersionDetailsModal');
    const versionDetailsTitle = document.getElementById('versionDetailsTitle');
    const versionDetailsSubtitle = document.getElementById('versionDetailsSubtitle');
    const versionDetailsContent = document.getElementById('version-details-content');
    const versionDetailsLoading = document.getElementById('version-details-loading');
    const versionDetailsData = document.getElementById('version-details-data');

    const showVersionHistory = async (curriculumId, curriculumName) => {
        versionHistoryTitle.textContent = 'Version History';
        versionHistorySubtitle.textContent = curriculumName;
        versionLoading.classList.remove('hidden');
        versionList.classList.add('hidden');
        versionList.innerHTML = '';
        
        versionHistoryModal.classList.remove('hidden');
        setTimeout(() => {
            versionHistoryModal.classList.remove('opacity-0');
            versionHistoryModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);

        try {
            const response = await fetch(`/api/curriculum-history/${curriculumId}/versions`);
            const data = await response.json();
            
            if (data.success && data.versions.length > 0) {
                renderVersionList(data.versions, curriculumId);
            } else {
                versionList.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No version history</h3>
                        <p class="mt-1 text-sm text-gray-500">This curriculum doesn't have any saved versions yet.</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Failed to load version history:', error);
            versionList.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-red-500">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading versions</h3>
                        <p class="mt-1 text-sm text-gray-500">Could not load version history. Please try again.</p>
                    </div>
                </div>
            `;
        } finally {
            versionLoading.classList.add('hidden');
            versionList.classList.remove('hidden');
        }
    };

    const renderVersionList = (versions, curriculumId) => {
        versionList.innerHTML = versions.map((version, index) => `
            <div class="version-item bg-white border border-gray-200 rounded-lg transition-all duration-200 ${version.is_current ? 'ring-2 ring-green-200 bg-green-50' : ''}" 
                 data-version-id="${version.id}" data-curriculum-id="${curriculumId}">
                
                <!-- Version Header -->
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">v${version.version_number}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Version from: ${version.created_at}</h3>
                                <p class="text-xs text-gray-500">${version.change_description}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            ${version.is_current ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">In Use</span>' : '<span class="text-xs text-gray-500 cursor-pointer hover:text-blue-600">Previous</span>'}
                            <button class="text-blue-600 hover:text-blue-800 text-sm" onclick="toggleVersionDetails(${version.id})">
                                <svg class="w-4 h-4 transform transition-transform version-chevron-${version.id}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Curriculum Layout (Initially Hidden) -->
                <div id="version-details-${version.id}" class="version-details hidden">
                    <div class="p-4">
                        <div class="curriculum-layout space-y-6">
                            ${renderCurriculumLayout(version.snapshot_data)}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    };

    const renderCurriculumLayout = (snapshotData) => {
        if (!snapshotData || !snapshotData.subjects) {
            return '<p class="text-gray-500 text-sm">No subjects data available</p>';
        }

        const subjects = snapshotData.subjects;
        const yearGroups = {};

        // Group subjects by year
        subjects.forEach(subject => {
            const year = subject.year || 1;
            if (!yearGroups[year]) {
                yearGroups[year] = { 1: [], 2: [] };
            }
            const semester = subject.semester || 1;
            yearGroups[year][semester].push(subject);
        });

        let layoutHTML = '';
        
        // Render each year
        Object.keys(yearGroups).sort().forEach(year => {
            const yearData = yearGroups[year];
            const sem1Subjects = yearData[1] || [];
            const sem2Subjects = yearData[2] || [];
            const sem1Units = sem1Subjects.reduce((sum, s) => sum + (s.subject_unit || 0), 0);
            const sem2Units = sem2Subjects.reduce((sum, s) => sum + (s.subject_unit || 0), 0);

            layoutHTML += `
                <div class="year-section">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">${getOrdinal(year)} Year</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Semester -->
                        <div class="semester-section">
                            <div class="flex justify-between items-center mb-2">
                                <h5 class="text-xs font-medium text-gray-600">First Semester</h5>
                                <span class="text-xs text-gray-500">Units: ${sem1Units}</span>
                            </div>
                            <div class="semester-subjects space-y-2 min-h-[60px] bg-blue-50 p-3 rounded-lg border border-blue-200">
                                ${sem1Subjects.map(subject => renderSubjectCard(subject)).join('')}
                                ${sem1Subjects.length === 0 ? '<p class="text-xs text-gray-400 italic">No subjects</p>' : ''}
                            </div>
                        </div>
                        
                        <!-- Second Semester -->
                        <div class="semester-section">
                            <div class="flex justify-between items-center mb-2">
                                <h5 class="text-xs font-medium text-gray-600">Second Semester</h5>
                                <span class="text-xs text-gray-500">Units: ${sem2Units}</span>
                            </div>
                            <div class="semester-subjects space-y-2 min-h-[60px] bg-purple-50 p-3 rounded-lg border border-purple-200">
                                ${sem2Subjects.map(subject => renderSubjectCard(subject)).join('')}
                                ${sem2Subjects.length === 0 ? '<p class="text-xs text-gray-400 italic">No subjects</p>' : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        return layoutHTML;
    };

    const renderSubjectCard = (subject) => {
        const typeColors = {
            'Major': 'bg-red-100 text-red-800 border-red-200',
            'Minor': 'bg-blue-100 text-blue-800 border-blue-200',
            'Elective': 'bg-green-100 text-green-800 border-green-200',
            'General Education': 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'GE': 'bg-yellow-100 text-yellow-800 border-yellow-200'
        };
        
        const colorClass = typeColors[subject.subject_type] || 'bg-gray-100 text-gray-800 border-gray-200';
        
        return `
            <div class="subject-card-mini ${colorClass} p-2 rounded border text-xs">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">${subject.subject_code}</p>
                        <p class="text-xs opacity-75 truncate">${subject.subject_name}</p>
                    </div>
                    <span class="ml-2 text-xs font-medium">${subject.subject_unit} units</span>
                </div>
            </div>
        `;
    };

    const getOrdinal = (num) => {
        const ordinals = ['', '1st', '2nd', '3rd', '4th', '5th'];
        return ordinals[num] || `${num}th`;
    };

    const toggleVersionDetails = (versionId) => {
        const details = document.getElementById(`version-details-${versionId}`);
        const chevron = document.querySelector(`.version-chevron-${versionId}`);
        
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            details.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    };

    const showVersionDetails = async (curriculumId, versionId) => {
        versionDetailsLoading.classList.remove('hidden');
        versionDetailsData.classList.add('hidden');
        
        versionDetailsModal.classList.remove('hidden');
        setTimeout(() => {
            versionDetailsModal.classList.remove('opacity-0');
            versionDetailsModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);

        try {
            const response = await fetch(`/api/curriculum-history/${curriculumId}/versions/${versionId}`);
            const data = await response.json();
            
            if (data.success) {
                renderVersionDetails(data.version);
            } else {
                versionDetailsData.innerHTML = '<div class="text-red-500 text-center py-8">Failed to load version details</div>';
            }
        } catch (error) {
            console.error('Failed to load version details:', error);
            versionDetailsData.innerHTML = '<div class="text-red-500 text-center py-8">Error loading version details</div>';
        } finally {
            versionDetailsLoading.classList.add('hidden');
            versionDetailsData.classList.remove('hidden');
        }
    };

    const renderVersionDetails = (version) => {
        versionDetailsTitle.textContent = `Version ${version.version_number} Details`;
        versionDetailsSubtitle.textContent = `${version.created_at} by ${version.changed_by}`;

        let subjectsHtml = '';
        if (version.subjects && Object.keys(version.subjects).length > 0) {
            for (const [year, semesters] of Object.entries(version.subjects)) {
                const yearOrdinal = year == 1 ? '1st' : year == 2 ? '2nd' : year == 3 ? '3rd' : `${year}th`;
                
                for (const [semester, subjects] of Object.entries(semesters)) {
                    const semesterText = semester == 1 ? 'First Semester' : 'Second Semester';
                    const totalUnits = subjects.reduce((sum, subject) => sum + (parseInt(subject.subject_unit) || 0), 0);
                    
                    subjectsHtml += `
                        <div class="mb-6">
                            <div class="bg-gray-50 px-4 py-3 rounded-lg mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">${yearOrdinal} Year - ${semesterText}</h3>
                                <p class="text-sm text-gray-600">Units: ${totalUnits}</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                ${subjects.map(subject => `
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900 text-sm">${subject.subject_name}</h4>
                                                <p class="text-xs text-gray-500 mt-1">${subject.subject_code}</p>
                                                <div class="flex items-center mt-2 space-x-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        ${subject.subject_unit} units
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getSubjectTypeColor(subject.subject_type)}">
                                                        ${subject.subject_type}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
            }
        } else {
            subjectsHtml = '<div class="text-center py-8 text-gray-500">No subjects found in this version</div>';
        }

        versionDetailsData.innerHTML = `
            <div class="space-y-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Version Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-blue-700">Version:</span>
                            <span class="ml-2 text-blue-600">${version.version_number}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-700">Total Subjects:</span>
                            <span class="ml-2 text-blue-600">${version.total_subjects}</span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="font-medium text-blue-700">Description:</span>
                            <span class="ml-2 text-blue-600">${version.change_description}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Subjects in this Version</h3>
                    ${subjectsHtml}
                </div>
            </div>
        `;
    };

    const getSubjectTypeColor = (type) => {
        switch (type) {
            case 'Major': return 'bg-blue-100 text-blue-800';
            case 'Minor': return 'bg-purple-100 text-purple-800';
            case 'Elective': return 'bg-red-100 text-red-800';
            default: return 'bg-orange-100 text-orange-800';
        }
    };

    const hideVersionHistory = () => {
        versionHistoryModal.classList.add('opacity-0');
        versionHistoryModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => versionHistoryModal.classList.add('hidden'), 300);
    };

    const hideVersionDetails = () => {
        versionDetailsModal.classList.add('opacity-0');
        versionDetailsModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => versionDetailsModal.classList.add('hidden'), 300);
    };


    // Event listeners for version history modals
    closeVersionHistoryModalBtn.addEventListener('click', hideVersionHistory);
    versionHistoryModal.addEventListener('click', (e) => { if (e.target === versionHistoryModal) hideVersionHistory(); });
    
    closeVersionDetailsModalBtn.addEventListener('click', hideVersionDetails);
    versionDetailsModal.addEventListener('click', (e) => { if (e.target === versionDetailsModal) hideVersionDetails(); });

    // SweetAlert for success messages
    const urlParams = new URLSearchParams(window.location.search);
    const successParam = urlParams.get('success');
    const messageParam = urlParams.get('message');
    
    // Check for success parameter in URL
    if (successParam === 'added' || successParam === 'true') {
        let message = 'Subject has been successfully added to the curriculum!';
        if (messageParam) {
            message = decodeURIComponent(messageParam);
        }
        
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true,
            allowOutsideClick: true
        });
        
        // Clean up URL parameters
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
    
    // Check for session flash messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true,
            allowOutsideClick: true
        });
    @endif
    
    @if(session('subject_added'))
        Swal.fire({
            icon: 'success',
            title: 'Subject Added Successfully!',
            text: '{{ session('subject_added') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true,
            allowOutsideClick: true
        });
    @endif
});
</script>

@endsection