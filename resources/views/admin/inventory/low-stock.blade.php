@extends('layouts.admin')

@section('title', 'Low Stock Items')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Low Stock Items</h1>
                    <p class="text-teal-100">Items with 5 or fewer items in stock</p>
                </div>
                <a href="{{ route('admin.inventory.index') }}"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Inventory
                </a>
            </div>
        </div>

        @if ($clothes->count() > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Item</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Category</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Quantity</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($clothes as $cloth)
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
                                            <p class="text-xs text-gray-500">{{ $cloth->size ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $cloth->category ?? 'General' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-red-600 font-bold text-lg">{{ $cloth->quantity }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $cloth->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $cloth->status == 'reserved' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($cloth->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.inventory.edit', $cloth->id) }}"
                                        class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-1 rounded-lg text-sm">
                                        <i class="fas fa-edit mr-1"></i> Update
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-green-50 border border-green-200 rounded-xl p-8 text-center">
                <i class="fas fa-check-circle text-green-500 text-5xl mb-3"></i>
                <p class="text-green-600 text-lg">Great news! No items are low in stock.</p>
                <p class="text-green-500 text-sm mt-1">All items have more than 5 units available.</p>
            </div>
        @endif
    </div>
@endsection
