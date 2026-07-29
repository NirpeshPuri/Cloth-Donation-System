{{-- @extends('layouts.admin')

@section('title', 'Manage Requests - Admin')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Manage Cloth Requests</h1>
            <p class="text-teal-100">Review and manage requests from users</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-clock text-yellow-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Pending</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                <p class="text-gray-500 text-sm">Awaiting approval</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Approved</h3>
                <p class="text-3xl font-bold text-green-600">{{ $approvedCount }}</p>
                <p class="text-gray-500 text-sm">Ready for pickup</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-times-circle text-red-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Rejected</h3>
                <p class="text-3xl font-bold text-red-600">{{ $rejectedCount }}</p>
                <p class="text-gray-500 text-sm">Not approved</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md">
                <i class="fas fa-truck text-blue-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Completed</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $completedCount }}</p>
                <p class="text-gray-500 text-sm">Delivered</p>
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

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Requester</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Item</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Quantity</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">#{{ $request->id }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold">{{ $request->receiver->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $request->receiver->email ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $request->receiver->phone ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($request->cloth->image_path)
                                        <img src="{{ Storage::url($request->cloth->image_path) }}"
                                            class="w-10 h-10 object-cover rounded">
                                    @else
                                        <i class="fas fa-tshirt text-teal-400 text-2xl"></i>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $request->cloth->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $request->cloth->size ?? 'One Size' }} |
                                            {{ $request->cloth->color ?? 'Various' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-semibold">{{ $request->quantity }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $request->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $request->status == 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.requests.show', $request->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if ($request->status == 'pending')
                                        <form method="POST" action="{{ route('admin.requests.approve', $request->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm"
                                                onclick="return confirm('Approve this request?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.requests.reject', $request->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm"
                                                onclick="return confirm('Reject this request?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    @endif
                                    @if ($request->status == 'approved')
                                        <form method="POST" action="{{ route('admin.requests.complete', $request->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-sm"
                                                onclick="return confirm('Mark as completed?')">
                                                <i class="fas fa-truck"></i> Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                No requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection --}}

@extends('layouts.admin')
@section('title', 'Manage Requests - Admin')
@section('content')
    <div class="container mx-auto px-6 py-0">
        <div class="gradient-bg rounded-2xl px-6 py-4 text-white mb-2">
            <div class="flex items-center justify-between gap-6">
                <div class="flex-shrink-0">
                    <h1 class="text-2xl font-bold mb-1">Manage Cloth Requests</h1>
                    <p class="text-teal-100 text-sm">Review and manage requests from users</p>
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
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-md p-3 mb-2">
        <form method="GET" action="{{ route('admin.requests.index') }}">
            <div class="flex gap-3 mb-4">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search receiver, email, phone, cloth..."
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if ($search || $category || $gender || $size || $color || $status || $quality || $dateFrom || $dateTo)
                    <a href="{{ route('admin.requests.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
                <a href="{{ route('admin.requests.export', request()->query()) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3">
                <select name="category" class="border border-gray-300 rounded-lg px-3 py-3">
                    <option value="">Category</option>
                    <option value="shirt" {{ $category == 'shirt' ? 'selected' : '' }}>Shirt</option>
                    <option value="pant" {{ $category == 'pant' ? 'selected' : '' }}>Pant</option>
                    <option value="dress" {{ $category == 'dress' ? 'selected' : '' }}>Dress</option>
                    <option value="jacket" {{ $category == 'jacket' ? 'selected' : '' }}>Jacket</option>
                    <option value="t-shirt" {{ $category == 't-shirt' ? 'selected' : '' }}>T-Shirt</option>
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
            <div class="mt-3 text-sm text-gray-500">Date From and Date To filter by request date.</div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-teal-600 text-white border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Requester</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Item</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Quantity</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">#{{ $request->id }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $request->receiver->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $request->receiver->email ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $request->receiver->phone ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($request->cloth && $request->cloth->image_path)
                                        <img src="{{ Storage::url($request->cloth->image_path) }}"
                                            alt="{{ $request->cloth->name }}"
                                            class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80"
                                            onclick="showImageModal('{{ Storage::url($request->cloth->image_path) }}')">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-tshirt text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $request->cloth->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $request->cloth->category ?? 'N/A' }} |
                                            {{ $request->cloth->gender ?? 'N/A' }} |
                                            {{ $request->cloth->size ?? 'One Size' }} |
                                            {{ $request->cloth->color ?? 'Various' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Available: {{ $request->cloth->quantity ?? 0 }} |
                                            Quality: {{ ucfirst($request->cloth->quality ?? 'N/A') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-semibold">{{ $request->quantity }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $request->status == 'completed' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $request->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.requests.show', $request->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    @if ($request->status == 'pending')
                                        <form method="POST"
                                            action="{{ route('admin.requests.approve', $request->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm"
                                                onclick="return confirm('Approve this request?')">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.requests.reject', $request->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm"
                                                onclick="return confirm('Reject this request?')">
                                                <i class="fas fa-times mr-1"></i>Reject
                                            </button>
                                        </form>
                                    @endif
                                    @if ($request->status == 'approved')
                                        <form method="POST"
                                            action="{{ route('admin.requests.complete', $request->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-sm"
                                                onclick="return confirm('Mark as completed?')">
                                                <i class="fas fa-truck mr-1"></i>Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>No requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50"
        onclick="closeImageModal()">
        <div class="max-w-2xl max-h-screen p-4 relative">
            <img id="modalImage" src="" alt="Full size" class="max-w-full max-h-screen rounded-lg shadow-2xl">
            <button class="absolute top-4 right-4 bg-white rounded-full p-2 text-gray-800 hover:bg-gray-200"
                onclick="event.stopPropagation(); closeImageModal()">
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
                document.getElementById('modalImage').src = '';
            }
        </script>
    @endpush
@endsection
