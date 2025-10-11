@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-2">Manage your account information and security settings</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Account Information</h2>
                        <p class="text-sm text-gray-600">Update your account details and email address</p>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.update') }}" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Username (Read-only) -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text" 
                                       id="username" 
                                       value="{{ $user->username }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                       readonly>
                                <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <div class="relative">
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           required>
                                    <div id="email-validation-icon" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                                        <!-- Loading spinner -->
                                        <svg id="email-loading" class="animate-spin h-5 w-5 text-gray-400 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <!-- Success icon -->
                                        <svg id="email-valid" class="h-5 w-5 text-green-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <!-- Error icon -->
                                        <svg id="email-invalid" class="h-5 w-5 text-red-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div id="email-validation-message" class="text-sm mt-1 hidden"></div>
                                <p class="text-xs text-gray-500 mt-1">Please use a real, active email address that can receive messages</p>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role (Read-only) -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <input type="text" 
                                       id="role" 
                                       value="{{ ucfirst($user->role) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                       readonly>
                            </div>

                            <!-- Member Since -->
                            <div>
                                <label for="created_at" class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                                <input type="text" 
                                       id="created_at" 
                                       value="{{ $user->created_at->format('F j, Y') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                       readonly>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" id="updateProfileButton"
                                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Card (Hidden for Employees) -->
                @unless($user->isEmployee())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                        <p class="text-sm text-gray-600">Update your password to keep your account secure</p>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.password') }}" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       required>
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       required>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       required>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" id="updatePasswordButton"
                                    class="px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <!-- Password Change Restricted Notice for Employees -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl overflow-hidden mt-6">
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Password Change Restricted</h3>
                                <p class="text-sm text-yellow-700 mt-1">As an employee, you cannot change your password through this interface. Please contact your administrator for password changes.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endunless
            </div>

            <!-- Profile Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ '@' . $user->username }}</p>
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full mt-2">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    
                    <div class="border-t border-gray-200 p-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Account Details</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Joined:</span>
                                <span class="text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="text-gray-900">{{ $user->updated_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Update Profile Modal --}}
<div id="updateProfileModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-8 md:p-10 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="profile-modal-panel">
            <button id="closeProfileModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="text-center mb-10">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-20 w-auto mb-6">
                <h2 class="text-3xl font-bold text-slate-800">Update Profile</h2>
                <p class="text-base text-slate-500 mt-2">Please review and confirm your profile information changes below.</p>
            </div>

            <div class="bg-slate-50 rounded-lg p-6 mb-8">
                <h3 class="font-semibold text-slate-700 mb-4 text-lg">Profile Changes Summary:</h3>
                <div id="profile-changes-summary" class="text-base text-slate-700 space-y-3">
                    <!-- Summary will be populated by JavaScript -->
                </div>
            </div>

            <div class="flex gap-6 pt-6">
                <button type="button" id="cancelProfileUpdateButton" class="flex-1 px-8 py-3.5 text-base font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                <button type="button" id="confirmProfileUpdateButton" class="flex-1 px-8 py-3.5 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    <span>Update Profile</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Update Password Modal (Hidden for Employees) --}}
