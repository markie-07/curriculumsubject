<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Management System - Terms Required</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
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
            background: linear-gradient(135deg, #f0f0dc 0%, #e5e5d1 25%, #e9dec1 50%, #f0cb9f 75%, #acc7b7 100%);
            position: relative;
            overflow: hidden;
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
            background: #2d3748;
            transform: rotate(45deg);
            animation: twinkle 3s infinite;
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
                radial-gradient(ellipse at 20% 30%, rgba(117, 212, 29, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(180, 255, 125, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 40% 80%, rgba(230, 230, 143, 0.1) 0%, transparent 50%);
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
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }
        
        .terms-content {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .terms-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .terms-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .terms-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .terms-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .blurred-background {
            filter: blur(8px);
            pointer-events: none;
        }

        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <!-- Blurred Background Login Form (Preview) -->
    <div id="background-content" class="blurred-background min-h-screen flex">
        <!-- Left Side - Login Form -->
        <div class="flex-1 flex items-center justify-end px-4 sm:px-6 lg:px-20 bg-white-left">
            <div class="max-w-md w-full space-y-8 relative z-10">
                <!-- Logo and Title -->
                <div class="text-center">
                    <img src="{{ asset('images/SMSIII LOGO.png') }}" alt="SMSIII Logo" class="mx-auto h-28 w-auto mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign in</h2>
                    <p class="text-gray-600">Please accept our terms to continue</p>
                </div>

                <!-- Disabled Login Form -->
                <form class="space-y-6 opacity-50">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            disabled
                            placeholder="Email/Username"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            disabled
                            placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                        >
                    </div>

                    <button 
                        type="button" 
                        disabled
                        class="w-full py-3 px-4 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed"
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

    <!-- Terms and Conditions Modal -->
    <div id="terms-modal" class="fixed inset-0 modal-backdrop flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden modal-enter">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <div class="text-center">
                    <img src="{{ asset('images/SMSIII LOGO.png') }}" alt="SMSIII Logo" class="mx-auto h-16 w-auto mb-4 filter brightness-0 invert">
                    <h1 class="text-2xl font-bold mb-2">Terms and Conditions Required</h1>
                    <p class="text-blue-100">Please read and accept our terms to access the Student Management System</p>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Terms Content -->
            <div class="p-6">
                <div class="terms-content bg-gray-50 p-6 rounded-lg border">
                    <div class="prose max-w-none">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">🔐 Security and Access Policy</h2>
                        <div class="mb-6 text-sm text-gray-700 space-y-3">
                            <p><strong>1.1 Account Security:</strong> You are responsible for maintaining the confidentiality of your login credentials. Do not share your username, password, or OTP codes with anyone.</p>
                            
                            <p><strong>1.2 Failed Login Attempts:</strong> Our system implements progressive security measures:</p>
                            <ul class="list-disc ml-6 space-y-1">
                                <li>After 5 failed login attempts, your account will be locked for 1 minute</li>
                                <li>Subsequent lockouts within 1 hour will result in 5-minute lockouts</li>
                                <li>Multiple security violations may result in extended account suspension</li>
                            </ul>
                            
                            <p><strong>1.3 Two-Factor Authentication:</strong> All logins require OTP verification sent to your registered email address. Ensure your email account is secure.</p>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Acceptable Use Policy</h2>
                        <div class="mb-6 text-sm text-gray-700 space-y-3">
                            <p><strong>2.1 Authorized Access:</strong> You may only access systems and data that you are explicitly authorized to use based on your role (Student, Admin, Super Admin).</p>
                            
                            <p><strong>2.2 Prohibited Activities:</strong></p>
                            <ul class="list-disc ml-6 space-y-1">
                                <li>Attempting to access unauthorized areas of the system</li>
                                <li>Sharing login credentials with other users</li>
                                <li>Using automated tools to attempt system access</li>
                                <li>Attempting to bypass security measures</li>
                                <li>Downloading or copying data without authorization</li>
                            </ul>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-800 mb-4">🛡️ Data Privacy and Protection</h2>
                        <div class="mb-6 text-sm text-gray-700 space-y-3">
                            <p><strong>3.1 Personal Information:</strong> We collect and process personal information necessary for educational administration, including names, email addresses, and academic records.</p>
                            
                            <p><strong>3.2 Data Security:</strong> We implement industry-standard security measures to protect your data, including encryption, secure authentication, and access logging.</p>
                            
                            <p><strong>3.3 Data Retention:</strong> Academic records are retained according to institutional policies and legal requirements.</p>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 System Monitoring and Logging</h2>
                        <div class="mb-6 text-sm text-gray-700 space-y-3">
                            <p><strong>4.1 Activity Monitoring:</strong> All system activities are logged for security and audit purposes, including:</p>
                            <ul class="list-disc ml-6 space-y-1">
                                <li>Login attempts and timestamps</li>
                                <li>IP addresses and user agents</li>
                                <li>Data access and modifications</li>
                                <li>System errors and security events</li>
                            </ul>
                            
                            <p><strong>4.2 Security Investigations:</strong> Logs may be reviewed to investigate security incidents or policy violations.</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                            <p class="text-sm text-blue-800">
                                <strong>📞 Contact Information:</strong> For questions about these terms or to report security concerns, contact the IT Administrator at your institution.
                            </p>
                        </div>

                        <div class="text-center mt-6 text-xs text-gray-500">
                            <p>Version 1.0 - Last Updated: {{ date('F j, Y') }}</p>
                            <p>Bestlink College of the Philippines - Student Management System</p>
                        </div>
                    </div>
                </div>

                <!-- Acceptance Form -->
                <form method="POST" action="{{ route('terms.accept.prelogin') }}" class="mt-6 space-y-4">
                    @csrf
                    
                    <!-- Checkbox Agreement -->
                    <div class="flex items-start space-x-3">
                        <input 
                            type="checkbox" 
                            id="accept_terms" 
                            name="accept_terms" 
                            required
                            class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="accept_terms" class="text-sm text-gray-700">
                            I have read, understood, and agree to comply with the Terms and Conditions, Security Policy, and Acceptable Use Policy outlined above.
                        </label>
                    </div>

                    <!-- Additional Security Acknowledgment -->
                    <div class="flex items-start space-x-3">
                        <input 
                            type="checkbox" 
                            id="security_acknowledgment" 
                            name="security_acknowledgment" 
                            required
                            class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="security_acknowledgment" class="text-sm text-gray-700">
                            I acknowledge that I am responsible for maintaining the security of my account credentials and will immediately report any suspected unauthorized access.
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <button 
                            type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                        >
                            ✅ Accept and Access Login
                        </button>
                        
                        <button 
                            type="button" 
                            onclick="window.history.back()" 
                            class="flex-1 bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                        >
                            ❌ Decline and Exit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Prevent closing modal by clicking outside or pressing escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
            }
        });

        document.getElementById('terms-modal').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Ensure both checkboxes are checked before allowing form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const termsChecked = document.getElementById('accept_terms').checked;
            const securityChecked = document.getElementById('security_acknowledgment').checked;
            
            if (!termsChecked || !securityChecked) {
                e.preventDefault();
                alert('Please accept both the Terms and Conditions and Security Acknowledgment to continue.');
            }
        });

        // Scroll to bottom detection for better UX
        const termsContent = document.querySelector('.terms-content');
        const acceptButton = document.querySelector('button[type="submit"]');
        
        let hasScrolledToBottom = false;
        
        termsContent.addEventListener('scroll', function() {
            const isScrolledToBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 10;
            if (isScrolledToBottom && !hasScrolledToBottom) {
                hasScrolledToBottom = true;
                acceptButton.classList.add('animate-pulse');
                setTimeout(() => {
                    acceptButton.classList.remove('animate-pulse');
                }, 2000);
            }
        });

        // Add visual feedback when checkboxes are checked
        document.getElementById('accept_terms').addEventListener('change', updateButtonState);
        document.getElementById('security_acknowledgment').addEventListener('change', updateButtonState);

        function updateButtonState() {
            const termsChecked = document.getElementById('accept_terms').checked;
            const securityChecked = document.getElementById('security_acknowledgment').checked;
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (termsChecked && securityChecked) {
                submitButton.classList.add('animate-pulse');
                submitButton.style.boxShadow = '0 0 20px rgba(59, 130, 246, 0.5)';
            } else {
                submitButton.classList.remove('animate-pulse');
                submitButton.style.boxShadow = '';
            }
        }
    </script>
</body>
</html>
