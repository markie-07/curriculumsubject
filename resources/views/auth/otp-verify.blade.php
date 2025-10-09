<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OTP Verification - Student Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }
        .verify-btn {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        .verify-btn:hover {
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
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-blue">
    <div class="min-h-screen flex">
        <!-- Left Side - OTP Form -->
        <div class="flex-1 flex items-center justify-end px-4 sm:px-6 lg:px-20 bg-white-left">
            <!-- Space Background Effects -->
            <div class="nebula"></div>
            <div class="stars" id="stars-container"></div>
            
            <div class="max-w-md w-full space-y-8 relative z-10">
                <!-- Logo and Title -->
                <div class="text-center logo-container">
                    <img src="{{ asset('images/SMSIII LOGO.png') }}" alt="SMSIII Logo" class="mx-auto h-28 w-auto mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Enter OTP Code</h2>
                    <p class="text-gray-600 mb-6">We've sent a 6-digit code to {{ session('pending_user_email', 'your email') }}</p>
                </div>

                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-6">
                    @csrf
                    
                    <!-- OTP Input Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4 text-center">
                            Enter 6-digit OTP Code
                        </label>
                        <div class="flex justify-center space-x-2">
                            <input 
                                type="text" 
                                name="otp_digit_1" 
                                maxlength="1" 
                                class="otp-input input-field border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                oninput="moveToNext(this, 'otp_digit_2')"
                                onkeydown="moveToPrev(event, this, null)"
                            >
                            <input 
                                type="text" 
                                name="otp_digit_2" 
                                maxlength="1" 
                                class="otp-input input-field border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                oninput="moveToNext(this, 'otp_digit_3')"
                                onkeydown="moveToPrev(event, this, 'otp_digit_1')"
                            >
                            <input 
                                type="text" 
                                name="otp_digit_3" 
                                maxlength="1" 
                                class="otp-input input-field border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                oninput="moveToNext(this, 'otp_digit_4')"
                                onkeydown="moveToPrev(event, this, 'otp_digit_2')"
                            >
                            <input 
                                type="text" 
                                name="otp_digit_4" 
                                maxlength="1" 
                                class="otp-input input-field border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                oninput="moveToNext(this, 'otp_digit_5')"
                                onkeydown="moveToPrev(event, this, 'otp_digit_3')"
                            >
                            <input 
                                type="text" 
                                name="otp_digit_5" 
                                maxlength="1" 
                                class="otp-input input-field border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                oninput="moveToNext(this, 'otp_digit_6')"
                                onkeydown="moveToPrev(event, this, 'otp_digit_4')"
                            >
                            <input 
                                type="text" 
                                name="otp_digit_6" 
                                maxlength="1" 
                                class="otp-input input-field border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                oninput="combineOtp()"
                                onkeydown="moveToPrev(event, this, 'otp_digit_5')"
                            >
                        </div>
                        <input type="hidden" name="otp_code" id="otp_code">
                    </div>

                    <!-- Verify Button -->
                    <button 
                        type="submit" 
                        class="verify-btn w-full py-3 px-4 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Verify OTP
                    </button>
                </form>

                <!-- Resend OTP -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Didn't receive the code? 
                        <a href="{{ route('otp.resend') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                            Resend OTP
                        </a>
                    </p>
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 mt-2 inline-block">
                        Back to Login
                    </a>
                </div>
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
        // Generate stars for space background
        function createStars() {
            const starsContainer = document.getElementById('stars-container');
            const numberOfStars = 100;
            
            for (let i = 0; i < numberOfStars; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                
                // Random size
                const sizes = ['small', 'medium', 'large'];
                const randomSize = sizes[Math.floor(Math.random() * sizes.length)];
                star.classList.add(randomSize);
                
                // Random position
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                
                // Random animation delay
                star.style.animationDelay = Math.random() * 2 + 's';
                
                starsContainer.appendChild(star);
            }
        }

        // OTP input navigation
        function moveToNext(current, nextFieldName) {
            if (current.value.length >= current.maxLength) {
                const nextField = document.getElementsByName(nextFieldName)[0];
                if (nextField) {
                    nextField.focus();
                }
            }
            combineOtp();
        }

        function moveToPrev(event, current, prevFieldName) {
            if (event.key === 'Backspace' && current.value.length === 0 && prevFieldName) {
                const prevField = document.getElementsByName(prevFieldName)[0];
                if (prevField) {
                    prevField.focus();
                }
            }
        }

        function combineOtp() {
            const digits = [];
            for (let i = 1; i <= 6; i++) {
                const field = document.getElementsByName(`otp_digit_${i}`)[0];
                digits.push(field.value);
            }
            document.getElementById('otp_code').value = digits.join('');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            createStars();
            
            // Focus first OTP input
            document.getElementsByName('otp_digit_1')[0].focus();
            
            // Add input event listeners for better UX
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = paste.replace(/[^0-9]/g, '').split('').slice(0, 6);
                    
                    digits.forEach((digit, index) => {
                        const field = document.getElementsByName(`otp_digit_${index + 1}`)[0];
                        if (field) {
                            field.value = digit;
                        }
                    });
                    
                    combineOtp();
                });
            });
        });
    </script>
</body>
</html>
