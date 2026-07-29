@extends('layouts.admin')

@section('title', 'Manage Donations - Admin')

@section('content')
    <div class="container mx-auto px-6 py-0">
        <div class="gradient-bg rounded-2xl px-6 py-4 text-white mb-2">
            <div class="flex items-center justify-between gap-6">
                <div class="flex-shrink-0">
                    <h1 class="text-2xl font-bold mb-1">Manage Donations</h1>
                    <p class="text-teal-100 text-sm">Review and approve donations to add them to inventory</p>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div class="bg-white/95 rounded-lg px-4 py-2 min-w-[100px]">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-yellow-600 text-sm"></i>
                            <span class="text-xs font-semibold text-gray-600">Pending</span>
                        </div>
                        <p class="text-xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                    </div>

                    <div class="bg-white/95 rounded-lg px-4 py-2 min-w-[100px]">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                            <span class="text-xs font-semibold text-gray-600">Approved</span>
                        </div>
                        <p class="text-xl font-bold text-green-600">{{ $approvedCount }}</p>
                    </div>

                    <div class="bg-white/95 rounded-lg px-4 py-2 min-w-[100px]">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-times-circle text-red-600 text-sm"></i>
                            <span class="text-xs font-semibold text-gray-600">Rejected</span>
                        </div>
                        <p class="text-xl font-bold text-red-600">{{ $rejectedCount }}</p>
                    </div>

                    <div class="bg-white/95 rounded-lg px-4 py-2 min-w-[100px]">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-truck text-blue-600 text-sm"></i>
                            <span class="text-xs font-semibold text-gray-600">Completed</span>
                        </div>
                        <p class="text-xl font-bold text-blue-600">{{ $completedCount }}</p>
                    </div>
                </div>
            </div>
        </div>
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

    <div class="bg-white rounded-xl shadow-md p-3 mb-2">
        <form method="GET" action="{{ route('admin.donations.index') }}">
            <div class="flex gap-3 mb-4">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search donor, email, phone..."
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>Search
                </button>

                @if ($search || $clothType || $gender || $size || $color || $status || $quality || $dateFrom || $dateTo)
                    <a href="{{ route('admin.donations.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif

                <a href="{{ route('admin.donations.export', request()->query()) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3">
                <select name="cloth_type" class="border border-gray-300 rounded-lg px-3 py-3">
                    <option value="">Cloth Type</option>
                    <option value="shirt" {{ $clothType == 'shirt' ? 'selected' : '' }}>Shirt</option>
                    <option value="pant" {{ $clothType == 'pant' ? 'selected' : '' }}>Pant</option>
                    <option value="dress" {{ $clothType == 'dress' ? 'selected' : '' }}>Dress</option>
                    <option value="jacket" {{ $clothType == 'jacket' ? 'selected' : '' }}>Jacket</option>
                    <option value="t-shirt" {{ $clothType == 't-shirt' ? 'selected' : '' }}>T-Shirt</option>
                </select>

                <select name="gender" class="border border-gray-300 rounded-lg px-3 py-3">
                    <option value="">Gender</option>
                    <option value="men" {{ $gender == 'men' ? 'selected' : '' }}>Men</option>
                    <option value="women" {{ $gender == 'women' ? 'selected' : '' }}>Women</option>
                    <option value="unisex" {{ $gender == 'unisex' ? 'selected' : '' }}>Unisex</option>
                    <option value="children" {{ $gender == 'children' ? 'selected' : '' }}>Children</option>
                </select>

                <select name="size" class="border border-gray-300 rounded-lg px-3 py-3">
                    <option value="">Size</option>
                    <option value="S" {{ $size == 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ $size == 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ $size == 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ $size == 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="XXL" {{ $size == 'XXL' ? 'selected' : '' }}>XXL</option>
                </select>

                <input type="text" name="color" value="{{ $color }}" placeholder="Color"
                    class="border border-gray-300 rounded-lg px-3 py-3">

                <select name="status" class="border border-gray-300 rounded-lg px-3 py-3">
                    <option value="">Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <select name="quality" class="border border-gray-300 rounded-lg px-3 py-3">
                    <option value="">Quality</option>
                    <option value="new" {{ $quality == 'new' ? 'selected' : '' }}>New</option>
                    <option value="good" {{ $quality == 'good' ? 'selected' : '' }}>Good</option>
                    <option value="fair" {{ $quality == 'fair' ? 'selected' : '' }}>Fair</option>
                </select>

                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    class="border border-gray-300 rounded-lg px-3 py-3">

                <input type="date" name="date_to" value="{{ $dateTo }}"
                    class="border border-gray-300 rounded-lg px-3 py-3">
            </div>

            <div class="mt-3 text-sm text-gray-500">
                Date From and Date To filter by donation date.
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-teal-600 text-white border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Donor Info</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Items</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Images</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($donations as $donation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">#{{ $donation->id }}</td>

                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $donation->donor->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $donation->donor->email ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $donation->donor->phone ?? 'N/A' }}</p>
                            </td>

                            <td class="px-6 py-4">
                                @foreach ($donation->items as $item)
                                    <div class="mb-2">
                                        <p class="font-medium">{{ $item->cloth_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $item->cloth_type }} |
                                            {{ $item->gender }} |
                                            {{ $item->size }} |
                                            {{ $item->color }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Qty: {{ $item->quantity }} |
                                            Quality: {{ ucfirst($item->quality) }}
                                        </p>
                                    </div>
                                @endforeach
                            </td>

                            <td class="px-6 py-4">
                                @foreach ($donation->items as $item)
                                    @if ($item->image_path)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($item->image_path) }}"
                                                alt="{{ $item->cloth_name }}"
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

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $donation->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.donations.show', $donation->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>

                                    @if ($donation->status == 'pending')
                                        <form method="POST"
                                            action="{{ route('admin.donations.approve', $donation->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm"
                                                onclick="return confirm('Add these items to inventory?')">
                                                <i class="fas fa-check-circle mr-1"></i>Approve
                                            </button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('admin.donations.reject', $donation->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm"
                                                onclick="return confirm('Reject this donation?')">
                                                <i class="fas fa-times-circle mr-1"></i>Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3"></i>
                                <p>No donations found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($donations->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $donations->links() }}
            </div>
        @endif
    </div>
    </div>

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
