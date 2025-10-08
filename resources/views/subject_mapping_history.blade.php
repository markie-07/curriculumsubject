@extends('layouts.app')

@section('content')
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
                    <p class="text-sm text-slate-500 mt-1">Review previously mapped subjects for any curriculum.</p>
                </div>
            </div>
        </div>

        {{-- Curriculums Display Section --}}
        <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
             <h2 class="text-xl font-bold text-slate-700 mb-6">Mapped Curriculums</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-10">
                <div>
                    <h3 class="flex items-center gap-3 text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span>Senior High</span>
                    </h3>
                    <div id="senior-high-curriculums" class="space-y-4 pt-2">
                        <p class="text-slate-500 text-sm py-4">No Senior High curriculums found.</p>
                    </div>
                </div>
                <div>
                    <h3 class="flex items-center gap-3 text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                       <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222 4 2.222V20M1 12v7a2 2 0 002 2h18a2 2 0 002-2v-7" /></svg>
                       <span>College</span>
                    </h3>
                    <div id="college-curriculums" class="space-y-4 pt-2">
                       <p class="text-slate-500 text-sm py-4">No College curriculums found.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- [MODAL 1] Curriculum Overview Modal --}}
        <div id="subjectsModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gray-50 border border-gray-200 w-full max-w-7xl rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col h-[90vh]" id="modal-panel-subjects">
                    <div class="p-6 border-b border-gray-200 bg-white rounded-t-2xl flex justify-between items-center">
                        <h2 id="modal-curriculum-title" class="text-2xl font-bold text-slate-800">Subjects</h2>
                        <button id="closeSubjectsModal" class="text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div id="modal-subjects-content" class="p-6 space-y-6 flex-1 overflow-y-auto"></div>
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
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Containers
    const seniorHighList = document.getElementById('senior-high-curriculums');
    const collegeList = document.getElementById('college-curriculums');
    
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
    const showSubjectDetailsModal = (subject) => {
        populateDetailsModal(subject);
        subjectDetailsModal.classList.remove('hidden');
        setTimeout(() => {
            subjectDetailsModal.classList.remove('opacity-0');
            subjectDetailsModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };
    const hideSubjectDetailsModal = () => {
        subjectDetailsModal.classList.add('opacity-0');
        subjectDetailsModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => subjectDetailsModal.classList.add('hidden'), 300);
    };
    closeSubjectDetailsModalBtn.addEventListener('click', hideSubjectDetailsModal);
    subjectDetailsModal.addEventListener('click', (e) => { if (e.target === subjectDetailsModal) hideSubjectDetailsModal(); });

    exportSubjectDetailsButton.addEventListener('click', () => {
        const subjectDataString = exportSubjectDetailsButton.dataset.subjectData;
        if (subjectDataString) {
            const subject = JSON.parse(subjectDataString);
            window.open(`/subjects/${subject.id}/export-pdf`, '_blank');
        }
    });

    // --- Card Creation Functions ---
    const createCurriculumCard = (curriculum) => {
        const card = document.createElement('div');
        card.className = 'curriculum-history-card group bg-white p-4 rounded-xl border border-slate-200 flex items-center gap-4 hover:border-blue-500 hover:shadow-lg transition-all duration-300 cursor-pointer';
        card.dataset.id = curriculum.id;
        card.dataset.name = curriculum.curriculum_name;
        card.dataset.code = curriculum.program_code;
        card.dataset.yearLevel = curriculum.year_level;
        const date = new Date(curriculum.created_at);
        const formattedDate = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        card.innerHTML = `
            <div class="flex-shrink-0 w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                <svg class="w-6 h-6 text-slate-500 group-hover:text-blue-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 group-hover:text-blue-600">${curriculum.curriculum_name}</h3>
                <p class="text-sm text-slate-500">${curriculum.program_code} &middot; ${curriculum.academic_year}</p>
                 <p class="text-xs text-slate-400 mt-1">Created: ${formattedDate}</p>
            </div>`;
        card.addEventListener('dblclick', async () => {
            modalCurriculumTitle.textContent = `${curriculum.curriculum_name} (${curriculum.program_code})`;
            modalSubjectsContent.innerHTML = '<p class="text-gray-500 text-center">Loading subjects...</p>';
            showSubjectsModal();
            try {
                const response = await fetch(`/api/curriculums/${curriculum.id}`);
                const data = await response.json();
                if (data.curriculum && data.curriculum.subjects.length > 0) {
                     renderModalContent(data.curriculum.subjects, curriculum.year_level);
                } else {
                    modalSubjectsContent.innerHTML = '<p class="text-gray-500 text-center">No subjects have been mapped to this curriculum yet.</p>';
                }
            } catch (error) {
                console.error('Failed to fetch subjects:', error);
                modalSubjectsContent.innerHTML = '<p class="text-red-500 text-center">Could not load subjects.</p>';
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
        tag.className = `subject-tag shadow-sm rounded-lg p-3 flex items-center justify-between w-full border ${tagClass} cursor-pointer`;
        tag.innerHTML = `
            <div class="flex items-center gap-3 flex-grow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <div class="flex-grow">
                    <p class="text-sm leading-tight text-main">${subject.subject_name}</p>
                    <p class="text-xs font-mono text-code">${subject.subject_code}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 ml-2 flex-shrink-0">
                <span class="text-xs font-semibold px-2 py-1 rounded-full unit-badge">${subject.subject_unit} units</span>
            </div>`;
        tag.addEventListener('dblclick', () => {
            showSubjectDetailsModal(subject);
        });
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
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Course Information</h3>
            <div id="details-course-info" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 bg-gray-50 p-4 rounded-lg"></div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Mapping Grids</h3>
            <div id="details-mapping-grids" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"></div>

            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Learning Outcomes</h3>
            <div id="details-learning-outcomes-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"></div>

            <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Weekly Plan (Weeks 1-15)</h3>
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

    const renderModalContent = (subjects, yearLevel) => {
        const maxYear = yearLevel === 'Senior High' ? 2 : 4;
        const subjectsByYearSem = {};
        subjects.forEach(subject => {
            const key = `${subject.pivot.year}-${subject.pivot.semester}`;
            if (!subjectsByYearSem[key]) {
                subjectsByYearSem[key] = { units: 0, subjects: [] };
            }
            subjectsByYearSem[key].subjects.push(subject);
            subjectsByYearSem[key].units += parseInt(subject.subject_unit, 10);
        });
        modalSubjectsContent.innerHTML = ''; 
        for (let i = 1; i <= maxYear; i++) {
            const yearSuffix = (i === 1) ? 'st' : (i === 2 ? 'nd' : (i === 3 ? 'rd' : 'th'));
            const yearSection = document.createElement('div');
            yearSection.innerHTML = `<h3 class="text-lg font-semibold text-gray-700 mb-3">${i}${yearSuffix} Year</h3><div class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>`;
            const semesterGrid = yearSection.querySelector('.grid');
            for (let j = 1; j <= 2; j++) {
                const semesterBox = document.createElement('div');
                semesterBox.className = 'bg-white border-2 border-solid border-gray-300 rounded-lg p-4';
                const key = `${i}-${j}`;
                const semData = subjectsByYearSem[key];
                const semesterTitle = j === 1 ? 'First Semester' : 'Second Semester';
                semesterBox.innerHTML = `<div class="border-b border-gray-200 pb-2 mb-3 flex justify-between items-center"><h4 class="font-semibold text-gray-600">${semesterTitle}</h4><div class="text-sm font-bold text-gray-700">Units: ${semData ? semData.units : 0}</div></div><div class="space-y-2 min-h-[80px]"></div>`;
                const subjectContainer = semesterBox.querySelector('.space-y-2');
                if (semData) {
                    semData.subjects.forEach(subject => {
                        const subjectTagElement = createSubjectTagForModal(subject);
                        subjectContainer.appendChild(subjectTagElement);
                    });
                } else {
                    subjectContainer.innerHTML = '<p class="text-xs text-center text-gray-400 pt-8">No subjects mapped.</p>';
                }
                semesterGrid.appendChild(semesterBox);
            }
            modalSubjectsContent.appendChild(yearSection);
        }
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
        } catch (error) {
            console.error('Failed to fetch curriculums:', error);
            seniorHighList.innerHTML = '<p class="text-red-500">Failed to load curriculums.</p>';
            collegeList.innerHTML = '<p class="text-red-500">Failed to load curriculums.</p>';
        }
    };
    
    fetchAndDisplayCurriculums();
});
</script>
@endsection