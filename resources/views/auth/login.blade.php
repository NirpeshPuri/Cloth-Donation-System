{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cloth Bank</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <!-- Back to Home Button (Top Left) -->
        <a href="{{ route('home') }}"
            class="absolute top-6 left-6 bg-white hover:bg-gray-100 text-teal-700 font-semibold px-4 py-2 rounded-full shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="gradient-bg px-6 py-8 text-center">
                <i class="fas fa-tshirt text-white text-4xl mb-3"></i>
                <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
                <p class="text-teal-100 mt-2">Sign in to your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="px-6 py-8">
                @csrf

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="mb-6 flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-teal-600 hover:underline">Forgot password?</a>
                </div>
                <button type="submit"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition">
                    Sign In <i class="fas fa-arrow-right ml-2"></i>
                </button>
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="text-teal-600 font-semibold hover:underline">Register</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cloth Bank</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }

        /* Error message styling */
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .error-message i {
            font-size: 0.75rem;
        }

        .input-error {
            border-color: #dc2626 !important;
        }

        .input-error:focus {
            ring-color: #dc2626 !important;
        }

        .input-success {
            border-color: #16a34a !important;
        }

        /* Toast notification */
        .toast-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            color: white;
            font-weight: 500;
            z-index: 99999;
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            max-width: 400px;
        }

        .toast-error {
            background: #dc2626;
        }

        .toast-success {
            background: #16a34a;
        }

        .toast-info {
            background: #3b82f6;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-message .toast-close {
            margin-left: 1rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .toast-message .toast-close:hover {
            opacity: 1;
        }

        /* Loading spinner */
        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-loading .btn-text {
            display: none;
        }

        .btn-loading .btn-spinner {
            display: inline-block;
        }

        .btn-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Password error highlight */
        .password-error-highlight {
            border-color: #dc2626 !important;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <!-- Back to Home Button (Top Left) -->
        <a href="{{ route('home') }}"
            class="absolute top-6 left-6 bg-white hover:bg-gray-100 text-teal-700 font-semibold px-4 py-2 rounded-full shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="gradient-bg px-6 py-8 text-center">
                <i class="fas fa-tshirt text-white text-4xl mb-3"></i>
                <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
                <p class="text-teal-100 mt-2">Sign in to your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="px-6 py-8" id="loginForm">
                @csrf

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Email Error Message (Only when email doesn't exist) -->
                @if ($errors->has('email'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-red-700">Email Not Found</p>
                                <p class="text-sm text-red-600">{{ $errors->first('email') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Password Error Message (Only when password is wrong) -->
                @if ($errors->has('password') && !$errors->has('email'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-red-700">Incorrect Password</p>
                                <p class="text-sm text-red-600">{{ $errors->first('password') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" name="email" id="emailInput" value="{{ old('email') }}" required
                        placeholder="Enter your email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @if ($errors->has('email')) input-error @elseif($errors->has('password') && old('email')) input-success @endif">
                    @if ($errors->has('email'))
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}
                        </p>
                    @elseif($errors->has('password') && old('email'))
                        <p class="text-green-600 text-sm mt-1"><i class="fas fa-check-circle"></i> Email verified</p>
                    @endif
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" name="password" id="passwordInput" required placeholder="Enter your password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @if ($errors->has('password') && !$errors->has('email')) password-error-highlight @endif">
                    @if ($errors->has('password') && !$errors->has('email'))
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="mb-6 flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-teal-600 hover:underline">Forgot
                        password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <span class="btn-spinner"></span>
                    <span class="btn-text">Sign In <i class="fas fa-arrow-right ml-2"></i></span>
                </button>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="text-teal-600 font-semibold hover:underline">Register</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ========== FORM SUBMISSION ==========
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
        });

        // ========== AUTO-HIDE ERRORS ==========
        document.getElementById('emailInput').addEventListener('input', function() {
            const errorElement = this.closest('.mb-4')?.querySelector('.error-message');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
            this.classList.remove('input-error');

            // Hide general error messages
            const generalErrors = document.querySelectorAll('.bg-red-50');
            generalErrors.forEach(el => el.style.display = 'none');
        });

        document.getElementById('passwordInput').addEventListener('input', function() {
            const errorElement = this.closest('.mb-4')?.querySelector('.error-message');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
            this.classList.remove('password-error-highlight');

            // Hide password error only
            const passwordErrors = document.querySelectorAll('.bg-red-50');
            passwordErrors.forEach(el => {
                if (el.textContent.includes('Incorrect Password') || el.textContent.includes('password')) {
                    el.style.display = 'none';
                }
            });
        });

        // ========== TOAST FUNCTION (for future use) ==========
        function showToast(message, type = 'info') {
            const existingToast = document.querySelector('.toast-message');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = `toast-message toast-${type}`;

            let icon = 'fa-info-circle';
            if (type === 'success') icon = 'fa-check-circle';
            else if (type === 'error') icon = 'fa-exclamation-circle';

            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas ${icon} text-xl"></i>
                        <span>${message}</span>
                    </div>
                    <span class="toast-close" onclick="this.parentElement.parentElement.remove()">&times;</span>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }
    </script>
</body>

</html>
