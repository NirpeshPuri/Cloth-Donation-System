@extends('layouts.master')

@section('title', 'Donate Clothes - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Donate Clothes</h1>
            <p class="text-teal-100">Your unused clothes can change someone's life. Add multiple items to your donation.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
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

                    <!-- Location Info -->
                    @if (isset($userLatitude) && isset($userLongitude))
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-700">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Showing collection centers near your location
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.donate.store') }}" enctype="multipart/form-data"
                        id="donationForm">
                        @csrf

                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Clothes Items</h3>

                        <div id="itemsContainer"></div>

                        <div class="mt-4 mb-6">
                            <button type="button" id="addMoreBtn"
                                class="w-full border-2 border-dashed border-teal-300 hover:border-teal-500 text-teal-600 hover:text-teal-700 py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Add Another Item
                            </button>
                        </div>

                        <!-- Pickup Details -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pickup Details</h3>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Select Collection Center *</label>
                                <select name="admin_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">Select a center near you</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}"
                                            {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }} - {{ $admin->address }}
                                            @if (isset($admin->distance))
                                                ({{ $admin->distance }} km away)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Pickup Address (Optional)</label>
                                <textarea name="pickup_address" rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                    placeholder="Your address for pickup if different from profile">{{ old('pickup_address') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Additional Notes</label>
                                <textarea name="notes" rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                    placeholder="Any special instructions?">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition">
                            <i class="fas fa-gift mr-2"></i> Donate All Items
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-teal-50 rounded-xl p-6 mb-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Why Donate?</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2"><i class="fas fa-heart text-teal-600 mt-1"></i><span
                                class="text-sm">Help someone in need</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-leaf text-teal-600 mt-1"></i><span
                                class="text-sm">Reduce textile waste</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-smile text-teal-600 mt-1"></i><span
                                class="text-sm">Feel good about giving</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-recycle text-teal-600 mt-1"></i><span
                                class="text-sm">Give clothes a second life</span></li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">What We Accept</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Men's clothes</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Women's clothes</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Children's clothes</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Winter wear & jackets</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Traditional attire</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let itemCount = 0;
            const container = document.getElementById('itemsContainer');

            const sizeOptions = {
                men: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
                women: ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                kids: ['0-3M', '3-6M', '6-9M', '9-12M', '12-18M', '18-24M', '2-3Y', '3-4Y', '4-5Y', '5-6Y', '6-7Y', '7-8Y',
                    '8-9Y', '9-10Y', '10-11Y', '11-12Y', '12-13Y'
                ],
                unisex: ['XS', 'S', 'M', 'L', 'XL', 'XXL']
            };

            function updateSizeOptions(selectElement, gender) {
                const itemCard = selectElement.closest('.item-card');
                const sizeSelect = itemCard.querySelector('.size-select');
                sizeSelect.innerHTML = '<option value="">Select Size</option>';

                if (gender && sizeOptions[gender]) {
                    sizeOptions[gender].forEach(size => {
                        const option = document.createElement('option');
                        option.value = size;
                        option.textContent = size;
                        sizeSelect.appendChild(option);
                    });
                    sizeSelect.disabled = false;
                } else {
                    sizeSelect.disabled = true;
                }
            }

            function addNewItem() {
                const itemId = itemCount;
                const itemCard = document.createElement('div');
                itemCard.className = 'item-card bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200';
                itemCard.setAttribute('data-id', itemId);

                itemCard.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-700">Item ${itemCount + 1}</h4>
                <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 text-sm">
                    <i class="fas fa-trash mr-1"></i> Remove
                </button>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Cloth Name *</label>
                    <input type="text" name="items[${itemCount}][cloth_name]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Cloth Type</label>
                    <select name="items[${itemCount}][cloth_type]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Type</option>
                        <option value="Shirt">Shirt</option>
                        <option value="T-Shirt">T-Shirt</option>
                        <option value="Jeans">Jeans</option>
                        <option value="Pants">Pants</option>
                        <option value="Jacket">Jacket</option>
                        <option value="Sweater">Sweater</option>
                        <option value="Dress">Dress</option>
                        <option value="Saree">Saree</option>
                        <option value="Kurta">Kurta</option>
                        <option value="Traditional">Traditional</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Gender *</label>
                    <select name="items[${itemCount}][gender]" required class="gender-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Gender</option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="kids">Kids</option>
                        <option value="unisex">Unisex</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Size *</label>
                    <select name="items[${itemCount}][size]" required class="size-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" disabled>
                        <option value="">Select Gender First</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Color</label>
                    <input type="text" name="items[${itemCount}][color]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Red, Blue, Black, etc.">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Quantity *</label>
                    <input type="number" name="items[${itemCount}][quantity]" value="1" min="1" max="100" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Quality *</label>
                    <select name="items[${itemCount}][quality]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Quality</option>
                        <option value="new">New (With tags)</option>
                        <option value="like_new">Like New (Worn 1-2 times)</option>
                        <option value="good">Good (Minor wear)</option>
                        <option value="fair">Fair (Visible wear, still usable)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Description</label>
                    <textarea name="items[${itemCount}][description]" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Any special notes about this item?"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Image (Optional)</label>
                    <input type="file" name="items[${itemCount}][image]" accept="image/*"
                        class="image-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700">
                    <div class="image-preview hidden mt-2">
                        <img src="#" alt="Preview" class="w-20 h-20 object-cover rounded-lg">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed: JPG, PNG, GIF</p>
                </div>
            </div>
        `;

                // Add event listeners
                const genderSelect = itemCard.querySelector('.gender-select');
                genderSelect.addEventListener('change', function() {
                    updateSizeOptions(this, this.value);
                });

                const imageInput = itemCard.querySelector('.image-input');
                const previewDiv = itemCard.querySelector('.image-preview');
                const previewImg = itemCard.querySelector('.image-preview img');

                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewDiv.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewDiv.classList.add('hidden');
                    }
                });

                const removeBtn = itemCard.querySelector('.remove-item-btn');
                removeBtn.addEventListener('click', function() {
                    itemCard.remove();
                    updateItemNumbers();
                });

                container.appendChild(itemCard);
                itemCount++;
            }

            function updateItemNumbers() {
                const items = document.querySelectorAll('.item-card');
                items.forEach((item, index) => {
                    const title = item.querySelector('h4');
                    if (title) {
                        title.textContent = `Item ${index + 1}`;
                    }

                    const inputs = item.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                addNewItem();
            });

            document.getElementById('addMoreBtn').addEventListener('click', addNewItem);
        </script>
    @endpush
@endsection
