@extends('layouts.app')

@section('content')
<style>
    .stars {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }
    
    .star {
        position: absolute;
        background: #1f2937;
        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        animation: twinkle 3s ease-in-out infinite alternate;
        opacity: 0.6;
    }
    
    .star:nth-child(odd) {
        animation-delay: 1s;
    }
    
    .star:nth-child(3n) {
        animation-delay: 2s;
    }
    
    .star.small {
        width: 4px;
        height: 4px;
        animation-duration: 2s;
    }
    
    .star.medium {
        width: 6px;
        height: 6px;
        animation-duration: 3s;
    }
    
    .star.large {
        width: 8px;
        height: 8px;
        animation-duration: 4s;
    }
    
    @keyframes twinkle {
        0% {
            opacity: 0.3;
            transform: scale(0.8) rotate(0deg);
        }
        25% {
            opacity: 0.6;
            transform: scale(1.0) rotate(90deg);
        }
        50% {
            opacity: 0.9;
            transform: scale(1.3) rotate(180deg);
        }
        75% {
            opacity: 0.5;
            transform: scale(1.1) rotate(270deg);
        }
        100% {
            opacity: 0.2;
            transform: scale(0.7) rotate(360deg);
        }
    }
    
    /* Additional floating animation */
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-10px) rotate(180deg);
        }
    }
    
    /* Pulse animation for some stars */
    @keyframes pulse {
        0%, 100% {
            opacity: 0.4;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.2);
        }
    }
    
    .dashboard-content {
        position: relative;
        z-index: 10;
    }
</style>

<!-- Animated Stars Background -->
<div class="stars">
    <!-- First Layer - Twinkling Stars -->
    <div class="star small" style="top: 10%; left: 15%;"></div>
    <div class="star medium" style="top: 20%; left: 80%;"></div>
    <div class="star large" style="top: 30%; left: 25%;"></div>
    <div class="star small" style="top: 15%; left: 60%;"></div>
    <div class="star medium" style="top: 50%; left: 10%;"></div>
    <div class="star large" style="top: 40%; left: 90%;"></div>
    <div class="star small" style="top: 70%; left: 30%;"></div>
    <div class="star medium" style="top: 60%; left: 70%;"></div>
    <div class="star large" style="top: 80%; left: 50%;"></div>
    <div class="star small" style="top: 25%; left: 45%;"></div>
    <div class="star medium" style="top: 85%; left: 20%;"></div>
    <div class="star small" style="top: 35%; left: 75%;"></div>
    <div class="star large" style="top: 65%; left: 85%;"></div>
    <div class="star small" style="top: 45%; left: 5%;"></div>
    <div class="star medium" style="top: 75%; left: 65%;"></div>
    
    <!-- Second Layer - More Stars -->
    <div class="star small" style="top: 5%; left: 35%; animation-delay: 0.5s;"></div>
    <div class="star medium" style="top: 12%; left: 95%; animation-delay: 1.5s;"></div>
    <div class="star small" style="top: 28%; left: 55%; animation-delay: 2.5s;"></div>
    <div class="star large" style="top: 18%; left: 40%; animation-delay: 0.8s;"></div>
    <div class="star small" style="top: 42%; left: 15%; animation-delay: 1.8s;"></div>
    <div class="star medium" style="top: 38%; left: 65%; animation-delay: 2.2s;"></div>
    <div class="star small" style="top: 55%; left: 35%; animation-delay: 0.3s;"></div>
    <div class="star large" style="top: 52%; left: 95%; animation-delay: 1.3s;"></div>
    <div class="star small" style="top: 68%; left: 8%; animation-delay: 2.8s;"></div>
    <div class="star medium" style="top: 72%; left: 88%; animation-delay: 0.6s;"></div>
    <div class="star small" style="top: 88%; left: 40%; animation-delay: 1.6s;"></div>
    <div class="star large" style="top: 92%; left: 75%; animation-delay: 2.6s;"></div>
    
    <!-- Third Layer - Corner Stars -->
    <div class="star small" style="top: 3%; left: 8%; animation-delay: 0.9s;"></div>
    <div class="star medium" style="top: 7%; left: 92%; animation-delay: 1.9s;"></div>
    <div class="star small" style="top: 93%; left: 12%; animation-delay: 2.9s;"></div>
    <div class="star large" style="top: 97%; left: 88%; animation-delay: 0.4s;"></div>
    
    <!-- Fourth Layer - Floating Stars -->
    <div class="star small" style="top: 22%; left: 18%; animation: float 4s ease-in-out infinite; animation-delay: 1.1s;"></div>
    <div class="star medium" style="top: 33%; left: 82%; animation: pulse 3s ease-in-out infinite; animation-delay: 2.1s;"></div>
    <div class="star small" style="top: 47%; left: 28%; animation: float 5s ease-in-out infinite; animation-delay: 0.7s;"></div>
    <div class="star large" style="top: 58%; left: 78%; animation: pulse 2.5s ease-in-out infinite; animation-delay: 1.7s;"></div>
    <div class="star small" style="top: 77%; left: 48%; animation: float 3.5s ease-in-out infinite; animation-delay: 2.7s;"></div>
    
    <!-- Fifth Layer - More Animated Stars -->
    <div class="star small" style="top: 8%; left: 28%; animation: pulse 2s ease-in-out infinite; animation-delay: 0.2s;"></div>
    <div class="star medium" style="top: 17%; left: 72%; animation: float 6s ease-in-out infinite; animation-delay: 1.2s;"></div>
    <div class="star small" style="top: 37%; left: 48%; animation: twinkle 2.5s ease-in-out infinite alternate; animation-delay: 2.2s;"></div>
    <div class="star large" style="top: 48%; left: 68%; animation: pulse 4s ease-in-out infinite; animation-delay: 0.8s;"></div>
    <div class="star small" style="top: 63%; left: 18%; animation: float 3s ease-in-out infinite; animation-delay: 1.8s;"></div>
    <div class="star medium" style="top: 83%; left: 68%; animation: twinkle 3.5s ease-in-out infinite alternate; animation-delay: 2.8s;"></div>
