<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Curriculum & Subject Management System</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/sms.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/SMSIII TAB LOGO.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/sms.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Line Awesome Icons -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Flash Messages Meta Tags for Notifications --}}
    @if(session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif
    @if(session('warning'))
        <meta name="flash-warning" content="{{ session('warning') }}">
    @endif
    @if(session('info'))
        <meta name="flash-info" content="{{ session('info') }}">
    @endif
    
     {{-- ADD THESE TWO SCRIPT TAGS FOR PDF EXPORT --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
</head>
    <style>
        /* Use the Inter font family */
        body {
            font-family: 'Inter', sans-serif;
        }

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

        /* Disable hover effects and shadows for mapping grid containers */
        .mapping-grid-container,
        .mapping-grid-container:hover {
            transform: none !important;
            box-shadow: none !important;
            border-color: inherit !important;
            background-color: inherit !important;
        }

        [data-theme="dark"] .mapping-grid-container,
        [data-theme="dark"] .mapping-grid-container:hover {
            transform: none !important;
            box-shadow: none !important;
            border-color: var(--border-primary) !important;
            background-color: var(--card-bg) !important;
        }

        /* Disable hover effects for all elements inside mapping grid containers */
        .mapping-grid-container:hover *,
        .mapping-grid-container *:hover {
            transform: none !important;
            box-shadow: inherit !important;
            border-color: inherit !important;
            background-color: inherit !important;
        }

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

        /* Specific component text fixes */
        [data-theme="dark"] .curriculum-history-card h3,
        [data-theme="dark"] .curriculum-history-card p {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .curriculum-history-card .text-slate-500 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .curriculum-history-card .text-slate-400 {
            color: var(--text-tertiary) !important;
        }

        /* Badge and tag text */
        [data-theme="dark"] .badge,
        [data-theme="dark"] .unit-badge {
            color: inherit !important;
        }

        /* Loading and error text */
        [data-theme="dark"] .text-red-500,
        [data-theme="dark"] .text-red-600 {
            color: #f87171 !important;
        }

        [data-theme="dark"] .text-green-500,
        [data-theme="dark"] .text-green-600 {
            color: #4ade80 !important;
        }

        [data-theme="dark"] .text-blue-500,
        [data-theme="dark"] .text-blue-600 {
            color: #60a5fa !important;
        }

        [data-theme="dark"] .text-yellow-500,
        [data-theme="dark"] .text-yellow-600 {
            color: #fbbf24 !important;
        }

        /* Ensure all text in dark backgrounds is visible */
        [data-theme="dark"] .bg-gray-50,
        [data-theme="dark"] .bg-slate-50 {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] .bg-gray-50 *,
        [data-theme="dark"] .bg-slate-50 * {
            color: var(--text-primary) !important;
        }

        /* Override any remaining invisible text */
        [data-theme="dark"] * {
            color: inherit;
        }

        [data-theme="dark"] body *:not(svg):not(path):not(circle):not(rect):not(line):not(polyline):not(polygon) {
            color: var(--text-primary);
        }

        /* Specific overrides for elements that should maintain their color */
        [data-theme="dark"] .text-white {
            color: #ffffff !important;
        }

        [data-theme="dark"] .text-black {
            color: var(--text-primary) !important;
        }

        /* Subject mapping history specific fixes */
        [data-theme="dark"] .subject-tag-major .text-main,
        [data-theme="dark"] .subject-tag-major .text-code {
            color: #1E40AF !important;
        }

        [data-theme="dark"] .subject-tag-minor .text-main,
        [data-theme="dark"] .subject-tag-minor .text-code {
            color: #5B21B6 !important;
        }

        [data-theme="dark"] .subject-tag-elective .text-main,
        [data-theme="dark"] .subject-tag-elective .text-code {
            color: #991B1B !important;
        }

        [data-theme="dark"] .subject-tag-general .text-main,
        [data-theme="dark"] .subject-tag-general .text-code {
            color: #9A3412 !important;
        }

        /* Subject mapping page specific dark mode fixes */
        
        /* Major subjects (blue theme) */
        [data-theme="dark"] .bg-blue-100 {
            background-color: rgba(59, 130, 246, 0.2) !important;
        }
        
        [data-theme="dark"] .border-blue-200 {
            border-color: rgba(59, 130, 246, 0.4) !important;
        }
        
        [data-theme="dark"] .text-blue-800 {
            color: #93c5fd !important;
        }
        
        [data-theme="dark"] .text-blue-700 {
            color: #bfdbfe !important;
        }
        
        [data-theme="dark"] .text-blue-500 {
            color: #60a5fa !important;
        }
        
        [data-theme="dark"] .bg-blue-200 {
            background-color: rgba(59, 130, 246, 0.3) !important;
        }

        /* Minor subjects (purple theme) */
        [data-theme="dark"] .bg-purple-100 {
            background-color: rgba(147, 51, 234, 0.2) !important;
        }
        
        [data-theme="dark"] .border-purple-200 {
            border-color: rgba(147, 51, 234, 0.4) !important;
        }
        
        [data-theme="dark"] .text-purple-800 {
            color: #c4b5fd !important;
        }
        
        [data-theme="dark"] .text-purple-700 {
            color: #ddd6fe !important;
        }
        
        [data-theme="dark"] .text-purple-500 {
            color: #a78bfa !important;
        }
        
        [data-theme="dark"] .bg-purple-200 {
            background-color: rgba(147, 51, 234, 0.3) !important;
        }

        /* Elective subjects (red theme) */
        [data-theme="dark"] .bg-red-100 {
            background-color: rgba(239, 68, 68, 0.2) !important;
        }
        
        [data-theme="dark"] .border-red-200 {
            border-color: rgba(239, 68, 68, 0.4) !important;
        }
        
        [data-theme="dark"] .text-red-800 {
            color: #fca5a5 !important;
        }
        
        [data-theme="dark"] .text-red-700 {
            color: #fecaca !important;
        }
        
        [data-theme="dark"] .text-red-500 {
            color: #f87171 !important;
        }
        
        [data-theme="dark"] .bg-red-200 {
            background-color: rgba(239, 68, 68, 0.3) !important;
        }

        /* General Education subjects (orange theme) */
        [data-theme="dark"] .bg-orange-50 {
            background-color: rgba(249, 115, 22, 0.15) !important;
        }
        
        [data-theme="dark"] .border-orange-200 {
            border-color: rgba(249, 115, 22, 0.4) !important;
        }
        
        [data-theme="dark"] .text-orange-700 {
            color: #fed7aa !important;
        }
        
        [data-theme="dark"] .text-orange-500 {
            color: #fb923c !important;
        }
        
        [data-theme="dark"] .bg-orange-200 {
            background-color: rgba(249, 115, 22, 0.3) !important;
        }

        /* Subject card backgrounds in dark mode */
        [data-theme="dark"] .subject-card {
            background-color: var(--card-bg) !important;
            border-color: var(--border-primary) !important;
        }

        /* Assigned card styling */
        [data-theme="dark"] .assigned-card {
            background-color: rgba(59, 130, 246, 0.2) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        [data-theme="dark"] .assigned-card .subject-name {
            color: #93c5fd !important;
        }

        /* Icon backgrounds for dark mode */
        [data-theme="dark"] .icon-bg-major {
            background-color: rgba(59, 130, 246, 0.2) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        [data-theme="dark"] .icon-bg-minor {
            background-color: rgba(147, 51, 234, 0.2) !important;
            border-color: rgba(147, 51, 234, 0.4) !important;
        }

        [data-theme="dark"] .icon-bg-elective {
            background-color: rgba(239, 68, 68, 0.2) !important;
            border-color: rgba(239, 68, 68, 0.4) !important;
        }

        [data-theme="dark"] .icon-bg-general {
            background-color: rgba(249, 115, 22, 0.2) !important;
            border-color: rgba(249, 115, 22, 0.4) !important;
        }

        [data-theme="dark"] .icon-bg-default {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-primary) !important;
        }

        /* Ensure subject tag text is always visible */
        [data-theme="dark"] .subject-tag p,
        [data-theme="dark"] .subject-tag span {
            color: inherit !important;
        }

        /* Delete button visibility */
        [data-theme="dark"] .delete-subject-tag {
            color: #f87171 !important;
        }

        [data-theme="dark"] .delete-subject-tag:hover {
            color: #fca5a5 !important;
        }

        /* Status badge dark mode fixes */
        [data-theme="dark"] .status-badge.text-green-700 {
            color: #22c55e !important;
        }

        [data-theme="dark"] .bg-green-100 {
            background-color: rgba(34, 197, 94, 0.2) !important;
        }

        /* Additional status badge colors for consistency */
        [data-theme="dark"] .text-green-700 {
            color: #4ade80 !important;
        }

        [data-theme="dark"] .text-green-600 {
            color: #22c55e !important;
        }

        [data-theme="dark"] .text-green-800 {
            color: #86efac !important;
        }

        /* Prerequisite page subject tag dark mode fixes */
        [data-theme="dark"] .subject-tag-major {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #93c5fd !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        [data-theme="dark"] .subject-tag-minor {
            background-color: rgba(147, 51, 234, 0.2) !important;
            color: #c4b5fd !important;
            border-color: rgba(147, 51, 234, 0.4) !important;
        }

        [data-theme="dark"] .subject-tag-elective {
            background-color: rgba(239, 68, 68, 0.2) !important;
            color: #fca5a5 !important;
            border-color: rgba(239, 68, 68, 0.4) !important;
        }

        [data-theme="dark"] .subject-tag-general {
            background-color: rgba(249, 115, 22, 0.2) !important;
            color: #fed7aa !important;
            border-color: rgba(249, 115, 22, 0.4) !important;
        }

        [data-theme="dark"] .subject-tag-default {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        /* Expand bottom area height */
        main.flex-1.overflow-x-hidden.overflow-y-auto.bg-gray-100.p-8 {
            min-height: calc(100vh - 80px) !important;
            height: auto !important;
            flex-grow: 1 !important;
        }

        /* Dark mode version */
        [data-theme="dark"] main.flex-1.overflow-x-hidden.overflow-y-auto.bg-gray-100.p-8 {
            background-color: var(--bg-primary) !important;
            min-height: calc(100vh - 80px) !important;
            height: auto !important;
            flex-grow: 1 !important;
        }

        /* Alternative selector for more specific targeting */
        .flex-1.overflow-x-hidden.overflow-y-auto.bg-gray-100 {
            min-height: calc(100vh - 100px) !important;
            flex-grow: 2 !important;
        }

        [data-theme="dark"] .flex-1.overflow-x-hidden.overflow-y-auto.bg-gray-100 {
            background-color: var(--bg-primary) !important;
        }

        /* Grade Setup page dark mode fixes */
        [data-theme="dark"] .bg-white\/70 {
            background-color: rgba(31, 41, 55, 0.9) !important;
        }

        [data-theme="dark"] .bg-gray-50\/50 {
            background-color: rgba(55, 65, 81, 0.5) !important;
        }

        [data-theme="dark"] .bg-gray-100 {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] .text-gray-800 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-gray-700 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-gray-600 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .text-amber-600 {
            color: #fbbf24 !important;
        }

        [data-theme="dark"] .bg-indigo-100 {
            background-color: rgba(99, 102, 241, 0.2) !important;
        }

        [data-theme="dark"] .text-indigo-600 {
            color: #a5b4fc !important;
        }

        [data-theme="dark"] .bg-teal-100 {
            background-color: rgba(20, 184, 166, 0.2) !important;
        }

        [data-theme="dark"] .text-teal-600 {
            color: #5eead4 !important;
        }

        [data-theme="dark"] .hover\:bg-indigo-50:hover {
            background-color: rgba(99, 102, 241, 0.1) !important;
        }

        [data-theme="dark"] .hover\:text-indigo-800:hover {
            color: #c7d2fe !important;
        }

        /* Progress circle and total weight text */
        [data-theme="dark"] #total-weight {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-gray-200 {
            color: var(--text-tertiary) !important;
        }

        /* Select dropdown styling */
        [data-theme="dark"] select {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] select option {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* Curriculum Export Tool select dropdown - same as prerequisite */
        [data-theme="dark"] #curriculum-select {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] #curriculum-select option {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* General curriculum export tool select styling */
        [data-theme="dark"] .curriculum_export_tool select {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .curriculum_export_tool select option {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* Focus states for export tool select */
        [data-theme="dark"] #curriculum-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        /* Grade history cards */
        [data-theme="dark"] .grade-history-card {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .grade-history-card:hover {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] .grade-history-card p {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .grade-history-card .text-gray-500 {
            color: var(--text-secondary) !important;
        }

        /* Border styling */
        [data-theme="dark"] .border-gray-200 {
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .border-gray-200\/80 {
            border-color: rgba(75, 85, 99, 0.8) !important;
        }

        /* Component row inputs */
        [data-theme="dark"] .component-row input {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        /* Accordion content */
        [data-theme="dark"] .accordion-content {
            background-color: var(--bg-secondary) !important;
        }

        /* Labels and form text */
        [data-theme="dark"] label {
            color: var(--text-primary) !important;
        }

        /* SVG icons inherit color properly */
        [data-theme="dark"] svg {
            color: inherit !important;
        }

        /* Curriculum Export Tool page dark mode fixes */
        [data-theme="dark"] .text-gray-900 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-gray-800 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-gray-700 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .text-gray-500 {
            color: var(--text-tertiary) !important;
        }

        /* Checkbox labels and hover states */
        [data-theme="dark"] label.hover\:bg-gray-50:hover {
            background-color: #1f2937 !important;
            border-color: var(--border-secondary) !important;
        }

        [data-theme="dark"] label.cursor-pointer {
            background-color: #1f2937 !important;
            border-color: var(--border-primary) !important;
        }

        /* Make checkbox labels darker by default in export tool */
        [data-theme="dark"] .flex.items-center.p-3.border {
            background-color: #1f2937 !important;
        }

        [data-theme="dark"] .transition-colors {
            background-color: #1f2937 !important;
        }

        [data-theme="dark"] label.cursor-pointer:hover {
            background-color: #111827 !important;
            border-color: var(--border-secondary) !important;
        }

        /* Darker hover effect for export tool form elements */
        [data-theme="dark"] .hover\:bg-gray-50:hover {
            background-color: #111827 !important;
            border-bottom-color: transparent !important;
            box-shadow: none !important;
        }

        /* Remove blue bottom effects in employee management */
        [data-theme="dark"] .hover\:bg-gray-50:hover {
            border-bottom: none !important;
            border-color: var(--border-secondary) !important;
        }

        /* Ensure no blue highlights or shadows on hover */
        [data-theme="dark"] .hover\:bg-gray-50:hover * {
            border-bottom-color: transparent !important;
            box-shadow: none !important;
        }

        /* Specific darker hover for curriculum export tool checkboxes */
        [data-theme="dark"] .transition-colors:hover {
            background-color: #111827 !important;
            border-color: #6b7280 !important;
        }

        /* Ensure borders remain visible on hover for all interactive elements */
        [data-theme="dark"] .border:hover {
            border-color: var(--border-secondary) !important;
        }

        [data-theme="dark"] .border-gray-300:hover {
            border-color: var(--border-secondary) !important;
        }

        [data-theme="dark"] .rounded-lg:hover {
            border-color: var(--border-secondary) !important;
        }

        /* Specific fixes for export tool hover borders */
        [data-theme="dark"] .transition-colors:hover {
            border-color: var(--border-secondary) !important;
        }

        [data-theme="dark"] label span {
            color: var(--text-primary) !important;
        }

        /* Checkboxes */
        [data-theme="dark"] input[type="checkbox"] {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] input[type="checkbox"]:checked {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        /* Filter summary */
        [data-theme="dark"] .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }

        [data-theme="dark"] .border-blue-200 {
            border-color: rgba(59, 130, 246, 0.3) !important;
        }

        [data-theme="dark"] .text-blue-800 {
            color: #93c5fd !important;
        }

        [data-theme="dark"] .text-blue-600 {
            color: #60a5fa !important;
        }

        /* Preview section */
        [data-theme="dark"] .bg-gray-50 {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] #preview-curriculum-name {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #preview-curriculum-code {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #preview-subject-count {
            color: #60a5fa !important;
        }

        /* Loading spinner */
        [data-theme="dark"] .border-blue-600 {
            border-color: #60a5fa !important;
        }

        /* Export history cards */
        [data-theme="dark"] .export-history-card {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .export-history-card:hover {
            background-color: var(--bg-tertiary) !important;
        }

        /* Search input */
        [data-theme="dark"] input[type="search"] {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] input[type="search"]::placeholder {
            color: var(--text-tertiary) !important;
        }

        /* Export button hover effects */
        [data-theme="dark"] .bg-blue-600:hover {
            background-color: #1d4ed8 !important;
        }

        /* Gray text variations */
        [data-theme="dark"] .text-gray-400 {
            color: var(--text-tertiary) !important;
        }

        /* Subject preview items */
        [data-theme="dark"] .subject-preview-item {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .subject-preview-item:hover {
            background-color: var(--bg-secondary) !important;
        }

        /* Ensure all text in export tool is visible */
        [data-theme="dark"] #curriculum-preview h2,
        [data-theme="dark"] #curriculum-preview h3,
        [data-theme="dark"] #curriculum-preview p,
        [data-theme="dark"] #curriculum-preview span {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #curriculum-preview .text-gray-500 {
            color: var(--text-secondary) !important;
        }

        /* Employee Management hover fixes - remove blue bottom effects */
        [data-theme="dark"] .hover\:bg-gray-50 {
            transition: background-color 0.2s ease !important;
        }

        [data-theme="dark"] .hover\:bg-gray-50:hover {
            background-color: var(--bg-tertiary) !important;
            border-bottom-color: transparent !important;
            border-bottom: none !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* Remove any focus or active state blue effects */
        [data-theme="dark"] .hover\:bg-gray-50:focus,
        [data-theme="dark"] .hover\:bg-gray-50:active {
            border-bottom-color: transparent !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* Ensure table rows and list items don't show blue effects */
        [data-theme="dark"] tr.hover\:bg-gray-50:hover,
        [data-theme="dark"] li.hover\:bg-gray-50:hover,
        [data-theme="dark"] div.hover\:bg-gray-50:hover {
            border-bottom: none !important;
            border-color: var(--border-primary) !important;
            box-shadow: none !important;
        }

        /* Custom Status Confirmation Modal Dark Mode Styling */
        [data-theme="dark"] #statusConfirmModal .bg-white {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] #statusConfirmModal .text-slate-800 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #statusConfirmModal .text-slate-600 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #statusConfirmModal .text-slate-700 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #statusConfirmModal .bg-slate-100 {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] #statusConfirmModal .hover\:bg-slate-200:hover {
            background-color: var(--bg-primary) !important;
        }

        /* Success Modal Dark Mode Styling */
        [data-theme="dark"] #successModal .bg-white {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] #successModal .text-slate-800 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #successModal .text-slate-500 {
            color: var(--text-secondary) !important;
        }

        /* Add Employee Modal Dark Mode Styling */
        [data-theme="dark"] #addEmployeeModal .bg-white {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] #addEmployeeModal .text-slate-800 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #addEmployeeModal .text-slate-500 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #addEmployeeModal .text-slate-700 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #addEmployeeModal .bg-slate-100 {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] #addEmployeeModal .hover\:bg-slate-200:hover {
            background-color: var(--bg-primary) !important;
        }

        [data-theme="dark"] #addEmployeeModal input {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        /* Notification System Dark Mode Styling */
        [data-theme="dark"] .notification-toast {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] .notification-title {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .notification-message {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .notification-close {
            color: var(--text-tertiary) !important;
            background-color: transparent !important;
        }

        [data-theme="dark"] .notification-close:hover {
            color: var(--text-secondary) !important;
            background-color: var(--bg-tertiary) !important;
        }

        /* Remove black backgrounds from notification icons */
        [data-theme="dark"] .notification-icon {
            background-color: transparent !important;
        }

        [data-theme="dark"] .notification-icon.success {
            background-color: #10b981 !important;
        }

        [data-theme="dark"] .notification-icon.warning {
            background-color: #f59e0b !important;
        }

        [data-theme="dark"] .notification-icon.error {
            background-color: #ef4444 !important;
        }

        [data-theme="dark"] .notification-icon.info {
            background-color: #3b82f6 !important;
        }

        /* Header Notification Dropdown Dark Mode */
        [data-theme="dark"] .notifications-dropdown-minimal {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] #notifications-dropdown {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] #notifications-dropdown .border-gray-200 {
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] #notifications-dropdown .text-gray-900 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #notifications-dropdown .text-gray-500 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #notifications-dropdown .text-blue-600 {
            color: #60a5fa !important;
        }

        [data-theme="dark"] #notifications-dropdown .hover\:text-blue-800:hover {
            color: #93c5fd !important;
        }

        /* Fix notification list area specifically */
        [data-theme="dark"] #notifications-list {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #notifications-list .text-center {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #notifications-list .text-gray-500 {
            color: var(--text-secondary) !important;
        }

        /* Notification items in dropdown */
        [data-theme="dark"] .notification-item {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .notification-item:hover {
            background-color: var(--bg-tertiary) !important;
        }

        /* Ensure all notification dropdown content is visible */
        [data-theme="dark"] #notifications-dropdown * {
            color: inherit !important;
        }

        [data-theme="dark"] #notifications-dropdown h3 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] #notifications-dropdown p {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #notifications-dropdown a {
            color: #60a5fa !important;
        }

        [data-theme="dark"] #notifications-dropdown a:hover {
            color: #93c5fd !important;
        }

        /* Header notification button dark mode */
        [data-theme="dark"] #notifications-button {
            background-color: transparent !important;
        }

        [data-theme="dark"] #notifications-button:hover {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] #notifications-button svg {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #notifications-button:hover svg {
            color: var(--text-primary) !important;
        }

        /* Settings button dark mode */
        [data-theme="dark"] #settings-button {
            background-color: transparent !important;
        }

        [data-theme="dark"] #settings-button:hover {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] #settings-button svg {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] #settings-button:hover svg {
            color: var(--text-primary) !important;
        }

        /* Dropdown menu dark mode */
        [data-theme="dark"] .dropdown-menu-minimal {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] .dropdown-link {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .dropdown-link:hover {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] .dropdown-icon {
            background-color: transparent !important;
        }

        [data-theme="dark"] .dropdown-divider {
            border-color: var(--border-primary) !important;
        }

        /* Remove any remaining black backgrounds from header elements */
        [data-theme="dark"] header .bg-gray-100 {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] header .hover\:bg-gray-100:hover {
            background-color: var(--bg-tertiary) !important;
        }

        /* Dashboard Dark Mode Styling */
        [data-theme="dark"] .stat-card {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .stat-card:hover {
            background-color: var(--bg-tertiary) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] .stat-number {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .stat-label {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .stat-icon-container {
            background-color: transparent !important;
        }

        /* Quick Actions Dark Mode */
        [data-theme="dark"] .quick-action-card {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .quick-action-title {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .quick-action-btn {
            background: var(--bg-tertiary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .quick-action-btn:hover {
            background: var(--bg-secondary) !important;
        }

        [data-theme="dark"] .quick-action-btn h4 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .quick-action-btn p {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .quick-action-icon {
            background-color: transparent !important;
        }

        /* Dashboard Header Dark Mode */
        [data-theme="dark"] .dashboard-title {
            color: var(--text-primary) !important;
            font-weight: 700 !important;
        }

        [data-theme="dark"] .dashboard-subtitle {
            color: var(--text-primary) !important;
            font-weight: 500 !important;
        }

        [data-theme="dark"] .dashboard-date-time {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .dashboard-time {
            color: var(--text-secondary) !important;
        }

        /* Ensure dashboard header text is always visible */
        [data-theme="dark"] .dashboard-header h1 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .dashboard-header p {
            color: var(--text-primary) !important;
        }

        /* Remove background effects and sync dashboard header with theme */
        [data-theme="dark"] .dashboard-header {
            background: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
            color: var(--text-primary) !important;
        }

        /* Remove animated star effects in dark mode */
        [data-theme="dark"] .dashboard-header::before,
        [data-theme="dark"] .dashboard-header::after {
            display: none !important;
        }

        /* Light mode dashboard header - clean background */
        [data-theme="light"] .dashboard-header,
        .dashboard-header {
            background: var(--bg-primary, white) !important;
            border-color: var(--border-primary, #e5e7eb) !important;
            color: var(--text-primary, #1f2937) !important;
        }

        /* Remove animated star effects in light mode too */
        [data-theme="light"] .dashboard-header::before,
        [data-theme="light"] .dashboard-header::after,
        .dashboard-header::before,
        .dashboard-header::after {
            display: none !important;
        }

        /* Chart containers dark mode */
        [data-theme="dark"] .bg-white.rounded-xl {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .text-gray-800 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-gray-900 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-gray-500 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .text-gray-600 {
            color: var(--text-secondary) !important;
        }

        /* Activity card dark mode */
        [data-theme="dark"] .activity-card {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        /* Dashboard sidebar dark mode */
        [data-theme="dark"] .dashboard-sidebar {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .sidebar-header {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .sidebar-section {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .sidebar-section h4 {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .sidebar-toggle-item label {
            color: var(--text-secondary) !important;
        }

        /* Remove black backgrounds from dashboard icons */
        [data-theme="dark"] .bg-blue-100,
        [data-theme="dark"] .bg-emerald-100,
        [data-theme="dark"] .bg-purple-100,
        [data-theme="dark"] .bg-indigo-100,
        [data-theme="dark"] .bg-amber-100,
        [data-theme="dark"] .bg-teal-100,
        [data-theme="dark"] .bg-cyan-100,
        [data-theme="dark"] .bg-green-100,
        [data-theme="dark"] .bg-orange-100 {
            background-color: transparent !important;
        }

        /* Gradient backgrounds for quick actions in dark mode */
        [data-theme="dark"] .bg-gradient-to-br {
            background: var(--bg-tertiary) !important;
        }

        /* Fix white buttons text visibility in dark mode */
        [data-theme="dark"] button {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .chart-switch-btn {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-primary) !important;
        }

        [data-theme="dark"] .chart-switch-btn.active {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        [data-theme="dark"] .chart-switch-btn:hover {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* Dashboard buttons general styling */
        [data-theme="dark"] .bg-white {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-white {
            color: var(--text-primary) !important;
        }

        /* Specific button text fixes */
        [data-theme="dark"] button span {
            color: inherit !important;
        }

        [data-theme="dark"] button .text-xs {
            color: inherit !important;
        }

        /* Toggle buttons and controls */
        [data-theme="dark"] .toggle-switch {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] .toggle-switch.active {
            background-color: #3b82f6 !important;
        }

        /* Sidebar toggle button arrow animation */
        .sidebar-toggle {
            transition: all 0.3s ease !important;
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        .sidebar-toggle i {
            transition: transform 0.3s ease !important;
        }

        /* When sidebar is open, rotate arrow to point left (close direction) */
        .sidebar-toggle.sidebar-open i {
            transform: rotate(180deg) !important;
        }

        /* Remove background box completely */
        .sidebar-toggle:hover {
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* Dark mode sidebar toggle button */
        [data-theme="dark"] .sidebar-toggle {
            color: var(--text-secondary) !important;
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        [data-theme="dark"] .sidebar-toggle:hover {
            color: var(--text-primary) !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* Light mode sidebar toggle button */
        [data-theme="light"] .sidebar-toggle,
        .sidebar-toggle {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        [data-theme="light"] .sidebar-toggle:hover,
        .sidebar-toggle:hover {
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* Enhanced border visibility for export tool */
        [data-theme="dark"] .grid .border:hover,
        [data-theme="dark"] .grid label:hover {
            border-color: rgba(75, 85, 99, 0.8) !important;
            border-width: 1px !important;
        }

        /* Ensure all form elements maintain visible borders */
        [data-theme="dark"] .border-gray-200:hover,
        [data-theme="dark"] .border-gray-300:hover {
            border-color: var(--border-secondary) !important;
            border-style: solid !important;
        }

        /* Preview section borders */
        [data-theme="dark"] #curriculum-preview .border:hover,
        [data-theme="dark"] #curriculum-preview .border-gray-200:hover {
            border-color: var(--border-secondary) !important;
        }

        /* Card and container borders on hover - disabled for course builder cards */
        /* [data-theme="dark"] .bg-white:hover {
            border-color: var(--border-secondary) !important;
        } */

        /* Specific export tool checkbox container borders */
        [data-theme="dark"] .grid-cols-2 label:hover {
            border: 1px solid var(--border-secondary) !important;
        }

        /* Loading spinner text */
        [data-theme="dark"] .animate-spin + span {
            color: var(--text-secondary) !important;
        }

        /* Version history and modal specific text */
        [data-theme="dark"] .version-item,
        [data-theme="dark"] .notification-item {
            color: var(--text-primary) !important;
        }

        /* Ensure all SVG icons maintain proper color inheritance */
        [data-theme="dark"] svg {
            color: inherit;
        }

        /* Fix for any remaining background/text contrast issues */
        [data-theme="dark"] .bg-slate-50,
        [data-theme="dark"] .bg-slate-100,
        [data-theme="dark"] .bg-gray-50,
        [data-theme="dark"] .bg-gray-100 {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] .bg-slate-200,
        [data-theme="dark"] .bg-gray-200 {
            background-color: var(--bg-tertiary) !important;
        }

        /* Ensure proper contrast for all text elements */
        [data-theme="dark"] .text-xs,
        [data-theme="dark"] .text-sm,
        [data-theme="dark"] .text-base,
        [data-theme="dark"] .text-lg,
        [data-theme="dark"] .text-xl,
        [data-theme="dark"] .text-2xl,
        [data-theme="dark"] .text-3xl {
            color: var(--text-primary) !important;
        }

        /* Override for specific utility classes that might be invisible */
        [data-theme="dark"] .font-bold,
        [data-theme="dark"] .font-semibold,
        [data-theme="dark"] .font-medium {
            color: var(--text-primary) !important;
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
        #sidebar.collapsed .nav-text,
        #sidebar.collapsed .section-header {
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



        /* Active navigation link styles - Professional & Modern */
        .nav-link.active {
            background: rgba(96, 165, 250, 0.12);
            position: relative;
        }
        .nav-link.active .w-8 {
            background: transparent;
        }
        .nav-link.active svg {
            color: #ffffff !important;
            stroke: #ffffff !important;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
        }
        .nav-link.active span {
            color: #ffffff !important;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        /* Active navigation link styles when sidebar is collapsed - Professional */
        #sidebar.collapsed .nav-link.active {
            background: rgba(96, 165, 250, 0.12);
        }
        #sidebar.collapsed .nav-link.active .w-8 {
            background: transparent;
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
            z-index: 9999;
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
<body class="bg-gray-100 dark-mode-transition">
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

            // Highlight active navigation link based on current URL
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            // Find the best matching link (exact match or longest matching path)
            let bestMatch = null;
            let bestMatchLength = 0;
            
            navLinks.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                
                // Exact match gets highest priority
                if (currentPath === linkPath) {
                    if (bestMatch) {
                        bestMatch.classList.remove('active');
                    }
                    link.classList.add('active');
                    bestMatch = link;
                    bestMatchLength = linkPath.length;
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
                console.log('Settings button clicked');
                if (dropdownMenu.style.display === 'block') {
                    console.log('Closing dropdown');
                    closeDropdown();
                } else {
                    console.log('Opening dropdown');
                    openDropdown();
                }
            });
            
            function openDropdown() {
                console.log('Opening dropdown');
                dropdownMenu.style.display = 'block';
                // Force reflow for animation
                dropdownMenu.offsetHeight;
                dropdownMenu.classList.add('show');
                console.log('Dropdown opened, display:', dropdownMenu.style.display, 'classes:', dropdownMenu.className);
            }
            
            function closeDropdown() {
                console.log('Closing dropdown');
                dropdownMenu.classList.remove('show');
                setTimeout(() => {
                    dropdownMenu.style.display = 'none';
                    console.log('Dropdown closed');
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
                fetch('/notifications', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.notifications && typeof data.unread_count !== 'undefined') {
                            displayNotifications(data.notifications);
                            updateNotificationBadge(data.unread_count);
                        }
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
            fetch('/notifications/unread-count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && typeof data.count !== 'undefined') {
                        updateNotificationBadge(data.count);
                    }
                })
                .catch(error => {
                    console.error('Error loading notification count:', error);
                    // Silently fail - don't show error to user
                });

            // Poll for new notifications every 30 seconds - TEMPORARILY DISABLED
            // Uncomment the following code once the JSON display issue is resolved
            /*
            setInterval(() => {
                fetch('/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && typeof data.count !== 'undefined') {
                            updateNotificationBadge(data.count);
                        }
                    })
                    .catch(error => {
                        console.error('Error polling notifications:', error);
                        // Silently fail - don't show error to user
                    });
            }, 30000);
            */

            // Dark Mode Functionality
            const darkModeToggle = document.getElementById('darkModeToggle');
            const htmlElement = document.documentElement;

            // Check for saved dark mode preference or default to light mode
            const savedTheme = localStorage.getItem('theme');
            
            // Set initial theme - always default to light mode unless manually set
            if (savedTheme) {
                htmlElement.setAttribute('data-theme', savedTheme);
                darkModeToggle.checked = savedTheme === 'dark';
            } else {
                // Always default to light mode, ignore system preference
                htmlElement.setAttribute('data-theme', 'light');
                darkModeToggle.checked = false;
                localStorage.setItem('theme', 'light');
            }

            // Toggle dark mode
            darkModeToggle.addEventListener('change', function() {
                const currentTheme = htmlElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                // Add transition class for smooth animation
                document.body.classList.add('dark-mode-transition');
                
                // Set new theme
                htmlElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                
                // Remove transition class after animation completes
                setTimeout(() => {
                    document.body.classList.remove('dark-mode-transition');
                }, 300);
                
                console.log(`Theme switched to: ${newTheme}`);
            });

            // System theme change listener removed - manual control only
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

        // Handle logout form submission - simplified approach
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form');
            const logoutButton = document.getElementById('logout-button');
            
            if (logoutForm) {
                console.log('Logout form found and event listener attached');
                logoutForm.addEventListener('submit', function(e) {
                    // Allow normal form submission without interference
                    // The AuthController logout method handles CSRF token issues gracefully
                    console.log('Logout form submitted');
                });
            } else {
                console.log('Logout form not found');
            }
            
            if (logoutButton) {
                console.log('Logout button found');
                logoutButton.addEventListener('click', function(e) {
                    console.log('Logout button clicked via event listener');
                    // Let the form submit naturally
                });
            } else {
                console.log('Logout button not found');
            }
        });
    </script>

    {{-- Include Notifications Component --}}
    @include('partials.notifications')
</body>
</html>
