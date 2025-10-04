<aside id="sidebar" class="w-72 bg-[#1e3a8a] text-gray-200 flex flex-col transition-all duration-300 ease-in-out sm:translate-x-0 -translate-x-full">
    <div class="flex items-center justify-between p-4 border-b border-blue-800">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="object-contain" style="width: 150px; height: 50px; max-width:none; max-height:none;">
            </div>
            <h1 class="text-sm font-semibold whitespace-nowrap sidebar-title">Curriculum & Subject<br>Management System</h1>
        </div>
    </div>
    <div class="flex flex-col items-center p-5 my-4">
        <div class="w-24 h-24 bg-gray-400 rounded-full mb-3 profile-avatar transition-all duration-300"></div>
        <p class="font-semibold profile-text">ME</p>
        <p class="text-sm text-gray-400 profile-text">22012345</p>
    </div>
    <nav class="flex-1 px-4 pb-4 space-y-2 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="nav-text">Dashboard</span>
        </a>
        <a href="{{ route('curriculum_builder') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="nav-text">Curriculum Builder</span>
        </a>
        {{-- New Course Builder Link --}}
        <a href="{{ route('course_builder') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="nav-text">Course Builder</span>
        </a>
        <a href="{{ route('subject_mapping') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="nav-text">Subject Mapping</span>
        </a>
        <a href="{{ route('pre_requisite') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path></svg>
            <span class="nav-text">Pre-requisite Configuration</span>
        </a>
        <a href="{{ route('subject_history') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path></svg>
            <span class="nav-text">Subject Offering History</span>  
        </a>
        <a href="{{ route('equivalency_tool') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            <span class="nav-text">Subject Equivalency Tool</span>
        </a>
        <a href="{{ route('grade_setup') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="nav-text">Grade Weighting Setup</span>
        </a>
        <a href="{{ route('compliance.validator') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span class="nav-text">Compliance Validator</span>
        </a>
        <a href="{{ route('curriculum_export_tool') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors nav-link">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            <span class="nav-text">Curriculum Export Tool</span>
        </a>
    </nav>
    <div class="p-4 mt-auto border-t border-blue-800 sidebar-footer">
        <p class="text-xs text-center text-gray-400">
            Bestlink College of the Philippines<br>
            Copyright © 2025 Ascendens Asia. All right reserved
        </p>
    </div>
</aside>