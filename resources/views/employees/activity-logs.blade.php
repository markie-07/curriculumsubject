@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">{{ $employee->name }}'s Activity Log</h1>
                <p class="text-blue-100 mt-2">Track all activities and exports</p>
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

    <!-- Employee Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-xl">{{ strtoupper(substr($employee->name, 0, 2)) }}</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-gray-900">{{ $employee->name }}</h3>
                <p class="text-gray-600">{{ $employee->email }} • {{ $employee->username }}</p>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status_badge_color }}">
                        {{ ucfirst($employee->status ?? 'active') }}
                    </span>
                    @if($employee->last_activity)
                        <span class="text-sm text-gray-500">Last active: {{ $employee->last_activity->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
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

    <!-- Activity Log -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Activity Timeline</h2>
            <p class="text-gray-600 text-sm mt-1">Recent activities and exports</p>
        </div>

        @if($activities->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($activities as $activity)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <span class="text-lg">{{ $activity->activity_icon }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity->formatted_description }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->created_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                            <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 {{ $activity->activity_color }}">
                                    {{ ucfirst($activity->activity_type) }}
                                </span>
                                @if($activity->ip_address)
                                    <span>IP: {{ $activity->ip_address }}</span>
                                @endif
                                @if(isset($activity->metadata['browser']['browser']))
                                    <span>{{ $activity->metadata['browser']['browser'] }} on {{ $activity->metadata['browser']['platform'] ?? 'Unknown' }}</span>
                                @endif
                            </div>
                            @if($activity->metadata && count($activity->metadata) > 0)
                                <div class="mt-2">
                                    <details class="text-xs text-gray-600">
                                        <summary class="cursor-pointer hover:text-gray-800">View Details</summary>
                                        <div class="mt-1 bg-gray-50 p-2 rounded text-xs">
                                            <pre>{{ json_encode($activity->metadata, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </details>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No activities found</h3>
                <p class="text-gray-500">This employee hasn't performed any activities yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
