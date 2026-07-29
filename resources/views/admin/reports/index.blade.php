@extends('layouts.admin')

@section('title', 'Reports Dashboard')

@section('content')
    <div class="container mx-auto px-2 py-0">
        <div class="container mx-auto px-6 py-0">
            <div class="gradient-bg rounded-2xl px-6 py-4 text-white mb-2">
                <div class="flex items-center justify-between gap-6">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold mb-2">Reports Dashboard</h1>
                        <p class="text-teal-100">Overview of all platform statistics and analytics</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-gift text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-teal-600">{{ $totalDonations }}</p>
                        <p class="text-xs text-gray-500">Total Donations</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tshirt text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $totalClothes }}</p>
                        <p class="text-xs text-gray-500">Total Clothes</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalRequests }}</p>
                        <p class="text-xs text-gray-500">Total Requests</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-purple-600">{{ $totalUsers }}</p>
                        <p class="text-xs text-gray-500">Total Users</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Donations -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Monthly Donations</h3>
                <div class="space-y-2">
                    @php
                        $maxDonations = $monthlyDonations->max('count') ?: 1;
                    @endphp
                    @foreach ($monthlyDonations as $month)
                        <div>
                            <div class="flex justify-between text-sm">
                                <span>{{ date('M Y', mktime(0, 0, 0, $month->month, 1, $month->year)) }}</span>
                                <span>{{ $month->count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-teal-600 h-4 rounded-full"
                                    style="width: {{ ($month->count / $maxDonations) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if ($monthlyDonations->isEmpty())
                        <p class="text-gray-500 text-center py-4">No donation data available</p>
                    @endif
                </div>
            </div>

            <!-- Monthly Requests -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Monthly Requests</h3>
                <div class="space-y-2">
                    @php
                        $maxRequests = $monthlyRequests->max('count') ?: 1;
                    @endphp
                    @foreach ($monthlyRequests as $month)
                        <div>
                            <div class="flex justify-between text-sm">
                                <span>{{ date('M Y', mktime(0, 0, 0, $month->month, 1, $month->year)) }}</span>
                                <span>{{ $month->count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full"
                                    style="width: {{ ($month->count / $maxRequests) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if ($monthlyRequests->isEmpty())
                        <p class="text-gray-500 text-center py-4">No request data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Donation Status</h3>
                @if ($donationStatus->count() > 0)
                    @foreach ($donationStatus as $status)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="capitalize">{{ $status->status }}</span>
                            <span class="font-semibold">{{ $status->count }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-4">No data available</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Request Status</h3>
                @if ($requestStatus->count() > 0)
                    @foreach ($requestStatus as $status)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="capitalize">{{ $status->status }}</span>
                            <span class="font-semibold">{{ $status->count }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-4">No data available</p>
                @endif
            </div>
        </div>

        <!-- Top Categories & Gender Distribution -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Top Donated Categories</h3>
                @if ($topCategories->count() > 0)
                    @foreach ($topCategories as $category)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span>{{ $category->cloth_type ?? 'Uncategorized' }}</span>
                            <span class="font-semibold">{{ $category->total }} items</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-4">No data available</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Inventory Category Breakdown</h3>
                @if ($categoryBreakdown->count() > 0)
                    @foreach ($categoryBreakdown as $category)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span>{{ $category->category ?? 'Uncategorized' }}</span>
                            <span class="font-semibold">{{ $category->total }} items</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-4">No data available</p>
                @endif
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">User Gender Distribution</h3>
                @if ($genderDistribution->count() > 0)
                    @foreach ($genderDistribution as $gender)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="capitalize">{{ $gender->gender ?? 'Not Specified' }}</span>
                            <span class="font-semibold">{{ $gender->count }}</span>
                        </div>
                    @endforeach
                    @php
                        $total = $genderDistribution->sum('count');
                    @endphp
                    <div class="mt-4 pt-2 border-t">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total</span>
                            <span class="font-bold">{{ $total }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No data available</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Clothes Gender Breakdown</h3>
                @if ($clothGenderBreakdown->count() > 0)
                    @foreach ($clothGenderBreakdown as $gender)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="capitalize">{{ $gender->gender ?? 'Unisex' }}</span>
                            <span class="font-semibold">{{ $gender->total }} items</span>
                        </div>
                    @endforeach
                    @php
                        $totalClothesGender = $clothGenderBreakdown->sum('total');
                    @endphp
                    <div class="mt-4 pt-2 border-t">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total</span>
                            <span class="font-bold">{{ $totalClothesGender }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No data available</p>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-clock text-teal-600"></i> Recent Donations
                    </h3>
                </div>
                <div class="p-4">
                    @if ($recentDonations->count() > 0)
                        @foreach ($recentDonations as $donation)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                <div>
                                    <p class="font-semibold text-sm">Donation #{{ $donation->id }}</p>
                                    <p class="text-xs text-gray-500">By {{ $donation->donor->name ?? 'Unknown' }}</p>
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
                    @else
                        <p class="text-gray-500 text-center py-4">No recent donations</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-clock text-blue-600"></i> Recent Requests
                    </h3>
                </div>
                <div class="p-4">
                    @if ($recentRequests->count() > 0)
                        @foreach ($recentRequests as $request)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                <div>
                                    <p class="font-semibold text-sm">{{ $request->cloth->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">By {{ $request->receiver->name ?? 'Unknown' }}</p>
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
                    @else
                        <p class="text-gray-500 text-center py-4">No recent requests</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
