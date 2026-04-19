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
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50">

    <!-- ================= NAVBAR ================= -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">

            <div class="flex justify-between items-center">

                <!-- LOGO -->
                <div class="flex items-center space-x-2">
                    <i class="fas fa-tshirt text-teal-700 text-2xl"></i>
                    <span class="font-extrabold text-2xl text-teal-800">
                        Threads<span class="text-teal-600">OfHope</span>
                    </span>
                </div>

                <!-- LINKS -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('user.home') }}" class="text-gray-700 hover:text-teal-600">Home</a>
                    <a href="{{ route('user.donate') }}" class="text-gray-700 hover:text-teal-600">Donate</a>
                    <a href="{{ route('user.my-donations') }}" class="text-gray-700 hover:text-teal-600">My
                        Donations</a>
                    <a href="{{ route('user.my-requests') }}" class="text-gray-700 hover:text-teal-600">My Requests</a>
                    <a href="{{ route('user.donate-money') }}" class="text-gray-700 hover:text-teal-600">
                        Donate Money
                    </a>
                    <a href="{{ route('user.profile') }}" class="text-gray-700 hover:text-teal-600">Profile</a>
                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center space-x-4">

                    {{-- <span class="text-gray-700 hidden md:inline">
                        Welcome, {{ Auth::user()->name }}!
                    </span> --}}

                    <!-- PROFILE ICON (CLICK = OPEN MODAL) -->
                    <img id="openProfileModal"
                        src="{{ Auth::user()->profile_photo
                            ? asset('storage/' . Auth::user()->profile_photo)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-teal-500 cursor-pointer hover:scale-110 transition">

                    <!-- LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </nav>

    <!-- ================= MAIN ================= -->
    <main>
        @yield('content')
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-gray-900 text-gray-300 mt-12 py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 ThreadsOfHope</p>
        </div>
    </footer>

    <!-- ================= PROFILE MODAL ================= -->
    <div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center">

        <div class="bg-white w-[90%] md:w-[400px] rounded-xl p-6 relative">

            <!-- CLOSE -->
            <button id="closeProfileModal" class="absolute top-2 right-3 text-xl text-gray-600">
                ✕
            </button>

            <!-- BIG PROFILE IMAGE -->
            <div class="flex justify-center">
                <img src="{{ Auth::user()->profile_photo
                    ? asset('storage/' . Auth::user()->profile_photo)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                    class="w-40 h-40 rounded-full border-4 border-teal-500 object-cover">
            </div>

            <!-- DETAILS -->
            <div class="text-center mt-4 space-y-1">
                <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                <p class="text-gray-600">{{ Auth::user()->phone }}</p>
                <p class="text-gray-600">{{ Auth::user()->address }}</p>
            </div>

        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        const openBtn = document.getElementById('openProfileModal');
        const modal = document.getElementById('profileModal');
        const closeBtn = document.getElementById('closeProfileModal');

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')

</body>

</html>
