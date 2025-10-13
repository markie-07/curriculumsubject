<header class="flex justify-between items-center p-2 sm:p-4 bg-white text-gray-800 shadow-md border-b-2 border-gray-200">
    <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-600 hover:text-gray-800 focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
    </div>
    <div class="flex items-center space-x-2 sm:space-x-4 lg:space-x-6">
        <span id="datetime-span" class="text-xs sm:text-sm hidden sm:block"></span>
        
        <!-- Notifications -->
        <div class="relative">
            <button id="notifications-button" class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200 group relative">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
            </button>
            <div id="notifications-dropdown" class="notifications-dropdown-minimal">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        <button id="mark-all-read" class="text-xs text-blue-600 hover:text-blue-800">Mark all as read</button>
                    </div>
                </div>
                <div id="notifications-list" class="max-h-80 overflow-y-auto">
                    <div class="p-4 text-center text-gray-500 text-sm">
                        Loading notifications...
                    </div>
                </div>
                <div class="p-3 border-t border-gray-200 text-center">
                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800">View all notifications</a>
                </div>
            </div>
        </div>
        
        <!-- User Info -->
        <div class="flex items-center space-x-2 sm:space-x-3">
            <div class="text-right hidden md:block">
                <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->username }}</p>
            </div>
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                <span class="text-sm font-semibold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
        </div>

        <!-- Settings Dropdown -->
        <div class="relative">
            <button id="settings-button" class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200 group">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                </svg>
            </button>
            <div id="dropdown-menu" class="dropdown-menu-minimal">
                <div class="dropdown-item">
                    <a href="{{ route('profile.show') }}" class="dropdown-link">
                        <div class="dropdown-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span>Profile</span>
                    </a>
                </div>
                <div class="dropdown-divider"></div>
                <div class="dropdown-item">
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" id="logout-button" class="dropdown-link logout-btn" onclick="console.log('Logout button clicked directly')">
                            <div class="dropdown-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <span>Sign out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>