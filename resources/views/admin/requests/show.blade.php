@extends('layouts.admin')

@section('title', 'Request Details - Admin')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.requests.index') }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Requests
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="gradient-bg px-8 py-6 text-white">
                <h1 class="text-2xl font-bold">Request #{{ $request->id }}</h1>
                <p class="text-teal-100 mt-1">Status: {{ ucfirst($request->status) }}</p>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Requester Information -->
                    <div class="border rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user text-teal-600 mr-2"></i> Requester Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-500 text-sm">Name</p>
                                <p class="font-semibold">{{ $request->receiver->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Email</p>
                                <p class="font-semibold">{{ $request->receiver->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Phone</p>
                                <p class="font-semibold">{{ $request->receiver->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Requested Date</p>
                                <p class="font-semibold">{{ $request->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item Information -->
                    <div class="border rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-tshirt text-teal-600 mr-2"></i> Item Information
                        </h3>
                        <div class="flex gap-4 mb-4">
                            @if ($request->cloth->image_path)
                                <img src="{{ Storage::url($request->cloth->image_path) }}"
                                    class="w-24 h-24 object-cover rounded-lg">
                            @else
                                <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-lg">{{ $request->cloth->name }}</p>
                                <p class="text-gray-500">{{ $request->cloth->category ?? 'General' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm">Size</p>
                                <p class="font-semibold">{{ $request->cloth->size ?? 'Various' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Color</p>
                                <p class="font-semibold">{{ $request->cloth->color ?? 'Various' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Quantity Requested</p>
                                <p class="font-semibold text-teal-600">{{ $request->quantity }} piece(s)</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Available Stock</p>
                                <p class="font-semibold">{{ $request->cloth->quantity }} piece(s)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if ($request->status == 'pending')
                    <div class="mt-8 flex gap-4 justify-end border-t pt-6">
                        <form method="POST" action="{{ route('admin.requests.approve', $request->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold"
                                onclick="return confirm('Approve this request? This will reduce inventory stock.')">
                                <i class="fas fa-check-circle mr-2"></i> Approve Request
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.requests.reject', $request->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold"
                                onclick="return confirm('Reject this request?')">
                                <i class="fas fa-times-circle mr-2"></i> Reject Request
                            </button>
                        </form>
                    </div>
                @endif

                @if ($request->status == 'approved')
                    <div class="mt-8 flex justify-end border-t pt-6">
                        <form method="POST" action="{{ route('admin.requests.complete', $request->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold"
                                onclick="return confirm('Mark this request as completed?')">
                                <i class="fas fa-truck mr-2"></i> Mark as Completed
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