@unless($user->isEmployee())
<div id="updatePasswordModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="password-modal-panel">
            <button id="closePasswordModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="text-center mb-8">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-16 w-auto mb-4">
                <h2 class="text-2xl font-bold text-slate-800">Update Password</h2>
                <p class="text-sm text-slate-500 mt-1">Confirm your password change for security.</p>
            </div>

            <div class="bg-red-50 rounded-lg p-4 mb-6 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div class="text-sm text-red-700">
                        <p class="font-semibold">Security Notice:</p>
                        <p>You will be logged out after changing your password and will need to log in again with your new credentials.</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" id="cancelPasswordUpdateButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                <button type="button" id="confirmPasswordUpdateButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                    </svg>
                    <span>Update Password</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endunless

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
document.addEventListener('DOMContentLoaded', function () {
    // Modal elements
    const updateProfileButton = document.getElementById('updateProfileButton');
    const updatePasswordButton = document.getElementById('updatePasswordButton');
    
    const profileModal = document.getElementById('updateProfileModal');
    const profileModalPanel = document.getElementById('profile-modal-panel');
    const closeProfileModalButton = document.getElementById('closeProfileModalButton');
    const cancelProfileUpdateButton = document.getElementById('cancelProfileUpdateButton');
    const confirmProfileUpdateButton = document.getElementById('confirmProfileUpdateButton');
    const profileChangesSummary = document.getElementById('profile-changes-summary');
    
    const passwordModal = document.getElementById('updatePasswordModal');
    const passwordModalPanel = document.getElementById('password-modal-panel');
    const closePasswordModalButton = document.getElementById('closePasswordModalButton');
    const cancelPasswordUpdateButton = document.getElementById('cancelPasswordUpdateButton');
    const confirmPasswordUpdateButton = document.getElementById('confirmPasswordUpdateButton');
    
    // Success modal elements
    const successModal = document.getElementById('successModal');
    const successModalPanel = document.getElementById('success-modal-panel');
    const successModalTitle = document.getElementById('success-modal-title');
    const successModalMessage = document.getElementById('success-modal-message');
    const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');

    // Modal helper functions
    const showProfileModal = () => {
        const name = document.getElementById('name').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        
        profileChangesSummary.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                    <div class="text-sm text-slate-500 mb-1">Full Name</div>
                    <div class="font-semibold text-slate-800">${name}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                    <div class="text-sm text-slate-500 mb-1">Username</div>
                    <div class="font-semibold text-slate-800">${username}</div>
                </div>
                <div class="md:col-span-2 bg-white p-4 rounded-lg border border-slate-200">
                    <div class="text-sm text-slate-500 mb-1">Email Address</div>
                    <div class="font-semibold text-slate-800">${email}</div>
                </div>
            </div>
        `;
        
        profileModal.classList.remove('hidden');
        setTimeout(() => {
            profileModal.classList.remove('opacity-0');
            profileModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hideProfileModal = () => {
        profileModal.classList.add('opacity-0');
        profileModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => profileModal.classList.add('hidden'), 300);
    };

    const showPasswordModal = () => {
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('password').value;
        
        if (!currentPassword || !newPassword) {
            alert('Please fill in all password fields.');
            return;
        }
        
        passwordModal.classList.remove('hidden');
        setTimeout(() => {
            passwordModal.classList.remove('opacity-0');
            passwordModalPanel.classList.remove('opacity-0', 'scale-95');
        }, 10);
    };

    const hidePasswordModal = () => {
        passwordModal.classList.add('opacity-0');
        passwordModalPanel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => passwordModal.classList.add('hidden'), 300);
    };

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

    // Email validation variables
    let emailValidationTimeout;
    let isEmailValid = false;
    const originalEmail = '{{ $user->email }}';
    
    // Email validation function
    async function validateEmail(email) {
        if (!email || email === originalEmail) {
            resetEmailValidation();
            updateProfileButtonState();
            return;
        }
        
        showEmailLoading();
        
        try {
            const response = await fetch('{{ route('profile.validate-email') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            });
            
            const data = await response.json();
            showEmailValidationResult(data.valid, data.message);
            isEmailValid = data.valid;
            updateProfileButtonState();
        } catch (error) {
            console.error('Email validation error:', error);
            showEmailValidationResult(false, 'Error validating email');
            isEmailValid = false;
            updateProfileButtonState();
        }
    }
    
    function showEmailLoading() {
        document.getElementById('email-validation-icon').classList.remove('hidden');
        document.getElementById('email-loading').classList.remove('hidden');
        document.getElementById('email-valid').classList.add('hidden');
        document.getElementById('email-invalid').classList.add('hidden');
        document.getElementById('email-validation-message').classList.add('hidden');
    }
    
    function showEmailValidationResult(isValid, message) {
        document.getElementById('email-loading').classList.add('hidden');
        
        if (isValid) {
            document.getElementById('email-valid').classList.remove('hidden');
            document.getElementById('email-invalid').classList.add('hidden');
            document.getElementById('email-validation-message').className = 'text-sm mt-1 text-green-600';
        } else {
            document.getElementById('email-valid').classList.add('hidden');
            document.getElementById('email-invalid').classList.remove('hidden');
            document.getElementById('email-validation-message').className = 'text-sm mt-1 text-red-600';
        }
        
        document.getElementById('email-validation-message').textContent = message;
        document.getElementById('email-validation-message').classList.remove('hidden');
    }
    
    function resetEmailValidation() {
        document.getElementById('email-validation-icon').classList.add('hidden');
        document.getElementById('email-loading').classList.add('hidden');
        document.getElementById('email-valid').classList.add('hidden');
        document.getElementById('email-invalid').classList.add('hidden');
        document.getElementById('email-validation-message').classList.add('hidden');
        isEmailValid = true; // Original email is valid
    }
    
    function updateProfileButtonState() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const updateButton = document.getElementById('updateProfileButton');
        
        if (name && email && (email === originalEmail || isEmailValid)) {
            updateButton.disabled = false;
            updateButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            updateButton.disabled = true;
            updateButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Email input event listener
    document.getElementById('email').addEventListener('input', function() {
        const email = this.value.trim();
        
        clearTimeout(emailValidationTimeout);
        
        if (email && email !== originalEmail) {
            emailValidationTimeout = setTimeout(() => {
                validateEmail(email);
            }, 500); // Debounce for 500ms
        } else {
            resetEmailValidation();
            updateProfileButtonState();
        }
    });
    
    // Name input event listener
    document.getElementById('name').addEventListener('input', updateProfileButtonState);
    
    // Initial validation check
    updateProfileButtonState();

    // Event listeners
    updateProfileButton.addEventListener('click', function() {
        const name = document.getElementById('name').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        
        if (!name || !username || !email) {
            alert('Please fill in all required fields.');
            return;
        }
        
        if (email !== originalEmail && !isEmailValid) {
            alert('Please use a real, active email address before updating your profile. Make sure the email exists and can receive messages.');
            return;
        }
        
        showProfileModal();
    });

    // Only add password button listener if it exists (not for employees)
    if (updatePasswordButton) {
        updatePasswordButton.addEventListener('click', showPasswordModal);
    }

    // Modal close event listeners
    closeProfileModalButton.addEventListener('click', hideProfileModal);
    cancelProfileUpdateButton.addEventListener('click', hideProfileModal);
    
    // Only add password modal listeners if elements exist (not for employees)
    if (closePasswordModalButton) {
        closePasswordModalButton.addEventListener('click', hidePasswordModal);
    }
    if (cancelPasswordUpdateButton) {
        cancelPasswordUpdateButton.addEventListener('click', hidePasswordModal);
    }
    closeSuccessModalButton.addEventListener('click', hideSuccessModal);

    // Confirm profile update
    confirmProfileUpdateButton.addEventListener('click', async function() {
        try {
            const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
            const formData = new FormData(form);
            
            const response = await fetch('{{ route('profile.update') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            hideProfileModal();
            
            if (response.ok) {
                showSuccessModal('Profile Updated!', 'Your profile information has been successfully updated.');
                setTimeout(() => window.location.reload(), 2000);
            } else {
                alert('Error updating profile. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating your profile.');
        }
    });

    // Confirm password update (only if button exists - not for employees)
    if (confirmPasswordUpdateButton) {
        confirmPasswordUpdateButton.addEventListener('click', async function() {
        try {
            const form = document.querySelector('form[action="{{ route('profile.password') }}"]');
            const formData = new FormData(form);
            
            const response = await fetch('{{ route('profile.password') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            hidePasswordModal();
            
            if (response.ok) {
                showSuccessModal('Password Updated!', 'Your password has been changed. You will be redirected to login.');
                setTimeout(() => window.location.href = '{{ route('login') }}', 3000);
            } else {
                alert('Error updating password. Please check your current password and try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating your password.');
        }
        });
    }

    // Close modals when clicking outside
    profileModal.addEventListener('click', function(e) {
        if (e.target === this) hideProfileModal();
    });

    // Only add password modal click listener if modal exists (not for employees)
    if (passwordModal) {
        passwordModal.addEventListener('click', function(e) {
            if (e.target === this) hidePasswordModal();
        });
    }

    successModal.addEventListener('click', function(e) {
        if (e.target === this) hideSuccessModal();
    });
});
</script>

@endsection
