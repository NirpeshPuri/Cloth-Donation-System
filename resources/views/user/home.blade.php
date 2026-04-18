@extends('layouts.master')

@section('title', 'Dashboard - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Welcome Section -->
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-teal-100">Find collection centers near you and browse available clothes</p>
        </div>

        <!-- Location Status Banner -->
        @if (!session()->has('user_latitude') && !Auth::user()->latitude)
            <div id="locationRequestBanner" class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Enable Location Services</p>
                            <p class="text-sm text-gray-600">Allow access to your location to find nearby collection centers
                            </p>
                        </div>
                    </div>
                    <button onclick="getUserLocation()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-location-dot mr-2"></i> Share My Location
                    </button>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-store text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-teal-600">{{ $nearbyAdmins->count() }}</p>
                        <p class="text-xs text-gray-500">Nearby Centers</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tshirt text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-teal-600">{{ $availableClothes->sum('quantity') ?? 0 }}</p>
                        <p class="text-xs text-gray-500">Available Items</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-teal-600">{{ $totalRequestedItems ?? 0 }}</p>
                        <p class="text-xs text-gray-500">My Requests</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Stats Cards -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-store text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Nearby Centers</h3>
                <p class="text-3xl font-bold text-teal-600">{{ $nearbyAdmins->count() }}</p>
                <p class="text-gray-500 text-sm">Collection centers near you</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-tshirt text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Available Items</h3>
                <p class="text-3xl font-bold text-teal-600">{{ $availableClothes->sum('quantity') ?? 0 }}</p>
                <p class="text-gray-500 text-sm">Total clothes in stock</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-hand-holding-heart text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">My Requests</h3>
                <p class="text-3xl font-bold text-teal-600">{{ $totalRequestedItems ?? 0 }}</p>
                <p class="text-gray-500 text-sm">Total items requested</p>
            </div>
        </div> --}}

        {{-- <!-- Receiver's Activity Section -->
        @if (isset($selectedAdmin))
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-chart-line text-teal-600"></i> My Request Activity
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ $pendingRequests ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Pending</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $approvedRequests ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Approved</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $completedRequests ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Completed</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-red-600">{{ $rejectedRequests ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Rejected</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-600">{{ $cancelledRequests ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Cancelled</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('user.my-requests') }}" class="text-teal-600 hover:text-teal-700 text-sm">
                                View All My Requests →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}

        <!-- Selected Admin Banner -->
        @if (isset($selectedAdmin))
            <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-8 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-teal-200 flex items-center justify-center overflow-hidden">
                        @if ($selectedAdmin->profile_photo)
                            <img src="{{ url($selectedAdmin->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-store text-teal-600 text-xl"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Currently browsing:</p>
                        <p class="font-semibold text-teal-800">{{ $selectedAdmin->name }} Collection Center</p>
                        @if (isset($selectedAdmin->distance))
                            <p class="text-xs text-gray-500">{{ $selectedAdmin->distance }} km away</p>
                        @endif
                    </div>
                </div>
                <button onclick="clearAdminSelection()"
                    class="text-teal-600 hover:text-teal-700 text-sm flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Browse Other Centers
                </button>
            </div>
        @endif

        <!-- Nearby Admins Section -->
        @if (!isset($selectedAdmin))
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt text-teal-600 mr-3"></i>
                    Collection Centers Near You
                    @if (session()->has('user_latitude') || Auth::user()->latitude)
                        <span class="text-sm font-normal text-gray-500 ml-3">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i> Location enabled
                        </span>
                    @endif
                </h2>

                @if ($nearbyAdmins->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($nearbyAdmins as $admin)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden hover-scale cursor-pointer transition-all border-2 border-transparent hover:border-teal-500 relative"
                                onclick="selectAdmin({{ $admin->id }})">
                                <div class="relative overflow-hidden bg-gradient-to-r from-teal-500 to-emerald-500">
                                    <div class="aspect-video w-full">
                                        @if ($admin->profile_photo)
                                            <img src="{{ url($admin->profile_photo) }}" alt="{{ $admin->name }} Banner"
                                                class="w-full h-full object-cover object-center">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center">
                                                <i class="fas fa-store text-white text-6xl mb-2 opacity-70"></i>
                                                <span class="text-white text-sm opacity-70">{{ $admin->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 text-sm font-semibold text-teal-600 shadow-md z-10">
                                        <i class="fas fa-location-dot mr-1"></i> {{ $admin->distance }} km
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-bold text-xl text-gray-800">{{ $admin->name }}</h3>
                                            <p class="text-gray-500 text-sm flex items-center mt-1">
                                                <i class="fas fa-phone-alt text-xs mr-1"></i> {{ $admin->phone }}
                                            </p>
                                            <p class="text-gray-500 text-sm flex items-center mt-1">
                                                <i class="fas fa-map-marker-alt text-xs mr-1"></i>
                                                {{ Str::limit($admin->address, 40) }}
                                            </p>
                                        </div>
                                        @if ($loop->first && $admin->distance <= 5)
                                            <span
                                                class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full whitespace-nowrap ml-2">
                                                <i class="fas fa-star mr-1"></i> Closest
                                            </span>
                                        @endif
                                    </div>
                                    <button
                                        class="w-full mt-4 bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-lg transition font-semibold">
                                        <i class="fas fa-store mr-2"></i> Browse This Center
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                        <i class="fas fa-info-circle text-yellow-600 text-3xl mb-2 block"></i>
                        <p class="text-gray-600">No collection centers found nearby.</p>
                        <p class="text-gray-500 text-sm mt-2">Try expanding your search area or check back later.</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Products Display Section (Only when admin is selected) -->
        @if (isset($selectedAdmin))
            @push('styles')
                <style>
                    .section-container {
                        position: relative;
                        margin-top: 3rem;
                    }

                    .scroll-container {
                        overflow-x: auto;
                        scroll-behavior: smooth;
                        -webkit-overflow-scrolling: touch;
                        scrollbar-width: thin;
                        display: flex;
                        gap: 1.5rem;
                        padding-bottom: 1rem;
                    }

                    .scroll-container::-webkit-scrollbar {
                        height: 6px;
                    }

                    .scroll-container::-webkit-scrollbar-track {
                        background: #f1f1f1;
                        border-radius: 10px;
                    }

                    .scroll-container::-webkit-scrollbar-thumb {
                        background: #0f766e;
                        border-radius: 10px;
                    }

                    .scroll-btn {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        background: white;
                        border-radius: 9999px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        padding: 0.5rem;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        z-index: 10;
                    }

                    .scroll-btn:hover {
                        background-color: #0f766e;
                        color: white;
                    }

                    .scroll-left {
                        left: -0.75rem;
                    }

                    .scroll-right {
                        right: -0.75rem;
                    }

                    .product-card {
                        flex-shrink: 0;
                        width: 16rem;
                        background: white;
                        border-radius: 0.75rem;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                        transition: all 0.3s ease;
                        cursor: pointer;
                    }

                    .product-card:hover {
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
                        transform: translateY(-4px);
                    }

                    .empty-section {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 3rem;
                        background-color: #f9fafb;
                        border-radius: 0.75rem;
                        text-align: center;
                    }

                    .filter-sidebar {
                        background: white;
                        border-radius: 1rem;
                        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
                        padding: 1.5rem;
                        position: sticky;
                        top: 80px;
                    }

                    .filter-sidebar h3 {
                        font-weight: 600;
                        margin-bottom: 1rem;
                        padding-bottom: 0.5rem;
                        border-bottom: 2px solid #e5e7eb;
                    }

                    .filter-group {
                        margin-bottom: 1.25rem;
                    }

                    .filter-group label {
                        display: block;
                        font-size: 0.875rem;
                        font-weight: 500;
                        color: #374151;
                        margin-bottom: 0.5rem;
                    }

                    .filter-group select,
                    .filter-group input {
                        width: 100%;
                        padding: 0.5rem;
                        border: 1px solid #e5e7eb;
                        border-radius: 0.5rem;
                        font-size: 0.875rem;
                        outline: none;
                        transition: all 0.2s;
                    }

                    .filter-group select:focus,
                    .filter-group input:focus {
                        border-color: #0f766e;
                        ring: 2px solid #0f766e;
                    }

                    .apply-filters-btn {
                        width: 100%;
                        background-color: #0f766e;
                        color: white;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        font-weight: 500;
                        transition: all 0.2s;
                    }

                    .apply-filters-btn:hover {
                        background-color: #0d5c56;
                    }

                    .reset-filters-btn {
                        width: 100%;
                        background-color: #e5e7eb;
                        color: #374151;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        font-weight: 500;
                        margin-top: 0.5rem;
                        transition: all 0.2s;
                    }

                    .reset-filters-btn:hover {
                        background-color: #d1d5db;
                    }

                    .search-results-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 1.5rem;
                        flex-wrap: wrap;
                        gap: 1rem;
                    }

                    .sort-select {
                        padding: 0.5rem;
                        border: 1px solid #e5e7eb;
                        border-radius: 0.5rem;
                        font-size: 0.875rem;
                    }

                    .recent-search-tag {
                        background-color: #f3f4f6;
                        padding: 0.25rem 0.75rem;
                        border-radius: 9999px;
                        font-size: 0.75rem;
                        cursor: pointer;
                        transition: all 0.2s;
                    }

                    .recent-search-tag:hover {
                        background-color: #e5e7eb;
                    }
                </style>
            @endpush

            <!-- Search and Filters Section -->
            <div class="grid lg:grid-cols-4 gap-6 mb-8">
                <!-- Filters Sidebar -->
                <div class="lg:col-span-1">
                    <div class="filter-sidebar">
                        <h3><i class="fas fa-filter mr-2"></i> Filters</h3>

                        <div class="filter-group">
                            <label><i class="fas fa-search mr-1"></i> Search</label>
                            <input type="text" id="searchInput" placeholder="Search by name, category..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="filter-group">
                            <label><i class="fas fa-venus-mars mr-1"></i> Gender</label>
                            <select id="genderFilter" onchange="updateSizeOptions()">
                                <option value="">All Genders</option>
                                <option value="men">👨 Men</option>
                                <option value="women">👩 Women</option>
                                <option value="kids">🧒 Kids</option>
                                <option value="unisex">👥 Unisex</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label><i class="fas fa-ruler mr-1"></i> Size</label>
                            <select id="sizeFilter">
                                <option value="">All Sizes</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label><i class="fas fa-star mr-1"></i> Quality</label>
                            <select id="qualityFilter">
                                <option value="">All Qualities</option>
                                <option value="new">New</option>
                                <option value="like_new">Like New</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                            </select>
                        </div>

                        <!-- Category Filter - Add this after Quality filter -->
                        <div class="filter-group">
                            <label><i class="fas fa-tags mr-1"></i> Category</label>
                            <select id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat['value'] }}">{{ $cat['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button class="apply-filters-btn" onclick="applyFilters()">
                            <i class="fas fa-search mr-2"></i> Apply Filters
                        </button>
                        <button class="reset-filters-btn" onclick="resetFilters()">
                            <i class="fas fa-undo-alt mr-2"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="lg:col-span-3">
                    <div class="search-results-header">
                        <h2 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-search text-teal-600 mr-2"></i>
                            Search Results
                            <span id="resultCount" class="text-sm text-gray-500 font-normal ml-2"></span>
                        </h2>
                        <div>
                            <label class="text-sm text-gray-600 mr-2">Sort by:</label>
                            <select id="sortBy" class="sort-select" onchange="applyFilters()">
                                <option value="latest">Latest First</option>
                                <option value="most_requested">Most Requested</option>
                            </select>
                        </div>
                    </div>

                    <!-- Recent Searches -->
                    <div id="recentSearchesContainer" class="mb-4 flex flex-wrap gap-2 items-center"></div>

                    <div id="searchResultsContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="text-center py-12 col-span-full">
                            <i class="fas fa-search text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">Use filters above to search for clothes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Original Sections -->
            <div id="originalSections">
                <!-- Popular Items Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-fire text-orange-500 mr-3"></i> Most Popular
                            </h2>
                            <p class="text-gray-500 mt-1">Most requested items by our community</p>
                        </div>
                        @if ($popularItems && $popularItems->count() > 4)
                            <a href="{{ route('user.category', 'popular') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($popularItems && $popularItems->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-popular">
                                @foreach ($popularItems as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->category ?? 'Clothing' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs text-gray-500">{{ $cloth->size ?? 'One Size' }}</span>
                                                <span class="text-xs text-teal-600">{{ $cloth->quantity }} left</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($popularItems->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-popular')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-popular')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-fire text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No popular items available at the moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Personalized Recommendations Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-3"></i> Recommended For You
                            </h2>
                            <p class="text-gray-500 mt-1">Based on your preferences and search history</p>
                        </div>
                        <button onclick="refreshRecommendations()"
                            class="text-teal-600 hover:text-teal-700 text-sm flex items-center gap-1">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>

                    @if ($recommendedClothes && $recommendedClothes->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-recommended">
                                @foreach ($recommendedClothes as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                            @if ($cloth->quantity <= 3 && $cloth->quantity > 0)
                                                <span
                                                    class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                                    Only {{ $cloth->quantity }} left
                                                </span>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->category ?? 'Clothing' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs text-gray-500">{{ $cloth->size ?? 'One Size' }}</span>
                                                <span class="text-xs text-teal-600">{{ $cloth->quantity }}
                                                    available</span>
                                            </div>
                                            <div class="mt-2 flex items-center gap-2">
                                                <span
                                                    class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">{{ $cloth->gender ?? 'Unisex' }}</span>
                                                @if ($cloth->quality)
                                                    <span
                                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full capitalize">{{ $cloth->quality }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($recommendedClothes->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-recommended')">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-recommended')">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-star text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No recommendations available at the moment.</p>
                            <p class="text-gray-400 text-sm mt-1">Start searching to get personalized recommendations.</p>
                        </div>
                    @endif
                </div>

                <!-- Seasonal Recommendations -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-calendar-alt text-teal-600 mr-3"></i>
                                {{ ucfirst($currentSeason ?? 'Summer') }} Collection
                            </h2>
                            <p class="text-gray-500 mt-1">Perfect for the current season</p>
                        </div>
                        @if ($seasonalRecommendations && $seasonalRecommendations->count() > 4)
                            <a href="{{ route('user.category', 'seasonal') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($seasonalRecommendations && $seasonalRecommendations->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-seasonal">
                                @foreach ($seasonalRecommendations as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->category ?? 'Clothing' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs text-gray-500">{{ $cloth->size ?? 'One Size' }}</span>
                                                <span class="text-xs text-teal-600">{{ $cloth->quantity }}
                                                    available</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($seasonalRecommendations->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-seasonal')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-seasonal')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-calendar-alt text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No seasonal items available at the moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Shirts Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-tshirt text-teal-600 mr-3"></i> Shirts & Tops
                            </h2>
                            <p class="text-gray-500 mt-1">Casual and formal shirts for every occasion</p>
                        </div>
                        @if ($categoryGroups['shirts']->count() > 4)
                            <a href="{{ route('user.category', 'shirts') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($categoryGroups['shirts']->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-shirts">
                                @foreach ($categoryGroups['shirts'] as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->size ?? 'One Size' }} |
                                                {{ $cloth->color ?? 'Various' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">{{ $cloth->gender ?? 'Unisex' }}</span>
                                                <span class="text-xs text-gray-500">{{ $cloth->quantity }} left</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($categoryGroups['shirts']->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-shirts')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-shirts')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-tshirt text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No shirts available at this collection center.</p>
                        </div>
                    @endif
                </div>

                <!-- Pants Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-shopping-cart text-teal-600 mr-3"></i> Pants & Jeans
                            </h2>
                            <p class="text-gray-500 mt-1">Comfortable pants, jeans, and trousers</p>
                        </div>
                        @if ($categoryGroups['pants']->count() > 4)
                            <a href="{{ route('user.category', 'pants') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($categoryGroups['pants']->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-pants">
                                @foreach ($categoryGroups['pants'] as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-shopping-cart text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->size ?? 'One Size' }} |
                                                {{ $cloth->color ?? 'Various' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">{{ $cloth->gender ?? 'Unisex' }}</span>
                                                <span class="text-xs text-gray-500">{{ $cloth->quantity }} left</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($categoryGroups['pants']->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-pants')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-pants')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-shopping-cart text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No pants or jeans available at this collection center.</p>
                        </div>
                    @endif
                </div>

                <!-- Shoes Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-shoe-prints text-teal-600 mr-3"></i> Shoes & Footwear
                            </h2>
                            <p class="text-gray-500 mt-1">Comfortable footwear for everyday use</p>
                        </div>
                        @if ($categoryGroups['shoes']->count() > 4)
                            <a href="{{ route('user.category', 'shoes') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    @if ($categoryGroups['shoes']->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-shoes">
                                @foreach ($categoryGroups['shoes'] as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-shoe-prints text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">
                                                Size: {{ $cloth->size ?? 'Various' }}
                                            </p>

                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">
                                                    {{ $cloth->gender ?? 'Unisex' }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ $cloth->quantity }} left
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($categoryGroups['shoes']->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-shoes')">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-shoes')">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-shoe-prints text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No shoes available at this collection center.</p>
                        </div>
                    @endif
                </div>

                <!-- Traditional Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-star-of-life text-teal-600 mr-3"></i> Traditional Attire
                            </h2>
                            <p class="text-gray-500 mt-1">Beautiful traditional and ethnic wear</p>
                        </div>
                        @if ($categoryGroups['traditional']->count() > 4)
                            <a href="{{ route('user.category', 'traditional') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($categoryGroups['traditional']->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-traditional">
                                @foreach ($categoryGroups['traditional'] as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-star-of-life text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->size ?? 'One Size' }} |
                                                {{ $cloth->color ?? 'Various' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">{{ $cloth->gender ?? 'Unisex' }}</span>
                                                <span class="text-xs text-gray-500">{{ $cloth->quantity }} left</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($categoryGroups['traditional']->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-traditional')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-traditional')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-star-of-life text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No traditional attire available at this collection center.</p>
                        </div>
                    @endif
                </div>

                <!-- Winter Wear Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-snowflake text-teal-600 mr-3"></i> Winter Wear
                            </h2>
                            <p class="text-gray-500 mt-1">Stay warm with our winter collection</p>
                        </div>
                        @if ($categoryGroups['winter']->count() > 4)
                            <a href="{{ route('user.category', 'winter') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($categoryGroups['winter']->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-winter">
                                @foreach ($categoryGroups['winter'] as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-snowflake text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->size ?? 'One Size' }} |
                                                {{ $cloth->color ?? 'Various' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">{{ $cloth->gender ?? 'Unisex' }}</span>
                                                <span class="text-xs text-gray-500">{{ $cloth->quantity }} left</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($categoryGroups['winter']->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-winter')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-winter')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-snowflake text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No winter wear available at this collection center.</p>
                        </div>
                    @endif
                </div>

                <!-- Dresses Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-female text-teal-600 mr-3"></i> Dresses & Gowns
                            </h2>
                            <p class="text-gray-500 mt-1">Elegant dresses for every occasion</p>
                        </div>
                        @if ($categoryGroups['dresses']->count() > 4)
                            <a href="{{ route('user.category', 'dresses') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($categoryGroups['dresses']->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-dresses">
                                @foreach ($categoryGroups['dresses'] as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-female text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->size ?? 'One Size' }} |
                                                {{ $cloth->color ?? 'Various' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">{{ $cloth->gender ?? 'Unisex' }}</span>
                                                <span class="text-xs text-gray-500">{{ $cloth->quantity }} left</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($categoryGroups['dresses']->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-dresses')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-dresses')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-female text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No dresses available at this collection center.</p>
                        </div>
                    @endif
                </div>

                <!-- Other Items Section -->
                <div class="section-container">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-ellipsis-h text-teal-600 mr-3"></i> More Items
                            </h2>
                            <p class="text-gray-500 mt-1">Explore other available items</p>
                        </div>
                        @if ($categoryGroups['other']->count() > 4)
                            <a href="{{ route('user.category', 'other') }}"
                                class="text-teal-600 hover:text-teal-700 text-sm font-semibold flex items-center gap-1">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                    @if ($categoryGroups['other']->count() > 0)
                        <div class="relative">
                            <div class="scroll-container" id="scroll-other">
                                @foreach ($categoryGroups['other'] as $cloth)
                                    <div class="product-card" onclick="viewDetails({{ $cloth->id }})">
                                        <div class="relative h-48 overflow-hidden">
                                            @if ($cloth->image_path)
                                                <img src="{{ Storage::url($cloth->image_path) }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-800">{{ $cloth->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $cloth->size ?? 'One Size' }} |
                                                {{ $cloth->color ?? 'Various' }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span
                                                    class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">{{ $cloth->gender ?? 'Unisex' }}</span>
                                                <span class="text-xs text-gray-500">{{ $cloth->quantity }} left</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($categoryGroups['other']->count() > 4)
                                <button class="scroll-btn scroll-left" onclick="scrollLeft('scroll-other')"><i
                                        class="fas fa-chevron-left"></i></button>
                                <button class="scroll-btn scroll-right" onclick="scrollRight('scroll-other')"><i
                                        class="fas fa-chevron-right"></i></button>
                            @endif
                        </div>
                    @else
                        <div class="empty-section">
                            <i class="fas fa-ellipsis-h text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No other items available at this collection center.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            let searchTimeout; // Keep but won't be used for auto-search

            // Load recent searches on page load
            function loadRecentSearches() {
                fetch('{{ route('user.recent-searches') }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('recentSearchesContainer');
                        if (data.searches && data.searches.length > 0) {
                            let html = '<span class="text-sm text-gray-500">Recent:</span>';
                            data.searches.forEach(search => {
                                html += `<button onclick="applyRecentSearch('${search.replace(/'/g, "\\'")}')" class="recent-search-tag">
                                ${search}
                            </button>`;
                            });
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '';
                        }
                    });
            }

            function applyRecentSearch(term) {
                document.getElementById('searchInput').value = term;
                applyFilters(); // This will trigger search on button click
            }

            function applyFilters() {
                const search = document.getElementById('searchInput').value;
                const gender = document.getElementById('genderFilter').value;
                const size = document.getElementById('sizeFilter').value;
                const quality = document.getElementById('qualityFilter').value;
                const category = document.getElementById('categoryFilter').value;
                const sortBy = document.getElementById('sortBy').value;

                // Show loading state
                document.getElementById('searchResultsContainer').innerHTML = `
            <div class="text-center py-12 col-span-full">
                <i class="fas fa-spinner fa-spin text-teal-600 text-3xl"></i>
                <p class="text-gray-500 mt-2">Searching...</p>
            </div>
        `;
                document.getElementById('originalSections').style.display = 'none';

                const url =
                    `{{ route('user.search') }}?search=${encodeURIComponent(search)}&gender=${gender}&size=${size}&quality=${quality}&category=${category}&sort_by=${sortBy}`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            console.error('Search error:', data.error);
                            throw new Error(data.error);
                        }
                        displaySearchResults(data);
                        loadRecentSearches();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('searchResultsContainer').innerHTML = `
                <div class="text-center py-12 col-span-full">
                    <i class="fas fa-exclamation-circle text-red-500 text-3xl mb-3"></i>
                    <p class="text-gray-500">Error loading results: ${error.message}</p>
                    <p class="text-gray-400 text-sm mt-2">Please check your connection and try again.</p>
                    <button onclick="applyFilters()" class="mt-4 bg-teal-600 text-white px-4 py-2 rounded-lg">Try Again</button>
                </div>
            `;
                    });
            }

            function displaySearchResults(data) {
                const container = document.getElementById('searchResultsContainer');
                document.getElementById('resultCount').innerHTML = `${data.total} items found`;

                if (data.items.length === 0) {
                    container.innerHTML = `
                <div class="text-center py-12 col-span-full">
                    <i class="fas fa-search text-gray-400 text-5xl mb-3"></i>
                    <p class="text-gray-500 text-lg">No items found matching your criteria.</p>
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or search term.</p>
                </div>
            `;
                    return;
                }

                let html = '';
                data.items.forEach(cloth => {
                    html += `
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition cursor-pointer" onclick="viewDetails(${cloth.id})">
                    <div class="relative h-48 overflow-hidden">
                        ${cloth.image_path ?
                            `<img src="/storage/${cloth.image_path}" class="w-full h-full object-cover hover:scale-110 transition duration-300">` :
                            `<div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                                                                                                                                                <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                                                                                                                                                            </div>`
                        }
                        ${cloth.quantity <= 3 && cloth.quantity > 0 ?
                            `<span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">Only ${cloth.quantity} left</span>` : ''}
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800">${escapeHtml(cloth.name)}</h3>
                        <p class="text-sm text-gray-500">${cloth.category || 'Clothing'}</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500">${cloth.size || 'One Size'}</span>
                            <span class="text-xs text-teal-600">${cloth.quantity} available</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">${cloth.gender || 'Unisex'}</span>
                            ${cloth.quality ? `<span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full capitalize">${cloth.quality}</span>` : ''}
                        </div>
                    </div>
                </div>
            `;
                });
                container.innerHTML = html;
            }

            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function resetFilters() {
                document.getElementById('searchInput').value = '';
                document.getElementById('genderFilter').value = '';
                document.getElementById('sizeFilter').value = '';
                document.getElementById('qualityFilter').value = '';
                document.getElementById('categoryFilter').value = '';
                document.getElementById('sortBy').value = 'latest';

                // Reset size options based on gender (which is now empty)
                updateSizeOptions();

                document.getElementById('originalSections').style.display = 'block';
                document.getElementById('searchResultsContainer').innerHTML = `
        <div class="text-center py-12 col-span-full">
            <i class="fas fa-search text-gray-400 text-5xl mb-3"></i>
            <p class="text-gray-500">Use filters above to search for clothes</p>
        </div>
    `;
                document.getElementById('resultCount').innerHTML = '';
            }

            // REMOVED - No auto-search on input
            // document.getElementById('searchInput').addEventListener('input', function() { ... });

            // Optional: Allow Enter key to trigger search
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });

            function viewDetails(clothId) {
                window.location.href = '/user/cloth/' + clothId;
            }

            function scrollLeft(sectionId) {
                const container = document.getElementById(sectionId);
                if (container) container.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            }

            function scrollRight(sectionId) {
                const container = document.getElementById(sectionId);
                if (container) container.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            }

            function getUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            fetch('{{ route('user.update.location') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        latitude: position.coords.latitude,
                                        longitude: position.coords.longitude
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) window.location.reload();
                                });
                        },
                        function() {
                            alert('Unable to get your location.');
                        }
                    );
                } else {
                    alert('Geolocation is not supported.');
                }
            }

            function refreshRecommendations() {
                // Show loading state
                const container = document.getElementById('scroll-recommended');
                if (container) {
                    container.innerHTML = `
            <div class="flex justify-center items-center w-full py-12">
                <i class="fas fa-spinner fa-spin text-teal-600 text-3xl"></i>
                <span class="ml-3 text-gray-500">Refreshing recommendations...</span>
            </div>
        `;
                }

                fetch('{{ route('user.refresh-recommendations') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to show new recommendations
                            window.location.reload();
                        } else {
                            alert('Failed to refresh recommendations. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error refreshing recommendations');
                    });
            }

            function selectAdmin(adminId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('user.select.admin') }}';
                form.innerHTML = `@csrf <input type="hidden" name="admin_id" value="${adminId}">`;
                document.body.appendChild(form);
                form.submit();
            }

            function clearAdminSelection() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('user.clear.admin') }}';
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }

            // Size options based on gender (same as donation page)
            const sizeOptions = {
                men: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
                women: ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                kids: ['0-3M', '3-6M', '6-9M', '9-12M', '12-18M', '18-24M', '2-3Y', '3-4Y', '4-5Y', '5-6Y', '6-7Y', '7-8Y',
                    '8-9Y', '9-10Y', '10-11Y', '11-12Y', '12-13Y'
                ],
                unisex: ['XS', 'S', 'M', 'L', 'XL', 'XXL']
            };

            // Function to update size options based on selected gender
            function updateSizeOptions() {
                const genderSelect = document.getElementById('genderFilter');
                const sizeSelect = document.getElementById('sizeFilter');
                const selectedGender = genderSelect.value;

                // Clear current options
                sizeSelect.innerHTML = '<option value="">All Sizes</option>';

                // Add options based on selected gender
                if (selectedGender && sizeOptions[selectedGender]) {
                    sizeOptions[selectedGender].forEach(size => {
                        const option = document.createElement('option');
                        option.value = size;
                        option.textContent = size;
                        sizeSelect.appendChild(option);
                    });
                } else if (!selectedGender || selectedGender === '') {
                    // If "All Genders" is selected, show all common sizes
                    const allSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                    allSizes.forEach(size => {
                        const option = document.createElement('option');
                        option.value = size;
                        option.textContent = size;
                        sizeSelect.appendChild(option);
                    });
                }
            }

            // Initialize size options on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateSizeOptions();
            });

            // Load recent searches on page load
            loadRecentSearches();
        </script>
    @endpush
@endsection
