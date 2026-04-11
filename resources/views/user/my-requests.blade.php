@extends('layouts.master')

@section('title', 'My Requests - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">My Requests</h1>
            <p class="text-teal-100">Track all your clothing requests</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($requests->count() > 0)
            <div class="space-y-4">
                @foreach ($requests as $request)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex gap-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                        @if ($request->cloth->image_path)
                                            <img src="{{ Storage::url($request->cloth->image_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-tshirt text-teal-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-800">{{ $request->cloth->name }}</h3>
                                        <p class="text-gray-500 text-sm">{{ $request->cloth->category ?? 'Clothing' }}</p>
                                        <p class="text-gray-600 text-sm mt-1">
                                            Size: {{ $request->cloth->size ?? 'Various' }} |
                                            Color: {{ $request->cloth->color ?? 'Various' }}
                                        </p>
                                        <p class="text-gray-600 text-sm">
                                            Quantity: {{ $request->quantity }}
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1">
                                            Collection Center: {{ $request->cloth->admin->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $request->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $request->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-2">{{ $request->created_at->format('M d, Y') }}</p>
                                    @if ($request->status == 'pending')
                                        <form method="POST" action="{{ route('user.request.cancel', $request->id) }}"
                                            class="mt-2">

                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm"
                                                onclick="return confirm('Cancel this request?')">
                                                Cancel Request
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl p-12 text-center">
                <i class="fas fa-hand-holding-heart text-gray-400 text-5xl mb-3 block"></i>
                <p class="text-gray-500 text-lg">You haven't made any requests yet.</p>
                <a href="{{ route('user.home') }}"
                    class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                    Browse Clothes
                </a>
            </div>
        @endif
    </div>
@endsection
