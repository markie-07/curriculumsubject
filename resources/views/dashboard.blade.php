@extends('layouts.app')

@section('content')
<style>
    /* Zoom-friendly responsive design */
    * {
        box-sizing: border-box;
    }
    
    .stars {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
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
        width: 0.25rem; /* 4px */
        height: 0.25rem;
        animation-duration: 2s;
    }
    
    .star.medium {
        width: 0.375rem; /* 6px */
        height: 0.375rem;
        animation-duration: 3s;
    }
    
    .star.large {
        width: 0.5rem; /* 8px */
        height: 0.5rem;
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
            transform: translateY(-0.625rem) rotate(180deg); /* -10px */
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
        padding-top: 10px;
        position: relative;
        z-index: 10;
        min-height: 100vh;
        max-width: 100vw;
        overflow-x: hidden;
        /* Enable container queries */
        container-type: inline-size;
    }
    
    /* Base grid layout - zoom synchronized like other files */
    .stats-grid {
        display: grid;
        width: 100%;
        /* Zoom-synchronized grid using vw units like other files */
        grid-template-columns: repeat(auto-fit, minmax(clamp(160px, 15vw, 220px), 1fr));
        gap: clamp(0.5rem, 2.5vw, 1.75rem);
        padding: clamp(0.25rem, 1vw, 0.75rem);
        /* Enable container queries for enhanced responsiveness */
        container-type: inline-size;
        /* Responsive margin like other components */
        margin-bottom: clamp(0.75rem, 2.5vw, 1.5rem);
    }
    
    /* Base stat card styles - zoom synchronized with cool hover effects */
    .stat-card {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        /* Zoom-synchronized padding like other components */
        padding: clamp(0.5rem, 2vw, 1.25rem);
        min-height: clamp(80px, 15vw, 140px);
        border-radius: clamp(0.5rem, 1vw, 1rem);
        position: relative;
        overflow: hidden;
        /* Cool hover preparation */
        transform: translateY(0);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Cool hover effects for stat cards */
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: currentColor;
    }
    
    /* Animated background gradient on hover */
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.6s ease;
        z-index: 1;
    }
    
    .stat-card:hover::before {
        left: 100%;
    }
    
    /* Ensure content stays above the shine effect */
    .stat-card > * {
        position: relative;
        z-index: 2;
    }
    
    /* Base icon container - zoom synchronized with cool hover effects */
    .stat-icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        /* Zoom-synchronized sizing like other files */
        width: clamp(2.5rem, 5vw, 4rem);
        height: clamp(2.5rem, 5vw, 4rem);
        border-radius: clamp(0.5rem, 1.2vw, 1rem);
        margin: 0 auto clamp(0.5rem, 1.5vw, 0.75rem) auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    /* Cool icon container hover effects */
    .stat-card:hover .stat-icon-container {
        transform: translateY(-4px) rotate(5deg) scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Pulsing glow effect on hover */
    .stat-icon-container::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.4s ease;
        z-index: 0;
    }
    
    .stat-card:hover .stat-icon-container::after {
        width: 120%;
        height: 120%;
    }
    
    /* Icon sizing - zoom synchronized with hover effects */
    .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        text-align: center;
        /* Zoom-synchronized icon size */
        font-size: clamp(1.25rem, 3vw, 2rem) !important;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    
    /* Icon bounce effect on hover */
    .stat-card:hover .stat-icon {
        animation: iconBounce 0.6s ease-in-out;
    }
    
    @keyframes iconBounce {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.2) rotate(-5deg); }
        50% { transform: scale(1.1) rotate(5deg); }
        75% { transform: scale(1.15) rotate(-2deg); }
    }
    
    /* Text sizing - zoom synchronized with hover effects */
    .stat-number {
        font-size: clamp(1rem, 3.5vw, 1.75rem);
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: clamp(0.25rem, 0.5vw, 0.5rem);
        transition: all 0.3s ease;
    }
    
    .stat-label {
        font-size: clamp(0.75rem, 2vw, 0.95rem);
        line-height: 1.3;
        transition: all 0.3s ease;
    }
    
    /* Text hover effects */
    .stat-card:hover .stat-number {
        transform: translateY(-2px);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card:hover .stat-label {
        transform: translateY(-1px);
        opacity: 0.8;
    }
    
    /* Dashboard header zoom synchronization with starry background */
    .dashboard-header {
        padding: clamp(0.75rem, 2vw, 1.25rem);
        border-radius: clamp(0.5rem, 1vw, 0.75rem);
        margin-bottom: clamp(0.5rem, 1.5vw, 1rem);
        position: relative;
        overflow: hidden;
        background: white;
        color: #1f2937; /* Dark text for white background */
        border: 1px solid #e5e7eb; /* Light border for definition */
    }
    
    /* Animated stars in dashboard header */
    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(3px 3px at 20px 30px, #000000, transparent),
            radial-gradient(3px 3px at 40px 70px, rgba(0, 0, 0, 0.9), transparent),
            radial-gradient(3px 3px at 90px 40px, #000000, transparent),
            radial-gradient(2px 2px at 130px 80px, rgba(0, 0, 0, 0.8), transparent),
            radial-gradient(2px 2px at 160px 30px, #000000, transparent),
            radial-gradient(3px 3px at 200px 60px, rgba(0, 0, 0, 0.9), transparent),
            radial-gradient(2px 2px at 240px 20px, #000000, transparent),
            radial-gradient(1px 1px at 280px 90px, rgba(0, 0, 0, 0.7), transparent),
            radial-gradient(3px 3px at 320px 50px, #000000, transparent),
            radial-gradient(2px 2px at 360px 10px, rgba(0, 0, 0, 0.9), transparent);
        background-repeat: repeat;
        background-size: 400px 100px;
        animation: starsMove 20s linear infinite, starsTwinkle 2s ease-in-out infinite alternate;
        opacity: 0.9;
        z-index: 1;
    }
    
    /* Twinkling effect for header stars */
    .dashboard-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(3px 3px at 50px 20px, #000000, transparent),
            radial-gradient(4px 4px at 100px 80px, rgba(0, 0, 0, 0.9), transparent),
            radial-gradient(2px 2px at 150px 40px, #000000, transparent),
            radial-gradient(3px 3px at 220px 70px, rgba(0, 0, 0, 0.8), transparent),
            radial-gradient(4px 4px at 300px 25px, #000000, transparent);
        background-repeat: repeat;
        background-size: 350px 100px;
        animation: starsTwinkle 2.5s ease-in-out infinite alternate, starsTwinkle2 1.8s ease-in-out infinite alternate;
        opacity: 0.7;
        z-index: 1;
    }
    
    /* Ensure header content stays above stars */
    .dashboard-header > * {
        position: relative;
        z-index: 2;
    }
    
    @keyframes starsMove {
        0% { transform: translateX(0); }
        125% { transform: translateX(-400px); }
    }
    
    @keyframes starsTwinkle {
        0% { opacity: 0.3; }
        50% { opacity: 0.9; }
        100% { opacity: 0.4; }
    }
    
    @keyframes starsTwinkle2 {
        0% { opacity: 0.5; }
        25% { opacity: 0.2; }
        50% { opacity: 0.8; }
        75% { opacity: 0.3; }
        100% { opacity: 0.6; }
    }
    
    .dashboard-title {
        font-size: clamp(1.5rem, 4vw, 2.25rem);
        line-height: 1.2;
        font-weight: 800;
        color: #111827 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        letter-spacing: -0.025em;
    }
    
    .dashboard-subtitle {
        font-size: clamp(0.875rem, 2.2vw, 1.125rem);
        line-height: 1.4;
        font-weight: 600;
        color: #374151 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        margin-top: clamp(0.25rem, 0.5vw, 0.5rem);
    }
    
    /* Enhanced date/time visibility */
    .dashboard-date-time {
        font-weight: 700;
        color: #111827 !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        font-size: clamp(0.875rem, 2vw, 1rem);
    }
    
    .dashboard-time {
        font-weight: 600;
        color: #4b5563 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        font-size: clamp(0.75rem, 1.8vw, 0.9rem);
    }
    
    /* Activities and Quick Actions zoom synchronization with hover effects */
    .activities-section {
        gap: clamp(1rem, 3vw, 1.5rem);
        padding: clamp(0.5rem, 1.5vw, 1rem);
    }
    
    .activity-card, .quick-action-card {
        padding: clamp(1rem, 2.5vw, 1.5rem);
        border-radius: clamp(0.75rem, 1.2vw, 1rem);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    /* Cool hover effects for activity and quick action cards */
    .activity-card:hover, .quick-action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Subtle background animation */
    .activity-card::before, .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
        z-index: 1;
    }
    
    .activity-card:hover::before, .quick-action-card:hover::before {
        transform: translateX(100%);
    }
    
    /* Ensure content stays above background effect */
    .activity-card > *, .quick-action-card > * {
        position: relative;
        z-index: 2;
    }
    
    .activity-title, .quick-action-title {
        font-size: clamp(1rem, 2.2vw, 1.25rem);
        margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
        transition: all 0.3s ease;
    }
    
    /* Title hover effects */
    .activity-card:hover .activity-title, 
    .quick-action-card:hover .quick-action-title {
        transform: translateX(4px);
        color: #4f46e5;
    }
    
    /* Quick action button hover effects */
    .quick-action-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .quick-action-btn:hover {
        transform: translateX(8px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    
    .quick-action-btn .quick-action-icon {
        transition: all 0.3s ease;
    }
    
    .quick-action-btn:hover .quick-action-icon {
        transform: scale(1.2) rotate(10deg);
    }
    
    /* Activity item hover effects */
    .activity-item {
        transition: all 0.3s ease;
        border-radius: clamp(0.5rem, 1vw, 0.75rem);
    }
    
    .activity-item:hover {
        transform: translateX(8px);
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
    
    /* Fix scrollbar styling for activities section */
    .activities-scroll {
        scrollbar-width: thin;
        scrollbar-color: #e2e8f0 transparent;
    }
    
    .activities-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .activities-scroll::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 3px;
    }
    
    .activities-scroll::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 3px;
        transition: background 0.3s ease;
    }
    
    .activities-scroll::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
    
    /* Hide scrollbar completely if preferred */
    .activities-scroll-hidden {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .activities-scroll-hidden::-webkit-scrollbar {
        display: none;
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

<div class="container mx-auto dashboard-content">
    <!-- Compact Header -->
    <div class="dashboard-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="dashboard-title">System Dashboard</h1>
                <p class="dashboard-subtitle">{{ $dashboardData['welcome_message'] }}</p>
            </div>
            <div class="text-right">
                <div class="dashboard-date-time" id="current-date">{{ now()->format('M d, Y') }}</div>
                <div class="dashboard-time" id="current-time">{{ now()->format('g:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid - Zoom Synchronized -->
    <div class="stats-grid">
        <!-- Curriculum Stats -->
        <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-blue-200 transition-all group stat-card">
            <div class="stat-icon-container bg-blue-100">
                <i class="las la-graduation-cap stat-icon text-blue-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-blue-600">{{ $dashboardData['stats']['curriculum_senior_high'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">Senior High</p>
        </a>

        <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-emerald-200 transition-all group stat-card">
            <div class="stat-icon-container bg-emerald-100">
                <i class="las la-university stat-icon text-emerald-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-emerald-600">{{ $dashboardData['stats']['curriculum_college'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">College</p>
        </a>

        <a href="{{ route('curriculum_builder') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-purple-200 transition-all group stat-card">
            <div class="stat-icon-container bg-purple-100">
                <i class="las la-folder-open stat-icon text-purple-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-purple-600">{{ $dashboardData['stats']['total_curriculums'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">Curriculum Total</p>
        </a>

        <a href="{{ route('subject_mapping') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-200 transition-all group stat-card">
            <div class="stat-icon-container bg-indigo-100">
                <i class="las la-book stat-icon text-indigo-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-indigo-600">{{ $dashboardData['stats']['total_subjects'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">Subjects</p>
        </a>

        <a href="{{ route('pre_requisite') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-amber-200 transition-all group stat-card">
            <div class="stat-icon-container bg-amber-100">
                <i class="las la-link stat-icon text-amber-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-amber-600">{{ $dashboardData['stats']['total_prerequisites'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">Prerequisites</p>
        </a>

        <a href="{{ route('equivalency_tool') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-teal-200 transition-all group stat-card">
            <div class="stat-icon-container bg-teal-100">
                <i class="las la-exchange-alt stat-icon text-teal-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-teal-600">{{ $dashboardData['stats']['total_equivalencies'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">Equivalency</p>
        </a>

        <a href="{{ route('curriculum_export_tool') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-cyan-200 transition-all group stat-card">
            <div class="stat-icon-container bg-cyan-100">
                <i class="las la-download stat-icon text-cyan-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-cyan-600">{{ $dashboardData['stats']['curriculum_exports'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">Exports</p>
        </a>

        <a href="{{ route('employees.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-green-200 transition-all group stat-card">
            <div class="stat-icon-container bg-green-100">
                <i class="las la-users stat-icon text-green-600"></i>
            </div>
            <p class="stat-number text-gray-900 group-hover:text-green-600">{{ $dashboardData['stats']['employees_active'] ?? 0 }}</p>
            <p class="stat-label text-gray-500">Active Staff</p>
        </a>
    </div>

    <!-- Two Column Layout for Activities and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 activities-section flex-1">
        <!-- Recent Activities -->
        @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 activity-card overflow-hidden">
            <h3 class="activity-title font-semibold text-gray-800 flex items-center">
                <div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="las la-clock text-violet-600" style="font-size: 1rem;"></i>
                </div>
                Recent Activities
            </h3>
            <div class="space-y-3 overflow-y-auto activities-scroll-hidden" style="max-height: 400px;">
                @foreach($dashboardData['recent_activities'] as $activity)
                <div class="flex items-center space-x-4 p-4 border border-gray-100 activity-item">
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 quick-action-card">
            <h3 class="quick-action-title font-semibold text-gray-800 flex items-center">
                <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="las la-bolt text-orange-600" style="font-size: 1rem;"></i>
                </div>
                Quick Actions
            </h3>
            <div class="space-y-4">
                <a href="{{ route('employees.index') }}" class="flex items-center p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200/50 quick-action-btn">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4 quick-action-icon">
                        <i class="las la-user-cog text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-gray-900">Manage Staff</h4>
                        <p class="text-sm text-gray-600">Employee accounts & status</p>
                    </div>
                </a>

                <a href="{{ route('curriculum_export_tool') }}" class="flex items-center p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200/50 quick-action-btn">
                    <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mr-4 quick-action-icon">
                        <i class="las la-file-export text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-gray-900">Export Tool</h4>
                        <p class="text-sm text-gray-600">Curriculum downloads</p>
                    </div>
                </a>

                <a href="{{ route('employees.all-activities') }}" class="flex items-center p-5 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl border border-violet-200/50 quick-action-btn">
                    <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mr-4 quick-action-icon">
                        <i class="las la-chart-bar text-white" style="font-size: 1.5rem;"></i>
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

<script>
function updateDateTime() {
    // Create a new Date object for Philippines timezone (UTC+8)
    const now = new Date();
    
    // Philippines timezone options
    const philippinesOptions = {
        timeZone: 'Asia/Manila',
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    };
    
    // Format date and time for Philippines
    const philippinesDateTime = new Intl.DateTimeFormat('en-US', philippinesOptions).formatToParts(now);
    
    // Extract parts
    let month = '';
    let day = '';
    let year = '';
    let hour = '';
    let minute = '';
    let dayPeriod = '';
    
    philippinesDateTime.forEach(part => {
        switch(part.type) {
            case 'month': month = part.value; break;
            case 'day': day = part.value; break;
            case 'year': year = part.value; break;
            case 'hour': hour = part.value; break;
            case 'minute': minute = part.value; break;
            case 'dayPeriod': dayPeriod = part.value; break;
        }
    });
    
    // Format the date and time
    const formattedDate = `${month} ${day}, ${year}`;
    const formattedTime = `${hour}:${minute} ${dayPeriod}`;
    
    // Update the DOM elements
    const dateElement = document.getElementById('current-date');
    const timeElement = document.getElementById('current-time');
    
    if (dateElement) {
        dateElement.textContent = formattedDate;
    }
    
    if (timeElement) {
        timeElement.textContent = formattedTime;
    }
}

// Update immediately when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateDateTime();
    
    // Update every second for real-time clock
    setInterval(updateDateTime, 1000);
});

// Also update when page becomes visible (in case user switches tabs)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateDateTime();
    }
});
</script>

@endsection
