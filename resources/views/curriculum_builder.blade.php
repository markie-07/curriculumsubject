@extends('layouts.app')

@section('content')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 md:p-8">
        <div>
            
            {{-- Main Header with New Icon --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
                <div class="flex items-start gap-4">
                    {{-- Icon for Curriculum Builder --}}
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="flex-grow flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Curriculum Builder</h1>
                            <p class="text-sm text-slate-500 mt-1">Design and manage your academic curriculums.</p>
                        </div>
                        <button id="addCurriculumButton" class="w-full mt-4 sm:mt-0 sm:w-auto flex items-center justify-center space-x-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            <span>Add Curriculum</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Curriculums Section --}}
            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-700 mb-4 sm:mb-0">Existing Curriculums</h2>
                    <div class="relative w-full sm:w-72">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                        <input type="text" id="search-bar" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-10">
                    <div>
                        <h3 class="flex items-center gap-3 text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Senior High</span>
                        </h3>
                        <div id="senior-high-curriculums" class="space-y-4 pt-2">
                            <p class="text-slate-500 text-sm py-4">No Senior High curriculums found.</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="flex items-center gap-3 text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-200">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                               <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222 4 2.222V20M1 12v7a2 2 0 002 2h18a2 2 0 002-2v-7" />
                            </svg>
                           <span>College</span>
                        </h3>
                        <div id="college-curriculums" class="space-y-4 pt-2">
                           <p class="text-slate-500 text-sm py-4">No College curriculums found.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal for adding/editing a curriculum --}}
            <div id="addCurriculumModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="modal-panel">
                        <button id="closeModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        
                        <div class="text-center mb-8">
                            <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-16 w-auto mb-4">
                            <h2 id="modal-title" class="text-2xl font-bold text-slate-800">Create New Curriculum</h2>
                            <p class="text-sm text-slate-500 mt-1">Fill in the details below to add a new curriculum.</p>
                        </div>

                        <form id="curriculumForm" class="space-y-6">
                            @csrf
                            <input type="hidden" id="curriculumId" name="curriculumId">
                            
                            <div>
                                <label for="curriculum" class="block text-sm font-medium text-slate-700 mb-1">Curriculum Name</label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                    <input type="text" id="curriculum" name="curriculum" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"  required>
                                </div>
                            </div>
                            <div>
                                <label for="programCode" class="block text-sm font-medium text-slate-700 mb-1">Program Code</label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3h15a.75.75 0 01.75.75v15a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-15zM8.25 9a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5h-7.5zM8.25 12.75a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5z" /></svg>
                                    <input type="text" id="programCode" name="programCode" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                </div>
                            </div>
                            <div>
                                <label for="academicYear" class="block text-sm font-medium text-slate-700 mb-1">Academic Year</label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" /></svg>
                                    <input type="text" id="academicYear" name="academicYear" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                </div>
                            </div>
                            <div>
                                <label for="yearLevel" class="block text-sm font-medium text-slate-700 mb-1">Level</label>
                                 <div class="relative">
                                     <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5-1.5V3" /></svg>
                                    <select id="yearLevel" name="yearLevel" class="w-full appearance-none pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                        <option value="" disabled selected>Select Level</option>
                                        <option value="Senior High">Senior High</option>
                                        <option value="College">College</option>
                                    </select>
                                    <svg class="w-5 h-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                            <div class="pt-6 flex flex-col sm:flex-row-reverse gap-3">
                                <button type="submit" id="submit-button" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                    <span>Create</span>
                                </button>
                                <button type="button" id="cancelModalButton" class="w-full sm:w-auto px-6 py-3 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Reusable Confirmation Modal --}}
            <div id="confirmationModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
                <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out" id="confirmation-modal-panel">
                    <div id="confirmation-modal-icon" class="w-12 h-12 rounded-full p-2 flex items-center justify-center mx-auto mb-4">
                        {{-- Icon will be set by JS --}}
                    </div>
                    <h3 id="confirmation-modal-title" class="text-lg font-semibold text-slate-800"></h3>
                    <p id="confirmation-modal-message" class="text-sm text-slate-500 mt-2"></p>
                    <div class="mt-6 flex justify-center gap-4">
                        <button id="cancel-confirmation-button" class="w-full px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">No</button>
                        <button id="confirm-action-button" class="w-full px-6 py-2.5 text-sm font-medium text-white rounded-lg transition-all">Yes</button>
                    </div>
                </div>
            </div>

            {{-- Reusable Success Modal --}}
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
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Main elements
            const addCurriculumButton = document.getElementById('addCurriculumButton');
            const seniorHighCurriculums = document.getElementById('senior-high-curriculums');
            const collegeCurriculums = document.getElementById('college-curriculums');
            const searchBar = document.getElementById('search-bar');

            // Add/Edit Modal elements
            const addCurriculumModal = document.getElementById('addCurriculumModal');
            const closeModalButton = document.getElementById('closeModalButton');
            const cancelModalButton = document.getElementById('cancelModalButton');
            const modalPanel = document.getElementById('modal-panel');
            const curriculumForm = document.getElementById('curriculumForm');
            const modalTitle = document.getElementById('modal-title');
            const submitButton = document.getElementById('submit-button');
            const curriculumIdField = document.getElementById('curriculumId');

            // Confirmation Modal elements
            const confirmationModal = document.getElementById('confirmationModal');
            const confirmationModalPanel = document.getElementById('confirmation-modal-panel');
            const confirmationModalTitle = document.getElementById('confirmation-modal-title');
            const confirmationModalMessage = document.getElementById('confirmation-modal-message');
            const confirmationModalIcon = document.getElementById('confirmation-modal-icon');
            const cancelConfirmationButton = document.getElementById('cancel-confirmation-button');
            const confirmActionButton = document.getElementById('confirm-action-button');

            // Success Modal elements
            const successModal = document.getElementById('successModal');
            const successModalPanel = document.getElementById('success-modal-panel');
            const successModalTitle = document.getElementById('success-modal-title');
            const successModalMessage = document.getElementById('success-modal-message');
            const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');

            let currentAction = null; // To store the function to execute on confirmation.

            // --- Modal Helper Functions ---
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
                setTimeout(() => {
                    confirmationModal.classList.add('hidden');
                    currentAction = null;
                }, 300);
            };

            closeSuccessModalButton.addEventListener('click', hideSuccessModal);
            cancelConfirmationButton.addEventListener('click', hideConfirmationModal);
            confirmActionButton.addEventListener('click', () => {
                if (typeof currentAction === 'function') {
                    currentAction();
                }
                hideConfirmationModal();
            });

            // --- Card & API Logic ---
            const createCurriculumCard = (curriculum) => {
                const card = document.createElement('div');
                card.className = 'curriculum-card group relative bg-white p-4 rounded-xl border border-slate-200 flex items-center gap-4 hover:border-blue-500 hover:shadow-lg transition-all duration-300';
                card.dataset.name = curriculum.curriculum_name.toLowerCase();
                card.dataset.code = curriculum.program_code.toLowerCase();
                card.dataset.id = curriculum.id;

                const date = new Date(curriculum.created_at);
                const formattedDate = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

                card.innerHTML = `
                    <div class="flex-shrink-0 w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                        <svg class="w-6 h-6 text-slate-500 group-hover:text-blue-600 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="flex-grow cursor-pointer" onclick="window.location.href='/subject_mapping?curriculumId=${curriculum.id}'">
                        <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors duration-300">${curriculum.curriculum_name}</h3>
                        <p class="text-sm text-slate-500">${curriculum.program_code} &middot; ${curriculum.academic_year}</p>
                        <p class="text-xs text-slate-400 mt-1">Created: ${formattedDate} at ${formattedTime}</p>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button class="edit-btn p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-100 rounded-md transition-colors" data-id="${curriculum.id}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                        </button>
                    </div>
                `;
                return card;
            };

            const renderCurriculums = (curriculums) => {
                seniorHighCurriculums.innerHTML = '';
                collegeCurriculums.innerHTML = '';
                let seniorHighCount = 0;
                let collegeCount = 0;

                curriculums.forEach(curriculum => {
                    const card = createCurriculumCard(curriculum);
                    if (curriculum.year_level === 'Senior High') {
                        seniorHighCurriculums.appendChild(card);
                        seniorHighCount++;
                    } else {
                        collegeCurriculums.appendChild(card);
                        collegeCount++;
                    }
                });

                if (seniorHighCount === 0) seniorHighCurriculums.innerHTML = '<p class="text-slate-500 text-sm py-4">No Senior High curriculums found.</p>';
                if (collegeCount === 0) collegeCurriculums.innerHTML = '<p class="text-slate-500 text-sm py-4">No College curriculums found.</p>';
                
                attachActionListeners();
            };
            
            const fetchCurriculums = () => {
                fetch('/api/curriculums')
                    .then(response => response.json())
                    .then(renderCurriculums)
                    .catch(error => console.error('Error fetching curriculums:', error));
            };

            const showAddEditModal = (isEdit = false, curriculum = null) => {
                curriculumForm.reset();
                 const modalSubTitle = document.querySelector('#modal-panel > div.text-center.mb-8 > p');
                if (isEdit && curriculum) {
                    modalTitle.textContent = 'Edit Curriculum';
                    modalSubTitle.textContent = 'Update the details for this curriculum.';
                    submitButton.querySelector('span').textContent = 'Update';
                    curriculumIdField.value = curriculum.id;
                    document.getElementById('curriculum').value = curriculum.curriculum;
                    document.getElementById('programCode').value = curriculum.program_code;
                    document.getElementById('academicYear').value = curriculum.academic_year;
                    document.getElementById('yearLevel').value = curriculum.year_level;
                } else {
                    modalTitle.textContent = 'Create New Curriculum';
                    modalSubTitle.textContent = 'Fill in the details below to add a new curriculum.';
                    submitButton.querySelector('span').textContent = 'Create';
                    curriculumIdField.value = '';
                }
                addCurriculumModal.classList.remove('hidden');
                setTimeout(() => {
                    addCurriculumModal.classList.remove('opacity-0');
                    modalPanel.classList.remove('opacity-0', 'scale-95');
                }, 10);
            };

            const hideAddEditModal = () => {
                addCurriculumModal.classList.add('opacity-0');
                modalPanel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => addCurriculumModal.classList.add('hidden'), 300);
            };
            
            const handleFormSubmit = (e) => {
                e.preventDefault();
                
                const performSubmit = async () => {
                    const id = curriculumIdField.value;
                    const method = id ? 'PUT' : 'POST';
                    const url = id ? `/api/curriculums/${id}` : '/api/curriculums';
                    
                    const formData = new FormData(curriculumForm);
                    const payload = {
                        curriculum: formData.get('curriculum'),
                        programCode: formData.get('programCode'),
                        academicYear: formData.get('academicYear'),
                        yearLevel: formData.get('yearLevel'),
                    };
                    
                    try {
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload)
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw errorData;
                        }
                        
                        hideAddEditModal();
                        fetchCurriculums();
                        showSuccessModal(
                            `Curriculum ${id ? 'Updated' : 'Created'}!`,
                            `The curriculum has been successfully ${id ? 'updated' : 'created'}.`
                        );
                    } catch (error) {
                        console.error('Error submitting form:', error);
                        let errorMessage = 'An error occurred. Please try again.';
                         if (error.message) {
                            errorMessage = error.message;
                            if(error.error) {
                                errorMessage += ` (Details: ${error.error})`;
                            }
                        }
                        alert('Error: ' + errorMessage);
                    }
                };

                const isUpdating = !!curriculumIdField.value;
                showConfirmationModal({
                    title: isUpdating ? 'Update Curriculum?' : 'Create Curriculum?',
                    message: `Are you sure you want to ${isUpdating ? 'update' : 'create'} this curriculum?`,
                    icon: `<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                    confirmButtonClass: 'bg-blue-600 hover:bg-blue-700',
                    onConfirm: performSubmit
                });
            };
            
            const attachActionListeners = () => {
                // Edit button
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const id = e.currentTarget.dataset.id;
                        showConfirmationModal({
                            title: 'Edit Curriculum?',
                            message: 'Are you sure you want to edit this curriculum?',
                            icon: `<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>`,
                            confirmButtonClass: 'bg-blue-600 hover:bg-blue-700',
                            async onConfirm() {
                                try {
                                    const response = await fetch(`/api/curriculums/${id}`);
                                    const data = await response.json();
                                    if (!response.ok) throw data;
                                    if (data.curriculum) {
                                        showAddEditModal(true, data.curriculum);
                                    } else {
                                        throw new Error('Invalid data format received.');
                                    }
                                } catch (error) {
                                   console.error('Error fetching curriculum data:', error);
                                   alert('Error: ' + (error.message || 'Failed to load curriculum data.'));
                                }
                            }
                        });
                    });
                });
                
                // Delete functionality removed
            };

            searchBar.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.curriculum-card').forEach(card => {
                    const name = card.dataset.name;
                    const code = card.dataset.code;
                    card.style.display = (name.includes(searchTerm) || code.includes(searchTerm)) ? 'flex' : 'none';
                });
            });

            addCurriculumButton.addEventListener('click', () => showAddEditModal());
            closeModalButton.addEventListener('click', hideAddEditModal);
            cancelModalButton.addEventListener('click', hideAddEditModal);
            curriculumForm.addEventListener('submit', handleFormSubmit);

            fetchCurriculums();
        });
    </script>
@endsection