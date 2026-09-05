<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloth Bank - Instagram</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #fafafa;
        }

        .insta-header {
            background: white;
            border-bottom: 1px solid #dbdbdb;
        }

        .insta-post {
            background: white;
            border: 1px solid #dbdbdb;
            border-radius: 8px;
        }

        .insta-story {
            border: 3px solid #e1306c;
            padding: 2px;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <!-- Instagram Header -->
    <div class="insta-header py-3 px-4 shadow-sm">
        <div class="container mx-auto max-w-4xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fab fa-instagram text-3xl"></i>
                <span class="text-xl font-bold">Cloth Bank</span>
            </div>
            <div class="flex items-center gap-4">
                <i class="fas fa-home text-xl"></i>
                <i class="fas fa-paper-plane text-xl"></i>
                <i class="fas fa-compass text-xl"></i>
                <i class="fas fa-heart text-xl"></i>
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Instagram Body -->
    <div class="container mx-auto max-w-4xl py-6 px-4">
        <!-- Stories -->
        <div class="flex gap-4 overflow-x-auto pb-4 mb-4">
            <div class="text-center flex-shrink-0">
                <div class="insta-story">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">📸</div>
                </div>
                <p class="text-xs mt-1">Our Story</p>
            </div>
            <div class="text-center flex-shrink-0">
                <div class="insta-story">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">👕</div>
                </div>
                <p class="text-xs mt-1">Donations</p>
            </div>
            <div class="text-center flex-shrink-0">
                <div class="insta-story">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">❤️</div>
                </div>
                <p class="text-xs mt-1">Impact</p>
            </div>
            <div class="text-center flex-shrink-0">
                <div class="insta-story">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">🌍</div>
                </div>
                <p class="text-xs mt-1">Community</p>
            </div>
            <div class="text-center flex-shrink-0">
                <div class="insta-story">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">🎄</div>
                </div>
                <p class="text-xs mt-1">Winter Drive</p>
            </div>
        </div>

        <!-- Post 1 -->
        <div class="insta-post mb-6">
            <div class="flex items-center gap-3 p-4">
                <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                <div>
                    <div class="font-bold text-sm">Cloth Bank</div>
                    <div class="text-xs text-gray-500">Kathmandu, Nepal</div>
                </div>
                <i class="fas fa-ellipsis-h ml-auto"></i>
            </div>
            <div class="bg-gray-100 h-64 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-tshirt text-teal-600 text-6xl"></i>
                    <p class="mt-2 font-bold text-gray-700">10,000 Donations!</p>
                    <p class="text-sm text-gray-500">Thank you for your support</p>
                </div>
            </div>
            <div class="p-4">
                <div class="flex gap-4 text-2xl">
                    <i class="far fa-heart"></i>
                    <i class="far fa-comment"></i>
                    <i class="far fa-paper-plane"></i>
                    <i class="far fa-bookmark ml-auto"></i>
                </div>
                <div class="mt-2">
                    <span class="font-bold text-sm">2,456 likes</span>
                    <p class="mt-1"><span class="font-bold">Cloth Bank</span> 🌟 Today we reached 10,000 donations!
                        Thank you to everyone who has contributed to making a difference in our community. Every piece
                        of clothing donated helps someone in need. ❤️</p>
                    <p class="text-sm text-gray-500 mt-1">View all 127 comments</p>
                    <div class="mt-2 flex items-center gap-2">
                        <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                        <input type="text" placeholder="Add a comment..."
                            class="flex-1 bg-gray-50 rounded-full px-4 py-1 text-sm focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Post 2 -->
        <div class="insta-post mb-6">
            <div class="flex items-center gap-3 p-4">
                <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                <div>
                    <div class="font-bold text-sm">Cloth Bank</div>
                    <div class="text-xs text-gray-500">Winter Collection</div>
                </div>
                <i class="fas fa-ellipsis-h ml-auto"></i>
            </div>
            <div class="bg-gray-100 h-64 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-snowflake text-teal-600 text-6xl"></i>
                    <p class="mt-2 font-bold text-gray-700">Winter Clothing Drive</p>
                    <p class="text-sm text-gray-500">Now accepting donations</p>
                </div>
            </div>
            <div class="p-4">
                <div class="flex gap-4 text-2xl">
                    <i class="far fa-heart"></i>
                    <i class="far fa-comment"></i>
                    <i class="far fa-paper-plane"></i>
                    <i class="far fa-bookmark ml-auto"></i>
                </div>
                <div class="mt-2">
                    <span class="font-bold text-sm">1,834 likes</span>
                    <p class="mt-1"><span class="font-bold">Cloth Bank</span> 📦 Winter clothing drive is now open!
                        We're collecting warm clothes for families in need. Drop off your donations or schedule a pickup
                        today. ❄️</p>
                    <p class="text-sm text-gray-500 mt-1">View all 94 comments</p>
                    <div class="mt-2 flex items-center gap-2">
                        <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                        <input type="text" placeholder="Add a comment..."
                            class="flex-1 bg-gray-50 rounded-full px-4 py-1 text-sm focus:outline-none">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <a href="{{ route('home') }}"
        class="fixed bottom-4 right-4 bg-gradient-to-r from-purple-500 to-pink-500 hover:opacity-90 text-white px-4 py-2 rounded-lg shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a> --}}
</body>

</html>
