@extends('layouts.admin')

@section('title', 'User Requests')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to User
            </a>
        </div>

        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Requests by {{ $user->name }}</h1>
            <p class="text-teal-100">All request history for this user</p>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Item</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Quantity</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $request->cloth->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center">{{ $request->quantity }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $request->status == 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $request->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
@endsection
