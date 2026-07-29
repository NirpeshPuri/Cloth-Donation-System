@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>

        <!-- User Profile Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="gradient-bg px-8 py-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center overflow-hidden">
                            @if ($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl font-bold text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                            <p class="text-teal-100">{{ $user->email }}</p>
                            <p class="text-teal-100 text-sm">{{ $user->phone ?? 'No phone' }}</p>
                        </div>
                    </div>
                    {{-- <div class="flex gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                    </div> --}}
                </div>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Gender</p>
                        <p class="font-semibold capitalize">{{ $user->gender ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Age</p>
                        <p class="font-semibold">{{ $user->age ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Address</p>
                        <p class="font-semibold">{{ $user->address ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Joined</p>
                        <p class="font-semibold">{{ $user->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-gift text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Donations</h3>
                <p class="text-3xl font-bold text-teal-600">{{ $totalDonations }}</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-hand-holding-heart text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Requests</h3>
                <p class="text-3xl font-bold text-teal-600">{{ $totalRequests }}</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-clock text-yellow-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Pending</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingRequests }}</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Approved</h3>
                <p class="text-3xl font-bold text-green-600">{{ $approvedRequests }}</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-times-circle text-red-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Rejected</h3>
                <p class="text-3xl font-bold text-red-600">{{ $rejectedRequests }}</p>
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-gift text-teal-600"></i> Recent Donations
                </h3>
            </div>
            <div class="p-4">
                @if ($recentDonations->count() > 0)
                    @foreach ($recentDonations as $donation)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                            <div>
                                <p class="font-semibold text-sm">Donation #{{ $donation->id }}</p>
                                <p class="text-xs text-gray-500">{{ $donation->items->sum('quantity') }} items</p>
                            </div>
                            <div>
                                <span
                                    class="text-xs px-2 py-1 rounded-full
                                {{ $donation->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $donation->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $donation->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.users.donations', $user->id) }}"
                            class="text-teal-600 hover:text-teal-700 text-sm">
                            View All Donations →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No donations yet.</p>
                @endif
            </div>
        </div>

        <!-- Recent Requests -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-hand-holding-heart text-teal-600"></i> Recent Requests
                </h3>
            </div>
            <div class="p-4">
                @if ($recentRequests->count() > 0)
                    @foreach ($recentRequests as $request)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                            <div>
                                <p class="font-semibold text-sm">{{ $request->cloth->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $request->quantity }}</p>
                            </div>
                            <div>
                                <span
                                    class="text-xs px-2 py-1 rounded-full
                                {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $request->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $request->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.users.requests', $user->id) }}"
                            class="text-teal-600 hover:text-teal-700 text-sm">
                            View All Requests →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No requests yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
