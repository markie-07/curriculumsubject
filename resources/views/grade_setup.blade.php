@extends('layouts.app')

@section('content')
<style>
    .progress-ring__circle { transition: stroke-dashoffset 0.35s; transform: rotate(-90deg); transform-origin: 50% 50%; }
    .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    .component-row input { background-color: transparent; }
    .grade-history-card { cursor: pointer; }
</style>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 sm:p-6 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Grade Scheme Setup Form --}}
        <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl p-6 md:p-8 rounded-2xl shadow-lg border border-gray-200/80">
            <form id="grade-setup-form" onsubmit="return false;">
                @csrf
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Grade Scheme Setup</h1>
                    <p class="text-sm text-gray-600 mt-1">Design and manage grading component weights for subjects.</p>
                </div>

                {{-- Subject Selection --}}
                <div class="border border-gray-200 bg-gray-50/50 p-6 rounded-xl">
                    <div class="flex items-center gap-3 pb-3 mb-4">
                        <div class="w-10 h-10 flex-shrink-0 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" /></svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-700">Select Subject</h2>
                    </div>
                    <div>
                        <label for="subject-select" class="block text-sm font-medium text-gray-600 mb-1">Subject / Course</label>
                        <select id="subject-select" class="w-full py-3 pl-4 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm transition-colors">
                            <option value="">Loading subjects...</option>
                        </select>
                    </div>
                </div>

                {{-- Grade Components --}}
                <div class="mt-8">
                    <div class="flex items-center gap-3 pb-3 mb-6">
                       <div class="w-10 h-10 flex-shrink-0 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M12 21a9 9 0 110-18 9 9 0 010 18z" /></svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-700">Semestral Grade Components</h2>
                    </div>

                    <div class="space-y-4" id="semestral-grade-accordion">
                        @include('partials.grade_component_table', ['period' => 'prelim', 'weight' => 30])
                        @include('partials.grade_component_table', ['period' => 'midterm', 'weight' => 30])
                        @include('partials.grade_component_table', ['period' => 'finals', 'weight' => 40])
                    </div>

                    <div class="mt-8 flex justify-center items-center p-4 bg-gray-100 rounded-lg border border-gray-200">
                        <div class="relative w-24 h-24">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <circle class="text-gray-200" stroke-width="10" stroke="currentColor" fill="transparent" r="45" cx="50" cy="50" />
                                <circle id="progress-circle" class="progress-ring__circle text-indigo-500" stroke-width="10" stroke-linecap="round" stroke="currentColor" fill="transparent" r="45" cx="50" cy="50" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span id="total-weight" class="text-2xl font-bold text-gray-700">100%</span>
                            </div>
                        </div>
                        <p class="ml-4 font-semibold text-gray-600">Total Weight</p>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200">
                    <button id="add-grade-btn" type="button" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6a1 1 0 10-2 0v5.586L7.707 10.293zM10 18a8 8 0 100-16 8 8 0 000 16z" /></svg>
                        Set Grade Scheme
                    </button>
                </div>
            </form>
        </div>

        {{-- Grade History --}}
        <div class="lg:col-span-1 bg-white/70 backdrop-blur-xl p-6 md:p-8 rounded-2xl shadow-lg border border-gray-200/80">
            <h2 class="text-xl font-bold text-gray-700 mb-4 pb-3 border-b">Grade History</h2>
            <div id="grade-history-container" class="space-y-4">
                 @if(isset($subjects) && $subjects->count() > 0)
                    @foreach($subjects as $subject)
                        <div class="grade-history-card p-4 border rounded-lg hover:bg-gray-50 transition-colors duration-200" data-subject-id="{{ $subject->id }}">
                            <p class="font-semibold text-gray-800">{{ $subject->subject_name }}</p>
                            <p class="text-sm text-gray-500">{{ $subject->subject_code }}</p>
                        </div>
                    @endforeach
                @else
                    <p id="no-history-message" class="text-gray-500">No subjects with grade schemes.</p>
                @endif
            </div>
        </div>
    </div>
</main>

