@extends('layouts.master')

@section('title', 'Donation Details - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('user.my-donations') }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to My Donations
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="gradient-bg px-6 py-6 text-white">
                <div class="flex justify-between items-center flex-wrap gap-3">
                    <div>
                        <h1 class="text-2xl font-bold">Donation #{{ $donation->id }}</h1>
                        <p class="text-teal-100 mt-1">{{ $donation->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-white/20">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Items List -->
                <h2 class="text-xl font-bold text-gray-800 mb-4">Donated Items</h2>
                <div class="space-y-4 mb-8">
                    @foreach ($donation->items as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            <div
                                class="w-20 h-20 bg-white rounded-lg flex items-center justify-center overflow-hidden shadow-sm">
                                @if ($item->image_path)
                                    <img src="{{ Storage::url($item->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-tshirt text-teal-400 text-4xl"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-800">{{ $item->cloth_name }}</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2 text-sm">
                                    @if ($item->cloth_type)
                                        <p><span class="text-gray-500">Type:</span> {{ $item->cloth_type }}</p>
                                    @endif
                                    @if ($item->gender)
                                        <p><span class="text-gray-500">Gender:</span> {{ ucfirst($item->gender) }}</p>
                                    @endif
                                    @if ($item->size)
                                        <p><span class="text-gray-500">Size:</span> {{ $item->size }}</p>
                                    @endif
                                    @if ($item->color)
                                        <p><span class="text-gray-500">Color:</span> {{ $item->color }}</p>
                                    @endif
                                    <p><span class="text-gray-500">Quantity:</span> {{ $item->quantity }}</p>
                                    <p><span class="text-gray-500">Quality:</span>
                                        {{ ucfirst(str_replace('_', ' ', $item->quality)) }}</p>
                                </div>
                                @if ($item->description)
                                    <p class="text-gray-600 text-sm mt-2">{{ $item->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pickup Details -->
                <div class="grid md:grid-cols-2 gap-6 pt-6 border-t">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Collection Center</h3>
                        <p class="text-gray-600">{{ $donation->admin->name ?? 'N/A' }}</p>
                        <p class="text-gray-500 text-sm">{{ $donation->admin->address ?? '' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Pickup Address</h3>
                        <p class="text-gray-600">{{ $donation->pickup_address ?? 'Same as collection center' }}</p>
                    </div>
                </div>

                @if ($donation->notes)
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="font-semibold text-gray-800 mb-2">Additional Notes</h3>
                        <p class="text-gray-600">{{ $donation->notes }}</p>
                    </div>
                @endif

                <!-- Status Timeline -->
                <div class="mt-8 pt-6 border-t">
                    <h3 class="font-semibold text-gray-800 mb-4">Donation Status</h3>
                    <div class="flex items-center justify-between flex-wrap">
                        <div class="text-center">
                            <div
                                class="w-8 h-8 rounded-full {{ $donation->status != 'pending' ? 'bg-green-500' : 'bg-teal-500' }} text-white flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <p class="text-sm font-semibold">Submitted</p>
                            <p class="text-xs text-gray-500">{{ $donation->created_at->format('M d') }}</p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="text-center">
                            <div
                                class="w-8 h-8 rounded-full {{ $donation->status == 'approved' || $donation->status == 'processing' || $donation->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }} text-white flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-clipboard-list text-sm"></i>
                            </div>
                            <p class="text-sm font-semibold">Reviewed</p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="text-center">
                            <div
                                class="w-8 h-8 rounded-full {{ $donation->status == 'processing' || $donation->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }} text-white flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-truck text-sm"></i>
                            </div>
                            <p class="text-sm font-semibold">Processing</p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="text-center">
                            <div
                                class="w-8 h-8 rounded-full {{ $donation->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }} text-white flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-check-double text-sm"></i>
                            </div>
                            <p class="text-sm font-semibold">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
