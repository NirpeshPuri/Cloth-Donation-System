@extends('layouts.master')

@section('title', $title . ' - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.home') }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Home
            </a>
        </div>

        <!-- Header -->
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">{{ $title }}</h1>
            <p class="text-teal-100">Browse from {{ $selectedAdmin->name }} Collection Center</p>
        </div>

        @if ($clothes && $clothes->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($clothes as $cloth)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition cursor-pointer"
                        onclick="viewDetails({{ $cloth->id }})">
                        <div class="relative h-56 overflow-hidden">
                            @if ($cloth->image_path)
                                <img src="{{ Storage::url($cloth->image_path) }}"
                                    class="w-full h-full object-cover hover:scale-110 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <i class="fas fa-tshirt text-teal-400 text-6xl"></i>
                                </div>
                            @endif
                            @if ($cloth->quantity <= 3 && $cloth->quantity > 0)
                                <span
                                    class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                    Only {{ $cloth->quantity }} left
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-800">{{ $cloth->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $cloth->category ?? 'Clothing' }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs text-gray-500">{{ $cloth->size ?? 'One Size' }}</span>
                                <span class="text-xs text-teal-600">{{ $cloth->quantity }} available</span>
                            </div>
                            <div class="mt-3 flex items-center gap-2">
                                <span class="text-xs bg-teal-100 text-teal-600 px-2 py-1 rounded-full">
                                    {{ $cloth->gender ?? 'Unisex' }}
                                </span>
                                @if ($cloth->color)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                        {{ $cloth->color }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl p-12 text-center">
                <i class="fas fa-box-open text-gray-400 text-5xl mb-3 block"></i>
                <p class="text-gray-500 text-lg">No items available in this category.</p>
                <p class="text-gray-400 text-sm mt-2">Please check back later or browse other categories.</p>
                <a href="{{ route('user.home') }}"
                    class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                    Back to Home
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function viewDetails(clothId) {
                window.location.href = '/user/cloth/' + clothId;
            }
        </script>
    @endpush
@endsection
