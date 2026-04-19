@extends('layouts.master')

@section('title', 'Payment Failed')

@section('content')

    <div class="container mx-auto px-6 py-16 text-center">

        <div class="bg-white shadow-lg rounded-xl p-8 max-w-lg mx-auto">

            <div class="text-red-600 text-5xl mb-4">
                ✖
            </div>

            <h1 class="text-2xl font-bold mb-2">Payment Failed</h1>

            <p class="text-gray-600 mb-4">
                Something went wrong with your payment. Please try again.
            </p>

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <a href="{{ route('user.donate-money') }}"
                class="inline-block mt-4 bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700">
                Try Again
            </a>

        </div>

    </div>

@endsection
