<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloth Bank - Twitter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #f5f8fa;
        }

        .tw-header {
            background: white;
            border-bottom: 1px solid #e6ecf0;
        }

        .tw-tweet {
            background: white;
            border: 1px solid #e6ecf0;
            border-radius: 16px;
        }

        .tw-trend {
            background: white;
            border: 1px solid #e6ecf0;
            border-radius: 16px;
        }
    </style>
</head>

<body>
    <!-- Twitter Header -->
    <div class="tw-header py-3 px-4 shadow-sm">
        <div class="container mx-auto max-w-6xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fab fa-twitter text-blue-400 text-3xl"></i>
                <span class="text-xl font-bold">Cloth Bank</span>
            </div>
            <div class="flex items-center gap-4">
                <i class="fas fa-home text-xl"></i>
                <i class="fas fa-hashtag text-xl"></i>
                <i class="fas fa-bell text-xl"></i>
                <i class="fas fa-envelope text-xl"></i>
                <i class="fas fa-user-circle text-2xl"></i>
                <button class="bg-blue-400 text-white px-4 py-1 rounded-full font-bold text-sm">Tweet</button>
            </div>
        </div>
    </div>

    <!-- Twitter Body -->
    <div class="container mx-auto max-w-6xl py-6 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Sidebar -->
            <div class="hidden md:block">
                <div class="tw-trend p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-5xl text-gray-400"></i>
                        <div>
                            <div class="font-bold">Cloth Bank</div>
                            <div class="text-sm text-gray-500">@Cloth Bank</div>
                        </div>
                    </div>
                    <div class="flex gap-4 mt-3 text-sm">
                        <div><span class="font-bold">12.4K</span> <span class="text-gray-500">Following</span></div>
                        <div><span class="font-bold">8.9K</span> <span class="text-gray-500">Followers</span></div>
                    </div>
                </div>
                <div class="tw-trend p-4 mt-4">
                    <h3 class="font-bold text-lg">Trending</h3>
                    <div class="mt-3 space-y-3">
                        <div><span class="text-sm text-gray-500">1. Trending</span>
                            <p class="font-bold">#DonateClothes</p>
                        </div>
                        <div><span class="text-sm text-gray-500">2. Trending</span>
                            <p class="font-bold">#WinterDrive</p>
                        </div>
                        <div><span class="text-sm text-gray-500">3. Trending</span>
                            <p class="font-bold">#Cloth Bank</p>
                        </div>
                        <div><span class="text-sm text-gray-500">4. Trending</span>
                            <p class="font-bold">#CommunitySupport</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Feed -->
            <div class="md:col-span-2 space-y-4">
                <!-- Tweet 1 -->
                <div class="tw-tweet p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                        <div>
                            <div>
                                <span class="font-bold">Cloth Bank</span>
                                <span class="text-gray-500">@Cloth Bank</span>
                                <span class="text-gray-500">· 3h</span>
                            </div>
                            <p class="mt-1">🌟 Today we reached 10,000 donations! Thank you to everyone who has
                                contributed to making a difference in our community. Every piece of clothing donated
                                helps someone in need. ❤️</p>
                            <div class="mt-3 bg-blue-50 p-4 rounded-lg text-center">
                                <p class="font-bold text-teal-600">10,000 Donations</p>
                                <p class="text-sm text-gray-500">And counting...</p>
                            </div>
                            <div class="flex gap-4 mt-3 text-gray-500">
                                <button class="hover:text-blue-400"><i class="far fa-comment"></i> 127</button>
                                <button class="hover:text-green-400"><i class="fas fa-retweet"></i> 89</button>
                                <button class="hover:text-red-400"><i class="far fa-heart"></i> 2.4K</button>
                                <button class="hover:text-blue-400"><i class="fas fa-chart-simple"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tweet 2 -->
                <div class="tw-tweet p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                        <div>
                            <div>
                                <span class="font-bold">Cloth Bank</span>
                                <span class="text-gray-500">@Cloth Bank</span>
                                <span class="text-gray-500">· 1d</span>
                            </div>
                            <p class="mt-1">📦 Winter clothing drive is now open! We're collecting warm clothes for
                                families in need. Drop off your donations or schedule a pickup today. ❄️</p>
                            <div class="mt-3 flex gap-2">
                                <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">🧥 Jackets</span>
                                <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">🧶 Sweaters</span>
                                <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">🧣 Scarves</span>
                            </div>
                            <div class="flex gap-4 mt-3 text-gray-500">
                                <button class="hover:text-blue-400"><i class="far fa-comment"></i> 94</button>
                                <button class="hover:text-green-400"><i class="fas fa-retweet"></i> 56</button>
                                <button class="hover:text-red-400"><i class="far fa-heart"></i> 1.8K</button>
                                <button class="hover:text-blue-400"><i class="fas fa-chart-simple"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <a href="{{ route('home') }}"
        class="fixed bottom-4 right-4 bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a> --}}
</body>

</html>
