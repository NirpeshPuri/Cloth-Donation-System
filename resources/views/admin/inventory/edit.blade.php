@extends('layouts.admin')

@section('title', 'Edit Inventory Item')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.inventory.index') }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Inventory
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 max-w-3xl mx-auto">
            <div class="gradient-bg rounded-xl p-6 text-white mb-6">
                <h1 class="text-2xl font-bold">Edit Inventory Item</h1>
                <p class="text-teal-100 mt-1">Update item details</p>
            </div>

            <form method="POST" action="{{ route('admin.inventory.update', $cloth->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Cloth Name *</label>
                            <input type="text" name="name" value="{{ old('name', $cloth->name) }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Category</label>
                            <input type="text" name="category" value="{{ old('category', $cloth->category) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="">Select Gender</option>
                                <option value="men" {{ old('gender', $cloth->gender) == 'men' ? 'selected' : '' }}>👨 Men
                                </option>
                                <option value="women" {{ old('gender', $cloth->gender) == 'women' ? 'selected' : '' }}>👩
                                    Women</option>
                                <option value="kids" {{ old('gender', $cloth->gender) == 'kids' ? 'selected' : '' }}>🧒
                                    Kids</option>
                                <option value="unisex" {{ old('gender', $cloth->gender) == 'unisex' ? 'selected' : '' }}>👥
                                    Unisex</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Size</label>
                            <input type="text" name="size" value="{{ old('size', $cloth->size) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('size')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Color</label>
                            <input type="text" name="color" value="{{ old('color', $cloth->color) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Season</label>
                            <select name="season"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="">Select Season</option>
                                <option value="summer" {{ old('season', $cloth->season) == 'summer' ? 'selected' : '' }}>☀️
                                    Summer</option>
                                <option value="monsoon" {{ old('season', $cloth->season) == 'monsoon' ? 'selected' : '' }}>
                                    🌧️ Monsoon</option>
                                <option value="autumn" {{ old('season', $cloth->season) == 'autumn' ? 'selected' : '' }}>🍂
                                    Autumn</option>
                                <option value="winter" {{ old('season', $cloth->season) == 'winter' ? 'selected' : '' }}>❄️
                                    Winter</option>
                                <option value="all_season"
                                    {{ old('season', $cloth->season) == 'all_season' ? 'selected' : '' }}>🌈 All Season
                                </option>
                            </select>
                            @error('season')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Quantity *</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $cloth->quantity) }}"
                                min="0" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Quality</label>
                            <select name="quality"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="">Select Quality</option>
                                <option value="new" {{ old('quality', $cloth->quality) == 'new' ? 'selected' : '' }}>✨
                                    New</option>
                                <option value="like_new"
                                    {{ old('quality', $cloth->quality) == 'like_new' ? 'selected' : '' }}>👍 Like New
                                </option>
                                <option value="good" {{ old('quality', $cloth->quality) == 'good' ? 'selected' : '' }}>✅
                                    Good</option>
                                <option value="fair" {{ old('quality', $cloth->quality) == 'fair' ? 'selected' : '' }}>⚠️
                                    Fair</option>
                            </select>
                            @error('quality')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                            <select name="status" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="available"
                                    {{ old('status', $cloth->status) == 'available' ? 'selected' : '' }}>🟢 Available
                                </option>
                                <option value="reserved"
                                    {{ old('status', $cloth->status) == 'reserved' ? 'selected' : '' }}>🟡 Reserved
                                </option>
                                <option value="donated" {{ old('status', $cloth->status) == 'donated' ? 'selected' : '' }}>
                                    🟣 Donated</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description', $cloth->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Image</label>
                    @if ($cloth->image_path)
                        <div class="mb-2">
                            <img src="{{ Storage::url($cloth->image_path) }}" alt="Current Image"
                                class="w-24 h-24 object-cover rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Current image</p>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" src="#" alt="Preview" class="w-24 h-24 object-cover rounded-lg">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Max size: 2MB</p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold">
                        <i class="fas fa-save mr-2"></i> Update Item
                    </button>
                    <a href="{{ route('admin.inventory.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview
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
    </script>
@endsection
