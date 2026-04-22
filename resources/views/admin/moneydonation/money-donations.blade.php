@extends('layouts.admin')

@section('title', 'Money Donations')

@section('content')

    <h1 class="text-2xl font-bold mb-6">Money Donation List</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-lg shadow">
            <h2 class="text-sm font-semibold text-green-700">Completed Donations</h2>
            <p class="text-2xl font-bold text-green-700">
                NPR {{ number_format($totalCompleted, 2) }}
            </p>
        </div>

        <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded-lg shadow">
            <h2 class="text-sm font-semibold text-yellow-700">Pending Donations</h2>
            <p class="text-2xl font-bold text-yellow-700">
                NPR {{ number_format($totalPending, 2) }}
            </p>
        </div>

        <div class="bg-red-100 border-l-4 border-red-600 p-4 rounded-lg shadow">
            <h2 class="text-sm font-semibold text-red-700">Failed Donations</h2>
            <p class="text-2xl font-bold text-red-700">
                NPR {{ number_format($totalFailed, 2) }}
            </p>
        </div>

    </div>

    <div class="bg-white shadow rounded-lg p-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Total Donation</h2>
        <p class="text-2xl font-bold text-green-600">
            NPR {{ number_format($totalAmount, 2) }}
        </p>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-3">

        <!-- SEARCH (takes remaining space) -->
        <div class="flex-1">
            <input type="text" id="searchInput" placeholder="Search by name, email or transaction ID..."
                class="w-full border-2 border-gray-400 focus:border-teal-600 focus:ring-2 focus:ring-teal-200 px-4 py-2 rounded-lg font-medium text-gray-800">
        </div>

        <!-- FILTER -->
        <div class="flex items-center gap-2 whitespace-nowrap">
            <span class="text-sm font-semibold text-gray-600">
                Filter by:
            </span>

            <select id="methodFilter" class="border px-4 py-2 rounded-lg">
                <option value="">All Methods</option>
                <option value="esewa">eSewa</option>
                <option value="khalti">Khalti</option>
            </select>
        </div>

    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-teal-600 text-white">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Method</th>
                    <th class="px-4 py-3">Transaction</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>

            <tbody id="donationTable">
                @include('admin.partials.donation_rows')
            </tbody>
        </table>
    </div>

    <!-- LOAD MORE -->
    <div class="text-center mt-6">
        @if ($donations->hasMorePages())
            <button id="loadMoreBtn" onclick="loadMore()" class="bg-gray-800 text-white px-6 py-2 rounded-lg">
                Load More
            </button>
        @endif
    </div>

    <!-- IMAGE MODAL -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-70 justify-center items-center z-50">

        <div class="bg-white p-4 rounded-lg">
            <img id="modalImage" class="max-w-2xl max-h-[80vh] rounded shadow-lg">
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        let page = 1;
        let currentSearch = "";
        let currentMethod = "";
        let searchTimeout = null;

        // 🔍 LIVE SEARCH (DEBOUNCED)
        document.getElementById('searchInput').addEventListener('input', function(e) {

            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value;
                page = 1;

                fetchData();
            }, 300); // ⏱ delay to avoid spam requests
        });

        // 🔽 FILTER CHANGE
        document.getElementById('methodFilter').addEventListener('change', function() {
            currentMethod = this.value;
            page = 1;

            fetchData();
        });

        // 📦 MAIN FETCH FUNCTION
        function fetchData() {
            fetch(window.location.pathname + `?page=${page}&search=${currentSearch}&method=${currentMethod}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {

                    document.getElementById('donationTable').innerHTML = data.html;

                    if (data.hasMore) {
                        document.getElementById('loadMoreBtn').style.display = 'inline-block';
                    } else {
                        document.getElementById('loadMoreBtn').style.display = 'none';
                    }
                })
                .catch(err => console.error(err));
        }

        // 🔽 LOAD MORE
        function loadMore() {
            page++;

            fetch(window.location.pathname + `?page=${page}&search=${currentSearch}&method=${currentMethod}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {

                    document.getElementById('donationTable')
                        .insertAdjacentHTML('beforeend', data.html);

                    if (!data.hasMore) {
                        document.getElementById('loadMoreBtn').style.display = 'none';
                    }
                })
                .catch(err => console.error(err));
        }

        // 🔍 SEARCH + FILTER
        function searchDonations() {
            currentSearch = document.getElementById('searchInput').value;
            currentMethod = document.getElementById('methodFilter').value;
            page = 2;

            fetch(window.location.pathname + `?search=${currentSearch}&method=${currentMethod}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {

                    document.getElementById('donationTable').innerHTML = data.html;

                    if (data.hasMore) {
                        document.getElementById('loadMoreBtn').style.display = 'inline-block';
                    } else {
                        document.getElementById('loadMoreBtn').style.display = 'none';
                    }
                })
                .catch(err => console.error('Search error:', err));
        }

        // 🔍 ENTER KEY SEARCH
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchDonations();
            }
        });

        // 🎯 AUTO FILTER
        document.getElementById('methodFilter').addEventListener('change', function() {
            searchDonations();
        });

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('imageModal');

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
@endpush
