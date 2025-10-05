@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
    {{-- The 'container' and 'mx-auto' classes have been removed from this div --}}
    <div>
        {{-- Header --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Subject Offering History</h1>
            <p class="text-sm text-gray-500 mt-1">View, retrieve, or export subjects that have been removed from curriculums.</p>
        </div>

        {{-- Filter and Search Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-end">
                {{-- Curriculum Filter --}}
                <div>
                    <label for="curriculum_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Curriculum</label>
                    <select id="curriculum_filter" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="{{ route('subject_history') }}">All Curriculums</option>
                        @foreach($curriculums as $curriculum)
                            {{-- UPDATE: Displaying the full curriculum details in the dropdown --}}
                            <option value="{{ route('subject_history', ['curriculum_id' => $curriculum->id]) }}"
                                    {{ request('curriculum_id') == $curriculum->id ? 'selected' : '' }}>
                                {{ $curriculum->year_level }}: {{ $curriculum->program_code }} {{ $curriculum->curriculum_name }} ({{ $curriculum->academic_year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Bar --}}
                <div class="lg:col-span-2">
                    <label for="historySearchInput" class="block text-sm font-medium text-gray-700 mb-1">Search Records</label>
                    <div class="relative">
                        <input type="text" id="historySearchInput" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Search by subject name, code, academic year...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- History Records Cards --}}
        <div id="history-records-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($history as $record)
            <div class="history-record bg-white p-5 rounded-xl shadow-lg transition-all hover:shadow-2xl hover:-translate-y-1 flex flex-col">
                <div class="flex-grow">
                     <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-lg text-gray-800">{{ $record->subject_name }}</p>
                            <p class="text-sm text-gray-500">{{ $record->subject_code }}</p>
                        </div>
                        <span class="text-xs font-semibold bg-red-100 text-red-700 px-2 py-1 rounded-full">Removed</span>
                    </div>

                    <div class="mt-4 space-y-3 border-t border-gray-100 pt-4">
                        {{-- UPDATE: Corrected Curriculum and added Academic Year display --}}
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6"></path></svg>
                            <span class="font-medium text-gray-600">Curriculum:</span>
                            <span class="text-gray-800 ml-1">{{ $record->curriculum->year_level }}: {{ $record->curriculum->program_code }} {{ $record->curriculum->curriculum_name }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-medium text-gray-600">Academic Year:</span>
                            <span class="text-gray-800 ml-1">({{ $record->academic_year }})</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-medium text-gray-600">Date Removed:</span>
                            <span class="text-gray-800 ml-1">{{ $record->created_at->format('M d, Y') }}</span>
                        </div>
                         <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                            <span class="font-medium text-gray-600">Original Placement:</span>
                            <span class="text-gray-800 ml-1">{{ $record->year }} Year, {{ $record->semester == 1 ? '1st Sem' : '2nd Sem' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <button class="view-details-btn text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors" data-details="{{ json_encode($record->subject) }}">View Details</button>
                    {{-- NEW: Export Button Added --}}
                    <button class="export-btn text-sm font-bold text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition-all shadow-md hover:shadow-lg" data-subject="{{ json_encode($record->subject) }}">Export</button>
                    <button class="retrieve-btn text-sm font-bold text-white bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition-all shadow-md hover:shadow-lg" data-id="{{ $record->id }}">Retrieve</button>
                </div>
            </div>
            @empty
            <div id="no-records-message" class="col-span-full text-center py-12 bg-white rounded-2xl shadow-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No history records found</h3>
                <p class="mt-1 text-sm text-gray-500">No subjects have been removed from the selected curriculum.</p>
            </div>
            @endforelse
        </div>
    </div>
</main>

{{-- Modal for Retrieve Confirmation --}}
<div id="retrieveConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
     <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-purple-100 p-2 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M7 7l9 9M20 20v-5h-5"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Retrieve Subject</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to add this subject back to its original place in the curriculum?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelRetrieveButton" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button id="confirmRetrieveButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Yes, Retrieve</button>
            </div>
        </div>
    </div>
</div>

{{-- NEW: Modal for Export Confirmation --}}
<div id="exportConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 p-2 flex items-center justify-center mx-auto mb-4">
                 <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Export Subject to PDF</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to download a PDF file of this subject's details and lessons?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button id="cancelExportButton" class="w-full px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button id="confirmExportButton" class="w-full px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Yes, Export</button>
            </div>
        </div>
    </div>
</div>


{{-- Modal for Viewing Subject Details (NEW AND IMPROVED) --}}
<div id="subjectDetailsModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-2">
        <div class="relative bg-white w-[98vw] h-[98vh] rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col" id="modal-details-panel">
            
            {{-- Modal Header (Sticky) --}}
            <div class="flex justify-between items-center p-5 border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-2xl">
                <h2 id="detailsSubjectName" class="text-2xl font-bold text-gray-800"></h2>
                <button id="closeDetailsModalButton" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            {{-- Modal Content Scrollable Area --}}
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
                        <div id="detailsProgramMapping" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700"></div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">COURSE MAPPING GRID</p>
                        <div id="detailsCourseMapping" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700"></div>
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
                     <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Course Policies and Statements:</p>
                        <div id="detailsCoursePolicies" class="p-3 bg-white border rounded-lg text-sm text-gray-700 whitespace-pre-line"></div>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Committee Members</p>
                        <div id="detailsCommitteeMembers" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Consultation Schedule</p>
                        <div id="detailsConsultationSchedule" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700 whitespace-pre-line">N/A</div>
                    </div>
                </div>
                
                {{-- 6. Approval --}}
                 <h3 class="text-xl font-bold text-gray-800 mb-4 pt-4 pb-2 border-b">Approval</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                         <p class="text-sm font-medium text-gray-500">Prepared</p>
                        <div id="detailsPreparedBy" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">N/A</div>
                    </div>
                     <div>
                         <p class="text-sm font-medium text-gray-500">Reviewed</p>
                        <div id="detailsReviewedBy" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">N/A</div>
                    </div>
                     <div>
                         <p class="text-sm font-medium text-gray-500">Approved</p>
                        <div id="detailsApprovedBy" class="p-3 bg-white border rounded-lg min-h-[50px] text-sm text-gray-700">N/A</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- Retrieve Modal Logic ---
    const retrieveModal = document.getElementById('retrieveConfirmationModal');
    const cancelRetrieveButton = document.getElementById('cancelRetrieveButton');
    const confirmRetrieveButton = document.getElementById('confirmRetrieveButton');
    let historyIdToRetrieve = null;

    document.querySelectorAll('.retrieve-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            historyIdToRetrieve = e.target.dataset.id;
            retrieveModal.classList.remove('hidden');
        });
    });

    const hideRetrieveModal = () => {
        retrieveModal.classList.add('hidden');
        historyIdToRetrieve = null;
    };

    cancelRetrieveButton.addEventListener('click', hideRetrieveModal);
    retrieveModal.addEventListener('click', (e) => { if (e.target === retrieveModal) hideRetrieveModal(); });

    confirmRetrieveButton.addEventListener('click', async () => {
        if (!historyIdToRetrieve) return;
        try {
            const response = await fetch(`/subject_history/${historyIdToRetrieve}/retrieve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Failed to retrieve the subject.');
            alert('Subject retrieved successfully!');
            window.location.reload();
        } catch (error) {
            console.error('Error retrieving subject:', error);
            alert('Error: ' + error.message);
        } finally {
            hideRetrieveModal();
        }
    });

    // --- NEW: Export Modal and PDF Generation Logic ---
    const exportModal = document.getElementById('exportConfirmationModal');
    const cancelExportButton = document.getElementById('cancelExportButton');
    const confirmExportButton = document.getElementById('confirmExportButton');
    let subjectToExport = null;

    document.querySelectorAll('.export-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            subjectToExport = JSON.parse(e.currentTarget.dataset.subject);
            exportModal.classList.remove('hidden');
        });
    });

    const hideExportModal = () => {
        exportModal.classList.add('hidden');
        subjectToExport = null;
    };

    cancelExportButton.addEventListener('click', hideExportModal);
    exportModal.addEventListener('click', (e) => { if (e.target === exportModal) hideExportModal(); });
    
    confirmExportButton.addEventListener('click', () => {
        if (!subjectToExport) return;
        generatePDF(subjectToExport);
        hideExportModal();
    });

    const generatePDF = (subject) => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Title
        doc.setFontSize(20);
        doc.setFont("helvetica", "bold");
        doc.text("Subject Syllabus", 105, 20, null, null, "center");

        // Subject Details Table
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

        // Lessons Table
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

    // --- Details Modal Logic ---
    const detailsModal = document.getElementById('subjectDetailsModal');
    const closeDetailsButton = document.getElementById('closeDetailsModalButton');
    const modalDetailsPanel = document.getElementById('modal-details-panel');

    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const subjectData = JSON.parse(e.target.dataset.details);
            showDetailsModal(subjectData);
        });
    });

    const hideDetailsModal = () => {
        detailsModal.classList.add('opacity-0');
        modalDetailsPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => detailsModal.classList.add('hidden'), 300);
    };

    closeDetailsButton.addEventListener('click', hideDetailsModal);
    detailsModal.addEventListener('click', (e) => { if (e.target.id === 'subjectDetailsModal') hideDetailsModal(); });

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
        if (!data) return;

        const setText = (element, value) => {
            if (element) {
                element.textContent = value || 'N/A';
            }
        };

        // Set text content for all details
        setText(document.getElementById('detailsSubjectName'), `${data.subject_name} (${data.subject_code})`);
        setText(document.getElementById('detailsCourseTitle'), data.subject_name);
        setText(document.getElementById('detailsSubjectCode'), data.subject_code);
        setText(document.getElementById('detailsSubjectType'), data.subject_type);
        setText(document.getElementById('detailsSubjectUnit'), data.subject_unit);
        setText(document.getElementById('detailsContactHours'), data.contact_hours);
        setText(document.getElementById('detailsPrerequisites'), data.prerequisites);
        setText(document.getElementById('detailsPrereqTo'), data.pre_requisite_to);
        setText(document.getElementById('detailsCourseDescription'), data.course_description);
        setText(document.getElementById('detailsPILO'), data.pilo_outcomes);
        setText(document.getElementById('detailsCILO'), data.cilo_outcomes);
        setText(document.getElementById('detailsLearningOutcomes'), data.learning_outcomes);
        setText(document.getElementById('detailsBasicReadings'), data.basic_readings);
        setText(document.getElementById('detailsExtendedReadings'), data.extended_readings);
        setText(document.getElementById('detailsCourseAssessment'), data.course_assessment);
        setText(document.getElementById('detailsCoursePolicies'), data.course_policies);
        setText(document.getElementById('detailsCommitteeMembers'), data.committee_members);
        setText(document.getElementById('detailsConsultationSchedule'), data.consultation_schedule);
        setText(document.getElementById('detailsPreparedBy'), data.prepared_by);
        setText(document.getElementById('detailsReviewedBy'), data.reviewed_by);
        setText(document.getElementById('detailsApprovedBy'), data.approved_by);

        // Render mapping grids
        document.getElementById('detailsProgramMapping').innerHTML = createMappingGridHtml(data.program_mapping_grid, 'PILO');
        document.getElementById('detailsCourseMapping').innerHTML = createMappingGridHtml(data.course_mapping_grid, 'CILO');

        const lessonsContainer = document.getElementById('detailsLessonsContainer');
        lessonsContainer.innerHTML = '';
        if (data.lessons && typeof data.lessons === 'object' && Object.keys(data.lessons).length > 0) {
            Object.keys(data.lessons).sort((a, b) => parseInt(a.replace('Week ', '')) - parseInt(b.replace('Week ', ''))).forEach(week => {
                const lessonString = data.lessons[week];
                const lessonData = {};
                const parts = lessonString.split(',, ');
                parts.forEach(part => {
                    if (part.startsWith('Detailed Lesson Content:')) lessonData.content = part.replace('Detailed Lesson Content:\\n', '');
                    if (part.startsWith('Student Intended Learning Outcomes:')) lessonData.silo = part.replace('Student Intended Learning Outcomes:\\n', '');
                    if (part.startsWith('Assessment:')) {
                        const match = part.match(/ONSITE: (.*) OFFSITE: (.*)/);
                        if (match) { lessonData.at_onsite = match[1]; lessonData.at_offsite = match[2]; }
                    }
                    if (part.startsWith('Activities:')) {
                        const match = part.match(/ON-SITE: (.*) OFF-SITE: (.*)/);
                        if (match) { lessonData.tla_onsite = match[1]; lessonData.tla_offsite = match[2]; }
                    }
                    if (part.startsWith('Learning and Teaching Support Materials:')) lessonData.ltsm = part.replace('Learning and Teaching Support Materials:\\n', '');
                    if (part.startsWith('Output Materials:')) lessonData.output = part.replace('Output Materials:\\n', '');
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
                lessonsContainer.innerHTML += weekHTML;
            });
        } else {
            lessonsContainer.innerHTML = '<p class="text-sm text-gray-500 mt-2">No lesson data was archived for this subject.</p>';
        }

        document.querySelectorAll('.week-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                content.classList.toggle('hidden');
                button.querySelector('svg').classList.toggle('rotate-180');
            });
        });

        detailsModal.classList.remove('hidden');
        setTimeout(() => {
            detailsModal.classList.remove('opacity-0');
            modalDetailsPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    // --- Filter and Search Logic ---
    document.getElementById('curriculum_filter').addEventListener('change', (e) => {
        window.location.href = e.target.value;
    });

    const searchInput = document.getElementById('historySearchInput');
    const recordsContainer = document.getElementById('history-records-container');
    const allRecords = recordsContainer.querySelectorAll('.history-record');
    const noRecordsMessage = document.getElementById('no-records-message');

    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase().trim();
        let visibleCount = 0;
        allRecords.forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const isVisible = cardText.includes(searchTerm);
            card.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        if (noRecordsMessage) {
            noRecordsMessage.style.display = (allRecords.length > 0 && visibleCount === 0) ? 'block' : 'none';
        }
    });
});
</script>
@endsection