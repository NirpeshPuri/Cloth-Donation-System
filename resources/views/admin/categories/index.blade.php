@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Manage Categories</h1>
            <p class="text-teal-100">Add, edit, or remove clothing categories</p>
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

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Add Category Form -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Add New Category</h2>
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Category Value (slug)</label>
                        <input type="text" name="value" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="e.g., shirt, jeans, kurta">
                        <p class="text-xs text-gray-500 mt-1">Used in URL and database (lowercase, no spaces)</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Display Label</label>
                        <input type="text" name="label" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="e.g., 👕 Shirts, 👖 Jeans">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Icon (Font Awesome)</label>
                        <input type="text" name="icon" value="fa-tag"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="fa-tshirt, fa-shopping-cart, etc.">
                    </div>
                    <button type="submit"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i> Add Category
                    </button>
                </form>
            </div>

            <!-- Categories List -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Existing Categories</h2>
                @if (count($categories) > 0)
                    <div class="space-y-3">
                        @foreach ($categories as $index => $cat)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <form method="POST" action="{{ route('admin.categories.update', $index) }}"
                                    class="update-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-xs text-gray-500">Value</label>
                                            <input type="text" name="value" value="{{ $cat['value'] }}" required
                                                class="w-full px-3 py-1 border rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Label</label>
                                            <input type="text" name="label" value="{{ $cat['label'] }}" required
                                                class="w-full px-3 py-1 border rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Icon</label>
                                            <input type="text" name="icon" value="{{ $cat['icon'] ?? 'fa-tag' }}"
                                                class="w-full px-3 py-1 border rounded-lg text-sm">
                                        </div>
                                        <div class="flex items-end gap-2">
                                            <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">
                                                <i class="fas fa-save"></i> Update
                                            </button>
                                            <button type="button" onclick="deleteCategory({{ $index }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No categories added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        function deleteCategory(index) {
            if (confirm('Are you sure you want to delete this category?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('admin/categories') }}/${index}`;
                form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
