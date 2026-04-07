@extends('layouts.master')

@section('title', $cloth->name . ' - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Collection
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="grid md:grid-cols-2 gap-8 p-6">
                <!-- Product Images Section -->
                <div>
                    <!-- Main Image -->
                    <div class="bg-gray-100 rounded-xl overflow-hidden mb-4">
                        @if ($cloth->image_path)
                            <img id="mainImage" src="{{ Storage::url($cloth->image_path) }}" alt="{{ $cloth->name }}"
                                class="w-full h-96 object-cover cursor-pointer"
                                onclick="openImageModal('{{ Storage::url($cloth->image_path) }}')">
                        @else
                            <div
                                class="w-full h-96 flex items-center justify-center bg-gradient-to-br from-teal-50 to-teal-100">
                                <i class="fas fa-tshirt text-teal-400 text-8xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    <div class="flex gap-3">
                        @if ($cloth->image_path)
                            <div class="w-20 h-20 rounded-lg overflow-hidden border-2 border-teal-500 cursor-pointer"
                                onclick="changeImage('{{ Storage::url($cloth->image_path) }}')">
                                <img src="{{ Storage::url($cloth->image_path) }}" alt="Thumb"
                                    class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info Section -->
                <div>
                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        @if ($cloth->quantity > 5)
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">✓ In Stock</span>
                        @elseif($cloth->quantity > 0)
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">⚠️ Only
                                {{ $cloth->quantity }} left</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">✗ Out of Stock</span>
                        @endif

                        <span class="bg-teal-100 text-teal-700 text-xs px-2 py-1 rounded-full capitalize">
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

                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full capitalize">
                            ⭐ {{ $cloth->quality ?? 'Good' }} Quality
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $cloth->name }}</h1>

                    <!-- Category -->
                    <p class="text-gray-500 mb-4">
                        <i class="fas fa-tag mr-1"></i> {{ $cloth->category ?? 'General Clothing' }}
                    </p>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-gray-500 text-sm">Size</p>
                            <p class="font-semibold text-gray-800">{{ $cloth->size ?? 'Various' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Color</p>
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full border"
                                    style="background-color: {{ strtolower($cloth->color) == 'marron' ? '#800020' : strtolower($cloth->color) ?? '#ccc' }}">
                                </div>
                                <p class="font-semibold text-gray-800">{{ $cloth->color ?? 'Various' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Quantity Available</p>
                            <p class="font-semibold text-teal-600">{{ $cloth->quantity }} pieces</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Condition</p>
                            <p class="font-semibold text-gray-800 capitalize">{{ $cloth->quality ?? 'Good' }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($cloth->description)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-2">Description</h3>
                            <p class="text-gray-600">{{ $cloth->description }}</p>
                        </div>
                    @endif

                    <!-- Collection Center Info -->
                    <div class="mb-6 p-4 bg-teal-50 rounded-lg">
                        <p class="text-gray-600 text-sm">Collection Center</p>
                        <p class="font-semibold text-teal-800">{{ $cloth->admin->name ?? 'N/A' }}</p>
                        <p class="text-gray-500 text-sm">{{ $cloth->admin->address ?? '' }}</p>
                        @if (isset($cloth->admin->distance))
                            <p class="text-xs text-teal-600 mt-1">{{ $cloth->admin->distance }} km away</p>
                        @endif
                    </div>

                    <!-- Quantity Selector and Add to Cart -->
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" id="decrementBtn" class="px-4 py-2 text-gray-600 hover:bg-gray-100"
                                {{ $cloth->quantity < 1 ? 'disabled' : '' }}>
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $cloth->quantity }}"
                                class="w-16 text-center border-0 focus:outline-none" readonly>
                            <button type="button" id="incrementBtn" class="px-4 py-2 text-gray-600 hover:bg-gray-100"
                                {{ $cloth->quantity < 1 ? 'disabled' : '' }}>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <button onclick="addToCart({{ $cloth->id }})"
                            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg transition font-semibold flex items-center justify-center gap-2"
                            {{ $cloth->quantity < 1 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>

                    <!-- Request Button -->
                    <button onclick="requestNow({{ $cloth->id }})"
                        class="w-full border-2 border-teal-600 text-teal-600 hover:bg-teal-50 py-3 rounded-lg transition font-semibold flex items-center justify-center gap-2"
                        {{ $cloth->quantity < 1 ? 'disabled' : '' }}>
                        <i class="fas fa-hand-holding-heart"></i> Request Now
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if (isset($relatedClothes) && $relatedClothes->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">You May Also Like</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedClothes as $related)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition cursor-pointer"
                            onclick="window.location.href='{{ route('user.cloth.detail', $related->id) }}'">
                            <div class="h-48 overflow-hidden">
                                @if ($related->image_path)
                                    <img src="{{ Storage::url($related->image_path) }}" alt="{{ $related->name }}"
                                        class="w-full h-full object-cover hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800">{{ $related->name }}</h3>
                                <p class="text-gray-500 text-sm">{{ $related->category ?? 'Clothing' }}</p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-xs text-gray-500">Size: {{ $related->size ?? 'Various' }}</span>
                                    <span class="text-xs text-teal-600">{{ $related->quantity }} available</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden items-center justify-center z-50"
        onclick="closeImageModal()">
        <div class="max-w-4xl max-h-screen p-4">
            <img id="modalImage" src="" alt="Full size" class="max-w-full max-h-screen rounded-lg">
            <button class="absolute top-4 right-4 bg-white rounded-full p-2 text-gray-800 hover:bg-gray-200"
                onclick="closeImageModal()">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            // Quantity selector
            const quantityInput = document.getElementById('quantity');
            const decrementBtn = document.getElementById('decrementBtn');
            const incrementBtn = document.getElementById('incrementBtn');
            const maxQuantity = {{ $cloth->quantity }};

            if (decrementBtn && incrementBtn && quantityInput) {
                decrementBtn.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                });

                incrementBtn.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue < maxQuantity) {
                        quantityInput.value = currentValue + 1;
                    }
                });
            }

            // Image modal functions
            function openImageModal(imageUrl) {
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('imageModal').classList.remove('hidden');
                document.getElementById('imageModal').classList.add('flex');
            }

            function closeImageModal() {
                document.getElementById('imageModal').classList.add('hidden');
                document.getElementById('imageModal').classList.remove('flex');
            }

            function changeImage(imageUrl) {
                document.getElementById('mainImage').src = imageUrl;
            }

            // Add to cart function
            function addToCart(clothId) {
                const quantity = document.getElementById('quantity').value;

                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cloth_id: clothId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Item added to cart!', 'success');
                            updateCartCount();
                        } else {
                            showNotification(data.message || 'Error adding to cart', 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Error adding to cart', 'error');
                    });
            }

            // Request now function
            function requestNow(clothId) {
                const quantity = document.getElementById('quantity').value;

                if (confirm(`Would you like to request ${quantity} item(s)?`)) {
                    fetch('{{ route('user.request.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                cloth_id: clothId,
                                quantity: quantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Request submitted successfully!', 'success');
                                setTimeout(() => {
                                    window.location.href = '{{ route('user.my-requests') }}';
                                }, 1500);
                            } else {
                                showNotification(data.message || 'Error submitting request', 'error');
                            }
                        })
                        .catch(error => {
                            showNotification('Error submitting request', 'error');
                        });
                }
            }

            // Notification function
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className =
                    `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} transition transform duration-300`;
                notification.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }

            // Update cart count in navbar
            function updateCartCount() {
                fetch('{{ route('cart.count') }}')
                    .then(response => response.json())
                    .then(data => {
                        const cartBadge = document.getElementById('cartCount');
                        if (cartBadge) {
                            if (data.count > 0) {
                                cartBadge.textContent = data.count;
                                cartBadge.classList.remove('hidden');
                            } else {
                                cartBadge.classList.add('hidden');
                            }
                        }
                    });
            }
        </script>
    @endpush
@endsection
