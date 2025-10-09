@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">All Employee Activities</h1>
                <p class="text-purple-100 mt-2">Monitor all employee activities and exports</p>
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
                    <p class="text-sm font-medium text-gray-600">Total Exports</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['by_type']['export'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Controls -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Export Activity Report</h3>
            <form method="GET" action="{{ route('employees.export-activities') }}" class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label for="start_date" class="text-sm font-medium text-gray-700">From:</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}" class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                </div>
                <div class="flex items-center space-x-2">
                    <label for="end_date" class="text-sm font-medium text-gray-700">To:</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    Export CSV
                </button>
            </form>
        </div>
    </div>

    <!-- All Activities -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Recent Activities</h2>
            <p class="text-gray-600 text-sm mt-1">Latest 50 employee activities</p>
        </div>

        @if($activities->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($activities as $activity)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">{{ strtoupper(substr($activity->user->name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $activity->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $activity->formatted_description }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">
                                        {{ $activity->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $activity->created_at->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 {{ $activity->activity_color }}">
                                    {{ $activity->activity_icon }} {{ ucfirst($activity->activity_type) }}
                                </span>
                                <span>{{ $activity->user->username }}</span>
                                @if($activity->ip_address)
                                    <span>IP: {{ $activity->ip_address }}</span>
                                @endif
                                @if(isset($activity->metadata['browser']['browser']))
                                    <span>{{ $activity->metadata['browser']['browser'] }}</span>
                                @endif
                                <a href="{{ route('employees.activity-logs', $activity->user->id) }}" class="text-blue-600 hover:text-blue-800">
                                    View All →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No activities found</h3>
                <p class="text-gray-500">No employee activities have been recorded yet.</p>
            </div>
        @endif
    </div>

    <!-- Activity Types Legend -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity Types</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-lg">📄</span>
                <span class="text-sm text-gray-600">Export</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-lg">🔑</span>
                <span class="text-sm text-gray-600">Login</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-lg">🚪</span>
                <span class="text-sm text-gray-600">Logout</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-lg">👁️</span>
                <span class="text-sm text-gray-600">Page View</span>
            </div>
        </div>
    </div>
</div>
@endsection
