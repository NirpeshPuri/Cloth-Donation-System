@extends('layouts.master')

@section('title', 'Payment Success')

@section('content')

    <style>
        @media print {

            button,
            a {
                display: none !important;
            }
        }
    </style>

    <div class="container mx-auto px-6 py-16 text-center">

        <div class="bg-white shadow-lg rounded-xl p-8 max-w-lg mx-auto">

            <div class="text-green-600 text-5xl mb-4">
                ✔
            </div>

            <h1 class="text-2xl font-bold mb-2">Payment Successful 🎉</h1>

            <p class="text-gray-600 mb-4">
                Thank you for your donation. Your support helps people in need.
            </p>

            <div class="bg-gray-100 rounded p-4 text-left text-sm">
                <p><strong>Transaction ID:</strong> {{ $transaction_id }}</p>
                <p><strong>Amount:</strong> NPR {{ $amount }}</p>
            </div>

            <div class="bg-gray-100 rounded p-4 text-left text-sm mt-4">

                <h2 class="text-lg font-bold mb-3 text-green-700">Donation Receipt</h2>

                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

                <p><strong>Transaction ID:</strong> {{ $transaction_id }}</p>
                <p><strong>Amount:</strong> NPR {{ $amount }}</p>

                <p><strong>Status:</strong> Completed</p>

            </div>
            <button onclick="window.print()" class="mt-4 bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                Download Receipt (PDF)
            </button>

            <a href="{{ route('user.home') }}"
                class="inline-block mt-6 bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700">
                Back to home
            </a>

        </div>

    </div>

@endsection
