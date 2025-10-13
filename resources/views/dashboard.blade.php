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
        transition: background-color 0.3s ease;
    }
    
    [data-theme="dark"] .star {
        background: #60a5fa;
        opacity: 0.8;
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
        padding-bottom: 20px;
        position: relative;
        z-index: 10;
        min-height: calc(100vh - 40px);
        max-width: 100vw;
        overflow-x: hidden;
        /* Enable container queries */
        container-type: inline-size;
    }
    
    /* Base grid layout - single row layout with minimal gaps */
    .stats-grid {
        display: grid;
        width: 100%;
        /* Single row with 8 equal columns */
        grid-template-columns: repeat(8, 1fr);
        gap: clamp(0.25rem, 0.5vw, 0.5rem);
        padding: 10px clamp(0.125rem, 0.5vw, 0.5rem) clamp(0.125rem, 0.5vw, 0.5rem) clamp(0.125rem, 0.5vw, 0.5rem);
        /* Enable container queries for enhanced responsiveness */
        container-type: inline-size;
        /* Responsive margin like other components */
        margin-bottom: clamp(0.5rem, 1.5vw, 1rem);
        /* Ensure no wrapping */
        overflow-x: auto;
    }

    /* Mobile responsiveness - stack on very small screens */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: clamp(0.125rem, 0.5vw, 0.375rem);
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: clamp(0.125rem, 0.5vw, 0.375rem);
        }
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
        font-size: clamp(1.75rem, 5vw, 2.75rem);
        line-height: 1.2;
        font-weight: 800;
        color: #111827 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        letter-spacing: -0.025em;
    }
    
    .dashboard-subtitle {
        font-size: clamp(1rem, 2.8vw, 1.375rem);
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
        font-size: clamp(1rem, 2.5vw, 1.25rem);
    }
    
    .dashboard-time {
        font-weight: 600;
        color: #4b5563 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        font-size: clamp(0.875rem, 2.2vw, 1.125rem);
    }
    
    /* Activities and Quick Actions zoom synchronization with hover effects */
    .activities-section {
        gap: clamp(1rem, 3vw, 1.5rem);
        padding: clamp(0.25rem, 1vw, 0.75rem);
        margin-bottom: clamp(0.5rem, 1vw, 1rem);
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
        font-size: clamp(1.125rem, 2.8vw, 1.5rem);
        margin-bottom: clamp(0.5rem, 1.2vw, 0.75rem);
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
    
    /* Activity item styles */
    .activity-item {
        border-radius: clamp(0.5rem, 1vw, 0.75rem);
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

    /* Chart section styles */
    .chart-switch-btn {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        color: #6b7280;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        min-width: 60px;
        white-space: nowrap;
    }

    .chart-switch-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #374151;
    }

    .chart-switch-btn.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .chart-container {
        transition: all 0.3s ease;
    }

    .chart-container canvas {
        max-height: 100% !important;
    }

    /* Chart loading animation */
    .chart-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #6b7280;
    }

    .chart-loading::after {
        content: '';
        width: 20px;
        height: 20px;
        border: 2px solid #e5e7eb;
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Sidebar styles */
    .dashboard-sidebar {
        position: fixed;
        top: 0;
        right: -320px;
        width: 320px;
        height: 100vh;
        background: white;
        border-left: 1px solid #e5e7eb;
        box-shadow: -4px 0 6px -1px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
    }

    .dashboard-sidebar.open {
        right: 0;
    }

    .sidebar-toggle {
        position: fixed;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        border-radius: 4px;
        color: #374151;
        cursor: pointer;
        box-shadow: none;
        transition: all 0.3s ease;
        z-index: 1001;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        padding: 8px 12px;
    }

    .sidebar-toggle:hover {
        background: rgba(55, 65, 81, 0.1);
        transform: translateY(-50%) scale(1.1);
    }

    .sidebar-toggle.sidebar-open {
        right: 340px;
    }
    
    #dashboard-sidebar-toggle.sidebar-open {
        right: 340px;
    }

    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .sidebar-content {
        padding: 20px;
    }

    .sidebar-section {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f9fafb;
    }

    .sidebar-section h4 {
        margin: 0 0 15px 0;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .sidebar-toggle-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .sidebar-toggle-item:last-child {
        border-bottom: none;
    }

    .sidebar-toggle-item label {
        font-size: 13px;
        color: #6b7280;
        cursor: pointer;
        flex: 1;
    }

    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        background: #d1d5db;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .toggle-switch.active {
        background: #3b82f6;
    }

    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s ease;
    }

    .toggle-switch.active::after {
        transform: translateX(20px);
    }

    /* Hidden sections */
    .dashboard-section.hidden {
        display: none !important;
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
    <div class="stats-grid dashboard-section" id="stats-section">
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

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 dashboard-section" id="charts-section">
        <!-- Curriculum Overview Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="las la-chart-bar text-blue-600" style="font-size: 1rem;"></i>
                    </div>
                    Curriculum Overview
                </h3>
                <div class="flex space-x-2">
                    <button onclick="switchChart('curriculum', 'bar')" class="chart-switch-btn active" data-chart="curriculum" data-type="bar" title="Bar Chart View">
                        <i class="las la-chart-bar mr-1"></i>
                        <span class="text-xs">Bar</span>
                    </button>
                </div>
            </div>
            <div class="chart-container" style="position: relative; height: 300px;">
                <canvas id="curriculumChart"></canvas>
            </div>
        </div>

        <!-- System Statistics Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="las la-chart-line text-emerald-600" style="font-size: 1rem;"></i>
                    </div>
                    System Statistics
                </h3>
                <div class="flex space-x-2">
                    <button onclick="switchChart('system', 'bar')" class="chart-switch-btn active" data-chart="system" data-type="bar" title="Bar Chart View">
                        <i class="las la-chart-bar mr-1"></i>
                        <span class="text-xs">Bar</span>
                    </button>
                    <button onclick="switchChart('system', 'radar')" class="chart-switch-btn" data-chart="system" data-type="radar" title="Radar Chart View">
                        <i class="las la-crosshairs mr-1"></i>
                        <span class="text-xs">Radar</span>
                    </button>
                </div>
            </div>
            <div class="chart-container" style="position: relative; height: 300px;">
                <canvas id="systemChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity Trends Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 dashboard-section" id="activity-chart-section">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <div class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="las la-chart-area text-purple-600" style="font-size: 1rem;"></i>
                </div>
                Activity Trends
            </h3>
            <div class="flex items-center space-x-4">
                <div class="flex space-x-2">
                    <button onclick="switchChart('activity', 'line')" class="chart-switch-btn active" data-chart="activity" data-type="line" title="Line Chart View">
                        <i class="las la-chart-line mr-1"></i>
                        <span class="text-xs">Line</span>
                    </button>
                    <button onclick="switchChart('activity', 'area')" class="chart-switch-btn" data-chart="activity" data-type="area" title="Area Chart View">
                        <i class="las la-chart-area mr-1"></i>
                        <span class="text-xs">Area</span>
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    <span class="font-medium">{{ $dashboardData['stats']['activities_today'] ?? 0 }}</span> activities today
                </div>
            </div>
        </div>
        <div class="chart-container" style="position: relative; height: 250px;">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <!-- New Widgets Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 dashboard-section" id="widgets-section">
        <!-- System Health Monitor -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="las la-heartbeat text-green-600" style="font-size: 1rem;"></i>
                    </div>
                    System Health
                </h3>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="ml-2 text-sm text-green-600 font-medium">Online</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="las la-database text-blue-500 mr-2"></i>
                        <span class="text-sm text-gray-600">Database</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-green-600">Connected</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="las la-server text-purple-500 mr-2"></i>
                        <span class="text-sm text-gray-600">Server</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-green-600" id="server-status">Running</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="las la-memory text-orange-500 mr-2"></i>
                        <span class="text-sm text-gray-600">Memory</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700" id="memory-usage">65%</span>
                        <div class="ml-2 w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500 rounded-full transition-all duration-300" style="width: 65%" id="memory-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="las la-clock text-indigo-500 mr-2"></i>
                        <span class="text-sm text-gray-600">Response</span>
                    </div>
                    <span class="text-sm font-medium text-indigo-600" id="response-time">~120ms</span>
                </div>
            </div>
        </div>

        <!-- Quick Search Widget -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="las la-search text-blue-600" style="font-size: 1rem;"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Quick Search</h3>
            </div>
            <div class="space-y-4">
                <div class="relative">
                    <input type="text" id="global-search" placeholder="Search curricula, subjects, employees..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <i class="las la-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="quickSearch('curriculum')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium hover:bg-blue-200 transition-colors">
                        <i class="las la-graduation-cap mr-1"></i>Curricula
                    </button>
                    <button onclick="quickSearch('subject')" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium hover:bg-purple-200 transition-colors">
                        <i class="las la-book mr-1"></i>Subjects
                    </button>
                    <button onclick="quickSearch('employee')" class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium hover:bg-green-200 transition-colors">
                        <i class="las la-users mr-1"></i>Staff
                    </button>
                </div>
                <div id="search-results" class="hidden">
                    <div class="border-t pt-3 mt-3">
                        <div class="text-xs text-gray-500 mb-2">Search Results:</div>
                        <div id="search-results-list" class="space-y-2 max-h-32 overflow-y-auto">
                            <!-- Results will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Downloads Tracker -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <div class="w-6 h-6 bg-cyan-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="las la-download text-cyan-600" style="font-size: 1rem;"></i>
                    </div>
                    Recent Downloads
                </h3>
                <a href="{{ route('curriculum_export_tool') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @if(isset($dashboardData['stats']['curriculum_exports']) && $dashboardData['stats']['curriculum_exports'] > 0)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="las la-file-pdf text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Curriculum Export</div>
                                <div class="text-xs text-gray-500">{{ now()->subMinutes(rand(5, 30))->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-xs text-green-600 font-medium">Complete</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="las la-file-excel text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Subject List</div>
                                <div class="text-xs text-gray-500">{{ now()->subHours(rand(1, 6))->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-xs text-green-600 font-medium">Complete</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="las la-download text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">No recent downloads</p>
                        <a href="{{ route('curriculum_export_tool') }}" class="text-xs text-blue-600 hover:text-blue-700 mt-1 inline-block">Start Export</a>
                    </div>
                @endif
                <div class="border-t pt-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Exports:</span>
                        <span class="font-semibold text-gray-900">{{ $dashboardData['stats']['curriculum_exports'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-1">
                        <span class="text-gray-600">This Month:</span>
                        <span class="font-semibold text-gray-900">{{ $dashboardData['stats']['exports_this_month'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout for Activities and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 activities-section flex-1 dashboard-section" id="activities-section">
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 activity-card overflow-hidden">
            <h3 class="activity-title font-semibold text-gray-800 flex items-center">
                <div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="las la-clock text-violet-600" style="font-size: 1rem;"></i>
                </div>
                Recent Activities
            </h3>
            <div class="space-y-3 overflow-y-auto activities-scroll-hidden" style="max-height: 400px;">
                @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
                    @foreach($dashboardData['recent_activities'] as $activity)
                    <div class="flex items-center space-x-4 p-4 border border-gray-100 activity-item">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-semibold text-sm">{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-medium text-gray-900 truncate">{{ $activity->user->name }}</p>
                            <p class="text-base text-gray-600 truncate">{{ $activity->formatted_description }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 {{ $activity->activity_color }}">
                                    {{ $activity->activity_icon }} {{ ucfirst($activity->activity_type) }}
                                </span>
                                <span class="text-base text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-center p-8 text-gray-500">
                        <div class="text-center">
                            <i class="las la-clock text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm font-medium">No recent activities</p>
                            <p class="text-xs text-gray-400 mt-1">Employee activities will appear here</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

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
                        <h4 class="text-lg font-semibold text-gray-900">Manage Staff</h4>
                        <p class="text-base text-gray-600">Employee accounts & status</p>
                    </div>
                </a>

                <a href="{{ route('curriculum_export_tool') }}" class="flex items-center p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200/50 quick-action-btn">
                    <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mr-4 quick-action-icon">
                        <i class="las la-file-export text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Export Tool</h4>
                        <p class="text-base text-gray-600">Curriculum downloads</p>
                    </div>
                </a>

                <a href="{{ route('employees.all-activities') }}" class="flex items-center p-5 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl border border-violet-200/50 quick-action-btn">
                    <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mr-4 quick-action-icon">
                        <i class="las la-chart-bar text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Activity Reports</h4>
                        <p class="text-base text-gray-600">Detailed activity logs</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar Toggle Button -->
<button class="sidebar-toggle" id="dashboard-sidebar-toggle" onclick="toggleDashboardSidebar()">
    <i class="las la-angle-double-left"></i>
</button>

<!-- Dashboard Sidebar -->
<div class="dashboard-sidebar" id="dashboard-sidebar">
    <div class="sidebar-header">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Dashboard Settings</h3>
            <button onclick="toggleDashboardSidebar()" class="text-gray-500 hover:text-gray-700">
                <i class="las la-times text-xl"></i>
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-2">Customize your dashboard view</p>
    </div>
    
    <div class="sidebar-content">
        <div class="sidebar-section">
            <h4>Show/Hide Sections</h4>
            
            <div class="sidebar-toggle-item">
                <label onclick="toggleSection('stats-section', document.getElementById('toggle-stats'))">Statistics Grid</label>
                <div class="toggle-switch active" id="toggle-stats" onclick="toggleSection('stats-section', this)"></div>
            </div>
            
            <div class="sidebar-toggle-item">
                <label onclick="toggleSection('charts-section', document.getElementById('toggle-charts'))">Charts Section</label>
                <div class="toggle-switch active" id="toggle-charts" onclick="toggleSection('charts-section', this)"></div>
            </div>
            
            <div class="sidebar-toggle-item">
                <label onclick="toggleSection('activity-chart-section', document.getElementById('toggle-activity-chart'))">Activity Trends Chart</label>
                <div class="toggle-switch active" id="toggle-activity-chart" onclick="toggleSection('activity-chart-section', this)"></div>
            </div>
            
            <div class="sidebar-toggle-item">
                <label onclick="toggleSection('widgets-section', document.getElementById('toggle-widgets'))">Widgets Section</label>
                <div class="toggle-switch active" id="toggle-widgets" onclick="toggleSection('widgets-section', this)"></div>
            </div>
            
            <div class="sidebar-toggle-item">
                <label onclick="toggleSection('activities-section', document.getElementById('toggle-activities'))">Activities & Quick Actions</label>
                <div class="toggle-switch active" id="toggle-activities" onclick="toggleSection('activities-section', this)"></div>
            </div>
        </div>
        
        <div class="sidebar-section">
            <h4>Quick Actions</h4>
            <div class="space-y-2">
                <button onclick="showAllSections()" class="w-full px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                    <i class="las la-eye mr-2"></i>Show All Sections
                </button>
                <button onclick="hideAllSections()" class="w-full px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    <i class="las la-eye-slash mr-2"></i>Hide All Sections
                </button>
                <button onclick="resetDashboard()" class="w-full px-3 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm font-medium hover:bg-orange-200 transition-colors">
                    <i class="las la-redo mr-2"></i>Reset to Default
                </button>
            </div>
        </div>
        
        <div class="sidebar-section">
            <h4>Dashboard Info</h4>
            <div class="text-xs text-gray-600 space-y-1">
                <div>Last Updated: <span id="last-updated">{{ now()->format('M d, Y g:i A') }}</span></div>
                <div>Sections: <span id="visible-sections">5</span> visible</div>
                <div>Settings saved automatically</div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
// Dashboard data from Laravel
const dashboardStats = @json($dashboardData['stats']);

// Chart instances
let curriculumChart = null;
let systemChart = null;
let activityChart = null;

// Chart configurations
const chartConfigs = {
    curriculum: {
        bar: {
            type: 'bar',
            data: {
                labels: ['Senior High', 'College', 'Total Subjects', 'Prerequisites'],
                datasets: [{
                    label: 'Count',
                    data: [
                        dashboardStats.curriculum_senior_high || 0,
                        dashboardStats.curriculum_college || 0,
                        dashboardStats.total_subjects || 0,
                        dashboardStats.total_prerequisites || 0
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(139, 92, 246)',
                        'rgb(245, 158, 11)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        }
    },
    system: {
        bar: {
            type: 'bar',
            data: {
                labels: ['Active Staff', 'Total Users', 'Exports', 'Equivalencies'],
                datasets: [{
                    label: 'Count',
                    data: [
                        dashboardStats.employees_active || 0,
                        dashboardStats.total_users || 0,
                        dashboardStats.curriculum_exports || 0,
                        dashboardStats.total_equivalencies || 0
                    ],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(6, 182, 212, 0.8)',
                        'rgba(20, 184, 166, 0.8)'
                    ],
                    borderColor: [
                        'rgb(16, 185, 129)',
                        'rgb(59, 130, 246)',
                        'rgb(6, 182, 212)',
                        'rgb(20, 184, 166)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        },
        radar: {
            type: 'radar',
            data: {
                labels: ['Staff', 'Users', 'Exports', 'Equivalencies', 'Subjects', 'Curriculums'],
                datasets: [{
                    label: 'System Metrics',
                    data: [
                        Math.max(1, Math.min(dashboardStats.employees_active || 1, 100)),
                        Math.max(1, Math.min(dashboardStats.total_users || 1, 100)),
                        Math.max(1, Math.min(dashboardStats.curriculum_exports || 1, 100)),
                        Math.max(1, Math.min(dashboardStats.total_equivalencies || 1, 100)),
                        Math.max(1, Math.min(dashboardStats.total_subjects || 1, 100)),
                        Math.max(1, Math.min(dashboardStats.total_curriculums || 1, 100))
                    ],
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(16, 185, 129)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(16, 185, 129)',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        min: 0,
                        ticks: {
                            stepSize: 20,
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        angleLines: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        pointLabels: {
                            color: '#374151',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        }
    },
    activity: {
        line: {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Activities',
                    data: [
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        dashboardStats.activities_today || 0
                    ],
                    borderColor: 'rgb(139, 92, 246)',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(139, 92, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        },
        area: {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Activities',
                    data: [
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 20) + 5,
                        dashboardStats.activities_today || 0
                    ],
                    borderColor: 'rgb(139, 92, 246)',
                    backgroundColor: 'rgba(139, 92, 246, 0.3)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(139, 92, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        }
    }
};

// System Health Monitoring
function updateSystemHealth() {
    // Simulate system metrics (in a real app, these would come from actual system monitoring)
    const memoryUsage = Math.floor(Math.random() * 30) + 50; // 50-80%
    const responseTime = Math.floor(Math.random() * 100) + 80; // 80-180ms
    
    // Update memory usage
    document.getElementById('memory-usage').textContent = memoryUsage + '%';
    document.getElementById('memory-bar').style.width = memoryUsage + '%';
    
    // Update memory bar color based on usage
    const memoryBar = document.getElementById('memory-bar');
    if (memoryUsage > 80) {
        memoryBar.className = 'h-full bg-red-500 rounded-full transition-all duration-300';
    } else if (memoryUsage > 65) {
        memoryBar.className = 'h-full bg-orange-500 rounded-full transition-all duration-300';
    } else {
        memoryBar.className = 'h-full bg-green-500 rounded-full transition-all duration-300';
    }
    
    // Update response time
    document.getElementById('response-time').textContent = '~' + responseTime + 'ms';
}

// Quick Search Functionality
function quickSearch(type) {
    const searchInput = document.getElementById('global-search');
    const resultsDiv = document.getElementById('search-results');
    const resultsList = document.getElementById('search-results-list');
    
    // Set placeholder based on type
    const placeholders = {
        'curriculum': 'Search curricula by name or code...',
        'subject': 'Search subjects by name or code...',
        'employee': 'Search staff by name or email...'
    };
    
    searchInput.placeholder = placeholders[type] || 'Search...';
    searchInput.focus();
    
    // Clear previous results
    resultsList.innerHTML = '';
    resultsDiv.classList.add('hidden');
}

// Global search functionality
function performGlobalSearch(query) {
    const resultsDiv = document.getElementById('search-results');
    const resultsList = document.getElementById('search-results-list');
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    // Simulate search results (in a real app, this would be an AJAX call)
    const mockResults = [
        { type: 'curriculum', name: 'Computer Science 2024', url: '{{ route("curriculum_builder") }}' },
        { type: 'curriculum', name: 'Information Technology 2023', url: '{{ route("curriculum_builder") }}' },
        { type: 'subject', name: 'Data Structures and Algorithms', url: '{{ route("subject_mapping") }}' },
        { type: 'subject', name: 'Database Management Systems', url: '{{ route("subject_mapping") }}' },
        { type: 'employee', name: 'John Doe', url: '{{ route("employees.index") }}' },
        { type: 'employee', name: 'Jane Smith', url: '{{ route("employees.index") }}' }
    ];
    
    // Filter results based on query
    const filteredResults = mockResults.filter(item => 
        item.name.toLowerCase().includes(query.toLowerCase())
    ).slice(0, 5);
    
    if (filteredResults.length > 0) {
        resultsList.innerHTML = filteredResults.map(result => `
            <a href="${result.url}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="w-6 h-6 bg-${getTypeColor(result.type)}-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="las ${getTypeIcon(result.type)} text-${getTypeColor(result.type)}-600 text-sm"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">${result.name}</div>
                    <div class="text-xs text-gray-500 capitalize">${result.type}</div>
                </div>
            </a>
        `).join('');
        resultsDiv.classList.remove('hidden');
    } else {
        resultsList.innerHTML = '<div class="text-sm text-gray-500 p-2">No results found</div>';
        resultsDiv.classList.remove('hidden');
    }
}

function getTypeColor(type) {
    const colors = {
        'curriculum': 'blue',
        'subject': 'purple',
        'employee': 'green'
    };
    return colors[type] || 'gray';
}

function getTypeIcon(type) {
    const icons = {
        'curriculum': 'la-graduation-cap',
        'subject': 'la-book',
        'employee': 'la-user'
    };
    return icons[type] || 'la-file';
}

// Recent Downloads Animation
function animateDownloadProgress() {
    // Simulate download progress animation
    const progressBars = document.querySelectorAll('.download-progress');
    progressBars.forEach(bar => {
        let width = 0;
        const interval = setInterval(() => {
            width += Math.random() * 10;
            if (width >= 100) {
                width = 100;
                clearInterval(interval);
                // Change status to complete
                const statusElement = bar.closest('.download-item').querySelector('.download-status');
                if (statusElement) {
                    statusElement.innerHTML = '<div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div><span class="text-xs text-green-600 font-medium">Complete</span>';
                }
            }
            bar.style.width = width + '%';
        }, 200);
    });
}

// Dashboard Sidebar functionality
function toggleDashboardSidebar() {
    console.log('toggleDashboardSidebar called');
    const sidebar = document.getElementById('dashboard-sidebar');
    const toggleBtn = document.getElementById('dashboard-sidebar-toggle');
    
    if (sidebar && toggleBtn) {
        sidebar.classList.toggle('open');
        toggleBtn.classList.toggle('sidebar-open');
        console.log('Dashboard sidebar toggled, open:', sidebar.classList.contains('open'));
    } else {
        console.error('Dashboard sidebar elements not found:', { sidebar, toggleBtn });
    }
}

// Section toggle functionality with localStorage persistence
function toggleSection(sectionId, toggleElement) {
    console.log('toggleSection called:', sectionId, toggleElement);
    const section = document.getElementById(sectionId);
    
    if (!section) {
        console.error('Section not found:', sectionId);
        return;
    }
    
    const isVisible = !section.classList.contains('hidden');
    console.log('Section current state - visible:', isVisible);
    
    if (isVisible) {
        section.classList.add('hidden');
        toggleElement.classList.remove('active');
        console.log('Section hidden');
    } else {
        section.classList.remove('hidden');
        toggleElement.classList.add('active');
        console.log('Section shown');
    }
    
    // Save state to localStorage
    saveSectionState(sectionId, !isVisible);
    updateVisibleSectionsCount();
}

// Save section visibility state
function saveSectionState(sectionId, isVisible) {
    const dashboardState = getDashboardState();
    dashboardState[sectionId] = isVisible;
    localStorage.setItem('dashboardState', JSON.stringify(dashboardState));
}

// Get dashboard state from localStorage
function getDashboardState() {
    const saved = localStorage.getItem('dashboardState');
    return saved ? JSON.parse(saved) : {};
}

// Load saved dashboard state
function loadDashboardState() {
    const state = getDashboardState();
    const sectionMappings = {
        'stats-section': 'toggle-stats',
        'charts-section': 'toggle-charts',
        'activity-chart-section': 'toggle-activity-chart',
        'widgets-section': 'toggle-widgets',
        'activities-section': 'toggle-activities'
    };
    
    Object.keys(sectionMappings).forEach(sectionId => {
        const section = document.getElementById(sectionId);
        const toggle = document.getElementById(sectionMappings[sectionId]);
        
        if (section && toggle) {
            const isVisible = state[sectionId] !== false; // Default to visible
            
            if (isVisible) {
                section.classList.remove('hidden');
                toggle.classList.add('active');
            } else {
                section.classList.add('hidden');
                toggle.classList.remove('active');
            }
        }
    });
    
    updateVisibleSectionsCount();
}

// Show all sections
function showAllSections() {
    const sections = document.querySelectorAll('.dashboard-section');
    const toggles = document.querySelectorAll('.toggle-switch');
    
    sections.forEach(section => {
        section.classList.remove('hidden');
        saveSectionState(section.id, true);
    });
    
    toggles.forEach(toggle => {
        toggle.classList.add('active');
    });
    
    updateVisibleSectionsCount();
}

// Hide all sections
function hideAllSections() {
    const sections = document.querySelectorAll('.dashboard-section');
    const toggles = document.querySelectorAll('.toggle-switch');
    
    sections.forEach(section => {
        section.classList.add('hidden');
        saveSectionState(section.id, false);
    });
    
    toggles.forEach(toggle => {
        toggle.classList.remove('active');
    });
    
    updateVisibleSectionsCount();
}

// Reset dashboard to default state
function resetDashboard() {
    localStorage.removeItem('dashboardState');
    showAllSections();
    
    // Show confirmation
    const lastUpdated = document.getElementById('last-updated');
    if (lastUpdated) {
        lastUpdated.textContent = new Date().toLocaleString();
    }
}

// Update visible sections count
function updateVisibleSectionsCount() {
    const visibleSections = document.querySelectorAll('.dashboard-section:not(.hidden)').length;
    const countElement = document.getElementById('visible-sections');
    if (countElement) {
        countElement.textContent = visibleSections;
    }
}

// Close dashboard sidebar when clicking outside
function initializeSidebarEvents() {
    document.addEventListener('click', (e) => {
        const sidebar = document.getElementById('dashboard-sidebar');
        const toggleBtn = document.getElementById('dashboard-sidebar-toggle');
        
        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && sidebar.classList.contains('open')) {
            toggleDashboardSidebar();
        }
    });
    
    // ESC key to close dashboard sidebar
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('dashboard-sidebar');
            if (sidebar.classList.contains('open')) {
                toggleDashboardSidebar();
            }
        }
    });
}

// Initialize widgets
function initializeWidgets() {
    // Update system health every 30 seconds
    updateSystemHealth();
    setInterval(updateSystemHealth, 30000);
    
    // Add search input event listener
    const searchInput = document.getElementById('global-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            performGlobalSearch(e.target.value);
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#global-search') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    }
    
    // Animate any existing download progress
    animateDownloadProgress();
}

// Initialize charts when page loads
function initializeCharts() {
    // Curriculum Chart
    const curriculumCtx = document.getElementById('curriculumChart');
    if (curriculumCtx) {
        curriculumChart = new Chart(curriculumCtx, chartConfigs.curriculum.bar);
    }

    // System Chart
    const systemCtx = document.getElementById('systemChart');
    if (systemCtx) {
        systemChart = new Chart(systemCtx, chartConfigs.system.bar);
    }

    // Activity Chart
    const activityCtx = document.getElementById('activityChart');
    if (activityCtx) {
        activityChart = new Chart(activityCtx, chartConfigs.activity.line);
    }
}

// Switch chart type
function switchChart(chartName, chartType) {
    try {
        console.log(`Switching ${chartName} chart to ${chartType}`);
        
        // Update button states
        document.querySelectorAll(`[data-chart="${chartName}"]`).forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-chart="${chartName}"][data-type="${chartType}"]`).classList.add('active');

        // Get the appropriate chart instance and config
        let chart, config, canvasId;
        
        switch(chartName) {
            case 'curriculum':
                chart = curriculumChart;
                config = chartConfigs.curriculum[chartType];
                canvasId = 'curriculumChart';
                break;
            case 'system':
                chart = systemChart;
                config = chartConfigs.system[chartType];
                canvasId = 'systemChart';
                break;
            case 'activity':
                chart = activityChart;
                config = chartConfigs.activity[chartType];
                canvasId = 'activityChart';
                break;
        }

        if (!config) {
            console.error(`No config found for ${chartName} chart type ${chartType}`);
            return;
        }

        if (!canvasId) {
            console.error(`No canvas ID found for ${chartName}`);
            return;
        }

        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.error(`Canvas element not found: ${canvasId}`);
            return;
        }

        // Destroy existing chart if it exists
        if (chart) {
            chart.destroy();
        }
        
        // Get fresh canvas context
        const ctx = canvas.getContext('2d');
        
        // Create new chart with animation
        const newChart = new Chart(ctx, config);
        
        // Update the global chart variable
        switch(chartName) {
            case 'curriculum':
                curriculumChart = newChart;
                break;
            case 'system':
                systemChart = newChart;
                break;
            case 'activity':
                activityChart = newChart;
                break;
        }
        
        console.log(`Successfully created ${chartType} chart for ${chartName}`);
        
    } catch (error) {
        console.error(`Error switching chart ${chartName} to ${chartType}:`, error);
    }
}

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
    console.log('DOM Content Loaded - Initializing dashboard...');
    
    updateDateTime();
    
    // Initialize charts
    initializeCharts();
    
    // Initialize widgets
    initializeWidgets();
    
    // Initialize sidebar functionality
    initializeSidebarEvents();
    
    // Load saved dashboard state
    loadDashboardState();
    
    // Check if dashboard sidebar elements exist
    const sidebar = document.getElementById('dashboard-sidebar');
    const toggleBtn = document.getElementById('dashboard-sidebar-toggle');
    console.log('Dashboard sidebar elements found:', { sidebar: !!sidebar, toggleBtn: !!toggleBtn });
    
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
