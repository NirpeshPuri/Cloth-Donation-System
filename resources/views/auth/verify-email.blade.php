<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Cloth Bank</title>
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
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="gradient-bg px-6 py-8 text-center">
                <i class="fas fa-envelope text-white text-4xl mb-3"></i>
                <h2 class="text-2xl font-bold text-white">Verify Your Email</h2>
                <p class="text-teal-100 mt-2">We sent you a verification link</p>
            </div>

            <div class="px-6 py-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6 bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-2 text-blue-700">
                        <i class="fas fa-info-circle"></i>
                        <span class="font-semibold text-sm">Check Your Email</span>
                    </div>
                    <p class="text-sm text-blue-600 mt-1">
                        We've sent a verification link to your email address. Please check your inbox and click the link
                        to verify your account.
                    </p>
                </div>

                <form method="POST" action="{{ route('verification.resend') }}" id="resendForm" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                    <p class="text-sm text-gray-600 text-center mb-4">
                        Didn't receive the email?
                    </p>

                    <button type="submit" id="resendBtn"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                        <span class="btn-spinner"></span>
                        <span class="btn-text">Resend Verification Email <i class="fas fa-paper-plane ml-2"></i></span>
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Already verified?
                        <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:underline">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resendForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('resendBtn');
            btn.classList.add('btn-loading');
            btn.disabled = true;
        });
    </script>
</body>

</html>
