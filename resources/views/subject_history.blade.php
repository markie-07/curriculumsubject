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


{{-- Modal for Viewing Subject Details (Consistent with subject_mapping.blade.php) --}}
<div id="subjectDetailsModal" class="fixed inset-0 z-[60] overflow-y-auto bg-black bg-opacity-60 hidden">
    <div class="flex items-start justify-center min-h-screen p-4 pt-8">
        <div class="relative bg-white w-11/12 h-[95vh] rounded-2xl shadow-2xl flex flex-col" id="modal-details-panel">
            <div class="flex justify-between items-center p-5 border-b border-gray-200">
                <h2 id="detailsSubjectName" class="text-xl font-bold text-gray-800"></h2>
                <button id="closeDetailsModalButton" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-6 flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-6">
                    <div><p class="text-sm font-medium text-gray-500">Subject Code</p><p id="detailsSubjectCode" class="text-base font-semibold text-gray-800"></p></div>
                    <div><p class="text-sm font-medium text-gray-500">Type</p><p id="detailsSubjectType" class="text-base font-semibold text-gray-800"></p></div>
                    <div><p class="text-sm font-medium text-gray-500">Unit</p><p id="detailsSubjectUnit" class="text-base font-semibold text-gray-800"></p></div>
                </div>
                <div class="space-y-2" id="detailsLessonsContainer">
                    <h3 class="text-lg font-bold text-gray-800 pt-4 border-t border-gray-200">Archived Lessons</h3>
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

    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const subjectData = JSON.parse(e.target.dataset.details);
            showDetailsModal(subjectData);
        });
    });

    const hideDetailsModal = () => detailsModal.classList.add('hidden');
    closeDetailsButton.addEventListener('click', hideDetailsModal);
    detailsModal.addEventListener('click', (e) => { if (e.target === detailsModal) hideDetailsModal(); });

    const showDetailsModal = (data) => {
        if (!data) return;
        document.getElementById('detailsSubjectName').textContent = `${data.subject_name} (${data.subject_code})`;
        document.getElementById('detailsSubjectCode').textContent = data.subject_code;
        document.getElementById('detailsSubjectType').textContent = data.subject_type;
        document.getElementById('detailsSubjectUnit').textContent = data.subject_unit;

        const lessonsContainer = document.getElementById('detailsLessonsContainer');
        lessonsContainer.innerHTML = '<h3 class="text-lg font-bold text-gray-800 pt-4 border-t border-gray-200">Archived Lessons</h3>';

        if (data.lessons && Object.keys(data.lessons).length > 0) {
             const sortedWeeks = Object.keys(data.lessons).sort((a, b) => parseInt(a.replace('Week ', '')) - parseInt(b.replace('Week ', '')));
             sortedWeeks.forEach(week => {
                const lessonContent = data.lessons[week];
                const accordionItem = document.createElement('div');
                accordionItem.className = 'border border-gray-200 rounded-lg';
                accordionItem.innerHTML = `
                    <button type="button" class="w-full flex justify-between items-center p-3 bg-gray-50 hover:bg-gray-100 transition-colors">
                        <span class="font-semibold text-sm text-gray-700">${week}</span>
                        <svg class="w-4 h-4 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="p-4 border-t border-gray-200 hidden bg-white prose prose-sm max-w-none text-gray-600 whitespace-pre-line">
                       ${lessonContent.replace(/^(Learning Objectives|Detailed Lesson Content|Activities|Assessment|Total Duration):/gm, '<strong class="block mt-2 mb-1 text-gray-800">$1:</strong>')}
                    </div>
                `;
                accordionItem.querySelector('button').addEventListener('click', (ev) => {
                    const content = ev.currentTarget.nextElementSibling;
                    content.classList.toggle('hidden');
                    ev.currentTarget.querySelector('svg').classList.toggle('rotate-180');
                });
                lessonsContainer.appendChild(accordionItem);
             });
        } else {
            lessonsContainer.innerHTML += '<p class="text-sm text-gray-500 mt-2">No lesson data was archived for this subject.</p>';
        }
        detailsModal.classList.remove('hidden');
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