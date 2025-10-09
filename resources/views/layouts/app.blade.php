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
    </script>
</body>
</html>
