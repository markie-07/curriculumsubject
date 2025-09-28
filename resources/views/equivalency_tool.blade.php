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

{{-- Modals --}}
<div id="modal-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

{{-- Success Modal --}}
<div id="success-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="success-modal-title">Success!</h3>
        <div class="mt-2">
            <p class="text-sm text-gray-500" id="success-modal-message"></p>
        </div>
        <div class="mt-4">
            <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700" onclick="closeModal('success-modal')">
                Got it, thanks!
            </button>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
     <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Equivalency</h3>
        <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this equivalency? This action cannot be undone.</p>
        <div class="mt-6 flex justify-center space-x-4">
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300" onclick="closeModal('delete-modal')">Cancel</button>
            <button type="button" id="confirm-delete-btn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-lg">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Equivalency</h2>
        <div class="space-y-6">
            <div>
                <label for="edit-source-subject" class="block text-sm font-medium text-gray-700">Source Subject Name</label>
                <input type="text" id="edit-source-subject" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-50 rounded-md shadow-sm">
            </div>
            <div>
                <label for="edit-equivalent-subject" class="block text-sm font-medium text-gray-700">Equivalent BCP Subject</label>
                <select id="edit-equivalent-subject" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-8 flex justify-end space-x-4">
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300" onclick="closeModal('edit-modal')">Cancel</button>
            <button type="button" id="save-edit-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Changes</button>
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
    const backdrop = document.getElementById('modal-backdrop');
    const successModal = document.getElementById('success-modal');
    const successModalMessage = document.getElementById('success-modal-message');
    const deleteModal = document.getElementById('delete-modal');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const editModal = document.getElementById('edit-modal');
    const editSourceSubjectInput = document.getElementById('edit-source-subject');
    const editEquivalentSubjectSelect = document.getElementById('edit-equivalent-subject');
    const saveEditBtn = document.getElementById('save-edit-btn');

    let itemToDelete = null;
    let itemToEdit = null;

    // --- MODAL HELPER FUNCTIONS ---
    window.openModal = (modalId) => {
        const modal = document.getElementById(modalId);
        backdrop.classList.remove('hidden');
        modal.classList.remove('hidden');
    };

    window.closeModal = (modalId) => {
        const modal = document.getElementById(modalId);
        backdrop.classList.add('hidden');
        modal.classList.add('hidden');
    };

    const showSuccessModal = (message) => {
        successModalMessage.textContent = message;
        openModal('success-modal');
    };

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
    createBtn.addEventListener('click', async function () {
        const sourceSubject = sourceSubjectInput.value.trim();
        const equivalentSubjectId = equivalentSubjectSelect.value;

        if (sourceSubject === '' || equivalentSubjectId === '') {
            alert('Please fill out both subject fields.');
            return;
        }

        try {
            const response = await fetch('/api/equivalencies', { // CORRECTED URL
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
                body: JSON.stringify({ source_subject_name: sourceSubject, equivalent_subject_id: equivalentSubjectId })
            });

            if (!response.ok) throw new Error((await response.json()).message || 'Failed to create.');

            const newEquivalency = await response.json();
            addEquivalencyToDOM(newEquivalency);
            showSuccessModal('Equivalency created successfully!');
            sourceSubjectInput.value = '';
            equivalentSubjectSelect.value = '';
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
        }
    });

    // Event Delegation for EDIT and DELETE
    equivalencyList.addEventListener('click', function (e) {
        const editButton = e.target.closest('.edit-equivalency-btn');
        const deleteButton = e.target.closest('.delete-equivalency-btn');

        if (editButton) {
            itemToEdit = editButton.closest('.equivalency-item');
            editSourceSubjectInput.value = itemToEdit.dataset.sourceName;
            editEquivalentSubjectSelect.value = itemToEdit.dataset.equivalentId;
            openModal('edit-modal');
        }

        if (deleteButton) {
            itemToDelete = deleteButton.closest('.equivalency-item');
            openModal('delete-modal');
        }
    });

    // UPDATE (Save Changes)
    saveEditBtn.addEventListener('click', async () => {
        const equivalencyId = itemToEdit.dataset.id;
        const updatedData = {
            source_subject_name: editSourceSubjectInput.value.trim(),
            equivalent_subject_id: editEquivalentSubjectSelect.value
        };

        try {
            const response = await fetch(`/api/equivalencies/${equivalencyId}`, { // CORRECTED URL
                method: 'PATCH',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
                body: JSON.stringify(updatedData)
            });

            if (!response.ok) throw new Error((await response.json()).message || 'Failed to update.');
            
            const updatedEquivalency = await response.json();
            const updatedCard = createEquivalencyCard(updatedEquivalency);
            itemToEdit.replaceWith(updatedCard);
            
            closeModal('edit-modal');
            showSuccessModal('Equivalency updated successfully!');
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
        }
    });

    // DELETE
    confirmDeleteBtn.addEventListener('click', async () => {
        const equivalencyId = itemToDelete.dataset.id;

        try {
            const response = await fetch(`/api/equivalencies/${equivalencyId}`, { // CORRECTED URL
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
            
            closeModal('delete-modal');
            showSuccessModal('Equivalency deleted successfully!');
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
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


