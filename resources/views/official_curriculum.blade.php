@extends('layouts.app')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 md:p-8">
    <div>
        {{-- Main Header --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
            <div class="flex items-start gap-4">
                <div class="bg-green-100 text-green-600 p-3 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Official Curriculum</h1>
                    <p class="text-sm text-slate-500 mt-1">View and manage approved curriculums and their subject mappings.</p>
                </div>
            </div>
        </div>

        {{-- Curriculums Display Section --}}
        <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-xl font-bold text-slate-700">Approved Curriculums</h2>
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

        {{-- Curriculum Subjects Modal --}}
        <div id="curriculumModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gray-50 border border-gray-200 w-full max-w-7xl rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col h-[90vh]" id="modal-panel">
                    <div class="p-6 border-b border-gray-200 bg-white rounded-t-2xl flex justify-between items-center">
                        <h2 id="modal-curriculum-title" class="text-2xl font-bold text-slate-800">Curriculum Subjects</h2>
                        <button id="closeCurriculumModal" class="text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div id="modal-curriculum-content" class="p-6 space-y-4 flex-1 overflow-y-auto"></div>
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
    
    // Modal elements
    const curriculumModal = document.getElementById('curriculumModal');
    const curriculumModalPanel = document.getElementById('modal-panel');
    const closeCurriculumModalBtn = document.getElementById('closeCurriculumModal');
    const modalCurriculumTitle = document.getElementById('modal-curriculum-title');
    const modalCurriculumContent = document.getElementById('modal-curriculum-content');

    // Modal functions
    const showCurriculumModal = () => {
        curriculumModal.classList.remove('hidden');
        setTimeout(() => {
            curriculumModal.classList.remove('opacity-0');
            curriculumModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };
    
    const hideCurriculumModal = () => {
        curriculumModal.classList.add('opacity-0');
        curriculumModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => curriculumModal.classList.add('hidden'), 300);
    };
    
    closeCurriculumModalBtn.addEventListener('click', hideCurriculumModal);
    curriculumModal.addEventListener('click', (e) => { if (e.target === curriculumModal) hideCurriculumModal(); });

    // Open curriculum modal and display subjects
    const openCurriculumModal = async (curriculum) => {
        modalCurriculumTitle.textContent = `${curriculum.curriculum_name} (${curriculum.program_code})`;
        modalCurriculumContent.innerHTML = '<p class="text-gray-500 text-center">Loading subjects...</p>';
        showCurriculumModal();
        
        try {
            const response = await fetch(`/api/curriculums/${curriculum.id}`);
            const data = await response.json();
            
            if (data.curriculum && data.curriculum.subjects) {
                renderCurriculumSubjects(data.curriculum);
            } else {
                modalCurriculumContent.innerHTML = '<p class="text-gray-500 text-center">No subjects found for this curriculum.</p>';
            }
        } catch (error) {
            console.error('Failed to fetch curriculum subjects:', error);
            modalCurriculumContent.innerHTML = '<p class="text-red-500 text-center">Could not load curriculum subjects.</p>';
        }
    };

    // Render curriculum subjects in modal
    const renderCurriculumSubjects = (curriculum) => {
        const yearLevel = curriculum.year_level;
        const maxYear = yearLevel === 'Senior High' ? 2 : 4;
        let html = '';

        for (let year = 1; year <= maxYear; year++) {
            const yearSuffix = (year === 1) ? 'st' : (year === 2 ? 'nd' : (year === 3 ? 'rd' : 'th'));
            html += `<div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">${year}${yearSuffix} Year</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;

            for (let semester = 1; semester <= 2; semester++) {
                const subjects = curriculum.subjects.filter(s => s.pivot.year == year && s.pivot.semester == semester);
                
                let semesterTitle = semester === 1 ? 'First Semester' : 'Second Semester';
                if (yearLevel === 'Senior High') {
                    if (year === 1) {
                        semesterTitle = semester === 1 ? 'First Quarter' : 'Second Quarter';
                    } else if (year === 2) {
                        semesterTitle = semester === 1 ? 'Third Quarter' : 'Fourth Quarter';
                    }
                }

                let totalUnits = 0;
                subjects.forEach(s => totalUnits += parseFloat(s.subject_unit || 0));

                html += `<div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                    <div class="border-b border-gray-300 pb-2 mb-3 flex justify-between items-center">
                        <h4 class="font-semibold text-gray-700">${semesterTitle}</h4>
                        <div class="text-sm font-bold text-gray-700">Units: ${totalUnits}</div>
                    </div>
                    <div class="space-y-2 min-h-[50px]">`;

                if (subjects.length > 0) {
                    subjects.forEach(subject => {
                        const typeColors = {
                            'Major': 'bg-blue-50 border-blue-200 text-blue-800',
                            'Minor': 'bg-purple-50 border-purple-200 text-purple-800',
                            'Elective': 'bg-red-50 border-red-200 text-red-800',
                            'General Education': 'bg-orange-50 border-orange-200 text-orange-800'
                        };
                        const colorClass = typeColors[subject.subject_type] || 'bg-gray-50 border-gray-200 text-gray-800';

                        html += `<div class="${colorClass} border rounded-lg p-3 flex items-center justify-between">
                            <div class="flex items-center gap-3 flex-grow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold leading-tight">${subject.subject_name}</p>
                                    <p class="text-xs font-mono">${subject.subject_code}</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-white">${subject.subject_unit} units</span>
                        </div>`;
                    });
                } else {
                    html += '<p class="text-xs text-center text-gray-400 pt-4">No subjects mapped.</p>';
                }

                html += `</div></div>`;
            }

            html += `</div></div>`;
        }

        modalCurriculumContent.innerHTML = html;
    };

    // Card creation function
            const createCurriculumCard = (curriculum) => {
                const card = document.createElement('div');
                
                // Determine card border, icon, and title colors based on approval status
                const approvalStatus = curriculum.approval_status || 'processing';
                let cardBorderClass = 'border-slate-200 hover:border-blue-500';
                let iconBgClass = 'bg-slate-100 group-hover:bg-blue-100';
                let iconColorClass = 'text-slate-500 group-hover:text-blue-600';
                let titleColorClass = 'text-slate-800 group-hover:text-blue-600';
                
                if (approvalStatus === 'approved') {
                    cardBorderClass = 'border-green-400 hover:border-green-500';
                    iconBgClass = 'bg-green-100 group-hover:bg-green-200';
                    iconColorClass = 'text-green-600 group-hover:text-green-700';
                    titleColorClass = 'text-green-700 group-hover:text-green-800';
                } else if (approvalStatus === 'rejected') {
                    cardBorderClass = 'border-red-400 hover:border-red-500';
                    iconBgClass = 'bg-red-100 group-hover:bg-red-200';
                    iconColorClass = 'text-red-600 group-hover:text-red-700';
                    titleColorClass = 'text-red-700 group-hover:text-red-800';
                }
                
                // Add cursor-pointer since it opens a modal
                card.className = `curriculum-card group relative bg-white p-4 rounded-xl border ${cardBorderClass} flex items-center gap-4 hover:shadow-lg transition-all duration-300 cursor-pointer`;
                card.dataset.name = curriculum.curriculum_name.toLowerCase();
                card.dataset.code = curriculum.program_code.toLowerCase();
                card.dataset.id = curriculum.id;
                card.dataset.version = curriculum.version_status || 'new';
                card.dataset.approvalStatus = approvalStatus;

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
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
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

                // Approval status badge
                let approvalBadge = '';
                if (approvalStatus === 'approved') {
                    approvalBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        Approved
                    </span>`;
                } else if (approvalStatus === 'rejected') {
                    approvalBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                        Rejected
                    </span>`;
                } else {
                    approvalBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                        </svg>
                        Processing
                    </span>`;
                }

                // Format memorandum year/category display
                const memorandumYearCategory = curriculum.memorandum_year 
                    ? curriculum.memorandum_year
                    : curriculum.memorandum_category 
                        ? curriculum.memorandum_category 
                        : '';

                card.innerHTML = `
                    <div class="flex-shrink-0 w-10 h-10 ${iconBgClass} rounded-lg flex items-center justify-center transition-colors duration-300">
                        <svg class="w-5 h-5 ${iconColorClass} transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-grow min-w-0 pr-2">
                                <h3 class="font-bold ${titleColorClass} transition-colors duration-300 truncate mb-1">${curriculum.curriculum_name}</h3>
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
                                <div class="flex items-center gap-1 flex-wrap justify-end">
                                    ${complianceBadge}
                                    ${totalUnitsDisplay}
                                    ${versionBadge}
                                    ${approvalBadge}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add click event listener to open modal
                card.addEventListener('click', () => openCurriculumModal(curriculum));
                
                return card;
            };

    // Fetch and display curriculums
    const fetchAndDisplayCurriculums = async () => {
        try {
            const response = await fetch('/api/curriculums');
            const curriculums = await response.json();
            
            // Filter only approved curriculums
            const approvedCurriculums = curriculums.filter(c => c.approval_status === 'approved');
            
            let seniorHighCount = 0;
            let collegeCount = 0;
            seniorHighList.innerHTML = '';
            collegeList.innerHTML = '';
            
            approvedCurriculums.forEach(c => {
                const card = createCurriculumCard(c);
                if (c.year_level === 'Senior High') {
                    seniorHighList.appendChild(card);
                    seniorHighCount++;
                } else if (c.year_level === 'College') {
                    collegeList.appendChild(card);
                    collegeCount++;
                }
            });
            
            if (seniorHighCount === 0) seniorHighList.innerHTML = '<p class="text-slate-500 text-sm py-4">No approved Senior High curriculums found.</p>';
            if (collegeCount === 0) collegeList.innerHTML = '<p class="text-slate-500 text-sm py-4">No approved College curriculums found.</p>';
            
            // Apply initial filter
            filterCurriculums();
        } catch (error) {
            console.error('Failed to fetch curriculums:', error);
            seniorHighList.innerHTML = '<p class="text-red-500">Failed to load curriculums.</p>';
            collegeList.innerHTML = '<p class="text-red-500">Failed to load curriculums.</p>';
        }
    };

    // Filter curriculums
    const filterCurriculums = () => {
        const searchTerm = searchBar.value.toLowerCase();
        const versionValue = versionFilter.value;
        const cards = document.querySelectorAll('.curriculum-card');
        
        cards.forEach(card => {
            const name = card.dataset.name;
            const code = card.dataset.code;
            const version = card.dataset.version;
            
            const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm);
            const matchesVersion = version === versionValue;
            
            if (matchesSearch && matchesVersion) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    };

    // Event listeners
    searchBar.addEventListener('input', filterCurriculums);
    versionFilter.addEventListener('change', filterCurriculums);

    // Initial load
    fetchAndDisplayCurriculums();
});
</script>
@endsection
