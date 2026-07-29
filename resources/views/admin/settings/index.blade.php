@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="container mx-auto px-2 py-0">
        <div class="container mx-auto px-6 py-0">
            <div class="gradient-bg rounded-2xl px-6 py-4 text-white mb-2">
                <div class="flex items-center justify-between gap-6">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold mb-2">Settings</h1>
                        <p class="text-teal-100">Manage your admin profile and database backups</p>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="settingsTab" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition active"
                        id="profile-tab" data-tab-target="#profile" type="button" role="tab">
                        <i class="fas fa-user-shield mr-2"></i>Admin Profile
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition"
                        id="backup-tab" data-tab-target="#backup" type="button" role="tab">
                        <i class="fas fa-database mr-2"></i>Backup
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="space-y-6">
            <!-- Admin Profile Tab -->
            <div id="profile" class="tab-content" role="tabpanel">
                <!-- Current Admin Profile -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-user-shield text-teal-600"></i> My Profile
                        </h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.settings.profile') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center gap-6 mb-6">
                                <!-- Profile Photo with Click to Enlarge -->
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full bg-teal-100 flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-80 transition"
                                        onclick="openImageModal('{{ $admin->profile_photo ? Storage::url($admin->profile_photo) : '' }}', '{{ $admin->name }}')">
                                        @if ($admin->profile_photo && Storage::disk('public')->exists($admin->profile_photo))
                                            <img src="{{ Storage::url($admin->profile_photo) }}"
                                                class="w-full h-full object-cover" alt="{{ $admin->name }}">
                                        @else
                                            <span class="text-teal-600 text-3xl font-bold">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <!-- Edit Icon Overlay -->
                                    <div class="absolute bottom-0 right-0 bg-teal-600 rounded-full p-1.5 border-2 border-white cursor-pointer"
                                        onclick="document.querySelector('input[name=\"profile_photo\"]').click()">
                                        <i class="fas fa-camera text-white text-xs"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">{{ $admin->name }}</p>
                                    <p class="text-gray-500">{{ $admin->email }}</p>
                                    <p class="text-xs text-gray-400">Admin since
                                        {{ $admin->created_at->format('M d, Y') }}
                                    </p>
                                    @if ($admin->email === 'bouddha.donation@gmail.com')
                                        <span
                                            class="inline-block mt-1 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-crown mr-1"></i> Main Admin
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                                        readonly
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Profile Photo</label>
                                    <input type="file" name="profile_photo" accept="image/*"
                                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700">
                                    @error('profile_photo')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2 border-t pt-4 mt-2">
                                    <h4 class="font-semibold text-gray-700 mb-3">Change Password</h4>
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2">Current
                                                Password</label>
                                            <input type="password" name="current_password"
                                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                            @error('current_password')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                                            <input type="password" name="password"
                                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                            @error('password')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2">Confirm
                                                Password</label>
                                            <input type="password" name="password_confirmation"
                                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class="mt-4 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                                <i class="fas fa-save mr-2"></i> Update Profile
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Manage Admins (Only for Main Admin) -->
                @if ($isMainAdmin)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-users-cog text-purple-600"></i> Manage Admins
                            </h3>
                        </div>
                        <div class="p-6">
                            <!-- Add New Admin Form -->
                            <form method="POST" action="{{ route('admin.settings.add-admin') }}"
                                enctype="multipart/form-data" class="mb-6">
                                @csrf
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                                        <input type="text" name="name" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                                        <input type="email" name="email" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                                        <input type="text" name="phone"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Profile Photo</label>
                                        <input type="file" name="profile_photo" accept="image/*"
                                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Password *</label>
                                        <input type="password" name="password" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Confirm Password
                                            *</label>
                                        <input type="password" name="password_confirmation" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    </div>
                                </div>
                                <button type="submit"
                                    class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg">
                                    <i class="fas fa-user-plus mr-2"></i> Add New Admin
                                </button>
                            </form>

                            <!-- Admins List -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Admin
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Email
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Phone
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Joined
                                            </th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($admins as $adminItem)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-80 transition"
                                                            onclick="openImageModal('{{ $adminItem->profile_photo ? Storage::url($adminItem->profile_photo) : '' }}', '{{ $adminItem->name }}')">
                                                            @if ($adminItem->profile_photo && Storage::disk('public')->exists($adminItem->profile_photo))
                                                                <img src="{{ Storage::url($adminItem->profile_photo) }}"
                                                                    class="w-full h-full object-cover"
                                                                    alt="{{ $adminItem->name }}">
                                                            @else
                                                                <span class="text-teal-600 text-xs font-bold">
                                                                    {{ strtoupper(substr($adminItem->name, 0, 1)) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <span class="text-sm font-semibold">{{ $adminItem->name }}</span>
                                                        @if ($adminItem->email === 'bouddha.donation@gmail.com')
                                                            <span
                                                                class="text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded">Main</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-sm">{{ $adminItem->email }}</td>
                                                <td class="px-4 py-3 text-sm">{{ $adminItem->phone ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    {{ $adminItem->created_at->format('M d, Y') }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    @if ($adminItem->email !== 'bouddha.donation@gmail.com')
                                                        <form method="POST"
                                                            action="{{ route('admin.settings.delete-admin', $adminItem->id) }}"
                                                            onsubmit="return confirm('Delete this admin?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-800">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400 text-xs">Protected</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Backup Tab -->
            <div id="backup" class="tab-content hidden" role="tabpanel">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-database text-blue-600"></i> Database Backup
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
                                <div>
                                    <p class="text-blue-800 font-semibold">Create a backup of your database</p>
                                    <p class="text-blue-600 text-sm mt-1">This will download a complete SQL backup of
                                        your
                                        database including all tables and data.</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div class="border rounded-lg p-4">
                                <p class="text-gray-500 text-sm">Database Name</p>
                                <p class="font-semibold">{{ config('database.connections.mysql.database') }}</p>
                            </div>
                            <div class="border rounded-lg p-4">
                                <p class="text-gray-500 text-sm">Database Size</p>
                                <p class="font-semibold">{{ $databaseSize ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.settings.backup') }}">
                            @csrf
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                                <i class="fas fa-download mr-2"></i> Download Database Backup
                            </button>
                        </form>

                        <p class="text-xs text-gray-500 mt-3">⚠️ Backup may take a few seconds. Please don't close this
                            page while the backup is being created.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50"
        onclick="closeImageModal()">
        <div class="relative max-w-2xl max-h-screen p-4">
            <button
                class="absolute top-2 right-2 text-white bg-black/50 hover:bg-black rounded-full w-10 h-10 flex items-center justify-center text-2xl z-10"
                onclick="closeImageModal()">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="Profile Photo"
                class="max-w-full max-h-screen rounded-lg shadow-2xl">
            <p id="modalCaption" class="text-center text-white mt-3 text-lg font-semibold"></p>
        </div>
    </div>

    <!-- Tab JavaScript -->
    <script>
        // Get all tab buttons and content panels
        const tabs = document.querySelectorAll('[data-tab-target]');
        const tabContents = document.querySelectorAll('.tab-content');

        // Add click event to each tab button
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                tabs.forEach(t => {
                    t.classList.remove('border-teal-600', 'text-teal-600');
                    t.classList.add('hover:text-gray-600', 'hover:border-gray-300');
                    t.style.borderBottomColor = 'transparent';
                });

                // Add active class to clicked tab
                tab.classList.add('border-teal-600', 'text-teal-600');
                tab.classList.remove('hover:text-gray-600', 'hover:border-gray-300');
                tab.style.borderBottomColor = '#0d9488';

                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Show the target tab content
                const target = document.querySelector(tab.dataset.tabTarget);
                if (target) {
                    target.classList.remove('hidden');
                }
            });
        });

        // Set default active tab (Profile)
        document.querySelector('[data-tab-target="#profile"]').click();

        // Image Modal Functions
        function openImageModal(imageUrl, name) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalCaption = document.getElementById('modalCaption');

            if (imageUrl) {
                modalImage.src = imageUrl;
                modalCaption.textContent = name || 'Profile Photo';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>

    <style>
        /* Modal animation */
        #imageModal img {
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endsection
