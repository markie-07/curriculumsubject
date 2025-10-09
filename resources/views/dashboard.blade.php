@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">System Dashboard</h1>
                <p class="text-blue-100 mt-2">{{ $dashboardData['welcome_message'] }}</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ now()->format('M d, Y') }}</div>
                <div class="text-blue-200">{{ now()->format('l, g:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Curriculum Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
            <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path>
                </svg>
            </div>
            Curriculum Overview
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Senior High</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $dashboardData['stats']['curriculum_senior_high'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Curriculum Programs</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-emerald-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">College</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $dashboardData['stats']['curriculum_college'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Curriculum Programs</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-purple-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $dashboardData['stats']['total_curriculums'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">All Programs</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Subject Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
            <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path>
                </svg>
            </div>
            Subject Management
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="{{ route('subject_mapping') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-indigo-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Subjects</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $dashboardData['stats']['total_subjects'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">All Courses</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('pre_requisite') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-amber-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Prerequisites</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-amber-600 transition-colors">{{ $dashboardData['stats']['total_prerequisites'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Dependencies</p>
                    </div>
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('subject_mapping_history') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-rose-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Mapping History</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-rose-600 transition-colors">{{ $dashboardData['stats']['total_mapping_history'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Records</p>
                    </div>
                    <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center group-hover:bg-rose-200 transition-colors">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('subject_history') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-gray-300 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Removed</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-gray-600 transition-colors">{{ $dashboardData['stats']['removed_subjects'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Archived</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Export & Employee Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
            <div class="w-6 h-6 bg-teal-100 rounded-md flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            System Activity
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="{{ route('equivalency_tool') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-teal-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Equivalency</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-teal-600 transition-colors">{{ $dashboardData['stats']['total_equivalencies'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Mappings</p>
                    </div>
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('curriculum_export_tool') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-cyan-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Exports</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-cyan-600 transition-colors">{{ $dashboardData['stats']['curriculum_exports'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Downloads</p>
                    </div>
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('employees.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-green-200 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Active Staff</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ $dashboardData['stats']['employees_active'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('employees.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-slate-300 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Inactive Staff</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-slate-600 transition-colors">{{ $dashboardData['stats']['employees_inactive'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Offline</p>
                    </div>
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activities -->
    @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
            <div class="w-6 h-6 bg-violet-100 rounded-md flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            Recent Activities
        </h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-200">
                @foreach($dashboardData['recent_activities'] as $activity)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">{{ strtoupper(substr($activity->user->name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity->user->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <p class="text-sm text-gray-600">
                                {{ $activity->formatted_description }}
                            </p>
                            <div class="mt-1 flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 {{ $activity->activity_color }}">
                                    {{ $activity->activity_icon }} {{ ucfirst($activity->activity_type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('employees.all-activities') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    View All Activities →
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
            <div class="w-5 h-5 bg-orange-100 rounded-md flex items-center justify-center mr-3">
                <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            Quick Actions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('employees.index') }}" class="group flex items-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300 border border-blue-200/50 hover:border-blue-300/50 hover:shadow-lg">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Manage Staff</h4>
                    <p class="text-sm text-gray-600">Employee accounts & status</p>
                </div>
            </a>

            <a href="{{ route('curriculum_export_tool') }}" class="group flex items-center p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-300 border border-emerald-200/50 hover:border-emerald-300/50 hover:shadow-lg">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Export Tool</h4>
                    <p class="text-sm text-gray-600">Curriculum downloads</p>
                </div>
            </a>

            <a href="{{ route('employees.all-activities') }}" class="group flex items-center p-6 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all duration-300 border border-violet-200/50 hover:border-violet-300/50 hover:shadow-lg">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Activity Reports</h4>
                    <p class="text-sm text-gray-600">Detailed activity logs</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection