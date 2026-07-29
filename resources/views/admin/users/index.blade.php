@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
    <div class="container mx-auto px-2 py-0">
        <div class="container mx-auto px-6 py-0">
            <div class="gradient-bg rounded-2xl px-6 py-4 text-white mb-2">
                <div class="flex items-center justify-between gap-6">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold mb-2">Manage Users</h1>
                        <p class="text-teal-100">View and manage all registered users</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-4 mb-2">
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-teal-600">{{ $totalUsers }}</p>
                        <p class="text-xs text-gray-500">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-gift text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $totalDonors }}</p>
                        <p class="text-xs text-gray-500">Donors</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalReceivers }}</p>
                        <p class="text-xs text-gray-500">Receivers</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-yellow-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-yellow-600">{{ $newUsersThisMonth }}</p>
                        <p class="text-xs text-gray-500">New This Month</p>
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

        <!-- Search & Filter -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, email or phone..."
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <select name="gender"
                        class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-undo mr-2"></i> Reset
                </a>

                <a href="{{ route('admin.users.export', request()->query()) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-teal-600 text-white border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Contact</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Gender</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Donations</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Requests</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Joined</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-14 h-14 rounded-full overflow-hidden border-2 border-teal-500 flex-shrink-0">
                                        @if ($user->profile_photo)
                                            <img src="{{ Storage::url($user->profile_photo) }}"
                                                class="w-full h-full object-cover cursor-pointer hover:scale-110 transition"
                                                onclick="showImageModal('{{ Storage::url($user->profile_photo) }}')">
                                        @else
                                            <div class="w-full h-full bg-teal-100 flex items-center justify-center">
                                                <span class="text-teal-600 font-bold text-lg">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm">{{ $user->phone ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->age ?? 'Age N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 capitalize">{{ $user->gender ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold">{{ $user->donationsGiven->count() }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold">{{ $user->requests->count() }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded-lg text-sm">
                                        <i class="fas fa-edit"></i>
                                    </a> --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                        onsubmit="return confirm('Delete this user? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users-slash text-4xl mb-2 block"></i>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- <div class="p-4">
                {{ $users->links() }}
            </div> --}}
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50"
        onclick="closeImageModal()">

        <div class="max-w-2xl max-h-screen p-4">

            <img id="modalImage" src="" class="max-w-full max-h-screen rounded-lg shadow-2xl">

            <button class="absolute top-4 right-4 bg-white rounded-full p-2 text-gray-800 hover:bg-gray-200"
                onclick="closeImageModal()">

                <i class="fas fa-times text-2xl"></i>

            </button>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function showImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        }
    </script>
@endpush
