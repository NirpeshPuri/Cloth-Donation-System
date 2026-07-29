@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 42px;
            padding: 5px;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 30px;
        }

        .donor-info {
            background: #f8fafc;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-top: 0.5rem;
            display: none;
        }

        .donor-info.visible {
            display: block;
        }

        .no-donor-badge {
            display: inline-block;
            background: #e5e7eb;
            color: #6b7280;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .current-image {
            position: relative;
            display: inline-block;
        }

        .current-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
        }

        .current-image .badge {
            position: absolute;
            bottom: -8px;
            right: -8px;
            background: #10b981;
            color: white;
            font-size: 0.6rem;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
        }

        .donor-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #10b981;
        }

        .donor-avatar-anonymous {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #9ca3af;
        }

        .donor-avatar-anonymous svg {
            width: 24px;
            height: 24px;
            color: #6b7280;
        }
    </style>
@endpush

@section('title', 'Edit Inventory Item')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Edit Inventory Item</h1>
                    <p class="text-teal-100">Update clothing item details</p>
                </div>
                <a href="{{ route('admin.inventory.index') }}"
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Inventory
                </a>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.inventory.update', $cloth->id) }}"
                        enctype="multipart/form-data" id="inventoryForm">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Cloth Details</h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Cloth Name *</label>
                                <input type="text" name="name" value="{{ old('name', $cloth->name) }}" required
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Category</label>
                                <select name="category"
                                    class="form-select w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">Select Category</option>
                                    <option value="Shirt"
                                        {{ old('category', $cloth->category) == 'Shirt' ? 'selected' : '' }}>👕 Shirt
                                    </option>
                                    <option value="T-Shirt"
                                        {{ old('category', $cloth->category) == 'T-Shirt' ? 'selected' : '' }}>👕 T-Shirt
                                    </option>
                                    <option value="Jeans"
                                        {{ old('category', $cloth->category) == 'Jeans' ? 'selected' : '' }}>👖 Jeans
                                    </option>
                                    <option value="Pants"
                                        {{ old('category', $cloth->category) == 'Pants' ? 'selected' : '' }}>👖 Pants
                                    </option>
                                    <option value="Jacket"
                                        {{ old('category', $cloth->category) == 'Jacket' ? 'selected' : '' }}>🧥 Jacket
                                    </option>
                                    <option value="Sweater"
                                        {{ old('category', $cloth->category) == 'Sweater' ? 'selected' : '' }}>🧥 Sweater
                                    </option>
                                    <option value="Dress"
                                        {{ old('category', $cloth->category) == 'Dress' ? 'selected' : '' }}>👗 Dress
                                    </option>
                                    <option value="Saree"
                                        {{ old('category', $cloth->category) == 'Saree' ? 'selected' : '' }}>👘 Saree
                                    </option>
                                    <option value="Kurta"
                                        {{ old('category', $cloth->category) == 'Kurta' ? 'selected' : '' }}>👘 Kurta
                                    </option>
                                    <option value="Traditional"
                                        {{ old('category', $cloth->category) == 'Traditional' ? 'selected' : '' }}>👘
                                        Traditional</option>
                                    <option value="Other"
                                        {{ old('category', $cloth->category) == 'Other' ? 'selected' : '' }}>📦 Other
                                    </option>
                                </select>
                                @error('category')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Gender</label>
                                <select name="gender" id="genderSelect"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">Select Gender</option>
                                    <option value="men" {{ old('gender', $cloth->gender) == 'men' ? 'selected' : '' }}>
                                        👨 Men</option>
                                    <option value="women"
                                        {{ old('gender', $cloth->gender) == 'women' ? 'selected' : '' }}>👩 Women</option>
                                    <option value="kids" {{ old('gender', $cloth->gender) == 'kids' ? 'selected' : '' }}>
                                        🧒 Kids</option>
                                    <option value="unisex"
                                        {{ old('gender', $cloth->gender) == 'unisex' ? 'selected' : '' }}>👥 Unisex
                                    </option>
                                </select>
                                @error('gender')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Size</label>
                                <select name="size" id="sizeSelect"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">Select Gender First</option>
                                </select>
                                @error('size')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Color</label>
                                <select name="color" id="colorSelect"
                                    class="color-select w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="{{ old('color', $cloth->color) }}">
                                        {{ old('color', $cloth->color) ?: 'Search for a color...' }}</option>
                                    <optgroup label="🔴 Red Shades">
                                        <option value="Red">🔴 Red</option>
                                        <option value="Light Red">🔴 Light Red</option>
                                        <option value="Dark Red">🔴 Dark Red</option>
                                        <option value="Crimson">❤️ Crimson</option>
                                        <option value="Maroon">🟤 Maroon</option>
                                        <option value="Burgundy">🍷 Burgundy</option>
                                        <option value="Rose">🌹 Rose</option>
                                        <option value="Ruby">💎 Ruby</option>
                                    </optgroup>
                                    <optgroup label="🔵 Blue Shades">
                                        <option value="Blue">🔵 Blue</option>
                                        <option value="Light Blue">🔵 Light Blue</option>
                                        <option value="Dark Blue">🔵 Dark Blue</option>
                                        <option value="Sky Blue">☁️ Sky Blue</option>
                                        <option value="Baby Blue">👶 Baby Blue</option>
                                        <option value="Navy Blue">⚓ Navy Blue</option>
                                        <option value="Royal Blue">👑 Royal Blue</option>
                                        <option value="Cyan">💙 Cyan</option>
                                        <option value="Turquoise">💚 Turquoise</option>
                                        <option value="Sapphire">💙 Sapphire</option>
                                        <option value="Indigo">💜 Indigo</option>
                                    </optgroup>
                                    <optgroup label="🟢 Green Shades">
                                        <option value="Green">🟢 Green</option>
                                        <option value="Light Green">🟢 Light Green</option>
                                        <option value="Dark Green">🟢 Dark Green</option>
                                        <option value="Lime">💚 Lime</option>
                                        <option value="Olive">🫒 Olive</option>
                                        <option value="Mint">🌿 Mint</option>
                                        <option value="Emerald">💚 Emerald</option>
                                        <option value="Teal">💙 Teal</option>
                                        <option value="Sage">🌿 Sage</option>
                                    </optgroup>
                                    <optgroup label="🟡 Yellow & Orange">
                                        <option value="Yellow">🟡 Yellow</option>
                                        <option value="Gold">✨ Gold</option>
                                        <option value="Orange">🟠 Orange</option>
                                        <option value="Coral">🪸 Coral</option>
                                        <option value="Peach">🍑 Peach</option>
                                        <option value="Amber">🟠 Amber</option>
                                    </optgroup>
                                    <optgroup label="🟣 Purple & Pink">
                                        <option value="Purple">🟣 Purple</option>
                                        <option value="Lavender">🪻 Lavender</option>
                                        <option value="Violet">🟣 Violet</option>
                                        <option value="Magenta">💜 Magenta</option>
                                        <option value="Pink">🌸 Pink</option>
                                        <option value="Hot Pink">💖 Hot Pink</option>
                                    </optgroup>
                                    <optgroup label="🟤 Brown & Neutral">
                                        <option value="Brown">🟤 Brown</option>
                                        <option value="Beige">🧵 Beige</option>
                                        <option value="Tan">🏖️ Tan</option>
                                        <option value="Chocolate">🍫 Chocolate</option>
                                        <option value="Khaki">🟤 Khaki</option>
                                        <option value="Grey">◻️ Grey</option>
                                        <option value="Silver">⭐ Silver</option>
                                        <option value="Charcoal">🖤 Charcoal</option>
                                        <option value="White">⚪ White</option>
                                        <option value="Black">⚫ Black</option>
                                    </optgroup>
                                    <optgroup label="🌈 Patterns & Other">
                                        <option value="Multicolor">🌈 Multicolor</option>
                                        <option value="Printed">🎨 Printed</option>
                                        <option value="Tie Dye">🎨 Tie Dye</option>
                                        <option value="Floral">🌸 Floral</option>
                                        <option value="Striped">📏 Striped</option>
                                        <option value="Checkered">🔲 Checkered</option>
                                        <option value="Denim">👖 Denim</option>
                                    </optgroup>
                                </select>
                                @error('color')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Quantity *</label>
                                <input type="number" name="quantity" value="{{ old('quantity', $cloth->quantity) }}"
                                    min="0" required
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                @error('quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Quality</label>
                                <select name="quality"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">Select Quality</option>
                                    <option value="new"
                                        {{ old('quality', $cloth->quality) == 'new' ? 'selected' : '' }}>✨ New (With tags)
                                    </option>
                                    <option value="like_new"
                                        {{ old('quality', $cloth->quality) == 'like_new' ? 'selected' : '' }}>👍 Like New
                                    </option>
                                    <option value="good"
                                        {{ old('quality', $cloth->quality) == 'good' ? 'selected' : '' }}>✅ Good</option>
                                    <option value="fair"
                                        {{ old('quality', $cloth->quality) == 'fair' ? 'selected' : '' }}>⚠️ Fair</option>
                                </select>
                                @error('quality')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Status *</label>
                                <select name="status" required
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="available"
                                        {{ old('status', $cloth->status) == 'available' ? 'selected' : '' }}>🟢 Available
                                    </option>
                                    <option value="reserved"
                                        {{ old('status', $cloth->status) == 'reserved' ? 'selected' : '' }}>🟡 Reserved
                                    </option>
                                    <option value="donated"
                                        {{ old('status', $cloth->status) == 'donated' ? 'selected' : '' }}>🟣 Donated
                                    </option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Donor</label>
                                <select name="donor_id" id="donorSelect" class="donor-select w-full">
                                    <option value=""></option>
                                </select>
                                <div id="donorInfo" class="donor-info mt-2">
                                    <div class="flex items-center gap-3">
                                        <div id="donorAvatar" class="flex-shrink-0">
                                            <!-- Avatar will be inserted here -->
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800" id="donorName"></p>
                                            <p class="text-sm text-gray-600" id="donorEmail"></p>
                                            <p class="text-sm text-gray-600" id="donorPhone"></p>
                                        </div>
                                        <button type="button" onclick="clearDonor()"
                                            class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-times"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle"></i>
                                    Leave empty if no donor is associated (will be assigned as Anonymous)
                                </p>
                                @error('donor_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Description</label>
                                <textarea name="description" rows="3"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description', $cloth->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2 text-sm">Image (Optional)</label>

                                @if ($cloth->image_path)
                                    <div class="mb-3 flex items-center gap-3">
                                        <div class="current-image">
                                            <img src="{{ Storage::url($cloth->image_path) }}" alt="Current Image">
                                            <span class="badge">Current</span>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Current image</p>
                                            <button type="button" onclick="removeImage()"
                                                class="text-red-500 hover:text-red-700 text-sm mt-1">
                                                <i class="fas fa-times"></i> Remove image
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" name="image" accept="image/*"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700">
                                <div id="imagePreview" class="mt-2 hidden">
                                    <img id="preview" src="#" alt="Preview"
                                        class="w-20 h-20 object-cover rounded-lg">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Max size: 2MB.
                                    Allowed: JPG, PNG, GIF</p>
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <button type="submit"
                                class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition">
                                <i class="fas fa-save mr-2"></i> Update Item
                            </button>
                            <a href="{{ route('admin.inventory.index') }}"
                                class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold transition">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-teal-50 rounded-xl p-6 mb-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Quick Tips</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-info-circle text-teal-600 mt-1"></i>
                            <span class="text-sm">Update item details as needed</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-search text-teal-600 mt-1"></i>
                            <span class="text-sm">Search donor by name, email, or phone</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-image text-teal-600 mt-1"></i>
                            <span class="text-sm">Upload a new image to replace current</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-user-secret text-teal-600 mt-1"></i>
                            <span class="text-sm">Leave donor empty for anonymous donation</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-blue-50 rounded-xl p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Donor Status</h3>
                    <div id="donorStatus" class="text-center py-4">
                        @if ($cloth->donor && $cloth->donor->email === 'anonymous@donation.com')
                            <div class="bg-gray-100 text-gray-600 p-3 rounded-lg">
                                <p class="font-semibold">🤫 Anonymous Donor</p>
                                <span class="inline-block mt-1 text-xs bg-gray-200 px-2 py-1 rounded-full">
                                    <i class="fas fa-user-secret"></i> Anonymous
                                </span>
                            </div>
                        @elseif ($cloth->donor)
                            <div class="bg-green-100 text-green-800 p-3 rounded-lg">
                                <p class="font-semibold">{{ $cloth->donor->name }}</p>
                                <p class="text-sm">{{ $cloth->donor->email }}</p>
                                <span class="inline-block mt-1 text-xs bg-green-200 px-2 py-1 rounded-full">
                                    <i class="fas fa-check-circle"></i> Donor Selected
                                </span>
                            </div>
                        @else
                            <span class="no-donor-badge">
                                <i class="fas fa-user-slash mr-1"></i> No Donor Selected
                            </span>
                        @endif
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-xl p-6 mt-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Item Information</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-semibold">ID:</span> #{{ $cloth->id }}</p>
                        <p><span class="font-semibold">Created:</span> {{ $cloth->created_at->format('M d, Y') }}</p>
                        <p><span class="font-semibold">Last Updated:</span> {{ $cloth->updated_at->format('M d, Y') }}</p>
                        <p><span class="font-semibold">Total Quantity:</span> {{ $cloth->quantity }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // ========== SIZE OPTIONS BASED ON GENDER ==========
            const sizeOptions = {
                men: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
                women: ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                kids: ['0-3M', '3-6M', '6-9M', '9-12M', '12-18M', '18-24M', '2-3Y', '3-4Y', '4-5Y', '5-6Y',
                    '6-7Y', '7-8Y', '8-9Y', '9-10Y', '10-11Y', '11-12Y', '12-13Y'
                ],
                unisex: ['XS', 'S', 'M', 'L', 'XL', 'XXL']
            };

            // Update size options on gender change
            function updateSizeOptions(gender) {
                const sizeSelect = document.getElementById('sizeSelect');
                const selectedValue = '{{ old('size', $cloth->size) }}';

                // Clear current options
                sizeSelect.innerHTML = '<option value="">Select Size</option>';

                if (gender && sizeOptions[gender]) {
                    sizeOptions[gender].forEach(size => {
                        const option = document.createElement('option');
                        option.value = size;
                        option.textContent = size;
                        if (size === selectedValue) {
                            option.selected = true;
                        }
                        sizeSelect.appendChild(option);
                    });
                    sizeSelect.disabled = false;
                } else {
                    sizeSelect.disabled = true;
                }
            }

            // Listen for gender change
            document.getElementById('genderSelect').addEventListener('change', function() {
                updateSizeOptions(this.value);
            });

            // Initialize on page load
            const initialGender = document.getElementById('genderSelect').value;
            if (initialGender) {
                updateSizeOptions(initialGender);
            }

            // ========== COLOR SELECT WITH SEARCH ==========
            $('#colorSelect').select2({
                placeholder: "Search for a color...",
                allowClear: true,
                width: '100%'
            });

            // ========== DONOR SEARCH WITH SELECT2 AJAX ==========
            const donorSelect = $('#donorSelect');

            donorSelect.select2({
                placeholder: "Search for a donor by name, email, or phone...",
                allowClear: true,
                width: '100%',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('admin.inventory.search-donor') }}',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        if (data.error) {
                            return {
                                results: []
                            };
                        }
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                templateResult: formatDonorResult,
                templateSelection: formatDonorSelection
            });

            function formatDonorResult(donor) {
                if (donor.loading) {
                    return donor.text;
                }

                return $(
                    '<div class="flex items-center">' +
                    '<div class="mr-2">👤</div>' +
                    '<div>' +
                    '<div class="font-semibold">' + donor.text + '</div>' +
                    '</div>' +
                    '</div>'
                );
            }

            function formatDonorSelection(donor) {
                if (!donor.id) {
                    return 'Search for a donor...';
                }

                if (donor.id) {
                    fetchDonorDetails(donor.id);
                }

                return donor.text || 'Search for a donor...';
            }

            // ========== FETCH DONOR DETAILS ==========
            function fetchDonorDetails(donorId) {
                if (!donorId) {
                    hideDonorInfo();
                    updateDonorStatus(null);
                    return;
                }

                $.ajax({
                    url: '{{ route('admin.inventory.get-donor', ['id' => ':id']) }}'.replace(':id',
                        donorId),
                    type: 'GET',
                    success: function(data) {
                        if (data) {
                            showDonorInfo(data);
                            updateDonorStatus(data);
                        } else {
                            hideDonorInfo();
                            updateDonorStatus(null);
                        }
                    },
                    error: function() {
                        hideDonorInfo();
                        updateDonorStatus(null);
                    }
                });
            }

            function showDonorInfo(donor) {
                $('#donorName').text(donor.name);
                $('#donorEmail').text('📧 ' + (donor.email || 'No email'));
                $('#donorPhone').text('📱 ' + (donor.phone || 'No phone'));

                // Show avatar
                const avatarContainer = $('#donorAvatar');
                if (donor.is_anonymous) {
                    avatarContainer.html(`
                        <div class="donor-avatar-anonymous">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    `);
                } else if (donor.profile_photo) {
                    avatarContainer.html(`<img src="${donor.profile_photo}" class="donor-avatar" alt="Donor">`);
                } else {
                    avatarContainer.html(`
                        <div class="donor-avatar-anonymous" style="background: #10b981;">
                            <span style="color: white; font-size: 20px; font-weight: bold;">
                                ${donor.name.charAt(0).toUpperCase()}
                            </span>
                        </div>
                    `);
                }

                $('#donorInfo').addClass('visible');
            }

            function hideDonorInfo() {
                $('#donorInfo').removeClass('visible');
                $('#donorAvatar').html('');
            }

            function updateDonorStatus(donor) {
                const statusDiv = $('#donorStatus');
                if (donor) {
                    if (donor.is_anonymous) {
                        statusDiv.html(`
                            <div class="bg-gray-100 text-gray-600 p-3 rounded-lg">
                                <p class="font-semibold">🤫 Anonymous Donor</p>
                                <span class="inline-block mt-1 text-xs bg-gray-200 px-2 py-1 rounded-full">
                                    <i class="fas fa-user-secret"></i> Anonymous
                                </span>
                            </div>
                        `);
                    } else {
                        statusDiv.html(`
                            <div class="bg-green-100 text-green-800 p-3 rounded-lg">
                                <p class="font-semibold">${donor.name}</p>
                                <p class="text-sm">${donor.email}</p>
                                <span class="inline-block mt-1 text-xs bg-green-200 px-2 py-1 rounded-full">
                                    <i class="fas fa-check-circle"></i> Donor Selected
                                </span>
                            </div>
                        `);
                    }
                } else {
                    statusDiv.html(`
                        <span class="no-donor-badge">
                            <i class="fas fa-user-slash mr-1"></i> No Donor Selected
                        </span>
                    `);
                }
            }

            // ========== HANDLE DONOR SELECTION CHANGE ==========
            donorSelect.on('change', function(e) {
                const donorId = $(this).val();
                if (!donorId) {
                    hideDonorInfo();
                    updateDonorStatus(null);
                }
            });

            // ========== CLEAR DONOR FUNCTION ==========
            window.clearDonor = function() {
                donorSelect.val(null).trigger('change');
                hideDonorInfo();
                updateDonorStatus(null);
            };

            // ========== IMAGE PREVIEW ==========
            document.querySelector('input[name="image"]').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const previewDiv = document.getElementById('imagePreview');
                const previewImg = document.getElementById('preview');

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

            // ========== LOAD EXISTING DONOR ON EDIT ==========
            @if ($cloth->donor_id)
                const existingDonorId = {{ $cloth->donor_id }};
                if (existingDonorId) {
                    // Check if donor is anonymous
                    @if ($cloth->donor && $cloth->donor->email === 'anonymous@donation.com')
                        // Set anonymous donor info
                        fetchDonorDetails(existingDonorId);
                    @else
                        // Set the select2 value for real donor
                        const donorOption = new Option(
                            '{{ $cloth->donor->name ?? '' }} - {{ $cloth->donor->email ?? '' }}',
                            existingDonorId,
                            true,
                            true
                        );
                        $('#donorSelect').append(donorOption).trigger('change');
                        // Fetch and display donor details
                        fetchDonorDetails(existingDonorId);
                    @endif
                }
            @endif

            // ========== PRESERVE OLD VALUES ==========
            @if (old('donor_id'))
                const oldDonorId = {{ old('donor_id') }};
                if (oldDonorId) {
                    fetchDonorDetails(oldDonorId);
                }
            @endif
        });

        // ========== REMOVE IMAGE FUNCTION ==========
        function removeImage() {
            if (confirm('Are you sure you want to remove this image?')) {
                // Add a hidden input to indicate image removal
                const form = document.getElementById('inventoryForm');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_image';
                input.value = '1';
                form.appendChild(input);

                // Hide the current image
                const currentImage = document.querySelector('.current-image');
                if (currentImage) {
                    currentImage.style.display = 'none';
                }
            }
        }
    </script>
@endpush
