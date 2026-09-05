<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Cloth;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ClothSeederMore extends Seeder
{
    // Map products to images
    private $productImages = [
        // Shirts
        'Classic Cotton Shirt' => 'shirt_white.jpg',
        'Striped Casual Shirt' => 'shirt_blue_striped.jpg',
        'Floral Print Top' => 'tshirt_floral.jpg',
        'Plain T-Shirt' => 'tshirt_black.jpg',
        'Premium Formal Shirt' => 'shirt_blue_striped.jpg',
        'Linen Casual Shirt' => 'shirt_white.jpg',
        'Elegant Blouse' => 'tshirt_floral.jpg',
        'Graphic T-Shirt' => 'tshirt_black.jpg',
        'Classic Casual Shirt' => 'shirt_blue_striped.jpg',
        'Silk Blouse' => 'tshirt_floral.jpg',

        // Jeans & Pants
        'Slim Fit Jeans' => 'jeans_blue.jpg',
        'High-Rise Jeans' => 'jeans_dark.jpg',
        'Chino Pants' => 'pants_khaki.jpg',
        'Tailored Trousers' => 'pants_black.jpg',
        'Relaxed Jeans' => 'jeans_blue.jpg',
        'Cargo Pants' => 'pants_khaki.jpg',
        'Casual Pants' => 'pants_black.jpg',
        'Slim Jeans' => 'jeans_dark.jpg',
        'Classic Denim' => 'jeans_blue.jpg',
        'Jogger Pants' => 'pants_khaki.jpg',

        // Jackets & Sweaters
        'Denim Jacket' => 'jacket_denim.jpg',
        'Woolen Sweater' => 'sweater_navy.jpg',
        'Leather Jacket' => 'jacket_denim.jpg',
        'Knitted Cardigan' => 'sweater_navy.jpg',
        'Puffer Jacket' => 'jacket_denim.jpg',
        'Classic Blazer' => 'sweater_navy.jpg',

        // Dresses
        'Summer Dress' => 'dress_floral.jpg',
        'Evening Gown' => 'dress_gown.jpg',
        'Maxi Dress' => 'dress_floral.jpg',
        'Party Dress' => 'dress_gown.jpg',
        'Beach Dress' => 'dress_floral.jpg',
        'Silk Gown' => 'dress_gown.jpg',

        // Traditional
        'Silk Saree' => 'saree_red.jpg',
        'Kurta Pajama' => 'kurta_white.jpg',
        'Embroidered Saree' => 'saree_red.jpg',
        'Designer Kurta' => 'kurta_white.jpg',
        'Festival Lehenga' => 'saree_red.jpg',
        'Dhoti Kurta' => 'kurta_white.jpg',

        // Shoes
        'Leather Formal Shoes' => 'shoes_brown.jpg',
        'White Sneakers' => 'sneakers_white.jpg',
        'Running Shoes' => 'sneakers_white.jpg',
        'Classic Formal Shoes' => 'shoes_brown.jpg',
        'Canvas Shoes' => 'sneakers_white.jpg',
        'Elegant Heels' => 'shoes_brown.jpg',

        // Kids
        'Kids Cartoon T-Shirt' => 'kids_tshirt.jpg',
        'Kids Sport Shoes' => 'kids_shoes.jpg',
        'Kids Summer Dress' => 'kids_tshirt.jpg',
        'Kids Winter Jacket' => 'kids_shoes.jpg',
        'Kids Denim Pants' => 'kids_tshirt.jpg',
        'Kids Party Wear' => 'kids_shoes.jpg',

        // Other
        'Denim Skirt' => 'skirt_denim.jpg',
        'Beanie Hat' => 'beanie_grey.jpg',
        'Leather Belt' => 'beanie_grey.jpg',
        'Printed Scarf' => 'skirt_denim.jpg',
        'Travel Backpack' => 'beanie_grey.jpg',
        'Sunglasses' => 'skirt_denim.jpg',
    ];

    // Different prefixes to make names unique
    private $prefixes = [
        'Premium', 'Deluxe', 'Classic', 'Elite', 'Ultimate',
        'Super', 'Mega', 'Pro', 'Plus', 'Ultra',
        'Royal', 'Grand', 'Supreme', 'Exclusive', 'Prime',
    ];

    public function run()
    {
        $this->command->info('Starting additional cloth seeder...');

        // Get all admins
        $admins = Admin::all();
        if ($admins->isEmpty()) {
            $this->command->error('No admin found! Please run AdminSeeder first.');

            return;
        }

        $donor = User::getAnonymousDonor();

        // Get sample clothes
        $clothes = $this->getSampleClothes();

        // Distribute clothes among admins with unique names
        foreach ($clothes as $index => $clothData) {
            // Cycle through admins
            $admin = $admins[$index % $admins->count()];

            // Get specific image for this product
            $imagePath = $this->getProductImage($clothData['name']);

            // Add random prefix to make name unique
            $prefix = $this->prefixes[array_rand($this->prefixes)];
            $uniqueName = $prefix.' '.$clothData['name'];

            Cloth::create([
                'admin_id' => $admin->id,
                'donor_id' => $donor->id,
                'name' => $uniqueName,
                'category' => $clothData['category'],
                'gender' => $clothData['gender'],
                'size' => $clothData['size'],
                'color' => $clothData['color'],
                'image_path' => $imagePath,
                'quantity' => $clothData['quantity'],
                'quality' => $clothData['quality'],
                'description' => $clothData['description'].' (Premium quality - Additional stock)',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ '.count($clothes).' additional sample clothes added successfully!');
        $this->command->info('📦 Total clothes now: '.Cloth::count());
    }

    private function getProductImage($productName)
    {
        // Check if this product has a specific image mapped
        if (isset($this->productImages[$productName])) {
            $imageName = $this->productImages[$productName];

            // Check if image exists in donation_items directory
            $fullPath = storage_path('app/public/donation_items/'.$imageName);

            if (file_exists($fullPath)) {
                // Copy image from donation_items to clothes directory
                $destinationPath = 'clothes/'.$imageName;

                // If you want to copy the image (recommended for organization)
                if (! Storage::disk('public')->exists($destinationPath)) {
                    Storage::disk('public')->copy('donation_items/'.$imageName, $destinationPath);
                }

                return $destinationPath;
            } else {
                $this->command->warn("Image not found: {$imageName} for product: {$productName}");

                return null;
            }
        }

        return null;
    }

    private function getSampleClothes()
    {
        return [
            // ========== SHIRTS (10 items) ==========
            [
                'name' => 'Classic Cotton Shirt',
                'category' => 'Shirt',
                'gender' => 'men',
                'size' => 'M',
                'color' => 'White',
                'quantity' => 15,
                'quality' => 'new',
                'description' => 'Premium 100% cotton formal shirt with classic fit. Crisp white color perfect for office wear.',
            ],
            [
                'name' => 'Striped Casual Shirt',
                'category' => 'Shirt',
                'gender' => 'men',
                'size' => 'L',
                'color' => 'Blue',
                'quantity' => 10,
                'quality' => 'like_new',
                'description' => 'Blue and white striped casual shirt with button-down collar. Perfect for smart casual looks.',
            ],
            [
                'name' => 'Floral Print Top',
                'category' => 'Shirt',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'Pink',
                'quantity' => 12,
                'quality' => 'new',
                'description' => 'Beautiful floral print top with V-neck and flutter sleeves. Light and breathable.',
            ],
            [
                'name' => 'Plain T-Shirt',
                'category' => 'T-Shirt',
                'gender' => 'unisex',
                'size' => 'L',
                'color' => 'Black',
                'quantity' => 25,
                'quality' => 'new',
                'description' => 'Essential plain black t-shirt, 100% cotton, comfortable fit. Wardrobe staple.',
            ],
            [
                'name' => 'Premium Formal Shirt',
                'category' => 'Shirt',
                'gender' => 'men',
                'size' => 'XL',
                'color' => 'Blue',
                'quantity' => 8,
                'quality' => 'new',
                'description' => 'Premium quality formal shirt in classic blue. Perfect for business meetings.',
            ],
            [
                'name' => 'Linen Casual Shirt',
                'category' => 'Shirt',
                'gender' => 'men',
                'size' => 'M',
                'color' => 'White',
                'quantity' => 6,
                'quality' => 'like_new',
                'description' => 'Lightweight linen shirt in white. Perfect for summer days.',
            ],
            [
                'name' => 'Elegant Blouse',
                'category' => 'Shirt',
                'gender' => 'women',
                'size' => 'S',
                'color' => 'Pink',
                'quantity' => 9,
                'quality' => 'new',
                'description' => 'Elegant blouse with ruffled details. Perfect for parties and events.',
            ],
            [
                'name' => 'Graphic T-Shirt',
                'category' => 'T-Shirt',
                'gender' => 'unisex',
                'size' => 'L',
                'color' => 'Black',
                'quantity' => 14,
                'quality' => 'new',
                'description' => 'Trendy graphic print t-shirt with unique design. 100% cotton.',
            ],
            [
                'name' => 'Classic Casual Shirt',
                'category' => 'Shirt',
                'gender' => 'men',
                'size' => 'M',
                'color' => 'Grey',
                'quantity' => 7,
                'quality' => 'new',
                'description' => 'Classic casual shirt with chest pocket. Versatile and stylish.',
            ],
            [
                'name' => 'Silk Blouse',
                'category' => 'Shirt',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'Cream',
                'quantity' => 5,
                'quality' => 'new',
                'description' => 'Elegant silk blouse in cream color. Perfect for formal occasions.',
            ],

            // ========== JEANS & PANTS (10 items) ==========
            [
                'name' => 'Slim Fit Jeans',
                'category' => 'Jeans',
                'gender' => 'men',
                'size' => '32',
                'color' => 'Blue',
                'quantity' => 8,
                'quality' => 'good',
                'description' => 'Classic slim fit blue jeans with slight stretch for comfort. Versatile and stylish.',
            ],
            [
                'name' => 'High-Rise Jeans',
                'category' => 'Jeans',
                'gender' => 'women',
                'size' => '28',
                'color' => 'Dark Blue',
                'quantity' => 6,
                'quality' => 'like_new',
                'description' => 'High-rise skinny jeans with stretch denim for perfect fit. Flattering and comfortable.',
            ],
            [
                'name' => 'Chino Pants',
                'category' => 'Pants',
                'gender' => 'men',
                'size' => '34',
                'color' => 'Khaki',
                'quantity' => 10,
                'quality' => 'new',
                'description' => 'Comfortable khaki chino pants, perfect for casual and semi-formal occasions.',
            ],
            [
                'name' => 'Tailored Trousers',
                'category' => 'Pants',
                'gender' => 'women',
                'size' => 'S',
                'color' => 'Black',
                'quantity' => 7,
                'quality' => 'good',
                'description' => 'Elegant black tailored trousers with straight leg cut. Perfect for office wear.',
            ],
            [
                'name' => 'Relaxed Jeans',
                'category' => 'Jeans',
                'gender' => 'men',
                'size' => '36',
                'color' => 'Grey',
                'quantity' => 5,
                'quality' => 'good',
                'description' => 'Comfortable relaxed fit grey jeans. Perfect for casual days.',
            ],
            [
                'name' => 'Cargo Pants',
                'category' => 'Pants',
                'gender' => 'men',
                'size' => '34',
                'color' => 'Olive',
                'quantity' => 6,
                'quality' => 'new',
                'description' => 'Durable cargo pants with multiple pockets. Great for outdoor activities.',
            ],
            [
                'name' => 'Casual Pants',
                'category' => 'Pants',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'White',
                'quantity' => 4,
                'quality' => 'like_new',
                'description' => 'Elegant white pants with straight leg. Perfect for summer.',
            ],
            [
                'name' => 'Slim Jeans',
                'category' => 'Jeans',
                'gender' => 'unisex',
                'size' => '30',
                'color' => 'Black',
                'quantity' => 9,
                'quality' => 'new',
                'description' => 'Classic slim black jeans. Versatile and goes with everything.',
            ],
            [
                'name' => 'Classic Denim',
                'category' => 'Jeans',
                'gender' => 'men',
                'size' => '33',
                'color' => 'Blue',
                'quantity' => 7,
                'quality' => 'new',
                'description' => 'Classic straight cut denim jeans. Timeless style.',
            ],
            [
                'name' => 'Jogger Pants',
                'category' => 'Pants',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'Grey',
                'quantity' => 8,
                'quality' => 'new',
                'description' => 'Comfortable jogger pants with elastic waist. Perfect for lounging.',
            ],

            // ========== JACKETS & SWEATERS (6 items) ==========
            [
                'name' => 'Denim Jacket',
                'category' => 'Jacket',
                'gender' => 'unisex',
                'size' => 'L',
                'color' => 'Blue',
                'quantity' => 5,
                'quality' => 'good',
                'description' => 'Classic denim jacket with button closure and multiple pockets. Timeless style.',
            ],
            [
                'name' => 'Woolen Sweater',
                'category' => 'Sweater',
                'gender' => 'men',
                'size' => 'M',
                'color' => 'Navy',
                'quantity' => 8,
                'quality' => 'new',
                'description' => 'Warm woolen sweater with crew neck, perfect for winter. Soft and comfortable.',
            ],
            [
                'name' => 'Leather Jacket',
                'category' => 'Jacket',
                'gender' => 'men',
                'size' => 'L',
                'color' => 'Brown',
                'quantity' => 3,
                'quality' => 'new',
                'description' => 'Premium leather jacket in brown. Stylish and durable.',
            ],
            [
                'name' => 'Knitted Cardigan',
                'category' => 'Sweater',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'Beige',
                'quantity' => 6,
                'quality' => 'like_new',
                'description' => 'Soft knitted cardigan with button front. Perfect for layering.',
            ],
            [
                'name' => 'Puffer Jacket',
                'category' => 'Jacket',
                'gender' => 'unisex',
                'size' => 'XL',
                'color' => 'Black',
                'quantity' => 4,
                'quality' => 'new',
                'description' => 'Warm puffer jacket with hood. Perfect for extreme cold weather.',
            ],
            [
                'name' => 'Classic Blazer',
                'category' => 'Jacket',
                'gender' => 'men',
                'size' => 'M',
                'color' => 'Navy',
                'quantity' => 3,
                'quality' => 'new',
                'description' => 'Classic navy blazer. Perfect for formal occasions.',
            ],

            // ========== DRESSES (6 items) ==========
            [
                'name' => 'Summer Dress',
                'category' => 'Dress',
                'gender' => 'women',
                'size' => 'S',
                'color' => 'Multicolor',
                'quantity' => 9,
                'quality' => 'new',
                'description' => 'Beautiful floral print summer dress with tie-up waist. Perfect for sunny days.',
            ],
            [
                'name' => 'Evening Gown',
                'category' => 'Dress',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'Burgundy',
                'quantity' => 3,
                'quality' => 'new',
                'description' => 'Elegant floor-length evening gown with lace details. Perfect for special occasions.',
            ],
            [
                'name' => 'Maxi Dress',
                'category' => 'Dress',
                'gender' => 'women',
                'size' => 'L',
                'color' => 'Blue',
                'quantity' => 7,
                'quality' => 'new',
                'description' => 'Comfortable maxi dress with floral print. Perfect for casual outings.',
            ],
            [
                'name' => 'Party Dress',
                'category' => 'Dress',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'Black',
                'quantity' => 5,
                'quality' => 'new',
                'description' => 'Elegant party dress with sequin details. Perfect for night events.',
            ],
            [
                'name' => 'Beach Dress',
                'category' => 'Dress',
                'gender' => 'women',
                'size' => 'S',
                'color' => 'White',
                'quantity' => 8,
                'quality' => 'new',
                'description' => 'Lightweight cover-up dress for beach days. Breathable and comfortable.',
            ],
            [
                'name' => 'Silk Gown',
                'category' => 'Dress',
                'gender' => 'women',
                'size' => 'L',
                'color' => 'Silver',
                'quantity' => 2,
                'quality' => 'new',
                'description' => 'Luxurious silk evening gown with elegant drape. Perfect for galas.',
            ],

            // ========== TRADITIONAL (6 items) ==========
            [
                'name' => 'Silk Saree',
                'category' => 'Saree',
                'gender' => 'women',
                'size' => 'One Size',
                'color' => 'Red',
                'quantity' => 4,
                'quality' => 'new',
                'description' => 'Premium silk saree with gold border and intricate designs. Perfect for festive occasions.',
            ],
            [
                'name' => 'Kurta Pajama',
                'category' => 'Kurta',
                'gender' => 'men',
                'size' => 'L',
                'color' => 'White',
                'quantity' => 6,
                'quality' => 'new',
                'description' => 'Classic white cotton kurta with matching pajama. Traditional and comfortable.',
            ],
            [
                'name' => 'Embroidered Saree',
                'category' => 'Saree',
                'gender' => 'women',
                'size' => 'One Size',
                'color' => 'Green',
                'quantity' => 3,
                'quality' => 'new',
                'description' => 'Beautiful embroidered saree with traditional designs. Perfect for weddings.',
            ],
            [
                'name' => 'Designer Kurta',
                'category' => 'Kurta',
                'gender' => 'men',
                'size' => 'M',
                'color' => 'Blue',
                'quantity' => 5,
                'quality' => 'new',
                'description' => 'Designer kurta with embroidery. Perfect for festive occasions.',
            ],
            [
                'name' => 'Festival Lehenga',
                'category' => 'Saree',
                'gender' => 'women',
                'size' => 'M',
                'color' => 'Gold',
                'quantity' => 3,
                'quality' => 'new',
                'description' => 'Beautiful lehenga with golden embroidery. Perfect for festivals.',
            ],
            [
                'name' => 'Dhoti Kurta',
                'category' => 'Kurta',
                'gender' => 'men',
                'size' => 'XL',
                'color' => 'Cream',
                'quantity' => 4,
                'quality' => 'new',
                'description' => 'Traditional dhoti kurta set. Perfect for cultural events.',
            ],

            // ========== SHOES (6 items) ==========
            [
                'name' => 'Leather Formal Shoes',
                'category' => 'Shoes',
                'gender' => 'men',
                'size' => '9',
                'color' => 'Brown',
                'quantity' => 4,
                'quality' => 'good',
                'description' => 'Genuine leather formal shoes with cushioned soles. Durable and elegant.',
            ],
            [
                'name' => 'White Sneakers',
                'category' => 'Shoes',
                'gender' => 'women',
                'size' => '7',
                'color' => 'White',
                'quantity' => 6,
                'quality' => 'new',
                'description' => 'Comfortable canvas sneakers with rubber soles. Perfect for everyday wear.',
            ],
            [
                'name' => 'Running Shoes',
                'category' => 'Shoes',
                'gender' => 'unisex',
                'size' => '10',
                'color' => 'Black',
                'quantity' => 5,
                'quality' => 'new',
                'description' => 'Lightweight running shoes with cushioned soles. Perfect for athletes.',
            ],
            [
                'name' => 'Classic Formal Shoes',
                'category' => 'Shoes',
                'gender' => 'men',
                'size' => '8',
                'color' => 'Black',
                'quantity' => 4,
                'quality' => 'like_new',
                'description' => 'Classic black formal shoes. Perfect for business attire.',
            ],
            [
                'name' => 'Canvas Shoes',
                'category' => 'Shoes',
                'gender' => 'unisex',
                'size' => '9',
                'color' => 'Blue',
                'quantity' => 7,
                'quality' => 'new',
                'description' => 'Comfortable canvas shoes in blue. Perfect for casual wear.',
            ],
            [
                'name' => 'Elegant Heels',
                'category' => 'Shoes',
                'gender' => 'women',
                'size' => '7',
                'color' => 'Black',
                'quantity' => 3,
                'quality' => 'new',
                'description' => 'Elegant black heels with comfortable fit. Perfect for parties.',
            ],

            // ========== KIDS (6 items) ==========
            [
                'name' => 'Kids Cartoon T-Shirt',
                'category' => 'T-Shirt',
                'gender' => 'kids',
                'size' => '5-6Y',
                'color' => 'Multicolor',
                'quantity' => 12,
                'quality' => 'new',
                'description' => 'Fun cartoon printed t-shirt for kids, 100% cotton. Soft and comfortable.',
            ],
            [
                'name' => 'Kids Sport Shoes',
                'category' => 'Shoes',
                'gender' => 'kids',
                'size' => '3',
                'color' => 'Blue',
                'quantity' => 8,
                'quality' => 'new',
                'description' => 'Lightweight sports shoes for active kids with good grip and comfort.',
            ],
            [
                'name' => 'Kids Summer Dress',
                'category' => 'Dress',
                'gender' => 'kids',
                'size' => '4-5Y',
                'color' => 'Pink',
                'quantity' => 10,
                'quality' => 'new',
                'description' => 'Cute summer dress for kids with floral print. Light and comfortable.',
            ],
            [
                'name' => 'Kids Winter Jacket',
                'category' => 'Jacket',
                'gender' => 'kids',
                'size' => '7-8Y',
                'color' => 'Red',
                'quantity' => 5,
                'quality' => 'new',
                'description' => 'Warm winter jacket for kids. Perfect for cold weather.',
            ],
            [
                'name' => 'Kids Denim Pants',
                'category' => 'Jeans',
                'gender' => 'kids',
                'size' => '6-7Y',
                'color' => 'Blue',
                'quantity' => 9,
                'quality' => 'new',
                'description' => 'Comfortable denim pants for kids with adjustable waist.',
            ],
            [
                'name' => 'Kids Party Wear',
                'category' => 'Other',
                'gender' => 'kids',
                'size' => '5-6Y',
                'color' => 'Multicolor',
                'quantity' => 6,
                'quality' => 'new',
                'description' => 'Beautiful party wear outfit for kids. Perfect for special occasions.',
            ],

            // ========== OTHER (6 items) ==========
            [
                'name' => 'Denim Skirt',
                'category' => 'Other',
                'gender' => 'women',
                'size' => 'S',
                'color' => 'Blue',
                'quantity' => 5,
                'quality' => 'like_new',
                'description' => 'Stylish denim skirt with A-line cut. Versatile and trendy.',
            ],
            [
                'name' => 'Beanie Hat',
                'category' => 'Other',
                'gender' => 'unisex',
                'size' => 'One Size',
                'color' => 'Grey',
                'quantity' => 10,
                'quality' => 'new',
                'description' => 'Warm knitted beanie hat for winter. Cozy and stylish.',
            ],
        ];
    }
}
