<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloth Bank - Facebook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #f0f2f5;
        }

        .fb-header {
            background: #1877f2;
        }

        .fb-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .fb-post {
            border-bottom: 1px solid #e4e6eb;
        }
    </style>
</head>

<body>
    <!-- Facebook Header -->
    <div class="fb-header text-white py-3 px-4 shadow-md">
        <div class="container mx-auto max-w-5xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fab fa-facebook text-3xl"></i>
                <span class="text-xl font-bold">Cloth Bank</span>
            </div>
            <div class="flex items-center gap-4">
                <i class="fas fa-search text-xl"></i>
                <i class="fas fa-home text-xl"></i>
                <i class="fas fa-bell text-xl"></i>
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Facebook Body -->
    <div class="container mx-auto max-w-5xl py-6 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Sidebar -->
            <div class="hidden md:block">
                <div class="fb-card p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-5xl text-gray-400"></i>
                        <div>
                            <div class="font-bold">Cloth Bank</div>
                            <div class="text-sm text-gray-500">Nonprofit Organization</div>
                        </div>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div
                            class="flex items-center gap-3 text-gray-700 hover:bg-gray-100 p-2 rounded-lg cursor-pointer">
                            <i class="fas fa-home w-6"></i> <span>Home</span>
                        </div>
                        <div
                            class="flex items-center gap-3 text-gray-700 hover:bg-gray-100 p-2 rounded-lg cursor-pointer">
                            <i class="fas fa-store w-6"></i> <span>Our Impact</span>
                        </div>
                        <div
                            class="flex items-center gap-3 text-gray-700 hover:bg-gray-100 p-2 rounded-lg cursor-pointer">
                            <i class="fas fa-heart w-6"></i> <span>Donations</span>
                        </div>
                        <div
                            class="flex items-center gap-3 text-gray-700 hover:bg-gray-100 p-2 rounded-lg cursor-pointer">
                            <i class="fas fa-users w-6"></i> <span>Community</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Feed -->
            <div class="md:col-span-2 space-y-4">
                <!-- Create Post -->
                <div class="fb-card p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                        <input type="text" placeholder="What's on your mind?"
                            class="flex-1 bg-gray-100 rounded-full px-4 py-2 focus:outline-none">
                    </div>
                    <div class="flex justify-around mt-3 pt-3 border-t border-gray-200">
                        <button class="flex items-center gap-2 text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg">
                            <i class="fas fa-video text-red-500"></i> Live
                        </button>
                        <button class="flex items-center gap-2 text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg">
                            <i class="fas fa-image text-green-500"></i> Photo
                        </button>
                        <button class="flex items-center gap-2 text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg">
                            <i class="fas fa-smile text-yellow-500"></i> Feeling
                        </button>
                    </div>
                </div>

                <!-- Post 1 -->
                <div class="fb-card p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                        <div>
                            <div class="font-bold">Cloth Bank</div>
                            <div class="text-sm text-gray-500">3 hours ago</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-gray-800">🌟 Today we reached 10,000 donations! Thank you to everyone who has
                            contributed to making a difference in our community. Every piece of clothing donated helps
                            someone in need.</p>
                    </div>
                    <div class="mt-3 bg-gray-100 rounded-lg p-4 text-center">
                        <i class="fas fa-tshirt text-teal-600 text-3xl"></i>
                        <p class="font-bold mt-2">10,000 Donations</p>
                        <p class="text-sm text-gray-500">And counting...</p>
                    </div>
                    <div class="flex justify-around mt-3 pt-3 border-t border-gray-200">
                        <button class="text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg flex items-center gap-2">
                            <i class="fas fa-thumbs-up"></i> 2.4K
                        </button>
                        <button class="text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg flex items-center gap-2">
                            <i class="fas fa-comment"></i> 127
                        </button>
                        <button class="text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg flex items-center gap-2">
                            <i class="fas fa-share"></i> 89
                        </button>
                    </div>
                </div>

                <!-- Post 2 -->
                <div class="fb-card p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                        <div>
                            <div class="font-bold">Cloth Bank</div>
                            <div class="text-sm text-gray-500">Yesterday</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-gray-800">📦 Winter clothing drive is now open! We're collecting warm clothes for
                            families in need. Drop off your donations or schedule a pickup today.</p>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div class="bg-gray-100 rounded-lg p-4 text-center">
                            <i class="fas fa-vest text-teal-600 text-2xl"></i>
                            <p class="text-sm mt-1">Winter Jackets</p>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-4 text-center">
                            <i class="fas fa-socks text-teal-600 text-2xl"></i>
                            <p class="text-sm mt-1">Sweaters</p>
                        </div>
                    </div>
                    <div class="flex justify-around mt-3 pt-3 border-t border-gray-200">
                        <button class="text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg flex items-center gap-2">
                            <i class="fas fa-thumbs-up"></i> 1.8K
                        </button>
                        <button class="text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg flex items-center gap-2">
                            <i class="fas fa-comment"></i> 94
                        </button>
                        <button class="text-gray-600 hover:bg-gray-100 px-4 py-1 rounded-lg flex items-center gap-2">
                            <i class="fas fa-share"></i> 56
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <a href="{{ route('home') }}"
        class="fixed bottom-4 right-4 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a> --}}
</body>

</html>
