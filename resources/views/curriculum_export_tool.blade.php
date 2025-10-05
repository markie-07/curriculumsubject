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

        // Redirect to a new route that will handle the PDF generation
        window.open(`/curriculum/${curriculumId}/export-pdf`, '_blank');

        try {
            const curriculumName = curriculumSelect.options[curriculumSelect.selectedIndex].text;
            const fileName = `${curriculumName}.pdf`;
            const newHistory = await saveExportHistory(curriculumId, fileName, 'PDF');
            addHistoryItemToDOM(newHistory);
        } catch (error) {
            console.error('Error saving export history:', error);
            alert('An error occurred while saving the export history. Please check the console for details.');
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
});
</script>
@endsection