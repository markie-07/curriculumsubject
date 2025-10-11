@extends('layouts.app')

@section('content')
<style>
    /* Color styles for subject types, consistent with the mapping page */
    .subject-tag-major { background-color: #DBEAFE; color: #1E40AF; border: 1px solid #BFDBFE; }
    .subject-tag-minor { background-color: #E9D5FF; color: #5B21B6; border: 1px solid #D8B4FE;}
    .subject-tag-elective { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FECACA;}
    .subject-tag-general { background-color: #FFEDD5; color: #9A3412; border: 1px solid #FED7AA;}
    .subject-tag-default { background-color: #F3F4F6; color: #374151; border: 1px solid #E5E7EB;}
</style>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
    <div>
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <div class="flex flex-col md:flex-row justify-between md:items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl font-bold text-gray-800">Set Subject Prerequisites</h1>
                    <p class="text-sm text-gray-500 mt-1">Define the relationships between subjects for a curriculum.</p>
                </div>
                <button id="setPrerequisiteBtn" class="bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-800 transition-colors shadow-md flex items-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Set Prerequisite
                </button>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="mb-6 pb-6 border-b border-gray-200">
                <label for="curriculum-selector-button" class="block text-lg font-semibold text-gray-700 mb-2">Select Curriculum to View</label>
                <div id="custom-curriculum-selector" class="relative">
                    <button type="button" id="curriculum-selector-button" class="w-full border border-gray-300 rounded-lg p-3 flex justify-between items-center bg-white text-left focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-500 truncate pr-2">-- Select a Curriculum --</span>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="curriculum-dropdown-panel" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                        <div class="p-2">
                            <input type="text" id="curriculum-search-input" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Search for a curriculum...">
                        </div>
                        <ul id="curriculum-options-list" class="max-h-60 overflow-y-auto">
                            @foreach($curriculums as $curriculum)
                                <li class="px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="{{ $curriculum->id }}" data-name="{{ $curriculum->curriculum }} ({{ $curriculum->program_code }})">
                                    {{ $curriculum->curriculum }} ({{ $curriculum->program_code }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4">Prerequisite Chain</h2>
            <div id="prerequisiteChain" class="space-y-4 text-gray-700">
                <p class="text-center text-gray-500 py-8">Select a curriculum from the dropdown above to view its prerequisite chain.</p>
            </div>
            
            <div class="mt-6 text-right">
                <button id="savePrerequisiteChainBtn" class="bg-green-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-green-700 transition-colors shadow-md hidden">
                    Save
                </button>
            </div>

        </div>
    </div>
</main>

{{-- Modal for Setting Prerequisites --}}
<div id="prerequisiteModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="prerequisite-modal-panel">
            <button id="closePrerequisiteModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="text-center mb-8">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-16 w-auto mb-4">
                <h2 class="text-2xl font-bold text-slate-800">Set Prerequisites</h2>
                <p class="text-sm text-slate-500 mt-1">Define prerequisite relationships between subjects.</p>
            </div>

            <form id="prerequisiteForm" class="space-y-6">
                @csrf
                <input type="hidden" id="modalCurriculumId" name="curriculum_id">
                <input type="hidden" id="modalSubjectCode" name="subject_code">
                
                <div class="bg-slate-50 p-4 rounded-lg border">
                    <p class="text-sm font-medium text-slate-600">Curriculum:</p>
                    <p id="modalCurriculumName" class="text-lg font-bold text-slate-800"></p>
                </div>
                
                <div>
                    <label for="modal-subject-selector-button" class="block text-sm font-medium text-slate-700 mb-2">Subject (Takes the Prerequisites)</label>
                    <div id="modal-custom-subject-selector" class="relative">
                        <button type="button" id="modal-subject-selector-button" class="w-full border border-slate-300 rounded-lg p-3 flex justify-between items-center bg-white text-left focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="text-slate-500 truncate pr-2">Select a curriculum first</span>
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="modal-subject-dropdown-panel" class="absolute z-20 w-full mt-1 bg-white border border-slate-300 rounded-lg shadow-lg hidden">
                            <div class="p-2">
                                <input type="text" id="modal-subject-search-input" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Search for a subject...">
                            </div>
                            <ul id="modal-subject-options-list" class="max-h-60 overflow-y-auto">
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Prerequisite Subjects (Select one or more)</label>
                    <div id="prerequisiteList" class="max-h-60 overflow-y-auto bg-slate-50 border border-slate-200 rounded-lg p-4 space-y-3">
                        <p class="text-slate-500">Select a subject to see available prerequisites.</p>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" id="cancelModalBtn" class="flex-1 px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                    <button type="submit" id="savePrerequisitesBtn" class="flex-1 px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center gap-2" disabled>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        <span>Save Prerequisites</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div id="confirmationModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out" id="confirmation-modal-panel">
        <div id="confirmation-modal-icon" class="w-12 h-12 rounded-full p-2 flex items-center justify-center mx-auto mb-4">
            {{-- Icon will be set by JS --}}
        </div>
        <h3 id="confirmation-modal-title" class="text-lg font-semibold text-slate-800"></h3>
        <p id="confirmation-modal-message" class="text-sm text-slate-500 mt-2"></p>
        <div class="mt-6 flex justify-center gap-4">
            <button id="cancel-confirmation-button" class="w-full px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
            <button id="confirm-action-button" class="w-full px-6 py-2.5 text-sm font-medium text-white rounded-lg transition-all">Confirm</button>
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
document.addEventListener('DOMContentLoaded', () => {
    // --- State Management ---
    let allSubjectsForCurriculum = [];
    let selectedCurriculum = { id: null, name: '-- Select a Curriculum --' };
    let selectedModalSubject = { code: null, name: 'Select a Subject' };

    // --- Main Page Elements ---
    const setPrerequisiteBtn = document.getElementById('setPrerequisiteBtn');
    const prerequisiteChainContainer = document.getElementById('prerequisiteChain');
    const savePrerequisiteChainBtn = document.getElementById('savePrerequisiteChainBtn');

    // --- Modal Elements ---
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmationModalPanel = document.getElementById('confirmation-modal-panel');
    const confirmationModalTitle = document.getElementById('confirmation-modal-title');
    const confirmationModalMessage = document.getElementById('confirmation-modal-message');
    const confirmationModalIcon = document.getElementById('confirmation-modal-icon');
    const cancelConfirmationButton = document.getElementById('cancel-confirmation-button');
    const confirmActionButton = document.getElementById('confirm-action-button');

    const successModal = document.getElementById('successModal');
    const successModalPanel = document.getElementById('success-modal-panel');
    const successModalTitle = document.getElementById('success-modal-title');
    const successModalMessage = document.getElementById('success-modal-message');
    const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');

    let currentAction = null;

    // Modal Helper Functions
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

    const showConfirmationModal = (config) => {
        confirmationModalTitle.textContent = config.title;
        confirmationModalMessage.textContent = config.message;
        confirmationModalIcon.innerHTML = config.icon;
        confirmActionButton.className = `w-full px-6 py-2.5 text-sm font-medium text-white rounded-lg transition-all ${config.confirmButtonClass}`;
        currentAction = config.onConfirm;

        confirmationModal.classList.remove('hidden');
        setTimeout(() => {
            confirmationModal.classList.remove('opacity-0');
            confirmationModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hideConfirmationModal = () => {
        confirmationModal.classList.add('opacity-0');
        confirmationModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => confirmationModal.classList.add('hidden'), 300);
    };

    // Modal Event Listeners
    cancelConfirmationButton.addEventListener('click', hideConfirmationModal);
    confirmActionButton.addEventListener('click', () => {
        if (currentAction) currentAction();
        hideConfirmationModal();
    });
    closeSuccessModalButton.addEventListener('click', hideSuccessModal);
    
    // --- Main Curriculum Searchable Dropdown Elements ---
    const mainCustomSelector = document.getElementById('custom-curriculum-selector');
    const mainSelectorButton = document.getElementById('curriculum-selector-button');
    const mainDropdownPanel = document.getElementById('curriculum-dropdown-panel');
    const mainSearchInput = document.getElementById('curriculum-search-input');
    const mainOptionsList = document.getElementById('curriculum-options-list');

    // --- Modal Elements ---
    const prerequisiteModal = document.getElementById('prerequisiteModal');
    const modalPanel = prerequisiteModal.querySelector('.transform');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const modalCurriculumIdInput = document.getElementById('modalCurriculumId');
    const modalCurriculumName = document.getElementById('modalCurriculumName');
    const modalSubjectCodeInput = document.getElementById('modalSubjectCode');
    const prerequisiteList = document.getElementById('prerequisiteList');
    const prerequisiteForm = document.getElementById('prerequisiteForm');
    const savePrerequisitesBtn = document.getElementById('savePrerequisitesBtn');
    
    // --- Modal Subject Searchable Dropdown Elements ---
    const modalCustomSelector = document.getElementById('modal-custom-subject-selector');
    const modalSelectorButton = document.getElementById('modal-subject-selector-button');
    const modalDropdownPanel = document.getElementById('modal-subject-dropdown-panel');
    const modalSearchInput = document.getElementById('modal-subject-search-input');
    const modalOptionsList = document.getElementById('modal-subject-options-list');
    
    // --- New Modals for Saving Workflow ---
    const savePrerequisiteModal = document.getElementById('savePrerequisiteModal');
    const cancelSavePrerequisite = document.getElementById('cancelSavePrerequisite');
    const confirmSavePrerequisite = document.getElementById('confirmSavePrerequisite');
    const proceedToComplianceValidatorModal = document.getElementById('proceedToComplianceValidatorModal');
    const declineProceedToComplianceValidator = document.getElementById('declineProceedToComplianceValidator');
    const confirmProceedToComplianceValidator = document.getElementById('confirmProceedToComplianceValidator');

    // --- Main Curriculum Dropdown Logic ---
    mainSelectorButton.addEventListener('click', (e) => {
        e.stopPropagation();
        mainDropdownPanel.classList.toggle('hidden');
    });

    mainSearchInput.addEventListener('input', () => {
        const filter = mainSearchInput.value.toLowerCase();
        mainOptionsList.querySelectorAll('li').forEach(option => {
            option.style.display = option.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    mainOptionsList.addEventListener('click', (e) => {
        if (e.target.tagName === 'LI') {
            selectedCurriculum.id = e.target.dataset.value;
            selectedCurriculum.name = e.target.dataset.name;
            mainSelectorButton.querySelector('span').textContent = selectedCurriculum.name;
            mainSelectorButton.querySelector('span').classList.remove('text-gray-500');
            mainDropdownPanel.classList.add('hidden');
            fetchPrerequisiteData(selectedCurriculum.id);
        }
    });

    document.addEventListener('click', (e) => {
        if (!mainCustomSelector.contains(e.target)) mainDropdownPanel.classList.add('hidden');
        if (!modalCustomSelector.contains(e.target)) modalDropdownPanel.classList.add('hidden');
    });

    // --- Modal Subject Dropdown Logic ---
    modalSelectorButton.addEventListener('click', (e) => {
        e.stopPropagation();
        modalDropdownPanel.classList.toggle('hidden');
    });

    modalSearchInput.addEventListener('input', () => {
        const filter = modalSearchInput.value.toLowerCase();
        modalOptionsList.querySelectorAll('li').forEach(option => {
            option.style.display = option.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    modalOptionsList.addEventListener('click', (e) => {
        if (e.target.tagName === 'LI') {
            selectedModalSubject.code = e.target.dataset.value;
            selectedModalSubject.name = e.target.dataset.name;
            modalSelectorButton.querySelector('span').textContent = selectedModalSubject.name;
            modalSelectorButton.querySelector('span').classList.remove('text-gray-500');
            modalDropdownPanel.classList.add('hidden');
            handleSubjectSelection(selectedModalSubject.code);
        }
    });

    // --- Modal Controls ---
    const showModal = (subjectCodeToEdit = null) => {
        if (!selectedCurriculum.id) {
            showConfirmationModal({
                title: 'No Curriculum Selected',
                message: 'Please select a curriculum from the main dropdown first.',
                icon: `<svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`,
                confirmButtonClass: 'bg-yellow-600 hover:bg-yellow-700',
                onConfirm: () => {}
            });
            return;
        }
        
        modalCurriculumIdInput.value = selectedCurriculum.id;
        modalCurriculumName.textContent = selectedCurriculum.name;
        
        fetchSubjectsForModal(selectedCurriculum.id).then(() => {
            if (subjectCodeToEdit) {
                const subject = allSubjectsForCurriculum.find(s => s.subject_code === subjectCodeToEdit);
                if (subject) {
                    selectedModalSubject.code = subject.subject_code;
                    selectedModalSubject.name = `${subject.subject_name} (${subject.subject_code})`;
                    modalSelectorButton.querySelector('span').textContent = selectedModalSubject.name;
                    modalSelectorButton.querySelector('span').classList.remove('text-slate-500');
                    handleSubjectSelection(subjectCodeToEdit);
                }
            }
        });

        prerequisiteModal.classList.remove('hidden');
        setTimeout(() => modalPanel.classList.remove('opacity-0', 'scale-95'), 10);
    };

    const hideModal = () => {
        modalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            prerequisiteModal.classList.add('hidden');
            prerequisiteForm.reset();
            modalOptionsList.innerHTML = '';
            prerequisiteList.innerHTML = '<p class="text-slate-500">Select a subject to see available prerequisites.</p>';
            modalSelectorButton.querySelector('span').textContent = 'Select a curriculum first';
            modalSelectorButton.querySelector('span').classList.add('text-slate-500');
            savePrerequisitesBtn.disabled = true;
        }, 300);
    };

    setPrerequisiteBtn.addEventListener('click', () => showModal());
    cancelModalBtn.addEventListener('click', hideModal);
    document.getElementById('closePrerequisiteModalButton').addEventListener('click', hideModal);
    prerequisiteModal.addEventListener('click', (e) => {
        if (e.target === prerequisiteModal) hideModal();
    });

    // --- Data Fetching and UI Rendering ---
    async function fetchPrerequisiteData(curriculumId) {
        if (!curriculumId) {
            prerequisiteChainContainer.innerHTML = '<p class="text-center text-gray-500 py-8">Select a curriculum from the dropdown above to view its prerequisite chain.</p>';
            setPrerequisiteBtn.disabled = true;
            return;
        }

        prerequisiteChainContainer.innerHTML = '<p class="text-center text-gray-500 py-8">Loading chain...</p>';
        setPrerequisiteBtn.disabled = false;

        try {
            const response = await fetch(`/api/prerequisites/${curriculumId}`);
            if (!response.ok) throw new Error('Failed to fetch data.');
            
            const data = await response.json();
            renderPrerequisiteChain(data.prerequisites || {}, data.subjects || []);
        } catch (error) {
            console.error('Error fetching prerequisite data:', error);
            prerequisiteChainContainer.innerHTML = '<p class="text-center text-red-500 py-8">Could not load prerequisite data.</p>';
        }
    }

    async function fetchSubjectsForModal(curriculumId) {
        try {
            const response = await fetch(`/api/prerequisites/${curriculumId}`);
            if (!response.ok) throw new Error('Failed to fetch subjects.');
            
            const data = await response.json();
            allSubjectsForCurriculum = data.subjects || [];
            populateSubjectDropdown(allSubjectsForCurriculum);
        } catch (error) {
            console.error('Error fetching subjects for modal:', error);
            modalOptionsList.innerHTML = '<li>Could not load subjects</li>';
        }
    }

    function populateSubjectDropdown(subjects) {
        modalOptionsList.innerHTML = '';
        modalSelectorButton.querySelector('span').textContent = subjects.length > 0 ? 'Select a Subject' : 'No subjects available';
        modalSelectorButton.disabled = subjects.length === 0;

        subjects.forEach(subject => {
            const li = document.createElement('li');
            li.className = 'px-4 py-2 hover:bg-blue-100 cursor-pointer';
            li.dataset.value = subject.subject_code;
            const subjectName = `${subject.subject_name} (${subject.subject_code})`;
            li.dataset.name = subjectName;
            li.textContent = subjectName;
            modalOptionsList.appendChild(li);
        });
    }

    function populatePrerequisiteCheckboxes(selectedSubjectCode, existingPrerequisites = []) {
        prerequisiteList.innerHTML = '';
        const eligibleSubjects = allSubjectsForCurriculum.filter(s => s.subject_code !== selectedSubjectCode);

        if (eligibleSubjects.length === 0) {
            prerequisiteList.innerHTML = '<p class="text-gray-500">No other subjects available to be prerequisites.</p>';
            return;
        }

        eligibleSubjects.forEach(subject => {
            const isChecked = existingPrerequisites.includes(subject.subject_code);
            const checkboxWrapper = document.createElement('div');
            checkboxWrapper.className = 'flex items-center';
            checkboxWrapper.innerHTML = `
                <input type="checkbox" id="prereq-${subject.subject_code}" name="prerequisite_codes[]" value="${subject.subject_code}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" ${isChecked ? 'checked' : ''}>
                <label for="prereq-${subject.subject_code}" class="ml-3 text-sm text-gray-700">${subject.subject_name} (${subject.subject_code})</label>
            `;
            prerequisiteList.appendChild(checkboxWrapper);
        });
    }

    /**
     * Renders the prerequisite chain with the corrected order.
     */
    function renderPrerequisiteChain(prerequisites, subjects) {
        prerequisiteChainContainer.innerHTML = '';

        const hasPrerequisites = Object.keys(prerequisites).some(key => prerequisites[key].length > 0);

        if (!hasPrerequisites) {
            prerequisiteChainContainer.innerHTML = '<p class="text-center text-gray-500 py-8">No prerequisites have been set for this curriculum yet.</p>';
            savePrerequisiteChainBtn.classList.add('hidden');
            return;
        }

        savePrerequisiteChainBtn.classList.remove('hidden');

        const subjectMap = new Map(subjects.map(s => [s.subject_code, s]));

        const getSubjectColorClass = (subjectType) => {
            const type = (subjectType || 'default').toLowerCase();
            const geIdentifiers = ["ge", "general education", "gen ed", "general"];
            if (type.includes('major')) return 'subject-tag-major';
            if (type.includes('minor')) return 'subject-tag-minor';
            if (type.includes('elective')) return 'subject-tag-elective';
            if (geIdentifiers.some(id => type.includes(id))) return 'subject-tag-general';
            return 'subject-tag-default';
        };
        
        subjects.forEach(subject => {
            const subjectCode = subject.subject_code;
            
            if (prerequisites[subjectCode] && prerequisites[subjectCode].length > 0) {
                const prereqs = prerequisites[subjectCode];
                
                // --- START: MODIFICATION FOR SORTING AND ORDER ---
                const subjectOrderMap = new Map(subjects.map((s, index) => [s.subject_code, index]));

                const sortedPrereqs = [...prereqs].sort((a, b) => {
                    const orderA = subjectOrderMap.get(a.prerequisite_subject_code);
                    const orderB = subjectOrderMap.get(b.prerequisite_subject_code);
                    return orderA - orderB;
                });

                const chainDiv = document.createElement('div');
                chainDiv.className = 'flex items-center justify-between gap-2 p-3 bg-gray-50 rounded-lg border';
                
                const subjectName = subject.subject_name;
                const subjectColorClass = getSubjectColorClass(subject.subject_type);
                const subjectHtml = `<span class="font-semibold px-3 py-1.5 rounded-md ${subjectColorClass}">${subjectName}</span>`;

                const prereqHtml = sortedPrereqs.map(p => {
                    const prereqSubject = subjectMap.get(p.prerequisite_subject_code);
                    if (!prereqSubject) return ''; 
                    
                    const prereqName = prereqSubject.subject_name;
                    const prereqColorClass = getSubjectColorClass(prereqSubject.subject_type);
                    return `<span class="font-semibold px-3 py-1.5 rounded-md ${prereqColorClass}">${prereqName}</span>`;
                }).join(' <span class="font-bold text-2xl text-gray-400 mx-2">&rarr;</span> ');

                // *** THIS IS THE FIX FOR THE DISPLAY ORDER ***
                chainDiv.innerHTML = `
                    <div class="flex-grow flex flex-wrap items-center gap-2">
                        ${subjectHtml}
                        <span class="font-bold text-2xl text-gray-400 mx-2">&rarr;</span>
                        ${prereqHtml}
                    </div>
                    <button class="edit-prereq-btn p-2 text-blue-600 hover:text-blue-800 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-subject-code="${subjectCode}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                    </button>
                `;
                // *** END OF FIX ***

                prerequisiteChainContainer.appendChild(chainDiv);
            }
        });

        addEditButtonListeners();
    }
    
    function addEditButtonListeners() {
        document.querySelectorAll('.edit-prereq-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const subjectCodeToEdit = e.currentTarget.dataset.subjectCode;
                showModal(subjectCodeToEdit);
            });
        });
    }

    async function handleSubjectSelection(subjectCode) {
        modalSubjectCodeInput.value = subjectCode;
        savePrerequisitesBtn.disabled = !subjectCode;

        if (!subjectCode || !selectedCurriculum.id) {
            prerequisiteList.innerHTML = '<p class="text-gray-500">Select a subject to see available prerequisites.</p>';
            return;
        }
        const response = await fetch(`/api/prerequisites/${selectedCurriculum.id}`);
        const data = await response.json();
        const existingPrerequisites = data.prerequisites[subjectCode] ? data.prerequisites[subjectCode].map(p => p.prerequisite_subject_code) : [];
        populatePrerequisiteCheckboxes(subjectCode, existingPrerequisites);
    }

    prerequisiteForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        savePrerequisitesBtn.disabled = true;
        const formData = new FormData(prerequisiteForm);
        const data = {
            curriculum_id: formData.get('curriculum_id'),
            subject_code: formData.get('subject_code'),
            prerequisite_codes: formData.getAll('prerequisite_codes[]')
        };
        try {
            const response = await fetch('/api/prerequisites', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to save prerequisites.');
            }
            
            const result = await response.json();
            hideModal();
            
            // Use SweetAlert for success
            Swal.fire({
                title: 'Success!',
                text: 'Prerequisites have been saved successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                fetchPrerequisiteData(data.curriculum_id);
            });
        } catch (error) {
            console.error('Error saving prerequisites:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to save prerequisites: ' + error.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } finally {
            savePrerequisitesBtn.disabled = false;
        }
    });

    savePrerequisiteChainBtn.addEventListener('click', () => {
        showConfirmationModal({
            title: 'Save Prerequisite Chain?',
            message: 'Are you sure you want to save the current prerequisite chain configuration?',
            icon: `<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
            confirmButtonClass: 'bg-blue-600 hover:bg-blue-700',
            onConfirm: () => {
                showConfirmationModal({
                    title: 'Proceed to Compliance Validator?',
                    message: 'Prerequisites saved successfully! Do you want to go to the compliance validator to check if your curriculum meets the compliance requirements?',
                    icon: `<svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>`,
                    confirmButtonClass: 'bg-green-600 hover:bg-green-700',
                    onConfirm: () => {
                        window.location.href = '{{ route('compliance.validator') }}';
                    }
                });
            }
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const curriculumIdFromUrl = urlParams.get('curriculumId');
    if (curriculumIdFromUrl) {
        const optionToSelect = mainOptionsList.querySelector(`li[data-value="${curriculumIdFromUrl}"]`);
        if (optionToSelect) {
            selectedCurriculum.id = curriculumIdFromUrl;
            selectedCurriculum.name = optionToSelect.dataset.name;
            mainSelectorButton.querySelector('span').textContent = selectedCurriculum.name;
            mainSelectorButton.querySelector('span').classList.remove('text-gray-500');
            fetchPrerequisiteData(curriculumIdFromUrl);
            showModal();
        }
    }
});
</script>
@endsection