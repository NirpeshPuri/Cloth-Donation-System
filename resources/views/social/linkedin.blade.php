<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloth Bank - LinkedIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #f3f2ef;
        }

        .li-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
        }

        .li-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- LinkedIn Header -->
    <div class="li-header py-2 px-4">
        <div class="container mx-auto max-w-6xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fab fa-linkedin text-blue-700 text-3xl"></i>
                <span class="text-xl font-bold text-gray-800">Cloth Bank</span>
            </div>
            <div class="flex items-center gap-4">
                <i class="fas fa-home text-xl"></i>
                <i class="fas fa-users text-xl"></i>
                <i class="fas fa-bell text-xl"></i>
                <i class="fas fa-comment-dots text-xl"></i>
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- LinkedIn Body -->
    <div class="container mx-auto max-w-6xl py-6 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Sidebar -->
            <div class="hidden md:block">
                <div class="li-card p-4 text-center">
                    <i class="fas fa-user-circle text-6xl text-gray-400"></i>
                    <div class="font-bold text-lg mt-2">Cloth Bank</div>
                    <div class="text-sm text-gray-500">Nonprofit Organization</div>
                    <div class="mt-2 text-sm">🌍 Kathmandu, Nepal</div>
                    <button class="w-full mt-3 bg-blue-600 text-white py-1 rounded-full font-bold">Follow</button>
                </div>
                <div class="li-card p-4 mt-4">
                    <h3 class="font-bold">Recent Activity</h3>
                    <div class="mt-2 space-y-2 text-sm">
                        <div class="flex items-center gap-2"><i class="fas fa-tshirt text-teal-600"></i> 10,000
                            Donations</div>
                        <div class="flex items-center gap-2"><i class="fas fa-snowflake text-blue-400"></i> Winter Drive
                            Open</div>
                        <div class="flex items-center gap-2"><i class="fas fa-users text-green-500"></i> 200+ Volunteers
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Feed -->
            <div class="md:col-span-2 space-y-4">
                <!-- Post 1 -->
                <div class="li-card p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                        <div>
                            <div class="font-bold">Cloth Bank</div>
                            <div class="text-sm text-gray-500">3 hours ago</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p>🌟 Today we reached 10,000 donations! Thank you to everyone who has contributed to making a
                            difference in our community. Every piece of clothing donated helps someone in need.</p>
                    </div>
                    <div class="mt-3 bg-gray-50 p-4 rounded-lg text-center">
                        <i class="fas fa-tshirt text-teal-600 text-3xl"></i>
                        <p class="font-bold mt-1">10,000 Donations</p>
                        <p class="text-sm text-gray-500">And counting...</p>
                    </div>
                    <div class="flex gap-4 mt-3 text-gray-500 text-sm">
                        <button class="hover:text-blue-600"><i class="far fa-thumbs-up"></i> 2.4K</button>
                        <button class="hover:text-blue-600"><i class="far fa-comment"></i> 127</button>
                        <button class="hover:text-blue-600"><i class="fas fa-retweet"></i> 89</button>
                    </div>
                </div>

                <!-- Post 2 -->
                <div class="li-card p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-4xl text-gray-400"></i>
                        <div>
                            <div class="font-bold">Cloth Bank</div>
                            <div class="text-sm text-gray-500">1 day ago</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p>📦 Winter clothing drive is now open! We're collecting warm clothes for families in need.
                            Drop off your donations or schedule a pickup today.</p>
                    </div>
                    <div class="mt-3 bg-gray-50 p-4 rounded-lg text-center">
                        <i class="fas fa-snowflake text-blue-400 text-3xl"></i>
                        <p class="font-bold mt-1">Winter Clothing Drive</p>
                        <p class="text-sm text-gray-500">Now accepting donations</p>
                    </div>
                    <div class="flex gap-4 mt-3 text-gray-500 text-sm">
                        <button class="hover:text-blue-600"><i class="far fa-thumbs-up"></i> 1.8K</button>
                        <button class="hover:text-blue-600"><i class="far fa-comment"></i> 94</button>
                        <button class="hover:text-blue-600"><i class="fas fa-retweet"></i> 56</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <a href="{{ route('home') }}"
        class="fixed bottom-4 right-4 bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a> --}}
</body>

</html>
