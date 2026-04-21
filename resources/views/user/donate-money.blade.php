@extends('layouts.master')

@section('title', 'Donate Money')

@section('content')

    <div class="container mx-auto px-6 py-12">

        <!-- HEADER -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-teal-700">Support ThreadsOfHope 💚</h1>
            <p class="text-gray-600 mt-2 max-w-xl mx-auto">
                Your small contribution can make a big difference. Help us provide clothes to those in need.
            </p>
        </div>

        <!-- DONATION BOX -->
        <div class="max-w-xl mx-auto bg-white shadow-lg rounded-xl p-6">

            <!-- AMOUNT -->
            <label class="block mb-2 font-semibold">Enter Amount (NPR)</label>
            <input type="number" id="amount" class="w-full border p-3 rounded mb-6" placeholder="Enter donation amount">

            <!-- OPTIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- eSewa -->
                <div onclick="payEsewa()"
                    class="cursor-pointer border rounded-xl p-4 text-center hover:shadow-lg hover:scale-105 transition">

                    <img src="{{ asset('images/logos/esewa-logo-png_seeklogo-469833.png') }}" class="h-28 mx-auto mb-3">

                    <h2 class="font-semibold text-green-700">Pay with eSewa</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Fast and secure payment using eSewa wallet.
                    </p>
                </div>

                <!-- Khalti -->
                <div id="khaltiBtn"
                    class="cursor-pointer border rounded-xl p-4 text-center hover:shadow-lg hover:scale-105 transition">

                    <img src="{{ asset('images/logos/khalti-logo.jpg') }}" class="h-28 mx-auto mb-3">

                    <h2 class="font-semibold text-purple-700">Pay with Khalti</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Easy payment with Khalti digital wallet.
                    </p>
                </div>

            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <!-- Khalti Script -->
    {{-- <script src="https://khalti.com/static/khalti-checkout.js"></script> --}}

    <script>
        // ================= eSewa Redirect =================
        function payEsewa() {
            let amount = document.getElementById('amount').value;

            if (!amount || amount <= 0) {
                alert("Please enter valid amount");
                return;
            }

            fetch("/esewa/store", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        amount: amount
                    })
                })
                .then(res => res.json())
                .then(data => {
                    window.location.href = data.redirect_url;
                });
        }

        // ================= Khalti (NEW API FLOW) =================
        document.getElementById("khaltiBtn").onclick = function() {
            let amount = document.getElementById('amount').value;

            if (!amount || amount <= 0) {
                alert("Please enter valid amount");
                return;
            }

            fetch("/khalti/initiate", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        amount: amount
                    })
                })
                .then(async res => {
                    let text = await res.text(); // 👈 read raw response
                    console.log(text); // 👈 SEE ERROR HERE

                    try {
                        return JSON.parse(text);
                    } catch {
                        throw new Error("Invalid JSON: " + text);
                    }
                })
                .then(data => {
                    console.log("FULL RESPONSE:", data); // 👈 ADD THIS

                    if (data.payment_url) {
                        window.location.href = data.payment_url;
                    } else {
                        alert("Error: " + JSON.stringify(data));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error: " + err.message);
                });
        };
    </script>
@endpush
