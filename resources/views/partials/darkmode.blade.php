<style>
    /* Dark Mode CSS Variables */
    :root {
        /* Light mode colors */
        --bg-primary: #f3f4f6;
        --bg-secondary: #ffffff;
        --bg-tertiary: #f9fafb;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-tertiary: #9ca3af;
        --border-primary: #e5e7eb;
        --border-secondary: #d1d5db;
        --sidebar-bg: #1e3a8a;
        --sidebar-text: #e5e7eb;
        --sidebar-hover: rgba(59, 130, 246, 0.1);
        --card-bg: #ffffff;
        --card-shadow: rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] {
        /* Dark mode colors */
        --bg-primary: #111827;
        --bg-secondary: #1f2937;
        --bg-tertiary: #374151;
        --text-primary: #f9fafb;
        --text-secondary: #d1d5db;
        --text-tertiary: #9ca3af;
        --border-primary: #374151;
        --border-secondary: #4b5563;
        --sidebar-bg: #0f172a;
        --sidebar-text: #e2e8f0;
        --sidebar-hover: rgba(59, 130, 246, 0.2);
        --card-bg: #1f2937;
        --card-shadow: rgba(0, 0, 0, 0.3);
    }

    /* Apply CSS variables to elements */
    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Dark mode sidebar styles */
    [data-theme="dark"] #sidebar {
        background-color: var(--sidebar-bg);
        border-color: #1e293b;
    }

    [data-theme="dark"] #sidebar .border-blue-800 {
        border-color: #1e293b !important;
    }

    [data-theme="dark"] #sidebar .border-blue-600\/30 {
        border-color: rgba(59, 130, 246, 0.3) !important;
    }

    [data-theme="dark"] #sidebar .text-gray-200 {
        color: var(--sidebar-text) !important;
    }

    [data-theme="dark"] #sidebar .text-gray-400 {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] #sidebar .text-blue-200 {
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] #sidebar .bg-blue-800\/20 {
        background-color: transparent !important;
    }

    [data-theme="dark"] #sidebar .hover\:bg-blue-800\/30:hover {
        background-color: rgba(59, 130, 246, 0.1) !important;
    }

    /* Remove black backgrounds from sidebar icon containers */
    [data-theme="dark"] #sidebar .bg-blue-800\/30 {
        background-color: transparent !important;
    }

    [data-theme="dark"] #sidebar .group-hover\:bg-blue-600\/40:hover {
        background-color: rgba(59, 130, 246, 0.2) !important;
    }

    /* Fix profile section background */
    [data-theme="dark"] #sidebar .bg-blue-800\/20 {
        background-color: transparent !important;
    }

    /* Ensure nav link icon backgrounds are transparent */
    [data-theme="dark"] #sidebar .nav-link .w-8 {
        background-color: transparent !important;
    }

    /* Force all sidebar backgrounds to be transparent */
    [data-theme="dark"] #sidebar .w-8.h-8 {
        background-color: transparent !important;
    }

    [data-theme="dark"] #sidebar .rounded-lg {
        background-color: transparent !important;
    }

    [data-theme="dark"] #sidebar div[class*="bg-blue"] {
        background-color: transparent !important;
    }

    [data-theme="dark"] #sidebar div[class*="bg-gray"] {
        background-color: transparent !important;
    }

    /* Specifically target all possible background variants */
    [data-theme="dark"] #sidebar .bg-blue-600\/30,
    [data-theme="dark"] #sidebar .bg-blue-700\/30,
    [data-theme="dark"] #sidebar .bg-blue-500\/30,
    [data-theme="dark"] #sidebar .bg-gray-600\/30,
    [data-theme="dark"] #sidebar .bg-gray-700\/30 {
        background-color: transparent !important;
    }

    /* Remove any remaining dark backgrounds */
    [data-theme="dark"] #sidebar * {
        background-color: inherit !important;
    }

    /* Override for the main sidebar background */
    [data-theme="dark"] #sidebar {
        background-color: var(--sidebar-bg) !important;
    }

    /* Force transparent backgrounds on all icon containers and nav elements */
    [data-theme="dark"] #sidebar .nav-link div,
    [data-theme="dark"] #sidebar .group div,
    [data-theme="dark"] #sidebar a div,
    [data-theme="dark"] #sidebar .flex div {
        background-color: transparent !important;
    }

    /* Specifically target the profile section */
    [data-theme="dark"] #sidebar .profile-section div {
        background-color: transparent !important;
    }

    /* Remove backgrounds from all child elements except the main sidebar */
    [data-theme="dark"] #sidebar > * * {
        background-color: transparent !important;
    }

    /* Keep only the main sidebar background */
    [data-theme="dark"] #sidebar > div,
    [data-theme="dark"] #sidebar > nav,
    [data-theme="dark"] #sidebar > aside {
        background-color: transparent !important;
    }

    /* Dark mode main content area */
    [data-theme="dark"] main {
        background-color: var(--bg-primary);
    }

    [data-theme="dark"] .bg-gray-100 {
        background-color: var(--bg-primary) !important;
    }

    [data-theme="dark"] .bg-gray-200 {
        background-color: var(--bg-secondary) !important;
    }

    [data-theme="dark"] .bg-white {
        background-color: var(--card-bg) !important;
    }

    [data-theme="dark"] .text-gray-900 {
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] .text-gray-700 {
        color: var(--text-secondary) !important;
    }

    [data-theme="dark"] .text-gray-600 {
        color: var(--text-secondary) !important;
    }

    [data-theme="dark"] .text-gray-500 {
        color: var(--text-tertiary) !important;
    }

    [data-theme="dark"] .border-gray-200 {
        border-color: var(--border-primary) !important;
    }

    [data-theme="dark"] .border-gray-300 {
        border-color: var(--border-secondary) !important;
    }

    /* Dark mode cards and components */
    [data-theme="dark"] .shadow-lg {
        box-shadow: 0 10px 15px -3px var(--card-shadow), 0 4px 6px -2px var(--card-shadow) !important;
    }

    [data-theme="dark"] .shadow-md {
        box-shadow: 0 4px 6px -1px var(--card-shadow), 0 2px 4px -1px var(--card-shadow) !important;
    }

    [data-theme="dark"] .shadow {
        box-shadow: 0 1px 3px 0 var(--card-shadow), 0 1px 2px 0 var(--card-shadow) !important;
    }

    /* Dark mode forms */
    [data-theme="dark"] input,
    [data-theme="dark"] select,
    [data-theme="dark"] textarea {
        background-color: var(--bg-tertiary);
        border-color: var(--border-primary);
        color: var(--text-primary);
    }

    [data-theme="dark"] input:focus,
    [data-theme="dark"] select:focus,
    [data-theme="dark"] textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Dark mode buttons */
    [data-theme="dark"] .bg-blue-600 {
        background-color: #2563eb !important;
    }

    [data-theme="dark"] .hover\:bg-blue-700:hover {
        background-color: #1d4ed8 !important;
    }

    [data-theme="dark"] .bg-red-600 {
        background-color: #dc2626 !important;
    }

    [data-theme="dark"] .hover\:bg-red-700:hover {
        background-color: #b91c1c !important;
    }

    [data-theme="dark"] .bg-green-600 {
        background-color: #16a34a !important;
    }

    [data-theme="dark"] .hover\:bg-green-700:hover {
        background-color: #15803d !important;
    }

    /* Dark mode status badges - more specific selectors */
    [data-theme="dark"] span.bg-green-100.text-green-800 {
        background-color:rgba(0, 252, 21, 0.49) !important;
        color:rgb(255, 255, 255) !important;
    }

    /* Make SVG icons in active status badge black */
    [data-theme="dark"] span.bg-green-100.text-green-800 svg {
        color: #000000 !important;
    }

    /* Alternative selectors for broader coverage */
    [data-theme="dark"] .bg-green-100.text-green-800 {
        background-color: #ffffff !important;
        color: #000000 !important;
    }

    [data-theme="dark"] .bg-green-100.text-green-800 svg {
        color: #000000 !important;
    }

    [data-theme="dark"] span.bg-gray-100 {
        background-color: rgba(156, 163, 175, 0.2) !important;
    }

    [data-theme="dark"] span.text-gray-800 {
        color: #d1d5db !important;
    }

    /* Dark mode tables */
    [data-theme="dark"] table {
        background-color: var(--card-bg);
    }

    [data-theme="dark"] th {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }

    [data-theme="dark"] td {
        border-color: var(--border-primary);
    }

    [data-theme="dark"] tr:nth-child(even) {
        background-color: var(--bg-tertiary);
    }

    /* Dark mode modals */
    [data-theme="dark"] .modal-content {
        background-color: var(--card-bg);
        border-color: var(--border-primary);
    }

    /* Enhanced modal styling for dark mode */
    [data-theme="dark"] .fixed.inset-0 {
        background-color: rgba(0, 0, 0, 0.7) !important;
    }

    [data-theme="dark"] .bg-gray-50 {
        background-color: var(--bg-secondary) !important;
    }

    [data-theme="dark"] .bg-gray-100 {
        background-color: var(--bg-tertiary) !important;
    }

    [data-theme="dark"] .border-gray-200 {
        border-color: var(--border-primary) !important;
    }

    [data-theme="dark"] .border-gray-300 {
        border-color: var(--border-secondary) !important;
    }

    /* Dark mode dropdown menus */
    [data-theme="dark"] .dropdown-menu-minimal {
        background-color: var(--card-bg);
        border-color: var(--border-primary);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .dropdown-link {
        color: var(--text-secondary);
    }

    [data-theme="dark"] .dropdown-link:hover {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }

    [data-theme="dark"] .dropdown-icon {
        background-color: var(--bg-tertiary);
    }

    [data-theme="dark"] .dropdown-link:hover .dropdown-icon {
        background-color: var(--border-secondary);
    }

    /* Dark mode notifications */
    [data-theme="dark"] .notifications-dropdown-minimal {
        background-color: var(--card-bg);
        border-color: var(--border-primary);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .notification-item {
        border-color: var(--border-primary);
    }

    [data-theme="dark"] .notification-item:hover {
        background-color: var(--bg-tertiary);
    }

    [data-theme="dark"] .notification-title {
        color: var(--text-primary);
    }

    [data-theme="dark"] .notification-message {
        color: var(--text-secondary);
    }

    [data-theme="dark"] .notification-time {
        color: var(--text-tertiary);
    }

    /* Dark mode toggle animation */
    .dark-mode-transition {
        transition: all 0.3s ease;
    }

    /* Enhanced Dark Mode Hover Effects */
    
    /* Enhanced button hover effects in dark mode */
    [data-theme="dark"] .bg-blue-600:hover {
        background-color: #1d4ed8 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
    }

    [data-theme="dark"] .bg-red-600:hover {
        background-color: #b91c1c !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4) !important;
    }

    [data-theme="dark"] .bg-green-600:hover {
        background-color: #15803d !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.4) !important;
    }

    [data-theme="dark"] .bg-yellow-600:hover {
        background-color: #ca8a04 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4) !important;
    }

    [data-theme="dark"] .bg-purple-600:hover {
        background-color: #7c3aed !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(147, 51, 234, 0.4) !important;
    }

    [data-theme="dark"] .bg-indigo-600:hover {
        background-color: #4338ca !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4) !important;
    }

    /* Enhanced navigation hover effects */
    [data-theme="dark"] .nav-link:hover {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(99, 102, 241, 0.25)) !important;
        transform: translateX(4px);
        border-left: 3px solid #60a5fa;
    }

    [data-theme="dark"] .nav-link:hover .w-8 {
        background: rgba(59, 130, 246, 0.3) !important;
        transform: scale(1.1);
    }

    [data-theme="dark"] .nav-link:hover svg {
        color: #93c5fd !important;
        transform: scale(1.1);
    }

    [data-theme="dark"] .nav-link:hover span {
        color: #f1f5f9 !important;
        font-weight: 600;
    }

    /* Enhanced card hover effects - disabled for course builder cards */
    [data-theme="dark"] .stat-card:hover {
        background-color: #374151 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4) !important;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    /* Disable hover effects and shadows for mapping grid containers in DARK MODE ONLY */
    [data-theme="dark"] .mapping-grid-container,
    [data-theme="dark"] .mapping-grid-container:hover {
        transform: none !important;
        box-shadow: none !important;
        border-color: var(--border-primary) !important;
        background-color: var(--card-bg) !important;
    }

    /* Disable hover effects for all elements inside mapping grid containers in DARK MODE ONLY */
    [data-theme="dark"] .mapping-grid-container:hover *,
    [data-theme="dark"] .mapping-grid-container *:hover {
        transform: none !important;
        box-shadow: inherit !important;
        border-color: inherit !important;
        background-color: inherit !important;
    }

    /* Enhanced table row hover effects - disabled for mapping grids and employees table */
    [data-theme="dark"] tr:hover:not(.mapping-grid tr:hover):not(.employees-table tr:hover) {
        background-color: rgba(59, 130, 246, 0.1) !important;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    [data-theme="dark"] tr:hover:not(.mapping-grid tr:hover):not(.employees-table tr:hover) td {
        color: var(--text-primary) !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
    }

    /* Disable hover effects for employees table */
    .employees-table tr:hover {
        transform: none !important;
        background-color: inherit !important;
    }

    [data-theme="dark"] .employees-table tr:hover {
        transform: none !important;
        background-color: inherit !important;
    }

    /* Employee header gradient - sync with dark mode */
    .employee-header-gradient {
        background: linear-gradient(to right, #2563eb, #9333ea);
    }

    [data-theme="dark"] .employee-header-gradient {
        background: linear-gradient(to right, #1e293b, #374151);
    }

    /* Enhanced form element hover effects */
    [data-theme="dark"] input:hover,
    [data-theme="dark"] select:hover,
    [data-theme="dark"] textarea:hover {
        background-color: #4b5563 !important;
        border-color: #60a5fa !important;
        transform: scale(1.02);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
    }

    /* Enhanced link hover effects */
    [data-theme="dark"] a:hover {
        color: #93c5fd !important;
        text-shadow: 0 0 8px rgba(147, 197, 253, 0.5);
        transition: all 0.2s ease;
    }

    /* Enhanced dropdown hover effects */
    [data-theme="dark"] .dropdown-link:hover {
        background-color: rgba(59, 130, 246, 0.15) !important;
        color: var(--text-primary) !important;
        transform: translateX(4px);
        border-left: 3px solid #60a5fa;
    }

    [data-theme="dark"] .dropdown-link:hover .dropdown-icon {
        background-color: rgba(59, 130, 246, 0.2) !important;
        transform: scale(1.1);
    }

    /* Enhanced notification hover effects */
    [data-theme="dark"] .notification-item:hover {
        background-color: rgba(59, 130, 246, 0.1) !important;
        transform: translateX(4px);
        border-left: 4px solid #60a5fa !important;
    }

    /* Enhanced sidebar footer hover effects */
    [data-theme="dark"] .sidebar-footer:hover {
        background-color: rgba(59, 130, 246, 0.1) !important;
    }

    /* Enhanced toggle switch hover effects */
    [data-theme="dark"] input[type="checkbox"]:hover {
        transform: scale(1.1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }

    /* Enhanced icon hover effects */
    [data-theme="dark"] .las:hover,
    [data-theme="dark"] svg:hover {
        color: #93c5fd !important;
        transform: scale(1.2);
        filter: drop-shadow(0 0 8px rgba(147, 197, 253, 0.5));
        transition: all 0.2s ease;
    }

    /* Enhanced badge hover effects */
    [data-theme="dark"] .badge:hover,
    [data-theme="dark"] .bg-blue-100:hover {
        background-color: rgba(59, 130, 246, 0.2) !important;
        color: #93c5fd !important;
        transform: scale(1.05);
    }

    /* Enhanced modal hover effects */
    [data-theme="dark"] .modal-content:hover {
        border-color: rgba(59, 130, 246, 0.4) !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5) !important;
    }

    /* Enhanced search input hover effects */
    [data-theme="dark"] input[type="search"]:hover,
    [data-theme="dark"] input[type="text"]:hover {
        background-color: #4b5563 !important;
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2), 0 0 15px rgba(59, 130, 246, 0.1) !important;
    }

    /* Enhanced pagination hover effects */
    [data-theme="dark"] .pagination a:hover {
        background-color: rgba(59, 130, 246, 0.2) !important;
        color: #93c5fd !important;
        transform: scale(1.1);
        border-color: #60a5fa !important;
    }

    /* Enhanced tab hover effects */
    [data-theme="dark"] .tab:hover {
        background-color: rgba(59, 130, 246, 0.15) !important;
        color: #93c5fd !important;
        border-bottom: 2px solid #60a5fa !important;
        transform: translateY(-2px);
    }

    /* Enhanced accordion hover effects */
    [data-theme="dark"] .accordion-header:hover {
        background-color: rgba(59, 130, 246, 0.1) !important;
        color: #93c5fd !important;
        transform: translateX(4px);
    }

    /* Global hover transition for all interactive elements */
    [data-theme="dark"] button,
    [data-theme="dark"] a,
    [data-theme="dark"] input,
    [data-theme="dark"] select,
    [data-theme="dark"] textarea,
    [data-theme="dark"] .nav-link,
    [data-theme="dark"] .dropdown-link,
    [data-theme="dark"] .notification-item,
    [data-theme="dark"] tr,
    [data-theme="dark"] .stat-card {
        transition: all 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease !important;
    }

    /* Enhanced focus effects for accessibility */
    [data-theme="dark"] *:focus {
        outline: 2px solid #60a5fa !important;
        outline-offset: 2px !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3) !important;
    }

    /* Comprehensive Dark Mode Text Visibility Fixes */
    
    /* All heading elements */
    [data-theme="dark"] h1,
    [data-theme="dark"] h2,
    [data-theme="dark"] h3,
    [data-theme="dark"] h4,
    [data-theme="dark"] h5,
    [data-theme="dark"] h6 {
        color: var(--text-primary) !important;
    }

    /* All paragraph and text elements */
    [data-theme="dark"] p,
    [data-theme="dark"] span,
    [data-theme="dark"] div,
    [data-theme="dark"] label,
    [data-theme="dark"] strong,
    [data-theme="dark"] em,
    [data-theme="dark"] small {
        color: var(--text-primary) !important;
    }

    /* Specific text color classes */
    [data-theme="dark"] .text-slate-800,
    [data-theme="dark"] .text-slate-700,
    [data-theme="dark"] .text-gray-800,
    [data-theme="dark"] .text-gray-700 {
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] .text-slate-600,
    [data-theme="dark"] .text-slate-500,
    [data-theme="dark"] .text-gray-600,
    [data-theme="dark"] .text-gray-500 {
        color: var(--text-secondary) !important;
    }

    [data-theme="dark"] .text-slate-400,
    [data-theme="dark"] .text-gray-400 {
        color: var(--text-tertiary) !important;
    }

    /* Form labels and input text */
    [data-theme="dark"] label,
    [data-theme="dark"] .form-label {
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] input::placeholder,
    [data-theme="dark"] textarea::placeholder,
    [data-theme="dark"] select option {
        color: var(--text-tertiary) !important;
    }

    /* Modal text elements */
    [data-theme="dark"] .modal-content h1,
    [data-theme="dark"] .modal-content h2,
    [data-theme="dark"] .modal-content h3,
    [data-theme="dark"] .modal-content h4,
    [data-theme="dark"] .modal-content h5,
    [data-theme="dark"] .modal-content h6,
    [data-theme="dark"] .modal-content p,
    [data-theme="dark"] .modal-content span,
    [data-theme="dark"] .modal-content div,
    [data-theme="dark"] .modal-content label {
        color: var(--text-primary) !important;
    }

    /* Card text elements */
    [data-theme="dark"] .bg-white h1,
    [data-theme="dark"] .bg-white h2,
    [data-theme="dark"] .bg-white h3,
    [data-theme="dark"] .bg-white h4,
    [data-theme="dark"] .bg-white h5,
    [data-theme="dark"] .bg-white h6,
    [data-theme="dark"] .bg-white p,
    [data-theme="dark"] .bg-white span,
    [data-theme="dark"] .bg-white div,
    [data-theme="dark"] .bg-white label {
        color: var(--text-primary) !important;
    }

    /* Subject card specific text */
    [data-theme="dark"] .subject-tag .text-main,
    [data-theme="dark"] .subject-tag .text-code {
        color: var(--text-primary) !important;
    }

    /* Table text elements */
    [data-theme="dark"] table th,
    [data-theme="dark"] table td,
    [data-theme="dark"] thead th,
    [data-theme="dark"] tbody td {
        color: var(--text-primary) !important;
    }

    /* List text elements */
    [data-theme="dark"] ul li,
    [data-theme="dark"] ol li {
        color: var(--text-primary) !important;
    }

    /* Link text visibility */
    [data-theme="dark"] a {
        color: #60a5fa !important;
    }

    [data-theme="dark"] a:visited {
        color: #a78bfa !important;
    }

    /* Button text */
    [data-theme="dark"] button {
        color: inherit !important;
    }
</style>
