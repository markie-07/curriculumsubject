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
        #sidebar.collapsed .profile-avatar {
            width: 2.5rem;
            height: 2.5rem;
        }
        #sidebar.collapsed .nav-link {
            justify-content: center;
        }
        #sidebar.collapsed .nav-link svg {
            margin-right: 0;
        }
        #sidebar.collapsed .sidebar-footer p {
            display: none;
        }
        /* Dropdown menu styles */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
        .dropdown-menu a {
            display: block;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .dropdown-menu a:hover {
            background: #3b82f6;
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
            settingsButton.addEventListener('click', () => {
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', (event) => {
                if (!settingsButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
