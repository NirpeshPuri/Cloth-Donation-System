@foreach ($donations as $donation)
    <tr class="border-b hover:bg-gray-50">

        <td class="px-4 py-3 font-medium">
            {{ $donation->id }}
        </td>

        <td class="px-4 py-3">
            <div class="flex items-center space-x-3">

                @php
                    $photo = $donation->user->profile_photo ?? null;
                    $imgSrc = $photo
                        ? asset('storage/' . $photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($donation->user->name ?? 'User');
                @endphp

                <!-- CLICKABLE IMAGE -->
                <img src="{{ $imgSrc }}"
                    class="w-14 h-14 rounded-full border-2 border-teal-500 cursor-pointer hover:scale-110 transition"
                    onclick="openImageModal('{{ $imgSrc }}')">

                <div>
                    <p class="font-semibold">{{ $donation->user->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $donation->user->email ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $donation->user->phone ?? 'N/A' }}</p>
                </div>

            </div>
        </td>

        <td class="px-4 py-3 font-semibold text-green-600">
            NPR {{ number_format($donation->amount, 2) }}
        </td>

        <td class="px-4 py-3">
            @if (str_starts_with($donation->transaction_id, 'txn_'))
                eSewa
            @else
                Khalti
            @endif
        </td>

        <td class="px-4 py-3">
            {{ $donation->transaction_id }}
        </td>

        <td class="px-4 py-3">

            @if ($donation->payment_status == 'completed')
                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">
                    Completed
                </span>
            @elseif ($donation->payment_status == 'pending')
                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">
                    Pending
                </span>
            @else
                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">
                    Failed
                </span>
            @endif

        </td>

        <td class="px-4 py-3 text-sm text-gray-500">
            {{ $donation->created_at->format('Y-m-d H:i') }}
        </td>

    </tr>
@endforeach
