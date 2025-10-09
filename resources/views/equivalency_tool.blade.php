@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 w-full bg-gray-50 min-h-screen">
    {{-- Page Header --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
        <div class="flex items-center">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Subject Equivalency Tool</h1>
                <p class="text-sm text-slate-500 mt-1">Map subjects from other schools to your existing subjects for seamless credit transfer.</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Left Panel: Create Equivalency --}}
        <div class="lg:col-span-4">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-slate-200 sticky top-8">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Create New Equivalency</h2>
                <p class="text-sm text-gray-500 mb-6">Enter the details of the subject from another institution and map it to one of your existing subjects.</p>
                
                <div class="space-y-6">
                    <div>
                        <label for="source-subject" class="block text-sm font-medium text-gray-700">Source Subject Name</label>
                        <p class="text-xs text-gray-500 mb-1">The name of the subject from the other institution.</p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v11.494m-5.747-5.747H17.747" /></svg>
                            </span>
                            <input type="text" id="source-subject" placeholder="e.g., Introduction to Programming" class="mt-1 block w-full py-2 pl-10 pr-3 border border-gray-300 bg-gray-50 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>
                    <div>
                        <label for="equivalent-subject" class="block text-sm font-medium text-gray-700">Equivalent BCP Subject</label>
                        <p class="text-xs text-gray-500 mb-1">The subject in your curriculum that it maps to.</p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                            </span>
                             <select id="equivalent-subject" class="mt-1 block w-full py-2 pl-10 pr-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm transition appearance-none">
                                <option value="" disabled selected>-- Select a Subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button id="create-equivalency-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Create Equivalency
                        </span>
                    </button>
                </div>
            </div>
        </div>


        {{-- Right Panel: Existing Equivalencies --}}
        <div class="lg:col-span-8">
             <div class="bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Existing Equivalencies</h2>
                <div class="relative flex items-center mb-6">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search-equivalency" placeholder="Search by subject name or code..." class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div id="equivalency-list" class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    @forelse ($equivalencies as $item)
                        <div class="equivalency-item p-4 border border-gray-200 rounded-lg flex justify-between items-center hover:bg-gray-50 hover:shadow-md transition-all duration-300" data-id="{{ $item->id }}" data-source-name="{{ $item->source_subject_name }}" data-equivalent-id="{{ $item->equivalent_subject_id }}">
                            <div class="flex items-center flex-grow">
                                <div class="bg-green-100 text-green-600 p-2 rounded-md mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-gray-800">{{ $item->source_subject_name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        Equivalent to: <span class="font-medium text-gray-600">{{ $item->equivalentSubject->subject_code }} - {{ $item->equivalentSubject->subject_name }}</span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Created on: {{ $item->created_at->format('M d, Y \a\t h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="edit-equivalency-btn text-gray-400 hover:text-blue-600 p-1 rounded-full transition-colors transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                                </button>
                                <button class="delete-equivalency-btn text-gray-400 hover:text-red-600 p-1 rounded-full transition-colors transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div id="no-equivalencies-message" class="text-center text-gray-500 py-10 border-2 border-dashed rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No equivalencies created yet.</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new equivalency.</p>
                        </div>
                    @endforelse
                </div>
            </div>
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

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="edit-modal-panel">
            <button id="closeEditModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="text-center mb-8">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-16 w-auto mb-4">
                <h2 class="text-2xl font-bold text-slate-800">Edit Equivalency</h2>
                <p class="text-sm text-slate-500 mt-1">Update the subject equivalency mapping.</p>
            </div>

            <form id="editEquivalencyForm" class="space-y-6">
                @csrf
                <div>
                    <label for="edit-source-subject" class="block text-sm font-medium text-slate-700 mb-2">Source Subject Name</label>
                    <input type="text" id="edit-source-subject" name="source_subject" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label for="edit-equivalent-subject" class="block text-sm font-medium text-slate-700 mb-2">Equivalent BCP Subject</label>
                    <select id="edit-equivalent-subject" name="equivalent_subject_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select a subject...</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" id="cancelEditModalButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                    <button type="submit" id="save-edit-button" class="flex-1 px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        <span>Update Equivalency</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const createBtn = document.getElementById('create-equivalency-btn');
    const sourceSubjectInput = document.getElementById('source-subject');
    const equivalentSubjectSelect = document.getElementById('equivalent-subject');
    const equivalencyList = document.getElementById('equivalency-list');
    const searchInput = document.getElementById('search-equivalency');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Modal elements
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

    const editModal = document.getElementById('editModal');
    const editModalPanel = document.getElementById('edit-modal-panel');
    const closeEditModalButton = document.getElementById('closeEditModalButton');
    const cancelEditModalButton = document.getElementById('cancelEditModalButton');
    const editEquivalencyForm = document.getElementById('editEquivalencyForm');
    const editSourceSubjectInput = document.getElementById('edit-source-subject');
    const editEquivalentSubjectSelect = document.getElementById('edit-equivalent-subject');

    let itemToDelete = null;
    let itemToEdit = null;
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

    const showEditModal = () => {
        editModal.classList.remove('hidden');
        setTimeout(() => {
            editModal.classList.remove('opacity-0');
            editModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hideEditModal = () => {
        editModal.classList.add('opacity-0');
        editModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => editModal.classList.add('hidden'), 300);
    };

    // Modal Event Listeners
    cancelConfirmationButton.addEventListener('click', hideConfirmationModal);
    confirmActionButton.addEventListener('click', () => {
        if (currentAction) currentAction();
        hideConfirmationModal();
    });
    closeSuccessModalButton.addEventListener('click', hideSuccessModal);
    closeEditModalButton.addEventListener('click', hideEditModal);
    cancelEditModalButton.addEventListener('click', hideEditModal);

    // --- CARD CREATION ---
    const createEquivalencyCard = (equivalency) => {
        const date = new Date(equivalency.created_at);
        const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

        const card = document.createElement('div');
        card.className = 'equivalency-item p-4 border border-gray-200 rounded-lg flex justify-between items-center hover:bg-gray-50 hover:shadow-md transition-all duration-300';
        card.dataset.id = equivalency.id;
        card.dataset.sourceName = equivalency.source_subject_name;
        card.dataset.equivalentId = equivalency.equivalent_subject_id;

        card.innerHTML = `
            <div class="flex items-center flex-grow">
                <div class="bg-green-100 text-green-600 p-2 rounded-md mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                </div>
                <div class="flex-grow">
                    <h3 class="font-semibold text-gray-800">${equivalency.source_subject_name}</h3>
                    <p class="text-sm text-gray-500">
                        Equivalent to: <span class="font-medium text-gray-600">${equivalency.equivalent_subject.subject_code} - ${equivalency.equivalent_subject.subject_name}</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Created on: ${formattedDate} at ${formattedTime}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button class="edit-equivalency-btn text-gray-400 hover:text-blue-600 p-1 rounded-full transition-colors transform hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                </button>
                <button class="delete-equivalency-btn text-gray-400 hover:text-red-600 p-1 rounded-full transition-colors transform hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;
        return card;
    };

    const addEquivalencyToDOM = (equivalency) => {
        const noItemsMessage = document.getElementById('no-equivalencies-message');
        if (noItemsMessage) noItemsMessage.remove();
        const newCard = createEquivalencyCard(equivalency);
        equivalencyList.prepend(newCard);
    };

    // --- API ACTIONS ---
    
    // CREATE
    createBtn.addEventListener('click', function () {
        const sourceSubject = sourceSubjectInput.value.trim();
        const equivalentSubjectId = equivalentSubjectSelect.value;

        if (sourceSubject === '' || equivalentSubjectId === '') {
            showConfirmationModal({
                title: 'Missing Information',
                message: 'Please fill out both subject fields before creating an equivalency.',
                icon: `<svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`,
                confirmButtonClass: 'bg-yellow-600 hover:bg-yellow-700',
                onConfirm: () => {}
            });
            return;
        }

        showConfirmationModal({
            title: 'Create Equivalency?',
            message: 'Are you sure you want to create this subject equivalency?',
            icon: `<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
            confirmButtonClass: 'bg-blue-600 hover:bg-blue-700',
            onConfirm: async () => {
                try {
                    const response = await fetch('/api/equivalencies', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
                        body: JSON.stringify({ source_subject_name: sourceSubject, equivalent_subject_id: equivalentSubjectId })
                    });

                    if (!response.ok) throw new Error((await response.json()).message || 'Failed to create.');

                    const newEquivalency = await response.json();
                    addEquivalencyToDOM(newEquivalency);
                    showSuccessModal('Equivalency Created!', 'The subject equivalency has been successfully created.');
                    sourceSubjectInput.value = '';
                    equivalentSubjectSelect.value = '';
                } catch (error) {
                    console.error('Error:', error);
                    showConfirmationModal({
                        title: 'Error',
                        message: `An error occurred: ${error.message}`,
                        icon: `<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>`,
                        confirmButtonClass: 'bg-red-600 hover:bg-red-700',
                        onConfirm: () => {}
                    });
                }
            }
        });
    });

    // Event Delegation for EDIT and DELETE
    equivalencyList.addEventListener('click', function (e) {
        const editButton = e.target.closest('.edit-equivalency-btn');
        const deleteButton = e.target.closest('.delete-equivalency-btn');

        if (editButton) {
            itemToEdit = editButton.closest('.equivalency-item');
            editSourceSubjectInput.value = itemToEdit.dataset.sourceName;
            editEquivalentSubjectSelect.value = itemToEdit.dataset.equivalentId;
            showEditModal();
        }

        if (deleteButton) {
            itemToDelete = deleteButton.closest('.equivalency-item');
            showConfirmationModal({
                title: 'Delete Equivalency?',
                message: 'Are you sure you want to delete this equivalency? This action cannot be undone.',
                icon: `<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>`,
                confirmButtonClass: 'bg-red-600 hover:bg-red-700',
                onConfirm: async () => {
                    const equivalencyId = itemToDelete.dataset.id;
                    try {
                        const response = await fetch(`/api/equivalencies/${equivalencyId}`, {
                            method: 'DELETE',
                            headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
                        });

                        if (!response.ok) throw new Error((await response.json()).message || 'Failed to delete.');
                        
                        // Animate out
                        itemToDelete.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                        itemToDelete.style.transform = 'translateX(100%)';
                        itemToDelete.style.opacity = '0';
                        
                        setTimeout(() => {
                            itemToDelete.remove();
                            if (equivalencyList.children.length === 0) {
                                 equivalencyList.innerHTML = `
                                    <div id="no-equivalencies-message" class="text-center text-gray-500 py-10 border-2 border-dashed rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No equivalencies created yet.</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new equivalency.</p>
                                    </div>`;
                            }
                        }, 300);
                        
                        showSuccessModal('Equivalency Deleted!', 'The equivalency has been successfully deleted.');
                    } catch (error) {
                        console.error('Error:', error);
                        showConfirmationModal({
                            title: 'Error',
                            message: `An error occurred: ${error.message}`,
                            icon: `<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>`,
                            confirmButtonClass: 'bg-red-600 hover:bg-red-700',
                            onConfirm: () => {}
                        });
                    }
                }
            });
        }
    });

    // UPDATE (Edit Form Submission)
    editEquivalencyForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const equivalencyId = itemToEdit.dataset.id;
        const updatedData = {
            source_subject_name: editSourceSubjectInput.value.trim(),
            equivalent_subject_id: editEquivalentSubjectSelect.value
        };

        try {
            const response = await fetch(`/api/equivalencies/${equivalencyId}`, {
                method: 'PATCH',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
                body: JSON.stringify(updatedData)
            });

            if (!response.ok) throw new Error((await response.json()).message || 'Failed to update.');
            
            const updatedEquivalency = await response.json();
            const updatedCard = createEquivalencyCard(updatedEquivalency);
            itemToEdit.replaceWith(updatedCard);
            
            hideEditModal();
            showSuccessModal('Equivalency Updated!', 'The equivalency has been successfully updated.');
        } catch (error) {
            console.error('Error:', error);
            showConfirmationModal({
                title: 'Error',
                message: `An error occurred: ${error.message}`,
                icon: `<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>`,
                confirmButtonClass: 'bg-red-600 hover:bg-red-700',
                onConfirm: () => {}
            });
        }
    });

    
    // --- SEARCH ---
    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.toLowerCase();
        const items = equivalencyList.getElementsByClassName('equivalency-item');

        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            const textContent = item.textContent.toLowerCase();
            item.style.display = textContent.includes(searchTerm) ? 'flex' : 'none';
        }
    });
});
</script>
@endsection


