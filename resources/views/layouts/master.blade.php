<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cloth Bank')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }

        /* Cart Badge Animation */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            min-width: 20px;
            text-align: center;
            line-height: 1.4;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Toast Notification */
        .toast-message {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            color: white;
            font-weight: 500;
            z-index: 9999;
            animation: slideUp 0.3s ease-out;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            max-width: 400px;
        }

        .toast-success {
            background: #0d9488;
        }

        .toast-error {
            background: #ef4444;
        }

        .toast-info {
            background: #3b82f6;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toast-message .toast-close {
            margin-left: 1rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .toast-message .toast-close:hover {
            opacity: 1;
        }

        /* Spinner for add to cart button */
        .btn-cart .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .btn-cart.loading .spinner {
            display: inline-block;
        }

        .btn-cart.loading .btn-text {
            display: none;
        }

        .btn-cart.in-cart {
            background: #059669;
        }

        .btn-cart.in-cart .btn-text {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Product Actions */
        .product-actions {
            display: flex;
            gap: 0.5rem;
            padding: 0.75rem 1rem 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .btn-view {
            flex: 1;
            background: #f3f4f6;
            color: #4b5563;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-view:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .btn-cart {
            flex: 1;
            background: #0d9488;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-cart:hover {
            background: #0f766e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .btn-cart:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50">

    <!-- ================= NAVBAR ================= -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">

            <div class="flex justify-between items-center">

                <!-- LOGO -->
                <div class="flex items-center space-x-2">
                    <i class="fas fa-tshirt text-teal-700 text-2xl"></i>
                    <span class="font-extrabold text-2xl text-teal-800">
                        Cloth Bank<span class="text-teal-600"></span>
                    </span>
                </div>

                <!-- LINKS -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('user.home') }}" class="text-gray-700 hover:text-teal-600">Home</a>
                    <a href="{{ route('user.donate') }}" class="text-gray-700 hover:text-teal-600">Donate</a>
                    <a href="{{ route('user.my-donations') }}" class="text-gray-700 hover:text-teal-600">My
                        Donations</a>
                    <a href="{{ route('user.my-requests') }}" class="text-gray-700 hover:text-teal-600">My Requests</a>
                    <a href="{{ route('user.donate-money') }}" class="text-gray-700 hover:text-teal-600">Donate
                        Money</a>
                    <a href="{{ route('user.profile') }}" class="text-gray-700 hover:text-teal-600">Profile</a>

                    <!-- CART LINK WITH BADGE -->
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-teal-600 transition relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cartCount" class="cart-badge"
                            style="display: {{ isset($cartCount) && $cartCount > 0 ? 'block' : 'none' }};">
                            {{ isset($cartCount) ? $cartCount : 0 }}
                        </span>
                    </a>
                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center space-x-4">

                    {{-- <span class="text-gray-700 hidden md:inline">
                        Welcome, {{ Auth::user()->name }}!
                    </span> --}}

                    <!-- PROFILE ICON (CLICK = OPEN MODAL) -->
                    <img id="openProfileModal"
                        src="{{ Auth::user()->profile_photo
                            ? asset('storage/' . Auth::user()->profile_photo)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-teal-500 cursor-pointer hover:scale-110 transition">

                    <!-- LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </nav>

    <!-- ================= MAIN ================= -->
    <main>
        @yield('content')
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-gray-900 text-gray-300 mt-12 py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2026 Cloth Bank</p>
        </div>
    </footer>

    <!-- ================= PROFILE MODAL ================= -->
    <div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center">

        <div class="bg-white w-[90%] md:w-[400px] rounded-xl p-6 relative">

            <!-- CLOSE -->
            <button id="closeProfileModal" class="absolute top-2 right-3 text-xl text-gray-600">
                ✕
            </button>

            <!-- BIG PROFILE IMAGE -->
            <div class="flex justify-center">
                <img src="{{ Auth::user()->profile_photo
                    ? asset('storage/' . Auth::user()->profile_photo)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                    class="w-40 h-40 rounded-full border-4 border-teal-500 object-cover">
            </div>

            <!-- DETAILS -->
            <div class="text-center mt-4 space-y-1">
                <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                <p class="text-gray-600">{{ Auth::user()->phone }}</p>
                <p class="text-gray-600">{{ Auth::user()->address }}</p>
            </div>

        </div>
    </div>

    <!-- ================= SCRIPTS ================= -->
    <script>
        // ========== PROFILE MODAL ==========
        const openBtn = document.getElementById('openProfileModal');
        const modal = document.getElementById('profileModal');
        const closeBtn = document.getElementById('closeProfileModal');

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // ========== CART FUNCTIONS ==========

        /**
         * Update the cart count badge in the navbar
         */
        function updateCartCount() {
            fetch('{{ route('cart.count') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const cartBadge = document.getElementById('cartCount');
                    if (data.count > 0) {
                        cartBadge.textContent = data.count;
                        cartBadge.style.display = 'block';
                    } else {
                        cartBadge.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error updating cart count:', error);
                });
        }

        /**
         * Add item to cart
         * @param {number} clothId - The ID of the cloth
         * @param {number} quantity - Available quantity
         */
        function addToCart(clothId, quantity) {
            if (quantity <= 0) {
                showToast('This item is currently out of stock.', 'error');
                return;
            }

            const btn = document.getElementById('cart-btn-' + clothId);
            if (btn) {
                btn.classList.add('loading');
                btn.disabled = true;
            }

            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        cloth_id: clothId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (btn) {
                        btn.classList.remove('loading');
                        btn.disabled = false;
                    }

                    if (data.success) {
                        // Update button if exists
                        if (btn) {
                            btn.classList.add('in-cart');
                            const btnText = btn.querySelector('.btn-text');
                            if (btnText) {
                                btnText.innerHTML = '<i class="fas fa-check"></i> In Cart';
                            }
                            btn.disabled = true;
                        }

                        // Update cart badge
                        updateCartCount();

                        // Update stats card if on home page
                        const cartStats = document.querySelectorAll(
                            '.grid.md\\:grid-cols-3 .text-2xl.font-bold.text-teal-600');
                        if (cartStats.length > 0) {
                            const lastStat = cartStats[cartStats.length - 1];
                            const currentCount = parseInt(lastStat.textContent) || 0;
                            lastStat.textContent = currentCount + 1;
                        }

                        showToast(data.message || 'Item added to cart!', 'success');
                    } else {
                        showToast(data.message || 'Failed to add item to cart.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (btn) {
                        btn.classList.remove('loading');
                        btn.disabled = false;
                    }
                    showToast('An error occurred. Please try again.', 'error');
                });
        }

        /**
         * Toast Notification System
         * @param {string} message - The message to display
         * @param {string} type - success, error, or info
         */
        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast-message');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = `toast-message toast-${type}`;

            let icon = 'fa-info-circle';
            if (type === 'success') icon = 'fa-check-circle';
            else if (type === 'error') icon = 'fa-exclamation-circle';

            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas ${icon} text-xl"></i>
                        <span>${message}</span>
                    </div>
                    <span class="toast-close" onclick="this.parentElement.parentElement.remove()">&times;</span>
                </div>
            `;

            document.body.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }

        /**
         * Escape HTML for safe display
         */
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ========== INITIALIZATION ==========

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        // Update cart count when coming back from cart page
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateCartCount();
            }
        });

        // Update cart count when going back/forward in browser
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                updateCartCount();
            }
        });
    </script>

    @stack('scripts')

</body>

</html>
