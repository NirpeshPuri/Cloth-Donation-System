@extends('layouts.master')

@section('title', 'My Donations - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">My Donations</h1>
            <p class="text-teal-100">Track all the clothes you've donated</p>
        </div>

        @if ($donations->count() > 0)
            <div class="space-y-6">
                @foreach ($donations as $donation)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="border-b px-6 py-4 bg-gray-50 flex justify-between items-center flex-wrap gap-3">
                            <div>
                                <span class="text-sm text-gray-500">Donation #{{ $donation->id }}</span>
                                <span
                                    class="ml-3 px-2 py-1 text-xs rounded-full
                                {{ $donation->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $donation->status == 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $donation->status == 'processing' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $donation->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $donation->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $donation->created_at->format('M d, Y') }}
                            </div>
                        </div>

                        <div class="p-6">
                            @foreach ($donation->items as $item)
                                <div class="flex items-center gap-4 py-3 border-b last:border-0">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                        @if ($item->image_path)
                                            <img src="{{ Storage::url($item->image_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-tshirt text-teal-400 text-3xl"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $item->cloth_name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $item->cloth_type ?? 'Clothing' }}
                                            @if ($item->size)
                                                | Size: {{ $item->size }}
                                            @endif
                                            @if ($item->color)
                                                | Color: {{ $item->color }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400 capitalize mt-1">
                                            <i class="fas fa-star mr-1"></i> Quality:
                                            {{ str_replace('_', ' ', $item->quality) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-teal-600">{{ $item->quantity }} pcs</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-4 pt-3 border-t flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-store mr-1"></i> {{ $donation->admin->name ?? 'Collection Center' }}
                                </div>
                                <a href="{{ route('user.donation.show', $donation->id) }}"
                                    class="text-teal-600 hover:text-teal-700 text-sm font-semibold">
                                    View Details <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl p-12 text-center">
                <i class="fas fa-gift text-gray-400 text-5xl mb-3 block"></i>
                <p class="text-gray-500 text-lg">You haven't donated any clothes yet.</p>
                <p class="text-gray-400 text-sm mt-2">Your donations can make a big difference in someone's life.</p>
                <a href="{{ route('user.donate') }}"
                    class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i> Make Your First Donation
                </a>
            </div>
        @endif
    </div>
@endsection
