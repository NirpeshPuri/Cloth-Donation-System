<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Cloth Bank</title>
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
                <i class="fas fa-key text-white text-4xl mb-3"></i>
                <h2 class="text-2xl font-bold text-white">Forgot Password</h2>
                <p class="text-teal-100 mt-2">We'll send you a reset link</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="px-6 py-8" id="forgotForm">
                @csrf

                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-red-700">Error</p>
                                <ul class="list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-6 bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-2 text-blue-700">
                        <i class="fas fa-info-circle"></i>
                        <span class="font-semibold text-sm">Password Reset</span>
                    </div>
                    <p class="text-xs text-blue-600 mt-1">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Enter your registered email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') input-error @enderror">
                    @error('email')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <span class="btn-spinner"></span>
                    <span class="btn-text">Send Reset Link <i class="fas fa-paper-plane ml-2"></i></span>
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
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('btn-loading');
            btn.disabled = true;
        });
    </script>
</body>

</html>
