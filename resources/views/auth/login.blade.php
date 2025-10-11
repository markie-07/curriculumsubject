<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Student Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Flash Messages Meta Tags -->
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .bg-gradient-blue {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .bg-white-left {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 30%, #e9ecef 70%, #dee2e6 100%);
            position: relative;
            overflow: hidden;
        }
        
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        
        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .star {
            position: absolute;
            background: #4f46e5;
            transform: rotate(45deg);
            animation: twinkle 3s infinite;
            box-shadow: 0 0 6px rgba(79, 70, 229, 0.5);
        }
        
        .star:nth-child(odd) {
            animation-delay: 1s;
        }
        
        .star.small {
            width: 1px;
            height: 1px;
        }
        
        .star.medium {
            width: 2px;
            height: 2px;
        }
        
        .star.large {
            width: 3px;
            height: 3px;
        }
        
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        
        .nebula {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at 20% 30%, rgba(79, 70, 229, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(99, 102, 241, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 40% 80%, rgba(139, 92, 246, 0.04) 0%, transparent 50%);
            animation: nebula-drift 20s infinite linear;
        }
        
        @keyframes nebula-drift {
            0% { transform: translateX(-10px) translateY(-5px); }
            50% { transform: translateX(10px) translateY(5px); }
            100% { transform: translateX(-10px) translateY(-5px); }
        }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .login-container {
            position: relative;
            z-index: 10;
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }
        .login-btn {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
        .logo-container {
            animation: fadeInUp 1s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-blue">
    <div class="min-h-screen flex">
        <!-- Left Side - Login Form -->
        <div class="flex-1 flex items-center justify-end px-4 sm:px-6 lg:px-20 bg-white-left">
            <!-- Particles.js Background -->
            <div id="particles-js"></div>
            
            <div class="max-w-md w-full space-y-8 login-container">
                <!-- Logo and Title -->
                <div class="text-center logo-container">
                    <img src="{{ asset('images/SMSIII LOGO.png') }}" alt="SMSIII Logo" class="mx-auto h-28 w-auto mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign in</h2>
                </div>

                <!-- Success Messages -->
                @if (session('logout_success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('logout_success') }}
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <!-- Login Fields -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li id="error-message">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <!-- Countdown Timer for Lockout -->
                        <div id="lockout-timer" class="mt-2 text-center text-red-700 font-semibold" style="display: none;">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Try again in: <span id="countdown-display">0m 0s</span></span>
                            </div>
                            <div id="progressive-lockout-info" class="mt-1 text-xs text-red-600" style="display: none;">
                                ⚠️ Repeated failures result in longer lockouts
                            </div>
                        </div>
                    </div>  
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            value="{{ old('email') }}"
                            placeholder="Email/Username"
                            class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        >
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                placeholder="••••••••"
                                class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            >
                                <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>


                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="login-btn w-full py-3 px-4 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Sign in
                    </button>
                </form>

            </div>
        </div>

        <!-- Right Side - Background Image -->
        <div class="hidden lg:block lg:w-1/2 relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-purple-600/20"></div>
            <img 
                src="{{ asset('images/sms.png') }}" 
                alt="Student Management System" 
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 flex items-center justify-center -ml-8">
                <div class="text-left text-white">
                    <h1 class="text-5xl font-bold mb-4">Student Management</h1>
                    <h1 class="text-5xl font-bold mb-4 flex item-left">System</h1>
                    <p class="text-xl text-blue-100">Student admission click here</p>
                </div>
            </div>
        </div>  
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                passwordField.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Initialize Particles.js
        function initParticles() {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#000000"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 40,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#000000",
                        "opacity": 0.3,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 6,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": false,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 325,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });
        }

        // Lockout countdown timer
        function startLockoutTimer() {
            const errorMessage = document.getElementById('error-message');
            const lockoutTimer = document.getElementById('lockout-timer');
            const countdownDisplay = document.getElementById('countdown-display');
            const progressiveInfo = document.getElementById('progressive-lockout-info');
            const loginForm = document.querySelector('form');
            
            if (!errorMessage || !errorMessage.textContent.includes('locked for')) {
                return;
            }
            
            // Extract time from error message (supports both 1m 30s and 4m 45s formats)
            const timeMatch = errorMessage.textContent.match(/(\d+)m (\d+)s/);
            if (!timeMatch) return;
            
            let totalSeconds = parseInt(timeMatch[1]) * 60 + parseInt(timeMatch[2]);
            
            // Show progressive lockout info if this is an extended lockout
            if (errorMessage.textContent.includes('Extended lockout') || totalSeconds > 120) {
                progressiveInfo.style.display = 'block';
            }
            
            // Show timer and disable form
            lockoutTimer.style.display = 'block';
            loginForm.style.opacity = '0.5';
            loginForm.style.pointerEvents = 'none';
            
            const timer = setInterval(() => {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                
                countdownDisplay.textContent = `${minutes}m ${seconds}s`;
                
                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    lockoutTimer.style.display = 'none';
                    loginForm.style.opacity = '1';
                    loginForm.style.pointerEvents = 'auto';
                    
                    // Hide error message
                    const errorContainer = document.querySelector('.bg-red-50');
                    if (errorContainer) {
                        errorContainer.style.display = 'none';
                    }
                }
                
                totalSeconds--;
            }, 1000);
        }

        // Prevent back navigation after logout
        function preventBackNavigation() {
            // Clear browser history
            if (window.history && window.history.pushState) {
                // Replace current history entry
                window.history.replaceState(null, null, window.location.href);
                
                // Add a new history entry
                window.history.pushState(null, null, window.location.href);
                
                // Listen for back button
                window.addEventListener('popstate', function(event) {
                    // Push forward again to prevent going back
                    window.history.pushState(null, null, window.location.href);
                    
                    // Show alert if user tries to go back
                    alert('You have been logged out. Please login again to access the system.');
                });
            }
            
            // Disable right-click context menu on login page
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
            
            // Disable F5 refresh and other shortcuts that might bypass security
            document.addEventListener('keydown', function(e) {
                // Disable F5, Ctrl+F5 (but allow Ctrl+R for user convenience)
                if (e.key === 'F5' || (e.ctrlKey && e.key === 'F5')) {
                    e.preventDefault();
                }
                
                // Disable Ctrl+Shift+I (Developer Tools)
                if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                    e.preventDefault();
                }
                
                // Disable F12 (Developer Tools)
                if (e.key === 'F12') {
                    e.preventDefault();
                }
            });
        }

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent back navigation
            preventBackNavigation();
            
            // Initialize particles
            initParticles();
            
            // Start lockout timer if needed
            startLockoutTimer();
            
            const inputs = document.querySelectorAll('.input-field');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
    
    <!-- Include Notifications -->
    @include('partials.notifications')
</body>
</html>