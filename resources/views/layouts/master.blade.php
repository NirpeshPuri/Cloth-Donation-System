<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ThreadsOfHope')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }

        .hover-scale {
            transition: transform 0.2s;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }

        .cloth-card {
            transition: all 0.3s ease;
        }

        .cloth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-tshirt text-teal-700 text-2xl"></i>
                    <span class="font-extrabold text-2xl text-teal-800">Threads<span
                            class="text-teal-600">OfHope</span></span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('user.home') }}"
                        class="text-gray-700 hover:text-teal-600 transition font-medium">Home</a>
                    <a href="{{ route('user.donate') }}" class="text-gray-700 hover:text-teal-600 transition">Donate</a>
                    <a href="{{ route('user.my-donations') }}" class="text-gray-700 hover:text-teal-600 transition">My
                        Donations</a>
                    <a href="{{ route('user.my-requests') }}" class="text-gray-700 hover:text-teal-600 transition">My
                        Requests</a>
                    <a href="#" class="text-gray-700 hover:text-teal-600 transition font-medium">Profile</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-teal-600 transition relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cartCount"
                            class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 hidden">0</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 hidden md:inline">Welcome, {{ Auth::user()->name }}!</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                    <button id="mobileMenuBtn" class="md:hidden text-2xl text-teal-800">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-12 py-8">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 ThreadsOfHope - Clothing donation platform. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            // Add mobile menu logic here
        });
    </script>
    @stack('scripts')
</body>

</html>
