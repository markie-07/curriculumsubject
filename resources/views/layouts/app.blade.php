<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Curriculum & Subject Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Line Awesome Icons -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     {{-- ADD THESE TWO SCRIPT TAGS FOR PDF EXPORT --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
</head>
    <style>
        /* Use the Inter font family */
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Global zoom-friendly responsive styles */
        * {
            box-sizing: border-box;
        }
        
        html {
            font-size: 16px; /* Base font size */
        }
        
        /* Responsive form elements */
        input, select, textarea, button {
            font-size: clamp(0.875rem, 1.5vw, 1rem);
            line-height: 1.5;
        }
        
        /* Responsive containers */
        .container {
            width: 100%;
            max-width: 100%;
            padding-left: clamp(0.5rem, 2vw, 1rem);
            padding-right: clamp(0.5rem, 2vw, 1rem);
        }
        
        /* Responsive text sizing */
        h1 { font-size: clamp(1.5rem, 3vw, 2rem); }
        h2 { font-size: clamp(1.25rem, 2.5vw, 1.75rem); }
        h3 { font-size: clamp(1.125rem, 2vw, 1.5rem); }
        h4 { font-size: clamp(1rem, 1.8vw, 1.25rem); }
        h5 { font-size: clamp(0.875rem, 1.6vw, 1.125rem); }
        h6 { font-size: clamp(0.75rem, 1.4vw, 1rem); }
        
        /* Responsive spacing */
        .responsive-padding {
            padding: clamp(0.5rem, 2vw, 1rem);
        }
        
        .responsive-margin {
            margin: clamp(0.5rem, 2vw, 1rem);
        }
        
        /* Form synchronization styles */
        .form-container {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .form-grid {
            display: grid;
            gap: clamp(0.75rem, 2vw, 1.5rem);
            grid-template-columns: repeat(auto-fit, minmax(min(250px, 100%), 1fr));
        }
        
        /* Button responsive sizing */
        .btn {
            padding: clamp(0.5rem, 1.5vw, 0.75rem) clamp(1rem, 3vw, 1.5rem);
            font-size: clamp(0.875rem, 1.5vw, 1rem);
            border-radius: clamp(0.25rem, 0.5vw, 0.5rem);
        }
        
        /* Table responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-responsive table {
            min-width: 600px;
            font-size: clamp(0.75rem, 1.3vw, 0.875rem);
        }
        
        /* Modal responsive */
        .modal-content {
            max-width: min(90vw, 600px);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Method 2: Pure CSS Container Queries Zoom Synchronization */
        
        /* Enable container queries on main containers */
        .dashboard-content,
        .form-container,
        .stats-grid,
        body {
            container-type: inline-size;
        }
        
        /* Base responsive typography using container queries */
        @container (min-width: 320px) {
            .container-text {
                font-size: clamp(0.875rem, 2cqw, 1rem);
                line-height: clamp(1.3, 2cqw, 1.6);
            }
            
            .container-heading {
                font-size: clamp(1.125rem, 3cqw, 1.5rem);
                line-height: clamp(1.2, 2.5cqw, 1.4);
            }
            
            .container-subheading {
                font-size: clamp(0.75rem, 1.8cqw, 0.875rem);
                line-height: clamp(1.3, 2cqw, 1.5);
            }
        }
        
        /* Form elements container queries */
        @container (min-width: 300px) {
            input, select, textarea {
                font-size: clamp(0.875rem, 2.5cqw, 1rem);
                padding: clamp(0.5rem, 2cqw, 0.75rem);
                border-radius: clamp(0.25rem, 1cqw, 0.5rem);
            }
            
            button, .btn {
                font-size: clamp(0.875rem, 2.2cqw, 1rem);
                padding: clamp(0.5rem, 2cqw, 0.75rem) clamp(1rem, 3cqw, 1.5rem);
                border-radius: clamp(0.375rem, 1.2cqw, 0.5rem);
            }
        }
        
        /* Enhanced Grid system with container queries for stats-grid synchronization */
        @container (min-width: 300px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(clamp(140px, 22cqw, 180px), 1fr));
                gap: clamp(0.5rem, 2cqw, 1rem);
                padding: clamp(0.25rem, 1cqw, 0.5rem);
            }
        }
        
        @container (min-width: 400px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(clamp(150px, 25cqw, 200px), 1fr));
                gap: clamp(0.75rem, 2.5cqw, 1.5rem);
                padding: clamp(0.5rem, 1.5cqw, 0.75rem);
            }
            
            .stat-card {
                padding: clamp(0.75rem, 2.5cqw, 1.25rem);
                min-height: clamp(100px, 20cqw, 140px);
            }
            
            .stat-icon-container {
                width: clamp(3rem, 6cqw, 4rem);
                height: clamp(3rem, 6cqw, 4rem);
            }
            
            .stat-icon {
                width: clamp(1.25rem, 2.5cqw, 2rem);
                height: clamp(1.25rem, 2.5cqw, 2rem);
            }
        }
        
        @container (min-width: 600px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(clamp(160px, 20cqw, 220px), 1fr));
                gap: clamp(1rem, 3cqw, 1.75rem);
                padding: clamp(0.75rem, 2cqw, 1rem);
            }
        }
        
        /* Dashboard header container queries */
        @container (min-width: 350px) {
            .dashboard-header {
                padding: clamp(0.75rem, 2cqw, 1.25rem);
                border-radius: clamp(0.5rem, 1.2cqw, 0.75rem);
            }
            
            .dashboard-title {
                font-size: clamp(1.125rem, 3.5cqw, 1.75rem);
            }
            
            .dashboard-subtitle {
                font-size: clamp(0.75rem, 2cqw, 1rem);
            }
        }
        
        /* Statistics numbers and labels */
        @container (min-width: 200px) {
            .stat-number {
                font-size: clamp(1.25rem, 4cqw, 2rem);
                font-weight: 700;
                line-height: 1.1;
            }
            
            .stat-label {
                font-size: clamp(0.75rem, 2.2cqw, 0.95rem);
                line-height: 1.3;
            }
        }
        
        /* Container queries for larger screen sizes and high zoom levels */
        @container (min-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(clamp(180px, 18cqw, 240px), 1fr));
                gap: clamp(1.25rem, 3.5cqw, 2rem);
                padding: clamp(1rem, 2.5cqw, 1.5rem);
            }
        }
        
        @container (min-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(clamp(200px, 16cqw, 260px), 1fr));
                gap: clamp(1.5rem, 4cqw, 2.25rem);
                padding: clamp(1.25rem, 3cqw, 1.75rem);
                max-width: clamp(1200px, 90cqw, 1400px);
                margin: 0 auto;
            }
        }
        
        @container (min-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(6, 1fr);
                gap: clamp(1.75rem, 4.5cqw, 2.5rem);
                padding: clamp(1.5rem, 3.5cqw, 2rem);
                max-width: clamp(1300px, 85cqw, 1500px);
            }
        }
        
        /* Tables with container queries */
        @container (min-width: 500px) {
            table {
                font-size: clamp(0.75rem, 1.8cqw, 0.9rem);
            }
            
            th, td {
                padding: clamp(0.5rem, 1.5cqw, 0.75rem);
            }
        }
        
        /* Modal container queries */
        @container (min-width: 400px) {
            .modal-content {
                max-width: clamp(300px, 80cqw, 600px);
                padding: clamp(1rem, 3cqw, 2rem);
                border-radius: clamp(0.5rem, 1.5cqw, 1rem);
            }
        }
        
        /* Line Awesome icon scaling with container queries */
        @container (min-width: 200px) {
            .stat-icon {
                font-size: clamp(1.5rem, 3.5cqw, 2.25rem) !important;
            }
            
            .stat-icon-container {
                width: clamp(3.5rem, 7cqw, 4.5rem);
                height: clamp(3.5rem, 7cqw, 4.5rem);
                border-radius: clamp(0.75rem, 1.5cqw, 1rem);
            }
        }
        
        @container (min-width: 400px) {
            .stat-icon {
                font-size: clamp(1.75rem, 4cqw, 2.5rem) !important;
            }
            
            .stat-icon-container {
                width: clamp(4rem, 8cqw, 5rem);
                height: clamp(4rem, 8cqw, 5rem);
                border-radius: clamp(1rem, 2cqw, 1.25rem);
            }
        }
        
        /* Ensure Line Awesome icons inherit container sizing and perfect centering */
        .las {
            line-height: 1;
            vertical-align: baseline;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Enhanced icon container styling */
        .stat-icon-container {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover .stat-icon-container {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Smooth transitions for all container query changes */
        * {
            transition: font-size 0.2s ease, padding 0.2s ease, gap 0.2s ease;
        }
        /* Custom scrollbar for a better look */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #1e3a8a;
        }
        ::-webkit-scrollbar-thumb {
            background: #60a5fa;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }
        /* Styles for the collapsed sidebar state */
        #sidebar.collapsed {
            width: 5rem;
        }
        #sidebar.collapsed .sidebar-title,
        #sidebar.collapsed .profile-text,
        #sidebar.collapsed .nav-text {
            display: none;
        }
        #sidebar.collapsed .profile-section {
            padding: 1rem 0.5rem;
            margin: 0.5rem 0;
        }
        #sidebar.collapsed .profile-avatar {
            width: 2.5rem;
            height: 2.5rem;
            margin-bottom: 0;
            font-size: 0.75rem;
        }
        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }
        #sidebar.collapsed .nav-link .w-8 {
            margin-right: 0;
        }
        #sidebar.collapsed .sidebar-footer p {
            display: none;
        }

        /* Active navigation link styles */
        .nav-link.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(99, 102, 241, 0.15));
            border-left: 3px solid #3b82f6;
        }
        .nav-link.active .w-8 {
            background: rgba(59, 130, 246, 0.2);
        }
        .nav-link.active svg {
            color: #60a5fa !important;
        }
        .nav-link.active span {
            color: #ffffff !important;
            font-weight: 600;
        }

        /* Smooth scrollbar for sidebar */
        #sidebar nav::-webkit-scrollbar {
            width: 4px;
        }
        #sidebar nav::-webkit-scrollbar-track {
            background: rgba(71, 85, 105, 0.1);
        }
        #sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.3);
            border-radius: 2px;
        }
        #sidebar nav::-webkit-scrollbar-thumb:hover {
            background: rgba(71, 85, 105, 0.5);
        }

        /* Section headers in collapsed state */
        #sidebar.collapsed h3 {
            display: none;
        }

        /* Locked item styles for employees */
        .locked-item {
            position: relative;
        }
        
        .locked-item:hover::after {
            content: "Admin Access Required";
            position: absolute;
            top: 50%;
            right: -140px;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            pointer-events: none;
        }
        
        .locked-item:hover::before {
            content: "";
            position: absolute;
            top: 50%;
            right: -8px;
            transform: translateY(-50%);
            border: 4px solid transparent;
            border-right-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            pointer-events: none;
        }
        #sidebar.collapsed .mb-6 {
            margin-bottom: 0.5rem;
        }
        /* Minimalist Dropdown menu styles */
        .dropdown-menu-minimal {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            z-index: 50;
            min-width: 180px;
            padding: 8px;
            opacity: 0;
            transform: translateY(-8px);
            transition: all 0.2s ease-out;
        }
        
        .dropdown-menu-minimal.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .dropdown-item {
            margin-bottom: 2px;
        }
        
        .dropdown-item:last-child {
            margin-bottom: 0;
        }
        
        .dropdown-link {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.15s ease;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }
        
        .dropdown-link:hover {
            background: #f8fafc;
            color: #334155;
        }
        
        .dropdown-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #f1f5f9;
            border-radius: 6px;
            margin-right: 12px;
            transition: all 0.15s ease;
        }
        
        .dropdown-link:hover .dropdown-icon {
            background: #e2e8f0;
        }
        
        .logout-btn {
            color: #ef4444;
        }
        
        .logout-btn:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        
        .logout-btn .dropdown-icon {
            background: #fee2e2;
        }
        
        .logout-btn:hover .dropdown-icon {
            background: #fecaca;
        }
        
        .dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 6px 0;
        }

        /* Notifications Dropdown Styles */
        .notifications-dropdown-minimal {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            z-index: 50;
            width: 320px;
            opacity: 0;
            transform: translateY(-8px);
            transition: all 0.2s ease-out;
        }
        
        .notifications-dropdown-minimal.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f8fafc;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .notification-item:hover {
            background: #f8fafc;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item.unread {
            background: #f0f9ff;
            border-left: 3px solid #3b82f6;
        }

        .notification-title {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .notification-message {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .notification-time {
            font-size: 11px;
            color: #9ca3af;
        }

        .notification-type-success {
            border-left-color: #10b981;
        }

        .notification-type-warning {
            border-left-color: #f59e0b;
        }

        .notification-type-error {
            border-left-color: #ef4444;
        }

        .notification-type-info {
            border-left-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-200">
        @include('partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('partials.header')
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            sidebarToggle.addEventListener('click', () => {
                if (window.innerWidth < 640) {
                    sidebar.classList.toggle('-translate-x-full');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });
            const dateTimeSpan = document.getElementById('datetime-span');
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                };
                const dateTimeString = now.toLocaleString('en-US', options).replace(/,(\s\d+:\d+)/, '$1');
                dateTimeSpan.textContent = dateTimeString;
            }
            updateDateTime();
            setInterval(updateDateTime, 1000);
            const settingsButton = document.getElementById('settings-button');
            const dropdownMenu = document.getElementById('dropdown-menu');
            
            settingsButton.addEventListener('click', (e) => {
                e.stopPropagation();
                if (dropdownMenu.style.display === 'block') {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });
            
            function openDropdown() {
                dropdownMenu.style.display = 'block';
                // Force reflow for animation
                dropdownMenu.offsetHeight;
                dropdownMenu.classList.add('show');
            }
            
            function closeDropdown() {
                dropdownMenu.classList.remove('show');
                setTimeout(() => {
                    dropdownMenu.style.display = 'none';
                }, 200); // Match transition duration
            }
            
            document.addEventListener('click', (event) => {
                if (!settingsButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    closeDropdown();
                }
            });

            // Notifications functionality
            const notificationsButton = document.getElementById('notifications-button');
            const notificationsDropdown = document.getElementById('notifications-dropdown');
            const notificationBadge = document.getElementById('notification-badge');
            const notificationsList = document.getElementById('notifications-list');
            const markAllReadBtn = document.getElementById('mark-all-read');

            // Toggle notifications dropdown
            notificationsButton.addEventListener('click', (e) => {
                e.stopPropagation();
                if (notificationsDropdown.style.display === 'block') {
                    closeNotifications();
                } else {
                    openNotifications();
                }
            });

            function openNotifications() {
                notificationsDropdown.style.display = 'block';
                notificationsDropdown.offsetHeight;
                notificationsDropdown.classList.add('show');
                loadNotifications();
            }

            function closeNotifications() {
                notificationsDropdown.classList.remove('show');
                setTimeout(() => {
                    notificationsDropdown.style.display = 'none';
                }, 200);
            }

            // Load notifications
            function loadNotifications() {
                fetch('/notifications')
                    .then(response => response.json())
                    .then(data => {
                        displayNotifications(data.notifications);
                        updateNotificationBadge(data.unread_count);
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        notificationsList.innerHTML = '<div class="p-4 text-center text-red-500 text-sm">Error loading notifications</div>';
                    });
            }

            // Display notifications
            function displayNotifications(notifications) {
                if (notifications.length === 0) {
                    notificationsList.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">No notifications</div>';
                    return;
                }

                const notificationsHtml = notifications.map(notification => {
                    const unreadClass = notification.read_at ? '' : 'unread';
                    const typeClass = `notification-type-${notification.type}`;
                    
                    return `
                        <div class="notification-item ${unreadClass} ${typeClass}" data-id="${notification.id}">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${formatTimeAgo(notification.created_at)}</div>
                        </div>
                    `;
                }).join('');

                notificationsList.innerHTML = notificationsHtml;

                // Add click handlers to notification items
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const notificationId = item.dataset.id;
                        markAsRead(notificationId);
                    });
                });
            }

            // Update notification badge
            function updateNotificationBadge(count) {
                if (count > 0) {
                    notificationBadge.textContent = count > 99 ? '99+' : count;
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            }

            // Mark notification as read
            function markAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(() => {
                    loadNotifications();
                })
                .catch(error => console.error('Error marking notification as read:', error));
            }

            // Mark all as read
            markAllReadBtn.addEventListener('click', () => {
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(() => {
                    loadNotifications();
                })
                .catch(error => console.error('Error marking all notifications as read:', error));
            });

            // Format time ago
            function formatTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) return 'Just now';
                if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
                if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
                return `${Math.floor(diffInSeconds / 86400)}d ago`;
            }

            // Close notifications when clicking outside
            document.addEventListener('click', (event) => {
                if (!notificationsButton.contains(event.target) && !notificationsDropdown.contains(event.target)) {
                    closeNotifications();
                }
            });

            // Load notification count on page load
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    updateNotificationBadge(data.count);
                })
                .catch(error => console.error('Error loading notification count:', error));

            // Poll for new notifications every 30 seconds
            setInterval(() => {
                fetch('/notifications/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        updateNotificationBadge(data.count);
                    })
                    .catch(error => console.error('Error polling notifications:', error));
            }, 30000);
        });

        // Prevent back navigation for authenticated pages
        function preventBackAfterLogout() {
            // Check if user is authenticated by checking for auth token or session
            if (document.querySelector('meta[name="csrf-token"]')) {
                // Add cache control meta tags
                const cacheControlMeta = document.createElement('meta');
                cacheControlMeta.setAttribute('http-equiv', 'Cache-Control');
                cacheControlMeta.setAttribute('content', 'no-cache, no-store, must-revalidate');
                document.head.appendChild(cacheControlMeta);

                const pragmaMeta = document.createElement('meta');
                pragmaMeta.setAttribute('http-equiv', 'Pragma');
                pragmaMeta.setAttribute('content', 'no-cache');
                document.head.appendChild(pragmaMeta);

                const expiresMeta = document.createElement('meta');
                expiresMeta.setAttribute('http-equiv', 'Expires');
                expiresMeta.setAttribute('content', '0');
                document.head.appendChild(expiresMeta);

                // Handle page visibility change (when user comes back from another tab/window)
                document.addEventListener('visibilitychange', function() {
                    if (document.visibilityState === 'visible') {
                        // Check if user is still authenticated
                        fetch('/csrf-token', {
                            method: 'GET',
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) {
                                // User is no longer authenticated, redirect to login
                                window.location.href = '/login';
                            }
                        })
                        .catch(() => {
                            // Network error or session expired, redirect to login
                            window.location.href = '/login';
                        });
                    }
                });

                // Handle browser back/forward navigation
                window.addEventListener('pageshow', function(event) {
                    // If page is loaded from cache (back button), check authentication
                    if (event.persisted) {
                        fetch('/csrf-token', {
                            method: 'GET',
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) {
                                window.location.href = '/login';
                            }
                        })
                        .catch(() => {
                            window.location.href = '/login';
                        });
                    }
                });
            }
        }

        // Initialize back navigation prevention
        document.addEventListener('DOMContentLoaded', preventBackAfterLogout);
    </script>
</body>
</html>