{{-- Grade Details Modal --}}
<div id="grade-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-lg font-bold text-gray-800">Grade Component Details</h3>
            <button id="close-modal-btn" class="text-gray-500 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div id="modal-content" class="mt-4 text-sm">
            {{-- Grade details will be loaded here --}}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    let subjects = [];
    const defaultStructure = {
        prelim: { weight: 30, components: [] },
        midterm: { weight: 30, components: [] },
        finals: { weight: 40, components: [] }
    };

    const accordionContainer = document.getElementById('semestral-grade-accordion');
    const totalWeightSpan = document.getElementById('total-weight');
    const progressCircle = document.getElementById('progress-circle');
    const addGradeBtn = document.getElementById('add-grade-btn');
    const subjectSelect = document.getElementById('subject-select');
    const gradeHistoryContainer = document.getElementById('grade-history-container');
    const gradeModal = document.getElementById('grade-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const modalContent = document.getElementById('modal-content');

    const createRow = (isSub, period, component = { name: '', weight: 0 }) => {
        const tr = document.createElement('tr');
        tr.className = `component-row ${isSub ? 'sub-component-row border-l-4 border-gray-200' : 'main-component-row'} hover:bg-gray-50`;
        const namePlaceholder = isSub ? "Sub-component Name" : "Main Component Name";
        const inputClass = isSub ? 'sub-input' : 'main-input';
        
        tr.innerHTML = `
            <td class="p-2 ${isSub ? 'pl-6' : 'pl-4'} align-middle">
                <input type="text" placeholder="${namePlaceholder}" value="${component.name}" class="component-name-input w-full border-0 focus:ring-0 p-1 rounded bg-transparent">
            </td>
            <td class="p-2 w-28 align-middle">
                <input type="number" value="${component.weight}" class="${inputClass} w-full text-center font-semibold border-gray-300 rounded-lg p-2 shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
            </td>
            <td class="p-2 w-28 text-center align-middle">
                <div class="flex items-center justify-center gap-1">
                    ${!isSub ? `<button type="button" class="add-sub-btn flex items-center justify-center w-8 h-8 text-gray-400 hover:text-blue-600 hover:bg-blue-100 rounded-full transition-colors" title="Add Sub-component"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg></button>` : '<span class="w-8 h-8"></span>'}
                    <button type="button" class="remove-row-btn flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full transition-colors" title="Remove Row"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg></button>
                </div>
            </td>
        `;
        return tr;
    };

    const resizeOpenAccordion = (element) => {
        const content = element.closest('.accordion-content');
        if (content && content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    };

    const handleDynamicEvents = (e) => {
        const target = e.target;
        if (target.closest('.add-component-btn')) {
            const tbody = target.closest('.accordion-content').querySelector('.component-tbody');
            tbody.appendChild(createRow(false, tbody.closest('.period-container').dataset.period));
            resizeOpenAccordion(tbody);
        } else if (target.closest('.add-sub-btn')) {
            const parentRow = target.closest('tr');
            const newSubRow = createRow(true, parentRow.closest('.period-container').dataset.period);
            parentRow.insertAdjacentElement('afterend', newSubRow);
            resizeOpenAccordion(parentRow);
        } else if (target.closest('.remove-row-btn')) {
            const rowToRemove = target.closest('tr');
            const accordionContent = rowToRemove.closest('.accordion-content');
            if (rowToRemove.classList.contains('main-component-row')) {
                let nextRow = rowToRemove.nextElementSibling;
                while (nextRow && nextRow.classList.contains('sub-component-row')) {
                    const toRemove = nextRow;
                    nextRow = nextRow.nextElementSibling;
                    toRemove.remove();
                }
            }
            rowToRemove.remove();
            if (accordionContent && accordionContent.style.maxHeight && accordionContent.style.maxHeight !== '0px') {
                accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
            }
        }
        calculateAndUpdateTotals();
    };
    
    const calculateAndUpdateTotals = () => {
        let semestralTotal = 0;
        document.querySelectorAll('.semestral-input').forEach(input => semestralTotal += Number(input.value) || 0);
        
        totalWeightSpan.textContent = `${semestralTotal}%`;
        const radius = progressCircle.r.baseVal.value;
        const circumference = 2 * Math.PI * radius;
        progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
        const offset = circumference - (Math.min(semestralTotal, 100) / 100) * circumference;
        progressCircle.style.strokeDashoffset = offset;
        progressCircle.classList.toggle('text-red-500', semestralTotal !== 100);
        progressCircle.classList.toggle('text-indigo-500', semestralTotal === 100);

        let allSubTotalsCorrect = true;
        document.querySelectorAll('.period-container').forEach(container => {
            let periodSubTotal = 0;
            container.querySelectorAll('.main-input').forEach(input => periodSubTotal += Number(input.value) || 0);

            const subTotalSpan = container.querySelector('.sub-total');
            subTotalSpan.textContent = `${periodSubTotal}%`;
            subTotalSpan.classList.toggle('text-red-500', periodSubTotal !== 100);
            subTotalSpan.classList.toggle('text-gray-700', periodSubTotal === 100);
            if (periodSubTotal !== 100) allSubTotalsCorrect = false;

            container.querySelectorAll('.main-component-row').forEach(mainRow => {
                let subComponentTotal = 0;
                let nextRow = mainRow.nextElementSibling;
                let hasSubComponents = false;
                while (nextRow && nextRow.classList.contains('sub-component-row')) {
                    hasSubComponents = true;
                    subComponentTotal += Number(nextRow.querySelector('.sub-input').value) || 0;
                    nextRow = nextRow.nextElementSibling;
                }
                
                if (hasSubComponents && subComponentTotal !== 100) {
                    mainRow.classList.add('bg-red-100');
                    allSubTotalsCorrect = false;
                } else {
                    mainRow.classList.remove('bg-red-100');
                }
            });
        });

        addGradeBtn.disabled = semestralTotal !== 100 || !allSubTotalsCorrect || !subjectSelect.value;
    };

    const getGradeDataFromDOM = () => {
        const data = {};
        document.querySelectorAll('.period-container').forEach(container => {
            const period = container.dataset.period;
            data[period] = {
                weight: Number(container.querySelector('.semestral-input').value) || 0,
                components: []
            };
            container.querySelectorAll('.main-component-row').forEach(mainRow => {
                const mainComponent = {
                    name: mainRow.querySelector('.component-name-input').value,
                    weight: Number(mainRow.querySelector('.main-input').value) || 0,
                    sub_components: []
                };
                let nextRow = mainRow.nextElementSibling;
                while (nextRow && nextRow.classList.contains('sub-component-row')) {
                    mainComponent.sub_components.push({
                        name: nextRow.querySelector('.component-name-input').value,
                        weight: Number(nextRow.querySelector('.sub-input').value) || 0,
                    });
                    nextRow = nextRow.nextElementSibling;
                }
                data[period].components.push(mainComponent);
            });
        });
        return data;
    };

    const loadGradeDataToDOM = (componentsData) => {
        const dataToLoad = componentsData || defaultStructure;
        Object.keys(dataToLoad).forEach(period => {
            const periodData = dataToLoad[period];
            const container = document.querySelector(`.period-container[data-period="${period}"]`);
            if (!container) return;
            container.querySelector('.semestral-input').value = periodData.weight || 0;
            const tbody = container.querySelector('.component-tbody');
            tbody.innerHTML = ''; 
            (periodData.components || []).forEach(component => {
                const mainRow = createRow(false, period, component);
                tbody.appendChild(mainRow);
                (component.sub_components || []).forEach(sub => {
                    const subRow = createRow(true, period, sub);
                    tbody.appendChild(subRow);
                });
            });
        });
        calculateAndUpdateTotals();
    };

    // --- THIS IS THE UPDATED FUNCTION ---
    const fetchAPI = async (url, options = {}) => {
        try {
            // Add "/api/" to the start of the URL.
            const apiUrl = `/api/${url}`;
            options.headers = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Accept': 'application/json', ...options.headers };
            const response = await fetch(apiUrl, options);
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'API Error');
            }
            return response.json();
        } catch (error) {
            Swal.fire('Error', error.message, 'error');
            throw error;
        }
    };
    
    const fetchAndPopulateSubjects = () => {
        const urlParams = new URLSearchParams(window.location.search);
        const newSubjectId = urlParams.get('new_subject_id');
        const newSubjectName = urlParams.get('new_subject_name');

        fetchAPI('subjects').then(data => {
            subjects = data;
            subjectSelect.innerHTML = '<option value="" disabled selected>Select a Subject</option>';
            subjects.forEach(subject => {
                const option = new Option(`${subject.subject_name} (${subject.subject_code})`, subject.id);
                subjectSelect.add(option);
            });

            if (newSubjectId && newSubjectName) {
                if (![...subjectSelect.options].some(opt => opt.value == newSubjectId)) {
                    const newOption = new Option(decodeURIComponent(newSubjectName), newSubjectId);
                    subjectSelect.add(newOption);
                }
                subjectSelect.value = newSubjectId;
                subjectSelect.dispatchEvent(new Event('change'));
            }
        });
    };

    const fetchGradeSetupForSubject = (subjectId) => {
        if (!subjectId) {
            loadGradeDataToDOM(defaultStructure);
            return;
        }
        fetchAPI(`grades/${subjectId}`).then(data => {
            if (data && data.components && Object.keys(data.components).length > 0) {
                 loadGradeDataToDOM(data.components);
            } else {
                 loadGradeDataToDOM(defaultStructure);
            }
        }).catch(() => loadGradeDataToDOM(defaultStructure));
    };
    
    const addSubjectToHistory = (subject) => {
        const noHistoryMessage = document.getElementById('no-history-message');
        if (noHistoryMessage) noHistoryMessage.remove();

        if (document.querySelector(`.grade-history-card[data-subject-id="${subject.id}"]`)) return;

        const card = document.createElement('div');
        card.className = 'grade-history-card p-4 border rounded-lg hover:bg-gray-50 transition-colors duration-200';
        card.dataset.subjectId = subject.id;
        card.innerHTML = `<p class="font-semibold text-gray-800">${subject.subject_name}</p><p class="text-sm text-gray-500">${subject.subject_code}</p>`;
        gradeHistoryContainer.appendChild(card);
    };
    
    accordionContainer.addEventListener('click', (e) => {
        const toggleButton = e.target.closest('.accordion-toggle');
        if (toggleButton) {
            const content = toggleButton.nextElementSibling;
            const icon = toggleButton.querySelector('svg');
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

            document.querySelectorAll('.accordion-content').forEach(c => { c.style.maxHeight = null });
            document.querySelectorAll('.accordion-toggle svg').forEach(i => i.classList.remove('rotate-180'));

            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.classList.add('rotate-180');
            }
        } else {
            handleDynamicEvents(e);
        }
    });

    accordionContainer.addEventListener('input', calculateAndUpdateTotals);
    subjectSelect.addEventListener('change', (e) => fetchGradeSetupForSubject(e.target.value));
    
    addGradeBtn.addEventListener('click', () => {
        Swal.fire({
            title: 'Confirm Grade Scheme', text: 'Are you sure you want to save this for the selected subject?', icon: 'question', showCancelButton: true,
            confirmButtonText: 'Yes, save it!', confirmButtonColor: '#4f46e5', cancelButtonColor: '#d33',
        }).then(async (result) => {
            if (result.isConfirmed) {
                const payload = { subject_id: subjectSelect.value, components: getGradeDataFromDOM() };
                try {
                    const data = await fetchAPI('grades', { method: 'POST', body: JSON.stringify(payload) });
                    Swal.fire('Saved!', data.message, 'success');
                    addSubjectToHistory(data.subject);
                    subjectSelect.value = '';
                    loadGradeDataToDOM(defaultStructure);
                } catch(e) { /* Error is handled in fetchAPI */ }
            }
        });
    });

    gradeHistoryContainer.addEventListener('click', async (e) => {
        const card = e.target.closest('.grade-history-card');
        if (card) {
            try {
                const gradeData = await fetchAPI(`grades/${card.dataset.subjectId}`);
                let contentHtml = '<div class="space-y-4">';
                for (const [period, data] of Object.entries(gradeData.components)) {
                    contentHtml += `<div class="border rounded-lg p-3 bg-gray-50"><h4 class="font-bold capitalize text-md text-gray-700">${period} (${data.weight}%)</h4><ul class="list-disc pl-5 mt-2 space-y-1 text-gray-600">`;
                    (data.components || []).forEach(comp => {
                        contentHtml += `<li><strong>${comp.name}</strong>: ${comp.weight}%</li>`;
                        if (comp.sub_components && comp.sub_components.length > 0) {
                            contentHtml += `<ul class="list-disc pl-6">`;
                            comp.sub_components.forEach(sub => { contentHtml += `<li>${sub.name}: ${sub.weight}%</li>`; });
                            contentHtml += `</ul>`;
                        }
                    });
                    contentHtml += `</ul></div>`;
                }
                contentHtml += '</div>';
                modalContent.innerHTML = contentHtml;
                gradeModal.classList.remove('hidden');
            } catch (error) { Swal.fire('Error', 'Could not fetch grade details.', 'error'); }
        }
    });

    closeModalBtn.addEventListener('click', () => gradeModal.classList.add('hidden'));

    fetchAndPopulateSubjects();
    loadGradeDataToDOM(defaultStructure);
});
</script>
@endsection