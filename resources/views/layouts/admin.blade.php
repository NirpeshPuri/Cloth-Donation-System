<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
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

        .sidebar-link {
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: #f0fdf4;
            color: #0f766e;
        }

        .sidebar-link.active {
            background-color: #0f766e;
            color: white;
        }

        /* Fix for gradient background visibility */
        .gradient-bg {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100">
    <!-- Top Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user-shield text-teal-700 text-2xl"></i>
                    <span class="font-extrabold text-2xl text-teal-800">Admin Panel</span>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-500 text-xl cursor-pointer hover:text-teal-600"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                    </div>

                    <span class="text-gray-700">{{ Auth::guard('admin')->user()->name }}</span>

                    <form method="POST" action="{{ route('admin.logout') }}">
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

    <!-- Sidebar and Main Content -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md">
            <div class="py-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-teal-50 text-teal-700 border-r-4 border-teal-600' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.donations.index') }}"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition {{ request()->routeIs('admin.donations.*') ? 'bg-teal-50 text-teal-700 border-r-4 border-teal-600' : '' }}">
                    <i class="fas fa-gift w-5 mr-3"></i>
                    <span>Donations</span>
                </a>

                <a href="{{ route('admin.requests.index') }}"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition {{ request()->routeIs('admin.requests.*') ? 'bg-teal-50 text-teal-700 border-r-4 border-teal-600' : '' }}">
                    <i class="fas fa-hand-holding-heart w-5 mr-3"></i>
                    <span>Requests</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition {{ request()->routeIs('admin.categories.*') ? 'bg-teal-50 text-teal-700 border-r-4 border-teal-600' : '' }}">
                    <i class="fas fa-tags w-5 mr-3"></i>
                    <span>Categories</span>
                </a>

                <a href="#"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                    <i class="fas fa-tshirt w-5 mr-3"></i>
                    <span>Inventory</span>
                </a>

                <a href="#"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                    <i class="fas fa-users w-5 mr-3"></i>
                    <span>Users</span>
                </a>

                <a href="#"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                    <i class="fas fa-chart-line w-5 mr-3"></i>
                    <span>Reports</span>
                </a>

                <a href="#"
                    class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                    <i class="fas fa-cog w-5 mr-3"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
