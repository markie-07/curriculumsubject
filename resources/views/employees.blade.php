@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    @if(request()->routeIs('employees.create') || request()->routeIs('employees.edit'))
        <!-- ADD/EDIT EMPLOYEE FORM -->
        <div class="employee-header-gradient rounded-lg shadow-lg p-6 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">
                        {{ request()->routeIs('employees.create') ? 'Add New Employee' : 'Edit Employee' }}
                    </h1>
                    <p class="text-blue-100 mt-2">
                        {{ request()->routeIs('employees.create') ? 'Create a new employee account with access credentials' : 'Update employee account information' }}
                    </p>
                </div>
                <div class="text-right">
                    <a href="{{ route('employees.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to Employees</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Employee Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Employee Information</h2>
                <p class="text-gray-600 text-sm mt-1">
                    {{ request()->routeIs('employees.create') ? 'Fill in the details to create a new employee account' : 'Update the employee account details' }}
                </p>
            </div>

            <form method="POST" 
                  action="{{ request()->routeIs('employees.create') ? route('employees.store') : route('employees.update', $employee->id ?? '') }}" 
                  class="p-6 space-y-6">
                @csrf
                @if(request()->routeIs('employees.edit'))
                    @method('PUT')
                @endif

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $employee->name ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                           placeholder="Enter employee's full name"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username', $employee->username ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('username') border-red-500 @enderror"
                           placeholder="Enter unique username"
                           required>
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">This will be used for login authentication</p>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $employee->email ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                           placeholder="employee@example.com"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Preferably a Gmail address for OTP authentication</p>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        @if(request()->routeIs('employees.create'))
                            Password <span class="text-red-500">*</span>
                        @else
                            New Password <span class="text-gray-500">(Leave blank to keep current password)</span>
                        @endif
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('password') border-red-500 @enderror"
                               placeholder="{{ request()->routeIs('employees.create') ? '••••••••' : '•••••••• (optional)' }}"
                               {{ request()->routeIs('employees.create') ? 'required' : '' }}>
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                onclick="togglePasswordVisibility('password', this)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        {{ request()->routeIs('employees.create') ? 'Minimum 8 characters required' : 'Minimum 8 characters required if changing password' }}
                    </p>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ request()->routeIs('employees.create') ? 'Confirm Password' : 'Confirm New Password' }}
                        @if(request()->routeIs('employees.create'))
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="{{ request()->routeIs('employees.create') ? '••••••••' : '•••••••• (optional)' }}"
                               {{ request()->routeIs('employees.create') ? 'required' : '' }}>
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                onclick="togglePasswordVisibility('password_confirmation', this)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('employees.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(request()->routeIs('employees.create'))
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @endif
                        </svg>
                        <span>{{ request()->routeIs('employees.create') ? 'Create Employee' : 'Update Employee' }}</span>
                    </button>
                </div>
            </form>
        </div>

        @if(request()->routeIs('employees.edit') && isset($employee))
            <!-- Employee Info Card for Edit -->
            <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                <div class="flex items-start space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">{{ strtoupper(substr($employee->name, 0, 2)) }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $employee->name }}</h3>
                        <div class="text-gray-600 space-y-1">
                            <p><strong>Current Username:</strong> {{ $employee->username }}</p>
                            <p><strong>Current Email:</strong> {{ $employee->email }}</p>
                            <p><strong>Account Created:</strong> {{ $employee->created_at->format('M d, Y \a\t g:i A') }}</p>
                            <p><strong>Last Updated:</strong> {{ $employee->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">📧 Employee Account Setup</h3>
                    <div class="text-blue-800 space-y-2">
                        @if(request()->routeIs('employees.create'))
                            <p><strong>✅ Automatic Role Assignment:</strong> New accounts are automatically assigned the "employee" role.</p>
                            <p><strong>🔐 Secure Authentication:</strong> Employees will use username/password + OTP for secure login.</p>
                            <p><strong>📨 Gmail Recommended:</strong> Gmail addresses work best with our OTP email system.</p>
                        @else
                            <p><strong>✅ Account Updates:</strong> Changes will be applied immediately to the employee account.</p>
                            <p><strong>🔐 Password Security:</strong> Leave password blank to keep the current password unchanged.</p>
                            <p><strong>📨 Email Changes:</strong> Updating email will affect OTP delivery for future logins.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- EMPLOYEE LIST VIEW -->
        <div class="employee-header-gradient rounded-lg shadow-lg p-6 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Employee Management</h1>
                    <p class="text-blue-100 mt-2">Manage employee accounts and access</p>
                </div>
                <div class="text-right">
                    <button id="addEmployeeButton" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add Employee</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Activity Statistics Dashboard -->
        @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Activities</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Week</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['week'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Exports</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['by_type']['export'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
                <div class="flex space-x-3">
                    <a href="{{ route('employees.all-activities') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        View All Activities
                    </a>
                    <a href="{{ route('employees.export-activities') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Export Report
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Employee List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Employee Accounts</h2>
                <p class="text-gray-600 text-sm mt-1">Total: {{ $employees->total() }} employees</p>
            </div>

            @if($employees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 employees-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activities</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($employees as $employee)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">{{ strtoupper(substr($employee->name, 0, 2)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                            <div class="text-sm text-gray-500">Employee</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->email }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if(str_contains($employee->email, '@gmail.com'))
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Gmail ✓
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Other Email
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $employee->username }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status_badge_color }}">
                                        {{ ucfirst($employee->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-600">{{ $employee->activity_logs_count ?? 0 }}</span>
                                        <a href="{{ route('employees.activity-logs', $employee->id) }}" class="text-blue-600 hover:text-blue-900 text-xs">
                                            View Logs
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($employee->last_activity)
                                        {{ $employee->last_activity->diffForHumans() }}
                                    @else
                                        <span class="text-gray-400">Never</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="text-blue-600 hover:text-blue-900 inline-flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    
                                    <button type="button" 
                                            class="{{ $employee->status === 'active' ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }} inline-flex items-center space-x-1 toggle-status-btn"
                                            data-employee-id="{{ $employee->id }}"
                                            data-employee-name="{{ $employee->name }}"
                                            data-current-status="{{ $employee->status }}"
                                            data-action="{{ $employee->status === 'active' ? 'deactivate' : 'activate' }}">
                                        @if($employee->status === 'active')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                            <span>Deactivate</span>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Activate</span>
                                        @endif
                                    </button>
                                    
                                    <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $employees->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No employees found</h3>
                    <p class="text-gray-500 mb-4">Get started by creating your first employee account.</p>
                    <a href="{{ route('employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                        Add First Employee
                    </a>
                </div>
            @endif
        </div>

        <!-- Info Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">📧 OTP Email System</h3>
                    <div class="text-blue-800 space-y-2">
                        <p><strong>✅ No App Passwords Needed:</strong> Employees can use their personal Gmail addresses without setting up app passwords.</p>
                        <p><strong>📨 Centralized Sending:</strong> OTP emails are sent from admin accounts to employee Gmail addresses.</p>
                        <p><strong>🔐 Secure Login:</strong> Employees receive OTP codes directly in their personal Gmail for secure authentication.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Employee Modal --}}
        <div id="addEmployeeModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="employee-modal-panel">
                    <button id="closeEmployeeModalButton" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200 rounded-full p-1 hover:bg-slate-100" aria-label="Close modal">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <div class="text-center mb-8">
                        <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="mx-auto h-16 w-auto mb-4">
                        <h2 id="employee-modal-title" class="text-2xl font-bold text-slate-800">Add New Employee</h2>
                        <p class="text-sm text-slate-500 mt-1">Create a new employee account with access credentials.</p>
                    </div>

                    <form id="employeeForm" class="space-y-6">
                        @csrf
                        <div>
                            <label for="modal-name" class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                            <input type="text" id="modal-name" name="name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="modal-username" class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                            <input type="text" id="modal-username" name="username" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="modal-email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                            <input type="email" id="modal-email" name="email" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="modal-password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" id="modal-password" name="password" class="w-full px-3 py-2 pr-10 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••" required>
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                        onclick="togglePasswordVisibility('modal-password', this)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="modal-password-confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="modal-password-confirmation" name="password_confirmation" class="w-full px-3 py-2 pr-10 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••" required>
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                        onclick="togglePasswordVisibility('modal-password-confirmation', this)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" id="cancelEmployeeModalButton" class="flex-1 px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                            <button type="submit" id="employee-submit-button" class="flex-1 px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                <span>Create Employee</span>
                            </button>
                        </div>
                    </form>
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

        {{-- Status Toggle Confirmation Modal --}}
        <div id="statusConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out hidden">
            <div class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="status-confirm-modal-panel">
                <div class="text-center mb-6">
                    <div id="status-icon-container" class="w-16 h-16 rounded-full p-3 flex items-center justify-center mx-auto mb-4">
                        <svg id="status-icon" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 id="status-confirm-title" class="text-xl font-bold text-slate-800 mb-2"></h3>
                    <p id="status-confirm-message" class="text-sm text-slate-600"></p>
                </div>
                
                <div class="flex gap-3">
                    <button id="cancelStatusChange" class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                    <button id="confirmStatusChange" class="flex-1 px-4 py-2.5 text-sm font-medium text-white rounded-lg transition-all">
                        <span id="confirm-button-text">Confirm</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
        // Password visibility toggle function
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const svg = button.querySelector('svg');
            
            if (input.type === 'password') {
                input.type = 'text';
                // Change to "eye-slash" icon (hidden)
                svg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                input.type = 'password';
                // Change back to "eye" icon (visible)
                svg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Modal elements
            const addEmployeeButton = document.getElementById('addEmployeeButton');
            const addEmployeeModal = document.getElementById('addEmployeeModal');
            const closeEmployeeModalButton = document.getElementById('closeEmployeeModalButton');
            const cancelEmployeeModalButton = document.getElementById('cancelEmployeeModalButton');
            const employeeModalPanel = document.getElementById('employee-modal-panel');
            const employeeForm = document.getElementById('employeeForm');
            
            // Success modal elements
            const successModal = document.getElementById('successModal');
            const successModalPanel = document.getElementById('success-modal-panel');
            const successModalTitle = document.getElementById('success-modal-title');
            const successModalMessage = document.getElementById('success-modal-message');
            const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');

            // Status confirmation modal elements
            const statusConfirmModal = document.getElementById('statusConfirmModal');
            const statusConfirmModalPanel = document.getElementById('status-confirm-modal-panel');
            const statusIconContainer = document.getElementById('status-icon-container');
            const statusIcon = document.getElementById('status-icon');
            const statusConfirmTitle = document.getElementById('status-confirm-title');
            const statusConfirmMessage = document.getElementById('status-confirm-message');
            const cancelStatusChange = document.getElementById('cancelStatusChange');
            const confirmStatusChange = document.getElementById('confirmStatusChange');
            const confirmButtonText = document.getElementById('confirm-button-text');

            // Store current action data
            let currentStatusAction = null;

            // Modal helper functions
            const showAddEmployeeModal = () => {
                addEmployeeModal.classList.remove('hidden');
                setTimeout(() => {
                    addEmployeeModal.classList.remove('opacity-0');
                    employeeModalPanel.classList.remove('opacity-0', 'scale-95');
                }, 10);
            };

            const hideAddEmployeeModal = () => {
                addEmployeeModal.classList.add('opacity-0');
                employeeModalPanel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => addEmployeeModal.classList.add('hidden'), 300);
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

            const showStatusConfirmModal = (employeeData) => {
                const action = employeeData.action;
                const employeeName = employeeData.name;
                
                // Set modal content based on action
                if (action === 'deactivate') {
                    statusIconContainer.className = 'w-16 h-16 rounded-full bg-orange-100 p-3 flex items-center justify-center mx-auto mb-4';
                    statusIcon.className = 'w-10 h-10 text-orange-600';
                    statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>';
                    statusConfirmTitle.textContent = 'Deactivate Employee';
                    statusConfirmMessage.textContent = `Are you sure you want to deactivate ${employeeName}? They will lose access to the system.`;
                    confirmStatusChange.className = 'flex-1 px-4 py-2.5 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-all';
                    confirmButtonText.textContent = 'Deactivate';
                } else {
                    statusIconContainer.className = 'w-16 h-16 rounded-full bg-green-100 p-3 flex items-center justify-center mx-auto mb-4';
                    statusIcon.className = 'w-10 h-10 text-green-600';
                    statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                    statusConfirmTitle.textContent = 'Activate Employee';
                    statusConfirmMessage.textContent = `Are you sure you want to activate ${employeeName}? They will regain access to the system.`;
                    confirmStatusChange.className = 'flex-1 px-4 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-all';
                    confirmButtonText.textContent = 'Activate';
                }
                
                currentStatusAction = employeeData;
                statusConfirmModal.classList.remove('hidden');
                setTimeout(() => {
                    statusConfirmModal.classList.remove('opacity-0');
                    statusConfirmModalPanel.classList.remove('opacity-0', 'scale-95');
                }, 10);
            };

            const hideStatusConfirmModal = () => {
                statusConfirmModal.classList.add('opacity-0');
                statusConfirmModalPanel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    statusConfirmModal.classList.add('hidden');
                    currentStatusAction = null;
                }, 300);
            };

            // Event listeners
            addEmployeeButton.addEventListener('click', showAddEmployeeModal);
            closeEmployeeModalButton.addEventListener('click', hideAddEmployeeModal);
            cancelEmployeeModalButton.addEventListener('click', hideAddEmployeeModal);
            closeSuccessModalButton.addEventListener('click', hideSuccessModal);

            // Status confirmation modal event listeners
            cancelStatusChange.addEventListener('click', hideStatusConfirmModal);
            
            // Status toggle buttons
            document.querySelectorAll('.toggle-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const employeeData = {
                        id: this.dataset.employeeId,
                        name: this.dataset.employeeName,
                        currentStatus: this.dataset.currentStatus,
                        action: this.dataset.action
                    };
                    showStatusConfirmModal(employeeData);
                });
            });

            // Confirm status change
            confirmStatusChange.addEventListener('click', async function() {
                if (!currentStatusAction) return;
                
                try {
                    const response = await fetch(`/employees/${currentStatusAction.id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        hideStatusConfirmModal();
                        
                        const actionText = currentStatusAction.action === 'activate' ? 'activated' : 'deactivated';
                        showSuccessModal(
                            'Status Updated!', 
                            `${currentStatusAction.name} has been successfully ${actionText}.`
                        );
                        
                        // Reload page after success modal
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        const errorData = await response.json();
                        alert('Error: ' + (errorData.message || 'Failed to update employee status'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while updating the employee status.');
                }
            });

            // Employee form submission
            employeeForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route('employees.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        hideAddEmployeeModal();
                        showSuccessModal('Employee Created!', 'The employee account has been successfully created.');
                        
                        // Reset form
                        employeeForm.reset();
                        
                        // Reload page after success modal
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        const errorData = await response.json();
                        let errorMessage = errorData.message || 'Failed to create employee';
                        
                        // Handle validation errors
                        if (errorData.errors) {
                            const errors = Object.values(errorData.errors).flat();
                            errorMessage = errors.join('\n');
                        }
                        
                        alert('Error: ' + errorMessage);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while creating the employee. Please check the console for details.');
                }
            });

            // Close modals when clicking outside
            addEmployeeModal.addEventListener('click', function(e) {
                if (e.target === this) hideAddEmployeeModal();
            });

            successModal.addEventListener('click', function(e) {
                if (e.target === this) hideSuccessModal();
            });
        });
        </script>
    @endif
</div>
@endsection
