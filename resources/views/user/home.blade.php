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
                <p class="text-3xl font-bold text-teal-600">{{ $clothes->sum('quantity') ?? 0 }}</p>
                <p class="text-gray-500 text-sm">Total clothes in stock</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-hand-holding-heart text-teal-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">My Requests</h3>
                <p class="text-3xl font-bold text-teal-600">{{ $totalRequests ?? 0 }}</p>
                <p class="text-gray-500 text-sm">Pending & completed</p>
            </div>
        </div>

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

        <!-- Nearby Admins Section (Hidden when admin selected) -->
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

                                <!-- Medium Banner Image -->
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

                                    @if (isset($admin->clothes_count) && $admin->clothes_count > 0)
                                        <div
                                            class="absolute bottom-3 left-3 bg-teal-600/90 backdrop-blur-sm rounded-full px-3 py-1 text-xs font-semibold text-white shadow-md z-10">
                                            <i class="fas fa-tshirt mr-1"></i> {{ $admin->clothes_count }} items
                                        </div>
                                    @endif
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
                        @if (!session()->has('user_latitude') && !Auth::user()->latitude)
                            <button onclick="getUserLocation()"
                                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                                <i class="fas fa-location-dot mr-2"></i> Share My Location
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- Clothes Section (E-commerce Grid) -->
        @if (isset($selectedAdmin))
            <div class="mt-8">
                <!-- Header with filters -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-tshirt text-teal-600 mr-3"></i>
                            Available Clothes from {{ $selectedAdmin->name }}
                        </h2>
                        <p class="text-gray-500 mt-1">Browse and request items you need</p>
                    </div>

                    <!-- Filters -->
                    <div class="flex gap-2">
                        <select id="genderFilter"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="all">All Genders</option>
                            <option value="men">👨 Men</option>
                            <option value="women">👩 Women</option>
                            <option value="kids">🧒 Kids</option>
                            <option value="unisex">👥 Unisex</option>
                        </select>
                        <select id="sizeFilter"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="all">All Sizes</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                </div>

                @if ($clothes->count() > 0)
                    <!-- Products Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="clothesGrid">
                        @foreach ($clothes as $cloth)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden cloth-card transition cursor-pointer hover:shadow-xl"
                                data-gender="{{ $cloth->gender }}" data-size="{{ $cloth->size }}"
                                onclick="viewDetails({{ $cloth->id }})">

                                <!-- Cloth Image -->
                                <div class="relative overflow-hidden bg-gray-200">
                                    <div class="aspect-square w-full">
                                        @if ($cloth->image_path)
                                            <img src="{{ Storage::url($cloth->image_path) }}" alt="{{ $cloth->name }}"
                                                class="w-full h-full object-cover hover:scale-110 transition duration-300">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-teal-50 to-teal-100">
                                                <i class="fas fa-tshirt text-teal-400 text-6xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Stock Badge -->
                                    @if ($cloth->quantity > 5)
                                        <span
                                            class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                            In Stock
                                        </span>
                                    @elseif($cloth->quantity > 0)
                                        <span
                                            class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                            Only {{ $cloth->quantity }} left
                                        </span>
                                    @else
                                        <span
                                            class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                            Out of Stock
                                        </span>
                                    @endif

                                    <!-- Gender Badge -->
                                    <span
                                        class="absolute bottom-2 left-2 bg-black/50 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full">
                                        @if ($cloth->gender == 'men')
                                            👨 Men
                                        @elseif($cloth->gender == 'women')
                                            👩 Women
                                        @elseif($cloth->gender == 'kids')
                                            🧒 Kids
                                        @else
                                            👥 Unisex
                                        @endif
                                    </span>
                                </div>

                                <!-- Cloth Details -->
                                <div class="p-4">
                                    <h3 class="font-bold text-lg text-gray-800 mb-1 line-clamp-1">{{ $cloth->name }}</h3>

                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs text-gray-500">Category:</span>
                                        <span class="text-sm text-gray-600">{{ $cloth->category ?? 'General' }}</span>
                                    </div>

                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-500 text-xs">Size:</span>
                                            <span
                                                class="font-semibold text-gray-800 text-sm">{{ $cloth->size ?? 'Various' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-500 text-xs">Color:</span>
                                            <div class="w-4 h-4 rounded-full border"
                                                style="background-color: {{ strtolower($cloth->color) == 'marron' ? '#800020' : strtolower($cloth->color) ?? '#ccc' }}">
                                            </div>
                                            <span
                                                class="font-semibold text-gray-800 text-sm">{{ $cloth->color ?? 'Various' }}</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                                            <span
                                                class="text-sm text-gray-600 capitalize">{{ $cloth->quality ?? 'Good' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-box text-teal-500 text-xs"></i>
                                            <span class="text-sm text-gray-600">{{ $cloth->quantity }} available</span>
                                        </div>
                                    </div>

                                    <button onclick="event.stopPropagation(); viewDetails({{ $cloth->id }})"
                                        class="w-full mt-2 bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg transition font-semibold flex items-center justify-center gap-2">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="hidden bg-gray-100 rounded-xl p-12 text-center">
                        <i class="fas fa-search text-gray-400 text-5xl mb-3 block"></i>
                        <p class="text-gray-500 text-lg">No clothes match your filters.</p>
                        <button onclick="resetFilters()" class="mt-4 text-teal-600 hover:text-teal-700">Reset
                            Filters</button>
                    </div>
                @else
                    <div class="bg-gray-100 rounded-xl p-12 text-center">
                        <i class="fas fa-box-open text-gray-400 text-5xl mb-3 block"></i>
                        <p class="text-gray-500 text-lg">No clothes available at this collection center.</p>
                        <p class="text-gray-400 text-sm mt-2">Please check back later or try another center.</p>
                        <button onclick="clearAdminSelection()"
                            class="mt-4 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-arrow-left mr-2"></i> Browse Other Centers
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // Filter functionality
            const genderFilter = document.getElementById('genderFilter');
            const sizeFilter = document.getElementById('sizeFilter');
            const clothesGrid = document.getElementById('clothesGrid');
            const noResults = document.getElementById('noResults');

            if (genderFilter && sizeFilter && clothesGrid) {
                function filterClothes() {
                    const selectedGender = genderFilter.value;
                    const selectedSize = sizeFilter.value;

                    const clothCards = document.querySelectorAll('.cloth-card');
                    let visibleCount = 0;

                    clothCards.forEach(card => {
                        const cardGender = card.getAttribute('data-gender');
                        const cardSize = card.getAttribute('data-size');

                        let genderMatch = selectedGender === 'all' || cardGender === selectedGender;
                        let sizeMatch = selectedSize === 'all' || cardSize === selectedSize;

                        if (genderMatch && sizeMatch) {
                            card.style.display = '';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (noResults) {
                        if (visibleCount === 0) {
                            noResults.classList.remove('hidden');
                        } else {
                            noResults.classList.add('hidden');
                        }
                    }
                }

                genderFilter.addEventListener('change', filterClothes);
                sizeFilter.addEventListener('change', filterClothes);
            }

            function resetFilters() {
                if (genderFilter) genderFilter.value = 'all';
                if (sizeFilter) sizeFilter.value = 'all';
                filterClothes();
            }

            function viewDetails(clothId) {
                window.location.href = '/user/cloth/' + clothId;
            }

            function getUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;

                            fetch('{{ route('user.update.location') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        latitude: latitude,
                                        longitude: longitude
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.reload();
                                    }
                                });
                        },
                        function(error) {
                            alert('Unable to get your location. Please enable location services.');
                        }
                    );
                } else {
                    alert('Geolocation is not supported by your browser.');
                }
            }

            function selectAdmin(adminId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('user.select.admin') }}';
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="admin_id" value="${adminId}">
                `;
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
        </script>
    @endpush
@endsection
