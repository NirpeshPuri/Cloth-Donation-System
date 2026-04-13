@extends('layouts.admin')

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
@endsection
