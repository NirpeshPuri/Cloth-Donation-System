@extends('layouts.admin')

@section('title', 'Manage Inventory')

@section('content')
    <div class="container mx-auto px-2 py-0">
        <div class="container mx-auto px-6 py-0">
            <div class="gradient-bg rounded-2xl px-6 py-4 text-white mb-2">
                <div class="flex items-center justify-between gap-6">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold mb-2">Manage Inventory</h1>
                        <p class="text-teal-100">View and manage all clothes in your collection center</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.inventory.low-stock') }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Low Stock
                        </a>
                        <a href="{{ route('admin.inventory.create') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i> Add New
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid md:grid-cols-5 gap-4 mb-2">
                <div class="bg-white rounded-xl p-4 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-tshirt text-teal-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-teal-600">{{ $totalItems }}</p>
                            <p class="text-xs text-gray-500">Total Items</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $availableItems }}</p>
                            <p class="text-xs text-gray-500">Available</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-yellow-600">{{ $reservedItems }}</p>
                            <p class="text-xs text-gray-500">Reserved</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-gift text-purple-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-purple-600">{{ $donatedItems }}</p>
                            <p class="text-xs text-gray-500">Donated</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-red-600">{{ $lowStockItems }}</p>
                            <p class="text-xs text-gray-500">Low Stock</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search & Filter -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or category..."
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <select name="status"
                            class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved
                            </option>
                            <option value="donated" {{ request('status') == 'donated' ? 'selected' : '' }}>Donated</option>
                        </select>
                    </div>
                    <div>
                        <select name="gender"
                            class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">All Genders</option>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                            <option value="kids">Kids</option>
                            <option value="unisex">Unisex</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.inventory.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </a>
                </form>
            </div>

            <!-- Inventory Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-teal-600 text-white border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Item</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Gender</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Size</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Quantity</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($clothes as $cloth)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($cloth->image_path)
                                            <img src="{{ Storage::url($cloth->image_path) }}"
                                                class="w-10 h-10 object-cover rounded">
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                                <i class="fas fa-tshirt text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold">{{ $cloth->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $cloth->color ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $cloth->category ?? 'General' }}</td>
                                <td class="px-6 py-4 capitalize">{{ $cloth->gender ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $cloth->size ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="font-semibold {{ $cloth->quantity <= 5 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $cloth->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $cloth->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $cloth->status == 'reserved' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $cloth->status == 'donated' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ ucfirst($cloth->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('admin.inventory.edit', $cloth->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.inventory.destroy', $cloth->id) }}"
                                            onsubmit="return confirm('Remove this item from inventory?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                                    No items in inventory.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $clothes->links() }}
                </div>
            </div>
        </div>
    @endsection
