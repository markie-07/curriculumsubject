@extends('layouts.app')

@section('content')
<style>
    .progress-ring__circle { transition: stroke-dashoffset 0.35s; transform: rotate(-90deg); transform-origin: 50% 50%; }
    .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    .component-row input { background-color: transparent; }
    .grade-history-card { cursor: pointer; }
    #grade-modal.opacity-0 { opacity: 0; }
    #grade-modal-panel.opacity-0 { opacity: 0; }
    #grade-modal-panel.scale-95 { transform: scale(0.95); }
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
                    <div class="flex items-center justify-between gap-3 pb-3 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex-shrink-0 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M12 21a9 9 0 110-18 9 9 0 010 18z" /></svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-700">Semestral Grade Components</h2>
                        </div>
                        <button id="add-grade-component-btn" type="button" class="flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors py-2 px-3 rounded-lg hover:bg-indigo-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            Add Grade Component
                        </button>
                    </div>

                    <div class="space-y-4" id="semestral-grade-accordion">
                        {{-- Grade components will be dynamically inserted here --}}
                    </div>

                    <div class="mt-8 flex justify-center items-center p-4 bg-gray-100 rounded-lg border border-gray-200">
                        <div class="relative w-24 h-24">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <circle class="text-gray-200" stroke-width="10" stroke="currentColor" fill="transparent" r="45" cx="50" cy="50" />
                                <circle id="progress-circle" class="progress-ring__circle text-indigo-500" stroke-width="10" stroke-linecap="round" stroke="currentColor" fill="transparent" r="45" cx="50" cy="50" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span id="total-weight" class="text-xl font-bold text-gray-700">0%</span>
                            </div>
                        </div>
                        <p class="ml-4 font-semibold text-gray-600">Total Weight</p>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200">
                    <button id="setGradeSchemeButton" type="button" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6a1 1 0 10-2 0v5.586L7.707 10.293zM10 18a8 8 0 100-16 8 8 0 000 16z" /></svg>
                        Set Grade Scheme
                    </button>
                    <button id="update-grade-setup-btn" class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed hidden">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                        Update Grade Scheme
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
<div id="grade-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300 opacity-0">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0" id="grade-modal-panel">
        {{-- Modal Header --}}
        <div class="flex justify-between items-center p-5 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex-shrink-0 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M12 21a9 9 0 110-18 9 9 0 010 18z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Grade Component Details</h3>
            </div>
            <button id="close-modal-btn" class="text-gray-400 hover:text-gray-700 transition-colors rounded-full p-1 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Modal Content --}}
        <div id="modal-content" class="p-8 max-h-[60vh] overflow-y-auto bg-gray-50">
            {{-- Grade details will be loaded here --}}
        </div>

        {{-- Modal Footer --}}
        <div class="flex justify-end p-5 bg-white border-t border-gray-200 rounded-b-2xl">
            <button id="edit-grade-setup-btn" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold py-2 px-5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Edit
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    let subjects = [];
    const defaultStructure = {}; // Start with an empty structure

    // Main form elements
    const accordionContainer = document.getElementById('semestral-grade-accordion');
    const totalWeightSpan = document.getElementById('total-weight');
    const progressCircle = document.getElementById('progress-circle');
    const addGradeBtn = document.getElementById('setGradeSchemeButton');
    const updateGradeSetupBtn = document.getElementById('update-grade-setup-btn');
    const subjectSelect = document.getElementById('subject-select');
    const gradeHistoryContainer = document.getElementById('grade-history-container');
    const addGradeComponentBtn = document.getElementById('add-grade-component-btn');

    // Modal elements
    const gradeModal = document.getElementById('grade-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const modalContent = document.getElementById('modal-content');
    const editGradeSetupBtn = document.getElementById('edit-grade-setup-btn');
    
    // State
    let isEditMode = false;
    let currentSubjectId = null;
    let componentCounter = 0;

    const createGradeComponent = (period = `component${++componentCounter}`, weight = 0, components = []) => {
        const componentContainer = document.createElement('div');
        componentContainer.className = 'period-container border border-gray-200/80 bg-white rounded-xl shadow-sm overflow-hidden';
        componentContainer.dataset.period = period;
        
        componentContainer.innerHTML = `
            <div class="accordion-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                <div class="flex items-center gap-4">
                    <input type="text" value="${period.startsWith('component') ? '' : period}" placeholder="Component Name (e.g., Midterm)" class="component-name-input font-semibold text-lg text-gray-700 capitalize border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="flex items-center">
                        <input type="number" value="${weight}" class="semestral-input w-20 text-center font-bold border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="ml-2 text-lg text-gray-600">%</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Sub-total: <span class="sub-total font-bold text-gray-700">100%</span></span>
                     <button type="button" class="remove-component-btn flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full transition-colors" title="Remove Component">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                    </button>
                    <svg class="w-6 h-6 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            <div class="accordion-content bg-gray-50/50 border-t border-gray-200/80">
                <div class="p-4">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr>
                                <th class="p-2 text-left font-semibold text-gray-600">Sub-Component</th>
                                <th class="p-2 text-center font-semibold text-gray-600 w-28">Weight (%)</th>
                                <th class="p-2 text-center font-semibold text-gray-600 w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="component-tbody"></tbody>
                    </table>
                    <div class="mt-4 flex justify-end">
                        <button type="button" class="add-component-btn inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors py-2 px-3 rounded-lg hover:bg-indigo-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            Add Main Component
                        </button>
                    </div>
                </div>
            </div>`;
            
        const tbody = componentContainer.querySelector('.component-tbody');
        components.forEach(comp => {
            const mainRow = createRow(false, period, comp);
            tbody.appendChild(mainRow);
            (comp.sub_components || []).forEach(sub => {
                const subRow = createRow(true, period, sub);
                tbody.appendChild(subRow);
            });
        });
        
        return componentContainer;
    };

    addGradeComponentBtn.addEventListener('click', () => {
        accordionContainer.appendChild(createGradeComponent());
        calculateAndUpdateTotals();
    });

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
        } else if (target.closest('.remove-component-btn')) {
            target.closest('.period-container').remove();
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
        
        const saveButtonIsDisabled = semestralTotal !== 100 || !allSubTotalsCorrect || !subjectSelect.value;
        addGradeBtn.disabled = saveButtonIsDisabled;
        updateGradeSetupBtn.disabled = saveButtonIsDisabled;
    };

    const getGradeDataFromDOM = () => {
        const data = {};
        document.querySelectorAll('.period-container').forEach(container => {
            const periodName = container.querySelector('.component-name-input').value.trim() || container.dataset.period;
            data[periodName] = {
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
                data[periodName].components.push(mainComponent);
            });
        });
        return data;
    };

    const loadGradeDataToDOM = (componentsData) => {
        accordionContainer.innerHTML = ''; // Clear existing components
        const dataToLoad = componentsData && Object.keys(componentsData).length > 0 ? componentsData : defaultStructure;
        
        Object.keys(dataToLoad).forEach(period => {
            const periodData = dataToLoad[period];
            const newComponent = createGradeComponent(period, periodData.weight, periodData.components);
            accordionContainer.appendChild(newComponent);
        });
        calculateAndUpdateTotals();
    };

    const toggleGradeComponents = (disabled) => {
        document.querySelectorAll('.semestral-input, .main-input, .sub-input, .component-name-input, .add-sub-btn, .remove-row-btn, .add-component-btn, .remove-component-btn, #add-grade-component-btn').forEach(el => {
            el.disabled = disabled;
        });
    };
    
    const showModal = (modalId) => {
        const modal = document.getElementById(modalId);
        const panel = document.getElementById(`${modalId}-panel`);
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hideModal = (modalId) => {
        const modal = document.getElementById(modalId);
        const panel = document.getElementById(`${modalId}-panel`);
        modal.classList.add('opacity-0');
        panel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    const fetchAPI = async (url, options = {}) => {
        try {
            const apiUrl = `/api/${url}`;
            console.log(`Making API request to: ${apiUrl}`);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                            document.querySelector('input[name="_token"]')?.value;
            
            options.headers = { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json', 
                ...options.headers 
            };
            
            const response = await fetch(apiUrl, options);
            console.log(`API Response status: ${response.status} ${response.statusText}`);
            
            if (!response.ok) {
                let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    // If response is not JSON, use status text
                }
                throw new Error(errorMessage);
            }
            
            const data = await response.json();
            console.log('API Response data:', data);
            return data;
        } catch (error) {
            console.error('API Error:', error);
            // Don't show Swal here, let the calling function handle it
            throw error;
        }
    };
    
    const fetchAndPopulateSubjects = async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const newSubjectId = urlParams.get('new_subject_id');
        const newSubjectName = urlParams.get('new_subject_name');

        try {
            // Show loading state
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
            subjectSelect.disabled = true;
            
            console.log('Fetching subjects from API...');
            const data = await fetchAPI('subjects');
            console.log('Subjects received:', data);
            
            subjects = data;
            
            // Clear loading and populate subjects
            subjectSelect.innerHTML = '<option value="" disabled selected>Select a Subject</option>';
            subjectSelect.disabled = false;
            
            if (subjects && subjects.length > 0) {
                subjects.forEach(subject => {
                    const option = new Option(`${subject.subject_name} (${subject.subject_code})`, subject.id);
                    subjectSelect.add(option);
                });
                console.log(`Successfully loaded ${subjects.length} subjects`);
            } else {
                subjectSelect.innerHTML = '<option value="" disabled>No subjects available</option>';
                console.log('No subjects found in database');
            }

            if (newSubjectId && newSubjectName) {
                if (![...subjectSelect.options].some(opt => opt.value == newSubjectId)) {
                    const newOption = new Option(decodeURIComponent(newSubjectName), newSubjectId);
                    subjectSelect.add(newOption);
                }
                subjectSelect.value = newSubjectId;
                subjectSelect.dispatchEvent(new Event('change'));
            }
        } catch (error) {
            console.error('Failed to load subjects:', error);
            subjectSelect.disabled = false;
            
            // Show error state with better messaging
            subjectSelect.innerHTML = '<option value="" disabled selected>Failed to load subjects - Please refresh the page</option>';
            
            // Show user-friendly error
            Swal.fire({
                title: 'Loading Error',
                text: `Failed to load subjects: ${error.message}. Please check your connection and refresh the page.`,
                icon: 'error',
                confirmButtonText: 'Refresh Page',
                cancelButtonText: 'Try Again',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    fetchAndPopulateSubjects(); // Retry
                }
            });
        }
    };

    const fetchGradeSetupForSubject = (subjectId) => {
        if (!subjectId) {
            loadGradeDataToDOM(defaultStructure);
            toggleGradeComponents(true);
            return;
        }
        currentSubjectId = subjectId;
        isEditMode = false; 
        updateGradeSetupBtn.classList.add('hidden'); 
        addGradeBtn.classList.remove('hidden');

        fetchAPI(`grades/${subjectId}`).then(data => {
            if (data && data.components && Object.keys(data.components).length > 0) {
                 loadGradeDataToDOM(data.components);
                 toggleGradeComponents(true); // Disable form if grade exists
                 addGradeBtn.disabled = true;
            } else {
                 loadGradeDataToDOM(defaultStructure);
                 toggleGradeComponents(false); // Enable form if no grade exists
                 addGradeBtn.disabled = false;
            }
            calculateAndUpdateTotals();
        }).catch(() => {
            loadGradeDataToDOM(defaultStructure);
            toggleGradeComponents(false);
            addGradeBtn.disabled = false;
            calculateAndUpdateTotals();
        });
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
            const icon = toggleButton.querySelector('svg:last-child');
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';
            
            // This allows only one accordion to be open at a time.
            // If you want multiple, remove this block.
            document.querySelectorAll('.accordion-content').forEach(c => { 
                if (c !== content) c.style.maxHeight = null;
            });
            document.querySelectorAll('.accordion-toggle svg:last-child').forEach(i => {
                if (i !== icon) i.classList.remove('rotate-180');
            });

            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.classList.add('rotate-180');
            } else {
                content.style.maxHeight = null;
                icon.classList.remove('rotate-180');
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
                    console.log('Saving grade scheme with payload:', payload);
                    const data = await fetchAPI('grades', { method: 'POST', body: JSON.stringify(payload) });
                    console.log('Grade scheme saved successfully:', data);
                    Swal.fire('Saved!', data.message, 'success');
                    addSubjectToHistory(data.subject);
                    subjectSelect.value = '';
                    loadGradeDataToDOM(defaultStructure);
                    toggleGradeComponents(true);
                    addGradeBtn.disabled = true;
                } catch(e) { 
                    console.error('Failed to save grade scheme:', e);
                    Swal.fire('Error!', 'Failed to save grade scheme: ' + e.message, 'error');
                }
            }
        });
    });

    editGradeSetupBtn.addEventListener('click', () => {
        Swal.fire({
            title: 'Are you sure?', text: "You are about to update the grade setup.", icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                isEditMode = true;
                toggleGradeComponents(false);
                hideModal('grade-modal');
                addGradeBtn.classList.add('hidden');
                updateGradeSetupBtn.classList.remove('hidden');
                calculateAndUpdateTotals();
            }
        });
    });

    updateGradeSetupBtn.addEventListener('click', () => {
        Swal.fire({
            title: 'Confirm Grade Scheme Update', text: 'Are you sure you want to update this for the selected subject?', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Yes, update it!', confirmButtonColor: '#4f46e5', cancelButtonColor: '#d33',
        }).then(async (result) => {
            if (result.isConfirmed) {
                const payload = { subject_id: currentSubjectId, components: getGradeDataFromDOM() };
                try {
                    const data = await fetchAPI('grades', { method: 'POST', body: JSON.stringify(payload) });
                    Swal.fire('Updated!', data.message, 'success');
                    isEditMode = false;
                    toggleGradeComponents(true);
                    addGradeBtn.classList.remove('hidden');
                    updateGradeSetupBtn.classList.add('hidden');
                    addGradeBtn.disabled = true;
                } catch (e) { /* Error is handled in fetchAPI */ }
            }
        });
    });

    gradeHistoryContainer.addEventListener('click', async (e) => {
        const card = e.target.closest('.grade-history-card');
        if (card) {
            const subjectId = card.dataset.subjectId;
            subjectSelect.value = subjectId;
            fetchGradeSetupForSubject(subjectId);
            try {
                const gradeData = await fetchAPI(`grades/${subjectId}`);
                
                let contentHtml = '<div class="space-y-6">';
                for (const [period, data] of Object.entries(gradeData.components)) {
                    contentHtml += `
                        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                            <h4 class="font-bold text-lg capitalize text-gray-800 flex items-center gap-3 border-b pb-3 mb-3">
                                ${period} <span class="text-sm font-bold text-gray-500 ml-auto">${data.weight}%</span>
                            </h4>
                            <div class="flow-root">
                                <div class="-my-2 divide-y divide-gray-100">`;

                    if (!data.components || data.components.length === 0) {
                        contentHtml += '<p class="text-sm text-gray-500 py-3">No components for this period.</p>';
                    } else {
                        (data.components || []).forEach(comp => {
                            contentHtml += `
                                <div class="py-3">
                                    <div class="flex items-center justify-between gap-4">
                                        <p class="font-medium text-gray-700">${comp.name}</p>
                                        <p class="font-mono text-base text-gray-900">${comp.weight}%</p>
                                    </div>`;
                            if (comp.sub_components && comp.sub_components.length > 0) {
                                contentHtml += '<div class="mt-2 pl-6 border-l-2 border-gray-200 space-y-2">';
                                comp.sub_components.forEach(sub => {
                                    contentHtml += `
                                        <div class="flex items-center justify-between text-sm">
                                            <p class="text-gray-600">${sub.name}</p>
                                            <p class="font-mono text-gray-600">${sub.weight}%</p>
                                        </div>`;
                                });
                                contentHtml += '</div>';
                            }
                            contentHtml += '</div>';
                        });
                    }
                    contentHtml += `</div></div></div>`;
                }
                contentHtml += '</div>';

                modalContent.innerHTML = contentHtml;
                showModal('grade-modal');

            } catch (error) { Swal.fire('Error', 'Could not fetch grade details.', 'error'); }
        }
    });
    
    closeModalBtn.addEventListener('click', () => hideModal('grade-modal'));
    gradeModal.addEventListener('click', (e) => { if (e.target.id === 'grade-modal') hideModal('grade-modal'); });

    fetchAndPopulateSubjects();
    loadGradeDataToDOM(defaultStructure);
    toggleGradeComponents(true);
});
</script>

{{-- Removed duplicate confirmation and success modals - using SweetAlert instead --}}

{{-- Removed duplicate JavaScript - the main script above handles all grade scheme functionality properly --}}

@endsection