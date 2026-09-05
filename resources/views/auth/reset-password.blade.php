<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Cloth Bank</title>
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

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .input-error {
            border-color: #dc2626 !important;
        }

        .password-strength {
            height: 4px;
            border-radius: 4px;
            margin-top: 6px;
            transition: all 0.3s ease;
            background: #e5e7eb;
        }

        .password-strength.weak {
            width: 25%;
            background: #dc2626;
        }

        .password-strength.medium {
            width: 50%;
            background: #f59e0b;
        }

        .password-strength.strong {
            width: 75%;
            background: #3b82f6;
        }

        .password-strength.very-strong {
            width: 100%;
            background: #16a34a;
        }

        .password-requirements {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 4px;
        }

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
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <a href="{{ route('login') }}"
            class="absolute top-6 left-6 bg-white hover:bg-gray-100 text-teal-700 font-semibold px-4 py-2 rounded-full shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Login
        </a>

        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="gradient-bg px-6 py-8 text-center">
                <i class="fas fa-lock text-white text-4xl mb-3"></i>
                <h2 class="text-2xl font-bold text-white">Reset Password</h2>
                <p class="text-teal-100 mt-2">Create a new password</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="px-6 py-8" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-red-700">Please fix the following errors:</p>
                                <ul class="list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" value="{{ $email }}" readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">Resetting password for this email</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                    <input type="password" name="password" id="password" required
                        placeholder="Enter new password (min 6 characters)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('password') input-error @enderror">
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="password-requirements mt-1" id="passwordRequirements">
                        <span id="req-length">❌ At least 6 characters</span><br>
                        <span id="req-upper">❌ At least one uppercase letter</span><br>
                        <span id="req-lower">❌ At least one lowercase letter</span><br>
                        <span id="req-number">❌ At least one number</span><br>
                        <span id="req-special">❌ At least one special character (!@#$%^&*)</span>
                    </div>
                    @error('password')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Confirm your new password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <span class="btn-spinner"></span>
                    <span class="btn-text">Reset Password <i class="fas fa-check ml-2"></i></span>
                </button>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Remember your password?
                        <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:underline">Sign In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');

        function validatePassword(password) {
            const requirements = {
                length: password.length >= 6,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
            };

            document.getElementById('req-length').innerHTML = (requirements.length ? '✅' : '❌') + ' At least 6 characters';
            document.getElementById('req-upper').innerHTML = (requirements.upper ? '✅' : '❌') +
                ' At least one uppercase letter';
            document.getElementById('req-lower').innerHTML = (requirements.lower ? '✅' : '❌') +
                ' At least one lowercase letter';
            document.getElementById('req-number').innerHTML = (requirements.number ? '✅' : '❌') + ' At least one number';
            document.getElementById('req-special').innerHTML = (requirements.special ? '✅' : '❌') +
                ' At least one special character (!@#$%^&*)';

            const strengthBar = document.getElementById('passwordStrength');
            const passedCount = Object.values(requirements).filter(Boolean).length;

            strengthBar.className = 'password-strength';
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.style.background = '#e5e7eb';
            } else if (passedCount <= 2) {
                strengthBar.classList.add('weak');
            } else if (passedCount <= 3) {
                strengthBar.classList.add('medium');
            } else if (passedCount <= 4) {
                strengthBar.classList.add('strong');
            } else {
                strengthBar.classList.add('very-strong');
            }
            return requirements;
        }

        passwordInput.addEventListener('input', function() {
            validatePassword(this.value);
        });

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');

            if (passwordInput.value !== passwordConfirm.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            const requirements = validatePassword(passwordInput.value);
            if (!Object.values(requirements).every(Boolean)) {
                e.preventDefault();
                alert('Please meet all password requirements!');
                return false;
            }

            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>
