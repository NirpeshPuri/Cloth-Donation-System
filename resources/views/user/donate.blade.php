@extends('layouts.master')

@section('title', 'Donate Clothes - ThreadsOfHope')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
            <h1 class="text-3xl font-bold mb-2">Donate Clothes</h1>
            <p class="text-teal-100">Your unused clothes can change someone's life. Add multiple items to your donation.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Location Info -->
                    @if (isset($userLatitude) && isset($userLongitude))
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-700">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Showing collection centers near your location
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.donate.store') }}" enctype="multipart/form-data"
                        id="donationForm">
                        @csrf

                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Clothes Items</h3>

                        <div id="itemsContainer"></div>

                        <div class="mt-4 mb-6">
                            <button type="button" id="addMoreBtn"
                                class="w-full border-2 border-dashed border-teal-300 hover:border-teal-500 text-teal-600 hover:text-teal-700 py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Add Another Item
                            </button>
                        </div>

                        <!-- Pickup Details -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pickup Details</h3>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Select Collection Center *</label>
                                <select name="admin_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">Select a center near you</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}"
                                            {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }} - {{ $admin->address }}
                                            @if (isset($admin->distance))
                                                ({{ $admin->distance }} km away)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Pickup Address (Optional)</label>
                                <textarea name="pickup_address" rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                    placeholder="Your address for pickup if different from profile">{{ old('pickup_address') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Additional Notes</label>
                                <textarea name="notes" rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                    placeholder="Any special instructions?">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition">
                            <i class="fas fa-gift mr-2"></i> Donate All Items
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-teal-50 rounded-xl p-6 mb-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Why Donate?</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2"><i class="fas fa-heart text-teal-600 mt-1"></i><span
                                class="text-sm">Help someone in need</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-leaf text-teal-600 mt-1"></i><span
                                class="text-sm">Reduce textile waste</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-smile text-teal-600 mt-1"></i><span
                                class="text-sm">Feel good about giving</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-recycle text-teal-600 mt-1"></i><span
                                class="text-sm">Give clothes a second life</span></li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">What We Accept</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Men's clothes</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Women's clothes</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Children's clothes</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Winter wear & jackets</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-sm"></i> <span
                                class="text-sm">Traditional attire</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container .select2-selection--single {
                height: 42px;
                padding: 5px;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 30px;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- jQuery first, then Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            let itemCount = 0;
            const container = document.getElementById('itemsContainer');

            const sizeOptions = {
                men: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
                women: ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                kids: ['0-3M', '3-6M', '6-9M', '9-12M', '12-18M', '18-24M', '2-3Y', '3-4Y', '4-5Y', '5-6Y', '6-7Y', '7-8Y',
                    '8-9Y', '9-10Y', '10-11Y', '11-12Y', '12-13Y'
                ],
                unisex: ['XS', 'S', 'M', 'L', 'XL', 'XXL']
            };

            function updateSizeOptions(selectElement, gender) {
                const itemCard = selectElement.closest('.item-card');
                const sizeSelect = itemCard.querySelector('.size-select');
                sizeSelect.innerHTML = '<option value="">Select Size</option>';

                if (gender && sizeOptions[gender]) {
                    sizeOptions[gender].forEach(size => {
                        const option = document.createElement('option');
                        option.value = size;
                        option.textContent = size;
                        sizeSelect.appendChild(option);
                    });
                    sizeSelect.disabled = false;
                } else {
                    sizeSelect.disabled = true;
                }
            }

            // Function to initialize Select2 on a color select element
            function initColorSelect(element) {
                if (element && typeof $ !== 'undefined') {
                    // Destroy existing Select2 if any
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).select2('destroy');
                    }
                    $(element).select2({
                        placeholder: "Search for a color...",
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $(element).parent()
                    });
                }
            }

            function addNewItem() {
                const itemId = itemCount;
                const itemCard = document.createElement('div');
                itemCard.className = 'item-card bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200';
                itemCard.setAttribute('data-id', itemId);

                // Get categories from PHP
                const categories = @json($categories ?? []);

                // Build category options HTML
                let categoryOptions = '<option value="">Select Type</option>';
                @if (isset($categories) && count($categories) > 0)
                    @foreach ($categories as $cat)
                        categoryOptions += `<option value="{{ $cat['value'] }}">{{ $cat['label'] }}</option>`;
                    @endforeach
                @else
                    categoryOptions += '<option value="Shirt">Shirt</option>';
                    categoryOptions += '<option value="T-Shirt">T-Shirt</option>';
                    categoryOptions += '<option value="Jeans">Jeans</option>';
                    categoryOptions += '<option value="Pants">Pants</option>';
                    categoryOptions += '<option value="Jacket">Jacket</option>';
                    categoryOptions += '<option value="Sweater">Sweater</option>';
                    categoryOptions += '<option value="Dress">Dress</option>';
                    categoryOptions += '<option value="Saree">Saree</option>';
                    categoryOptions += '<option value="Kurta">Kurta</option>';
                    categoryOptions += '<option value="Traditional">Traditional</option>';
                    categoryOptions += '<option value="Other">Other</option>';
                @endif

                itemCard.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-semibold text-gray-700">Item ${itemCount + 1}</h4>
            <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 text-sm">
                <i class="fas fa-trash mr-1"></i> Remove
            </button>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Cloth Name *</label>
                <input type="text" name="items[${itemCount}][cloth_name]" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Cloth Type</label>
                <select name="items[${itemCount}][cloth_type]" class="category-select w-full px-3 py-2 border border-gray-300 rounded-lg">
                    ${categoryOptions}
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Gender *</label>
                <select name="items[${itemCount}][gender]" required class="gender-select w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Select Gender</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                    <option value="kids">Kids</option>
                    <option value="unisex">Unisex</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Size *</label>
                <select name="items[${itemCount}][size]" required class="size-select w-full px-3 py-2 border border-gray-300 rounded-lg" disabled>
                    <option value="">Select Gender First</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Color</label>
                <select name="items[${itemCount}][color]" class="color-select w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Search for a color...</option>

                    <optgroup label="🔴 Red Shades">
                        <option value="Red">🔴 Red</option>
                        <option value="Light Red">🔴 Light Red</option>
                        <option value="Dark Red">🔴 Dark Red</option>
                        <option value="Crimson">❤️ Crimson</option>
                        <option value="Maroon">🟤 Maroon</option>
                        <option value="Burgundy">🍷 Burgundy</option>
                        <option value="Rose">🌹 Rose</option>
                        <option value="Ruby">💎 Ruby</option>
                        <option value="Scarlet">❤️ Scarlet</option>
                        <option value="Cherry">🍒 Cherry</option>
                        <option value="Tomato">🍅 Tomato</option>
                        <option value="Rust">🔧 Rust</option>
                        <option value="Mahogany">🪵 Mahogany</option>
                        <option value="Cardinal">❤️ Cardinal</option>
                        <option value="Fire Brick">🔥 Fire Brick</option>
                        <option value="Indian Red">🇮🇳 Indian Red</option>
                    </optgroup>

                    <optgroup label="🟠 Orange Shades">
                        <option value="Orange">🟠 Orange</option>
                        <option value="Light Orange">🟠 Light Orange</option>
                        <option value="Dark Orange">🟠 Dark Orange</option>
                        <option value="Coral">🪸 Coral</option>
                        <option value="Salmon">🐟 Salmon</option>
                        <option value="Peach">🍑 Peach</option>
                        <option value="Apricot">🍑 Apricot</option>
                        <option value="Tangerine">🍊 Tangerine</option>
                        <option value="Amber">🟠 Amber</option>
                        <option value="Mango">🥭 Mango</option>
                        <option value="Papaya">🍈 Papaya</option>
                        <option value="Pumpkin">🎃 Pumpkin</option>
                        <option value="Orange Red">🟠 Orange Red</option>
                        <option value="Burnt Orange">🔥 Burnt Orange</option>
                    </optgroup>

                    <optgroup label="🟡 Yellow Shades">
                        <option value="Yellow">🟡 Yellow</option>
                        <option value="Light Yellow">🟡 Light Yellow</option>
                        <option value="Dark Yellow">🟡 Dark Yellow</option>
                        <option value="Gold">✨ Gold</option>
                        <option value="Mustard">🟡 Mustard</option>
                        <option value="Lemon">🍋 Lemon</option>
                        <option value="Sunflower">🌻 Sunflower</option>
                        <option value="Honey">🍯 Honey</option>
                        <option value="Butter">🧈 Butter</option>
                        <option value="Banana">🍌 Banana</option>
                        <option value="Canary Yellow">🐤 Canary Yellow</option>
                        <option value="Goldenrod">🌾 Goldenrod</option>
                        <option value="Khaki">🟤 Khaki</option>
                        <option value="Beige">🧵 Beige</option>
                    </optgroup>

                    <optgroup label="🟢 Green Shades">
                        <option value="Green">🟢 Green</option>
                        <option value="Light Green">🟢 Light Green</option>
                        <option value="Dark Green">🟢 Dark Green</option>
                        <option value="Lime">💚 Lime</option>
                        <option value="Olive">🫒 Olive</option>
                        <option value="Mint">🌿 Mint</option>
                        <option value="Emerald">💚 Emerald</option>
                        <option value="Forest Green">🌲 Forest Green</option>
                        <option value="Sea Green">🌊 Sea Green</option>
                        <option value="Teal">💙 Teal</option>
                        <option value="Army Green">🪖 Army Green</option>
                        <option value="Pistachio">🥜 Pistachio</option>
                        <option value="Sage">🌿 Sage</option>
                        <option value="Olive Green">🫒 Olive Green</option>
                        <option value="Chartreuse">💚 Chartreuse</option>
                        <option value="Hunter Green">🏹 Hunter Green</option>
                        <option value="Jade">💚 Jade</option>
                        <option value="Kelly Green">🍀 Kelly Green</option>
                        <option value="Fern Green">🌿 Fern Green</option>
                    </optgroup>

                    <optgroup label="🔵 Blue Shades">
                        <option value="Blue">🔵 Blue</option>
                        <option value="Light Blue">🔵 Light Blue</option>
                        <option value="Dark Blue">🔵 Dark Blue</option>
                        <option value="Sky Blue">☁️ Sky Blue</option>
                        <option value="Baby Blue">👶 Baby Blue</option>
                        <option value="Powder Blue">💙 Powder Blue</option>
                        <option value="Navy Blue">⚓ Navy Blue</option>
                        <option value="Royal Blue">👑 Royal Blue</option>
                        <option value="Cyan">💙 Cyan</option>
                        <option value="Turquoise">💚 Turquoise</option>
                        <option value="Sapphire">💙 Sapphire</option>
                        <option value="Indigo">💜 Indigo</option>
                        <option value="Midnight Blue">🌙 Midnight Blue</option>
                        <option value="Ocean Blue">🌊 Ocean Blue</option>
                        <option value="Steel Blue">🔧 Steel Blue</option>
                        <option value="Cornflower Blue">🌸 Cornflower Blue</option>
                        <option value="Azure">💙 Azure</option>
                        <option value="Cerulean">💙 Cerulean</option>
                        <option value="Cobalt Blue">💙 Cobalt Blue</option>
                        <option value="Denim">👖 Denim</option>
                        <option value="Periwinkle">🌸 Periwinkle</option>
                        <option value="Aqua">💧 Aqua</option>
                    </optgroup>

                    <optgroup label="🟣 Purple Shades">
                        <option value="Purple">🟣 Purple</option>
                        <option value="Light Purple">🟣 Light Purple</option>
                        <option value="Dark Purple">🟣 Dark Purple</option>
                        <option value="Lavender">🪻 Lavender</option>
                        <option value="Violet">🟣 Violet</option>
                        <option value="Magenta">💜 Magenta</option>
                        <option value="Orchid">🌺 Orchid</option>
                        <option value="Plum">🍒 Plum</option>
                        <option value="Lilac">🌸 Lilac</option>
                        <option value="Mauve">🌸 Mauve</option>
                        <option value="Amethyst">💜 Amethyst</option>
                        <option value="Eggplant">🍆 Eggplant</option>
                        <option value="Fuchsia">💖 Fuchsia</option>
                        <option value="Grape">🍇 Grape</option>
                        <option value="Mulberry">🍇 Mulberry</option>
                    </optgroup>

                    <optgroup label="🌸 Pink Shades">
                        <option value="Pink">🌸 Pink</option>
                        <option value="Light Pink">🌸 Light Pink</option>
                        <option value="Dark Pink">🌸 Dark Pink</option>
                        <option value="Hot Pink">💖 Hot Pink</option>
                        <option value="Baby Pink">👶 Baby Pink</option>
                        <option value="Rose Pink">🌹 Rose Pink</option>
                        <option value="Blush">😊 Blush</option>
                        <option value="Coral Pink">🪸 Coral Pink</option>
                        <option value="Salmon Pink">🐟 Salmon Pink</option>
                        <option value="Bubblegum Pink">🍬 Bubblegum Pink</option>
                        <option value="Peach Puff">🍑 Peach Puff</option>
                        <option value="Flamingo Pink">🦩 Flamingo Pink</option>
                        <option value="Magenta Pink">💜 Magenta Pink</option>
                    </optgroup>

                    <optgroup label="🟤 Brown Shades">
                        <option value="Brown">🟤 Brown</option>
                        <option value="Light Brown">🟤 Light Brown</option>
                        <option value="Dark Brown">🟤 Dark Brown</option>
                        <option value="Beige">🧵 Beige</option>
                        <option value="Cream">🥛 Cream</option>
                        <option value="Tan">🏖️ Tan</option>
                        <option value="Chocolate">🍫 Chocolate</option>
                        <option value="Coffee">☕ Coffee</option>
                        <option value="Bronze">🥉 Bronze</option>
                        <option value="Khaki">🟤 Khaki</option>
                        <option value="Caramel">🍬 Caramel</option>
                        <option value="Chestnut">🌰 Chestnut</option>
                        <option value="Cocoa">🍫 Cocoa</option>
                        <option value="Mocha">☕ Mocha</option>
                        <option value="Taupe">🏔️ Taupe</option>
                        <option value="Walnut">🌰 Walnut</option>
                        <option value="Copper">🔶 Copper</option>
                        <option value="Rust">🔧 Rust</option>
                    </optgroup>

                    <optgroup label="◻️ Grey & Silver Shades">
                        <option value="Grey">◻️ Grey</option>
                        <option value="Light Grey">◻️ Light Grey</option>
                        <option value="Dark Grey">◻️ Dark Grey</option>
                        <option value="Silver">⭐ Silver</option>
                        <option value="Charcoal">🖤 Charcoal</option>
                        <option value="Smoke">🌫️ Smoke</option>
                        <option value="Slate">🪨 Slate</option>
                        <option value="Ash Gray">🌫️ Ash Gray</option>
                        <option value="Dove Gray">🕊️ Dove Gray</option>
                        <option value="Graphite">✏️ Graphite</option>
                        <option value="Iron Gray">⚙️ Iron Gray</option>
                        <option value="Platinum">💎 Platinum</option>
                    </optgroup>

                    <optgroup label="⚪ White & Off-White Shades">
                        <option value="White">⚪ White</option>
                        <option value="Off White">⚪ Off White</option>
                        <option value="Ivory">🦷 Ivory</option>
                        <option value="Cream">🥛 Cream</option>
                        <option value="Snow White">❄️ Snow White</option>
                        <option value="Pearl White">🦪 Pearl White</option>
                        <option value="Linen">🧵 Linen</option>
                        <option value="Ecru">🧵 Ecru</option>
                        <option value="Vanilla">🍦 Vanilla</option>
                    </optgroup>

                    <optgroup label="⚫ Black Shades">
                        <option value="Black">⚫ Black</option>
                        <option value="Jet Black">⚫ Jet Black</option>
                        <option value="Charcoal Black">🖤 Charcoal Black</option>
                        <option value="Midnight Black">🌙 Midnight Black</option>
                        <option value="Onyx">💎 Onyx</option>
                        <option value="Raven Black">🐦‍⬛ Raven Black</option>
                    </optgroup>

                    <optgroup label="🌈 Multicolor & Patterns">
                        <option value="Multicolor">🌈 Multicolor</option>
                        <option value="Printed">🎨 Printed</option>
                        <option value="Tie Dye">🎨 Tie Dye</option>
                        <option value="Floral">🌸 Floral</option>
                        <option value="Striped">📏 Striped</option>
                        <option value="Checkered">🔲 Checkered</option>
                        <option value="Polka Dot">⚪ Polka Dot</option>
                        <option value="Plaid">📏 Plaid</option>
                        <option value="Camouflage">🌿 Camouflage</option>
                        <option value="Animal Print">🐆 Animal Print</option>
                        <option value="Ombre">🎨 Ombre</option>
                        <option value="Patchwork">🧩 Patchwork</option>
                    </optgroup>

                    <optgroup label="🎨 Other Colors">
                        <option value="Cyan">💙 Cyan</option>
                        <option value="Aqua">💧 Aqua</option>
                        <option value="Lime">💚 Lime</option>
                        <option value="Olive">🫒 Olive</option>
                        <option value="Mauve">🌸 Mauve</option>
                        <option value="Wine">🍷 Wine</option>
                        <option value="Nude">👚 Nude</option>
                        <option value="Denim">👖 Denim</option>
                        <option value="Lavender">🪻 Lavender</option>
                        <option value="Turquoise">💚 Turquoise</option>
                        <option value="Fuchsia">💖 Fuchsia</option>
                        <option value="Indigo">💜 Indigo</option>
                        <option value="Violet">🟣 Violet</option>
                        <option value="Magenta">💜 Magenta</option>
                        <option value="Teal">💙 Teal</option>
                        <option value="Coral">🪸 Coral</option>
                        <option value="Peach">🍑 Peach</option>
                        <option value="Mint">🌿 Mint</option>
                        <option value="Lavender Blush">🌸 Lavender Blush</option>
                    </optgroup>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Quantity *</label>
                <input type="number" name="items[${itemCount}][quantity]" value="1" min="1" max="100" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Quality *</label>
                <select name="items[${itemCount}][quality]" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Select Quality</option>
                    <option value="new">New (With tags)</option>
                    <option value="like_new">Like New (Worn 1-2 times)</option>
                    <option value="good">Good (Minor wear)</option>
                    <option value="fair">Fair (Visible wear, still usable)</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Description</label>
                <textarea name="items[${itemCount}][description]" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                    placeholder="Any special notes about this item?"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1 text-sm">Image (Optional)</label>
                <input type="file" name="items[${itemCount}][image]" accept="image/*"
                    class="image-input w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700">
                <div class="image-preview hidden mt-2">
                    <img src="#" alt="Preview" class="w-20 h-20 object-cover rounded-lg">
                </div>
                <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed: JPG, PNG, GIF</p>
            </div>
        </div>
    `;

                // Add event listeners
                const genderSelect = itemCard.querySelector('.gender-select');
                genderSelect.addEventListener('change', function() {
                    updateSizeOptions(this, this.value);
                });

                // Initialize Select2 for color dropdown
                const colorSelect = itemCard.querySelector('.color-select');
                if (colorSelect) {
                    setTimeout(() => {
                        if (typeof $ !== 'undefined') {
                            if ($(colorSelect).hasClass('select2-hidden-accessible')) {
                                $(colorSelect).select2('destroy');
                            }
                            $(colorSelect).select2({
                                placeholder: "Search for a color...",
                                allowClear: true,
                                width: '100%',
                                dropdownParent: $(colorSelect).parent()
                            });
                        }
                    }, 10);
                }

                const imageInput = itemCard.querySelector('.image-input');
                const previewDiv = itemCard.querySelector('.image-preview');
                const previewImg = itemCard.querySelector('.image-preview img');

                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewDiv.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewDiv.classList.add('hidden');
                    }
                });

                const removeBtn = itemCard.querySelector('.remove-item-btn');
                removeBtn.addEventListener('click', function() {
                    itemCard.remove();
                    updateItemNumbers();
                });

                container.appendChild(itemCard);
                itemCount++;
            }

            function updateItemNumbers() {
                const items = document.querySelectorAll('.item-card');
                items.forEach((item, index) => {
                    const title = item.querySelector('h4');
                    if (title) {
                        title.textContent = `Item ${index + 1}`;
                    }

                    const inputs = item.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });
                });
            }

            // Initialize first item on page load
            document.addEventListener('DOMContentLoaded', function() {
                addNewItem();
            });

            // Add more button click
            document.getElementById('addMoreBtn').addEventListener('click', addNewItem);
        </script>
    @endpush
@endsection
