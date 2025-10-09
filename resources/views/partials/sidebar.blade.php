<aside id="sidebar" class="w-72 bg-[#1e3a8a] text-gray-200 flex flex-col transition-all duration-300 ease-in-out sm:translate-x-0 -translate-x-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between p-4 border-b border-blue-800">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('/images/SMSIII LOGO.png') }}" alt="SMS3 Logo" class="object-contain" style="width: 150px; height: 50px; max-width:none; max-height:none;">
            </div>
            <h1 class="text-sm font-semibold whitespace-nowrap sidebar-title">Curriculum & Subject<br>Management System</h1>
        </div>
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
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Dashboard</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Dashboard</span>
        </a>
        @endif

        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- Curriculum Builder -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Curriculum Builder</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('curriculum_builder') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Curriculum Builder</span>
        </a>
        @endif

        <!-- Subject Mapping -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Subject Mapping</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('subject_mapping') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Subject Mapping</span>
        </a>
        @endif

        <!-- Pre-requisite -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Pre-requisite</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('pre_requisite') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path></svg>
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
        <a href="{{ route('compliance.validator') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Compliance Validator</span>
        </a>
        @endif

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- Course Builder -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Course Builder</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('course_builder') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Course Builder</span>
        </a>
        @endif

        <!-- Grade Weighting Setup -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Grade Weighting Setup</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('grade_setup') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Grade Weighting Setup</span>
        </a>
        @endif

        <!-- Subject Equivalency Tool -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Subject Equivalency Tool</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('equivalency_tool') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Subject Equivalency Tool</span>
        </a>
        @endif

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- Mapping History -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Mapping History</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('subject_mapping_history') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Mapping History</span>
        </a>
        @endif

        <!-- Subject Offering History (Optional) -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Subject Offering History</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('subject_history') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.494h18"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Subject Offering History</span>
        </a>
        @endif

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- Curriculum Export Tool - Accessible to ALL roles (Activity logging for employees only) -->
        <a href="{{ route('curriculum_export_tool') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <span class="nav-text group-hover:text-white transition-colors duration-300">Curriculum Export Tool</span>
        </a>

        <!-- Separator Line -->
        <div class="border-t border-blue-600/30 my-3"></div>

        <!-- Employee Management -->
        @if($isEmployee)
        <div class="locked-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gray-600/20 border border-gray-500/30 cursor-not-allowed opacity-60 relative">
            <div class="w-8 h-8 bg-gray-600/30 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
            </div>
            <span class="nav-text text-gray-400">Employee Management</span>
            <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        @else
        <a href="{{ route('employees.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-blue-800/50 hover:to-blue-700/50 transition-all duration-300 nav-link border border-transparent hover:border-blue-600/30 hover:shadow-lg hover:shadow-blue-900/20">
            <div class="w-8 h-8 bg-blue-800/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600/40 transition-all duration-300 group-hover:scale-110">
                <svg class="w-4 h-4 text-blue-300 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
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
