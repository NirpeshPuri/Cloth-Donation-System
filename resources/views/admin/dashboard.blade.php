@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Welcome Section -->
        <div class="gradient-bg rounded-2xl p-6 md:p-8 text-white mb-6">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h1>
            <p class="text-teal-100 text-sm md:text-base">Manage donations, inventory, and collection centers</p>
        </div>

        <!-- Stats Cards - Row 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Total Donations</p>
                        <p class="text-2xl md:text-3xl font-bold text-teal-600">{{ $totalDonations ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-gift text-teal-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Pending Donations</p>
                        <p class="text-2xl md:text-3xl font-bold text-yellow-600">{{ $pendingDonations ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Pending Requests</p>
                        <p class="text-2xl md:text-3xl font-bold text-orange-600">{{ $pendingRequests ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-orange-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Total Clothes</p>
                        <p class="text-2xl md:text-3xl font-bold text-teal-600">{{ $totalClothes ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tshirt text-teal-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards - Row 2 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Approved Donations</p>
                        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $approvedDonations ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Approved Requests</p>
                        <p class="text-2xl md:text-3xl font-bold text-blue-600">{{ $approvedRequests ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-double text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Collection Centers</p>
                        <p class="text-2xl md:text-3xl font-bold text-purple-600">{{ $totalAdmins ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-store text-purple-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Completed Requests</p>
                        <p class="text-2xl md:text-3xl font-bold text-emerald-600">{{ $completedRequests ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-emerald-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('admin.donations.index') }}"
                class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition group border-l-4 border-teal-500">
                <div class="flex items-center justify-between">
                    <div>
                        <i class="fas fa-gift text-teal-600 text-2xl mb-2 block"></i>
                        <h3 class="font-bold text-gray-800">Manage Donations</h3>
                        <p class="text-gray-500 text-xs mt-1">Review and approve donations</p>
                        <p class="text-teal-600 text-xs mt-2 font-semibold">Pending: {{ $pendingDonations ?? 0 }}</p>
                    </div>
                    <i class="fas fa-arrow-right text-teal-600 text-xl group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <a href="{{ route('admin.requests.index') }}"
                class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition group border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <i class="fas fa-hand-holding-heart text-orange-600 text-2xl mb-2 block"></i>
                        <h3 class="font-bold text-gray-800">Manage Requests</h3>
                        <p class="text-gray-500 text-xs mt-1">Review user requests</p>
                        <p class="text-orange-600 text-xs mt-2 font-semibold">Pending: {{ $pendingRequests ?? 0 }}</p>
                    </div>
                    <i class="fas fa-arrow-right text-orange-600 text-xl group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <a href="#"
                class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition group border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <i class="fas fa-tshirt text-purple-600 text-2xl mb-2 block"></i>
                        <h3 class="font-bold text-gray-800">Manage Inventory</h3>
                        <p class="text-gray-500 text-xs mt-1">Add or remove clothes</p>
                        <p class="text-purple-600 text-xs mt-2 font-semibold">Total: {{ $totalClothes ?? 0 }} items</p>
                    </div>
                    <i class="fas fa-arrow-right text-purple-600 text-xl group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <a href="#"
                class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition group border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <i class="fas fa-store text-blue-600 text-2xl mb-2 block"></i>
                        <h3 class="font-bold text-gray-800">Collection Centers</h3>
                        <p class="text-gray-500 text-xs mt-1">Manage centers</p>
                        <p class="text-blue-600 text-xs mt-2 font-semibold">Total: {{ $totalAdmins ?? 0 }} centers</p>
                    </div>
                    <i class="fas fa-arrow-right text-blue-600 text-xl group-hover:translate-x-1 transition"></i>
                </div>
            </a>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Recent Donations -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-gift text-teal-600"></i> Recent Donations
                    </h3>
                </div>
                <div class="p-4">
                    @if (isset($recentDonations) && $recentDonations->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentDonations as $donation)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-sm">Donation #{{ $donation->id }}</p>
                                        <p class="text-xs text-gray-500">{{ $donation->donor->name ?? 'Unknown' }}</p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-xs px-2 py-1 rounded-full
                                        {{ $donation->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $donation->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4 text-sm">No recent donations</p>
                    @endif
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.donations.index') }}"
                            class="text-teal-600 hover:text-teal-700 text-sm">View All Donations →</a>
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-hand-holding-heart text-orange-600"></i> Recent Requests
                    </h3>
                </div>
                <div class="p-4">
                    @if (isset($recentRequests) && $recentRequests->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentRequests as $request)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-sm">{{ $request->cloth->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">By: {{ $request->receiver->name ?? 'Unknown' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-xs px-2 py-1 rounded-full
                                        {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4 text-sm">No recent requests</p>
                    @endif
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.requests.index') }}"
                            class="text-orange-600 hover:text-orange-700 text-sm">View All Requests →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
