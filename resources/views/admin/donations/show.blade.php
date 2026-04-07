@extends('layouts.admin')

@section('title', 'Donation Details - Admin')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.donations.index') }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Donations
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="gradient-bg px-8 py-6 text-white">
                <h1 class="text-2xl font-bold">Donation #{{ $donation->id }}</h1>
                <p class="text-teal-100 mt-1">From: {{ $donation->donor->name }} ({{ $donation->donor->email }})</p>
            </div>

            <div class="p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Donated Items</h3>
                    <div class="space-y-4">
                        @foreach ($donation->items as $item)
                            <div class="border rounded-xl p-5 flex justify-between items-start hover:shadow-md transition">
                                <div class="flex gap-5">
                                    @if ($item->image_path)
                                        <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100">
                                            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->cloth_name }}"
                                                class="w-full h-full object-cover cursor-pointer hover:opacity-80"
                                                onclick="showImageModal('{{ Storage::url($item->image_path) }}')">
                                        </div>
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-tshirt text-gray-400 text-4xl"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-lg">{{ $item->cloth_name }}</p>
                                        <p class="text-gray-600 text-sm mt-1">
                                            Type: {{ $item->cloth_type ?? 'N/A' }} | Gender:
                                            {{ ucfirst($item->gender ?? 'N/A') }}
                                        </p>
                                        <p class="text-gray-600 text-sm">
                                            Size: {{ $item->size ?? 'N/A' }} | Color: {{ $item->color ?? 'N/A' }}
                                        </p>
                                        <p class="text-gray-600 text-sm">
                                            Quality: {{ ucfirst($item->quality) }}
                                        </p>
                                        @if ($item->description)
                                            <p class="text-gray-500 text-sm mt-2">{{ $item->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-teal-600">Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t pt-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">Collection Center</p>
                            <p class="font-semibold">{{ $donation->admin->name ?? 'Not assigned' }}</p>
                        </div>
                        @if ($donation->pickup_address)
                            <div>
                                <p class="text-gray-500 text-sm">Pickup Address</p>
                                <p class="font-semibold">{{ $donation->pickup_address }}</p>
                            </div>
                        @endif
                    </div>
                    @if ($donation->notes)
                        <div class="mt-4">
                            <p class="text-gray-500 text-sm">Additional Notes</p>
                            <p class="text-gray-700">{{ $donation->notes }}</p>
                        </div>
                    @endif
                </div>

                @if ($donation->status == 'pending')
                    <div class="mt-8 flex gap-4 pt-4 border-t">
                        <form method="POST" action="{{ route('admin.donations.approve', $donation->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg text-base font-semibold transition"
                                onclick="return confirm('Add these items to inventory?')">
                                <i class="fas fa-check-circle mr-2"></i> Approve & Add to Inventory
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.donations.reject', $donation->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg text-base font-semibold transition"
                                onclick="return confirm('Reject this donation?')">
                                <i class="fas fa-times-circle mr-2"></i> Reject Donation
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50"
        onclick="closeImageModal()">
        <div class="max-w-2xl max-h-screen p-4">
            <img id="modalImage" src="" alt="Full size" class="max-w-full max-h-screen rounded-lg shadow-2xl">
            <button class="absolute top-4 right-4 bg-white rounded-full p-2 text-gray-800 hover:bg-gray-200"
                onclick="closeImageModal()">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            function showImageModal(imageUrl) {
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('imageModal').classList.remove('hidden');
                document.getElementById('imageModal').classList.add('flex');
            }

            function closeImageModal() {
                document.getElementById('imageModal').classList.add('hidden');
                document.getElementById('imageModal').classList.remove('flex');
            }
        </script>
    @endpush
@endsection
