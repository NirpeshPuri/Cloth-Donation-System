@extends('layouts.master')

@section('title', 'My Cart - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">My Cart</h1>
            <p class="text-teal-100">Review your items before requesting</p>
        </div>

        @if ($cartItems->count() > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Item</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Size/Color</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Quantity</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($cartItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->cloth->image_path)
                                            <img src="{{ Storage::url($item->cloth->image_path) }}"
                                                class="w-12 h-12 object-cover rounded">
                                        @else
                                            <i class="fas fa-tshirt text-teal-400 text-2xl"></i>
                                        @endif
                                        <div>
                                            <p class="font-semibold">{{ $item->cloth->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->cloth->category ?? 'Clothing' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm">{{ $item->cloth->size ?? 'One Size' }}</span> /
                                    <span class="text-sm">{{ $item->cloth->color ?? 'Various' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" value="{{ $item->quantity }}" min="1"
                                        max="{{ $item->cloth->quantity }}"
                                        class="w-20 text-center border border-gray-300 rounded-lg px-2 py-1"
                                        onchange="updateQuantity({{ $item->id }}, this.value)">
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="removeItem({{ $item->id }})"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-6 bg-gray-50 border-t">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600">Total Items: <span class="font-bold">{{ $total }}</span></p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('user.home') }}"
                                class="px-6 py-2 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50">
                                Continue Shopping
                            </a>
                            <form method="POST" action="{{ route('cart.checkout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                                    Submit All Requests
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl p-12 text-center">
                <i class="fas fa-shopping-cart text-gray-400 text-5xl mb-3 block"></i>
                <p class="text-gray-500 text-lg">Your cart is empty</p>
                <a href="{{ route('user.home') }}"
                    class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                    Browse Clothes
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function updateQuantity(itemId, quantity) {
                fetch(`/cart/update/${itemId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }

            function removeItem(itemId) {
                if (confirm('Remove this item from cart?')) {
                    fetch(`/cart/remove/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            }
        </script>
    @endpush
@endsection
