<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default categories
        $defaultCategories = [
            ['value' => 'shirt', 'label' => '👕 Shirts', 'icon' => 'fa-tshirt'],
            ['value' => 't-shirt', 'label' => '👕 T-Shirts', 'icon' => 'fa-tshirt'],
            ['value' => 'jeans', 'label' => '👖 Jeans', 'icon' => 'fa-shopping-cart'],
            ['value' => 'pants', 'label' => '👖 Pants', 'icon' => 'fa-shopping-cart'],
            ['value' => 'jacket', 'label' => '🧥 Jackets', 'icon' => 'fa-snowflake'],
            ['value' => 'sweater', 'label' => '🧥 Sweaters', 'icon' => 'fa-tshirt'],
            ['value' => 'dress', 'label' => '👗 Dresses', 'icon' => 'fa-female'],
            ['value' => 'saree', 'label' => '👘 Saree', 'icon' => 'fa-star-of-life'],
            ['value' => 'kurta', 'label' => '👘 Kurta', 'icon' => 'fa-star-of-life'],
            ['value' => 'traditional', 'label' => '👘 Traditional', 'icon' => 'fa-star-of-life'],
            ['value' => 'winter', 'label' => '❄️ Winter Wear', 'icon' => 'fa-snowflake'],
            ['value' => 'summer', 'label' => '☀️ Summer Wear', 'icon' => 'fa-sun'],
        ];

        DB::table('settings')->insert([
            'key' => 'categories',
            'value' => json_encode($defaultCategories),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
