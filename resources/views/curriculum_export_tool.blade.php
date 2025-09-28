@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
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
                    <div>
                        <label for="curriculum-select" class="block text-sm font-medium text-gray-700 mb-2">Select Curriculum</label>
                        <select id="curriculum-select" class="mt-1 block w-full py-3 px-4 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm transition-all">
                            <option value="">-- Please select a curriculum --</option>
                            @foreach($curriculums as $curriculum)
                                <option value="{{ $curriculum->id }}">{{ $curriculum->curriculum }} ({{ $curriculum->program_code }})</option>
                            @endforeach
                        </select>
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
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Export History</h2>
            
            <div class="relative mb-4">
                <input type="text" id="history-search" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Search history...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            
            <div id="export-history" class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const exportButton = document.getElementById('export-curriculum-btn');
    const curriculumSelect = document.getElementById('curriculum-select');
    const exportHistoryContainer = document.getElementById('export-history');
    const noHistoryMessage = document.getElementById('no-history-msg');
    const historySearchInput = document.getElementById('history-search');

    curriculumSelect.addEventListener('change', function() {
        exportButton.disabled = !this.value;
    });

    exportButton.addEventListener('click', async function () {
        const curriculumId = curriculumSelect.value;
        if (!curriculumId) {
            alert('Please select a curriculum to export.');
            return;
        }

        try {
            const response = await fetch(`/api/curriculum/${curriculumId}/details`);
            if (!response.ok) throw new Error('Failed to fetch curriculum details.');
            const curriculum = await response.json();
            const fileName = `${curriculum.program_code}_${curriculum.curriculum}.pdf`;
            
            generatePdf(curriculum, fileName);
            
            const newHistory = await saveExportHistory(curriculumId, fileName, 'PDF');
            addHistoryItemToDOM(newHistory);

        } catch (error) {
            console.error('Export Error:', error);
            alert('An error occurred while exporting the curriculum. Please check the console for details.');
        }
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

    const addHeader = (doc) => {
        const pageWidth = doc.internal.pageSize.getWidth();
        const margin = 15;
        
        const newLogo = `{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/bcp logo.png'))) }}`;
        doc.addImage(newLogo, 'PNG', margin, 5, 20, 20);

        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFont('helvetica', 'bold');
        doc.text('BESTLINK COLLEGE OF THE PHILIPPINES', pageWidth / 2, 12, { align: 'center' });

        doc.setFontSize(9);
        doc.setFont('helvetica', 'normal');
        doc.text('#1071 Brgy. Kaligayahan, Quirino Hi-way, Novaliches, Quezon City', pageWidth / 2, 17, { align: 'center' });
    };

    function generatePdf(curriculum, fileName) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pageWidth = doc.internal.pageSize.getWidth();
        const pageHeight = doc.internal.pageSize.getHeight();
        const margin = 15;
        let y = 0;

        const addFooter = (doc) => {
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(150);
                doc.text(`Page ${i} of ${pageCount}`, pageWidth / 2, pageHeight - 10, { align: 'center' });
            }
        };

        const checkPageBreak = (currentY, title) => {
            if (currentY > pageHeight - 30) {
                doc.addPage();
                addHeader(doc, title);
                return 30; // Reset Y to top margin
            }
            return currentY;
        };
        
        // --- Title Page ---
        addHeader(doc);
        let yPos = 80;
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(26);
        doc.setTextColor(40);
        const curriculumTitle = curriculum.curriculum;
        const titleOptions = { align: 'center', maxWidth: pageWidth - (margin * 2) };
        doc.text(curriculumTitle, pageWidth / 2, yPos, titleOptions);
        const titleHeight = doc.getTextDimensions(curriculumTitle, titleOptions).h;
        yPos += titleHeight + 10;
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(16);
        doc.setTextColor(100);
        doc.text(`Program Code: ${curriculum.program_code}`, pageWidth / 2, yPos, { align: 'center' });
        yPos += 8;
        doc.text(`Academic Year: ${curriculum.academic_year}`, pageWidth / 2, yPos, { align: 'center' });
        yPos += 8;
        doc.text(`Year: ${curriculum.year_level}`, pageWidth / 2, yPos, { align: 'center' });
        
        // --- Curriculum Details Page ---
        doc.addPage();
        addHeader(doc, 'Curriculum Details');
        y = 30;

        doc.setFontSize(18);
        doc.setFont('helvetica', 'bold');
        doc.text('Curriculum Details', margin, y);
        y += 15;

        const subjectsByYearAndSem = {};
        curriculum.subjects.forEach(subject => {
            const key = `${subject.pivot.year}-${subject.pivot.semester}`;
            if (!subjectsByYearAndSem[key]) subjectsByYearAndSem[key] = [];
            subjectsByYearAndSem[key].push(subject);
        });
        const sortedKeys = Object.keys(subjectsByYearAndSem).sort();

        for (const key of sortedKeys) {
            y = checkPageBreak(y, 'Curriculum Details');
            const [year, semester] = key.split('-');
            const semesterText = semester == 1 ? 'First Semester' : (semester == 2 ? 'Second Semester' : 'Summer');
            
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text(`${getYearOrdinal(year)} Year - ${semesterText}`, margin, y);
            y += 10;

            for (const subject of subjectsByYearAndSem[key]) {
                y = checkPageBreak(y, 'Curriculum Details');
                
                // Subject Summary
                const prerequisites = subject.prerequisites.map(p => p.prerequisite_subject_code).join(', ') || 'None';
                doc.autoTable({
                    startY: y,
                    head: [['Code', 'Subject Name', 'Units', 'Prerequisites']],
                    body: [[subject.subject_code, subject.subject_name, subject.subject_unit, prerequisites]],
                    theme: 'striped',
                    headStyles: { fillColor: [22, 160, 133], textColor: 255 },
                    styles: { font: 'helvetica', fontSize: 10 },
                    margin: { left: margin, right: margin }
                });
                y = doc.lastAutoTable.finalY + 10;
                y = checkPageBreak(y, 'Curriculum Details');

                // Subject Syllabus Details
                doc.autoTable({
                    startY: y,
                    body: [
                        ['Subject Name:', subject.subject_name],
                        ['Subject Code:', subject.subject_code],
                        ['Subject Type:', subject.subject_type],
                        ['Units:', subject.subject_unit.toString()]
                    ],
                    theme: 'grid',
                    styles: { font: 'helvetica', fontSize: 11, cellPadding: 3 },
                    columnStyles: { 0: { fontStyle: 'bold', fillColor: '#f0f0f0' } },
                    margin: { left: margin, right: margin }
                });
                y = doc.lastAutoTable.finalY + 10;
                y = checkPageBreak(y, 'Curriculum Details');

                // Weekly Topics
                const lessonsData = [];
                if (subject.lessons && typeof subject.lessons === 'object') {
                    for (let i = 1; i <= 15; i++) {
                        const week = `Week ${i}`;
                        const lessonText = subject.lessons[week] || 'N/A';
                        const formattedLesson = lessonText.includes("Learning Objectives:") ? formatLessonPlan(lessonText, doc, margin, pageWidth) : [lessonText];
                        lessonsData.push([week, formattedLesson.join('\n')]);
                    }
                }
                
                doc.autoTable({
                    startY: y,
                    head: [['Week', 'Lesson / Topics']],
                    body: lessonsData,
                    theme: 'grid',
                    headStyles: { fillColor: [44, 62, 80], textColor: 255 },
                    styles: { font: 'helvetica', fontSize: 9, cellPadding: 2, valign: 'middle' },
                    columnStyles: { 0: { cellWidth: 20, fontStyle: 'bold' }, 1: { cellWidth: 'auto' } },
                    margin: { left: margin, right: margin }
                });
                y = doc.lastAutoTable.finalY + 20; // Add space after each subject
            }
        }

        addFooter(doc);
        doc.save(fileName);
    }
    
    function formatLessonPlan(lessonText, doc, margin, pageWidth) {
        const formattedLines = [];
        const sections = {
            "Learning Objectives:": [],
            "Detailed Lesson Content:": [],
            "Activities:": [],
            "Assessment:": []
        };
        let currentSection = null;

        const lines = lessonText.split(',, ');
        lines.forEach(line => {
            const trimmedLine = line.trim();
            if (sections.hasOwnProperty(trimmedLine)) {
                currentSection = trimmedLine;
            } else if (currentSection) {
                sections[currentSection].push(trimmedLine);
            } else {
                formattedLines.push(trimmedLine);
            }
        });

        for (const section in sections) {
            if (sections[section].length > 0) {
                formattedLines.push(`\n**${section}**`);
                sections[section].forEach(item => {
                    const bulletPoints = item.split('- ');
                    if(bulletPoints.length > 1){
                        bulletPoints.slice(1).forEach(point => {
                             const wrappedText = doc.splitTextToSize(`- ${point.trim()}`, pageWidth - margin * 2 - 30);
                             formattedLines.push(...wrappedText);
                        });
                    } else {
                        const wrappedText = doc.splitTextToSize(item.trim(), pageWidth - margin * 2 - 30);
                        formattedLines.push(...wrappedText);
                    }
                });
            }
        }
        
        return formattedLines;
    }

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
                </div>
            </div>
        `;
        exportHistoryContainer.prepend(item);
    }

    function getYearOrdinal(year) {
        if (year === '1') return '1st';
        if (year === '2') return '2nd';
        if (year === '3') return '3rd';
        return `${year}th`;
    }
});
</script>
@endsection