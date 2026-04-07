@extends('layouts.admin')

@section('title', 'Manage Donations - Admin')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Manage Donations</h1>
            <p class="text-teal-100">Review and approve donations to add them to inventory</p>
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

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Donor Info</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Items</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Images</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($donations as $donation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">#{{ $donation->id }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold">{{ $donation->donor->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $donation->donor->email }}</p>
                                    <p class="text-sm text-gray-500">{{ $donation->donor->phone }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($donation->items as $item)
                                    <div class="mb-2">
                                        <p class="font-medium">{{ $item->cloth_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $item->cloth_type }} | {{ $item->gender }} | {{ $item->size }} |
                                            {{ $item->color }}
                                        </p>
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }} | Quality:
                                            {{ ucfirst($item->quality) }}</p>
                                    </div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($donation->items as $item)
                                    @if ($item->image_path)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->cloth_name }}"
                                                class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80"
                                                onclick="showImageModal('{{ Storage::url($item->image_path) }}')">
                                        </div>
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-tshirt text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $donation->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $donation->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $donation->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $donation->status == 'completed' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $donation->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $donation->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.donations.show', $donation->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    @if ($donation->status == 'pending')
                                        <form method="POST" action="{{ route('admin.donations.approve', $donation->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition"
                                                onclick="return confirm('Add these items to inventory?')">
                                                <i class="fas fa-check-circle mr-1"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.donations.reject', $donation->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition"
                                                onclick="return confirm('Reject this donation?')">
                                                <i class="fas fa-times-circle mr-1"></i> Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
