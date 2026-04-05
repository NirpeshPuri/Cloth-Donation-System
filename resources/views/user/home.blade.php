<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ThreadsOfHope</title>
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
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-tshirt text-teal-700 text-2xl"></i>
                    <span class="font-extrabold text-2xl text-teal-800">Threads<span
                            class="text-teal-600">OfHope</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        <!-- Welcome Section -->
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-teal-100">Ready to make a difference today?</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-tshirt text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Clothes Donated</h3>
                <p class="text-3xl font-bold text-teal-600">0</p>
                <p class="text-gray-500 text-sm">Total items donated</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-hand-holding-heart text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Requests Made</h3>
                <p class="text-3xl font-bold text-teal-600">0</p>
                <p class="text-gray-500 text-sm">Total requests</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-users text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Lives Impacted</h3>
                <p class="text-3xl font-bold text-teal-600">0</p>
                <p class="text-gray-500 text-sm">People helped</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid md:grid-cols-2 gap-6">
            <a href="#" class="bg-teal-600 hover:bg-teal-700 text-white rounded-xl p-6 text-center transition">
                <i class="fas fa-gift text-3xl mb-3 block"></i>
                <h3 class="text-xl font-semibold mb-2">Donate Clothes</h3>
                <p class="text-teal-100">Give your unused clothes a second life</p>
            </a>
            <a href="#"
                class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl p-6 text-center transition">
                <i class="fas fa-hand-holding-heart text-3xl mb-3 block"></i>
                <h3 class="text-xl font-semibold mb-2">Request Clothes</h3>
                <p class="text-emerald-100">Get clothes you need for free</p>
            </a>
        </div>
    </div>
</body>

</html>