</div>

<div class="container mx-auto px-4 py-3 h-screen overflow-hidden dashboard-content">
    <!-- Compact Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-3 text-white mb-3">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">System Dashboard</h1>
                <p class="text-blue-100 text-xs">{{ $dashboardData['welcome_message'] }}</p>
            </div>
            <div class="text-right text-sm">
                <div class="font-bold">{{ now()->format('M d, Y') }}</div>
                <div class="text-blue-200 text-xs">{{ now()->format('g:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid - Larger when less content -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-4">
        <!-- Curriculum Stats -->
        <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-blue-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-blue-600">{{ $dashboardData['stats']['curriculum_senior_high'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Senior High</p>
            </div>
        </a>

        <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-emerald-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-emerald-600">{{ $dashboardData['stats']['curriculum_college'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">College</p>
            </div>
        </a>

        <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-purple-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-purple-600">{{ $dashboardData['stats']['total_curriculums'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Total</p>
            </div>
        </a>

        <a href="{{ route('subject_mapping') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-indigo-600">{{ $dashboardData['stats']['total_subjects'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Subjects</p>
            </div>
        </a>

        <a href="{{ route('pre_requisite') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-amber-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-amber-600">{{ $dashboardData['stats']['total_prerequisites'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Prerequisites</p>
            </div>
        </a>

        <a href="{{ route('equivalency_tool') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-teal-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-teal-600">{{ $dashboardData['stats']['total_equivalencies'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Equivalency</p>
            </div>
        </a>

        <a href="{{ route('curriculum_export_tool') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-cyan-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-cyan-600">{{ $dashboardData['stats']['curriculum_exports'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Exports</p>
            </div>
        </a>

        <a href="{{ route('employees.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg hover:border-green-200 transition-all group">
            <div class="text-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-900 group-hover:text-green-600">{{ $dashboardData['stats']['employees_active'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Active Staff</p>
            </div>
        </a>
    </div>

    <!-- Two Column Layout for Activities and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 flex-1">
        <!-- Recent Activities -->
        @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 overflow-hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Recent Activities
            </h3>
            <div class="space-y-3 overflow-y-auto" style="max-height: 400px;">
                @foreach($dashboardData['recent_activities'] as $activity)
                <div class="flex items-center space-x-4 p-4 hover:bg-gray-50 rounded-lg border border-gray-100">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $activity->user->name }}</p>
                        <p class="text-sm text-gray-600 truncate">{{ $activity->formatted_description }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 {{ $activity->activity_color }}">
                                {{ $activity->activity_icon }} {{ ucfirst($activity->activity_type) }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                Quick Actions
            </h3>
            <div class="space-y-4">
                <a href="{{ route('employees.index') }}" class="flex items-center p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all group border border-blue-200/50 hover:border-blue-300/50 hover:shadow-lg">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-gray-900">Manage Staff</h4>
                        <p class="text-sm text-gray-600">Employee accounts & status</p>
                    </div>
                </a>

                <a href="{{ route('curriculum_export_tool') }}" class="flex items-center p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all group border border-emerald-200/50 hover:border-emerald-300/50 hover:shadow-lg">
                    <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-gray-900">Export Tool</h4>
                        <p class="text-sm text-gray-600">Curriculum downloads</p>
                    </div>
                </a>

                <a href="{{ route('employees.all-activities') }}" class="flex items-center p-5 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all group border border-violet-200/50 hover:border-violet-300/50 hover:shadow-lg">
                    <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-gray-900">Activity Reports</h4>
                        <p class="text-sm text-gray-600">Detailed activity logs</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
