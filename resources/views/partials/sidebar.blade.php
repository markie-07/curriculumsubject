<aside id="sidebar" class="w-72 bg-[#1e3a8a] text-gray-200 flex flex-col transition-all duration-300 ease-in-out sm:translate-x-0 -translate-x-full collapsed" style="z-index: 40 !important;">
    <!-- Header Section -->
    <div class="flex items-center justify-between p-4 border-b border-blue-800">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="object-contain" style="width: 150px; height: 50px; max-width:none; max-height:none;">
            </div>
            <h1 class="text-sm font-semibold whitespace-nowrap sidebar-title">Curriculum & Subject<br>Management System</h1>
        </div>
        <!-- Mobile Close Button -->
        <button id="sidebar-close" class="sm:hidden text-gray-400 hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <!-- User Profile Section -->
    <div class="flex flex-col items-center p-5 my-4 profile-section">
        <div class="relative group">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full mb-3 profile-avatar transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl hover:scale-105 border-2 border-blue-400/30 hover:border-blue-300/50">
                <span class="text-white font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
            </div>
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full opacity-0 group-hover:opacity-20 transition-opacity duration-300 blur-sm"></div>
        </div>
        <div class="text-center bg-blue-800/20 rounded-lg px-4 py-2 hover:bg-blue-800/30 transition-all duration-200">
            <p class="font-semibold profile-text text-white">{{ Auth::user()->name }}</p>
            <p class="text-sm text-blue-200 profile-text">{{ '@' . Auth::user()->username }}</p>
        </div>
    </div>
    <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto">
        @php $isEmployee = Auth::user()->role === 'employee'; @endphp
        
        <!-- Dashboard -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Dashboard</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('dashboard') }}" data-tooltip="Dashboard" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Dashboard</span>
        </a>
        @endif

        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- Official Curriculum -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Official Curriculum</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('official_curriculum') }}" data-tooltip="Official Curriculum" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Official Curriculum</span>
        </a>
        @endif

        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- GROUP 1: Curriculum Management -->
        <div class="px-4 py-2 mb-2 section-header">
            <h3 class="text-xs font-bold text-blue-300 uppercase tracking-wider">Curriculum Management</h3>
            <p class="text-[10px] text-blue-200/60 mt-0.5">Build and manage curriculums</p>
        </div>
        <!-- Curriculum Builder -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Curriculum Builder</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('curriculum_builder') }}" data-tooltip="Curriculum Builder" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Curriculum Builder</span>
        </a>
        @endif

        <!-- Subject Mapping -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Subject Mapping</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('subject_mapping') }}" data-tooltip="Subject Mapping" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Subject Mapping</span>
        </a>
        @endif

        <!-- Pre-requisite -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Pre-requisite</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('pre_requisite') }}" data-tooltip="Pre-requisite" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Pre-requisite</span>
        </a>
        @endif

        <!-- Compliance Validator -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Compliance Validator</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('compliance.validator') }}" data-tooltip="Compliance Validator" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Compliance Validator</span>
        </a>
        @endif

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- GROUP 2: Course & Grading -->
        <div class="px-4 py-2 mb-2 section-header">
            <h3 class="text-xs font-bold text-blue-300 uppercase tracking-wider">Course & Grading</h3>
            <p class="text-[10px] text-blue-200/60 mt-0.5">Course and grade management</p>
        </div>
        <!-- Course Builder -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Course Builder</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('course_builder') }}" data-tooltip="Course Builder" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Course Builder</span>
        </a>
        @endif

        <!-- Grade Weighting Setup -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Grade Weighting Setup</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('grade_setup') }}" data-tooltip="Grade Weighting Setup" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Grade Weighting Setup</span>
        </a>
        @endif

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- SOLO: Subject Equivalency Tool -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Subject Equivalency Tool</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('equivalency_tool') }}" data-tooltip="Subject Equivalency Tool" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Subject Equivalency Tool</span>
        </a>
        @endif

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- SOLO: Mapping History -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Mapping History</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('subject_mapping_history') }}" data-tooltip="Mapping History" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Mapping History</span>
        </a>
        @endif

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- SOLO: Curriculum Export Tool - Accessible to ALL roles -->
        <a href="{{ route('curriculum_export_tool') }}" data-tooltip="Curriculum Export Tool" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Curriculum Export Tool</span>
        </a>

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- SOLO: Employee Management -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Employee Management</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('employees.index') }}" data-tooltip="Employee Management" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Employee Management</span>
        </a>
        @endif
    </nav>
    
    <div class="p-4 mt-auto border-t border-blue-800 sidebar-footer">
        <p class="text-xs text-center text-gray-400">
            Bestlink College of the Philippines<br>
            Copyright © 2025 Ascendens Asia. All right reserved
        </p>
    </div>
</aside>
