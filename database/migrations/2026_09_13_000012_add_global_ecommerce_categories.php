<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categories = [
            ['name' => 'Elektroniki (Electronics)', 'slug' => 'electronics', 'icon' => '📱'],
            ['name' => 'Mavazi na Viatu (Fashion & Shoes)', 'slug' => 'fashion', 'icon' => '👟'],
            ['name' => 'Saa na Vifaa (Watches & Accessories)', 'slug' => 'watches', 'icon' => '⌚'],
            ['name' => 'Vifaa vya Shule na Ofisi (Stationery)', 'slug' => 'stationery', 'icon' => '📚'],
            ['name' => 'Vifaa vya Nyumbani na Jikoni (Home & Kitchen)', 'slug' => 'home-kitchen', 'icon' => '🍳'],
            ['name' => 'Vyakula, Nafaka na Vinywaji (Groceries & Food)', 'slug' => 'groceries-food', 'icon' => '🍎'],
            ['name' => 'Simu na Kompyuta (Computers & Laptops)', 'slug' => 'computers-laptops', 'icon' => '💻'],
            ['name' => 'Urembo, Vipodozi na Manukato (Beauty & Cosmetics)', 'slug' => 'beauty-cosmetics', 'icon' => '💄'],
            ['name' => 'Afya, Dawa na Lishe (Health & Wellness)', 'slug' => 'health-wellness', 'icon' => '💊'],
            ['name' => 'Samani na Mapambo ya Ndani (Furniture & Decor)', 'slug' => 'furniture-decor', 'icon' => '🛋️'],
            ['name' => 'Magari, Pikipiki na Vipuri (Automotive & Spares)', 'slug' => 'automotive-parts', 'icon' => '🚗'],
            ['name' => 'Michezo na Mazoezi (Sports & Fitness)', 'slug' => 'sports-fitness', 'icon' => '⚽'],
            ['name' => 'Watoto, Malezi na Vichezeo (Baby Care & Toys)', 'slug' => 'baby-toys', 'icon' => '🧸'],
            ['name' => 'Vifaa vya Ujenzi na Zana (Hardware & Tools)', 'slug' => 'hardware-tools', 'icon' => '🔨'],
            ['name' => 'Kilimo, Mifugo na Bustani (Agriculture & Farming)', 'slug' => 'agriculture-farming', 'icon' => '🌾'],
            ['name' => 'Umeme wa Jua na Nishati (Solar Power & Energy)', 'slug' => 'solar-energy', 'icon' => '☀️'],
            ['name' => 'Mabegi, Mikoba na Safari (Bags & Luggage)', 'slug' => 'bags-luggage', 'icon' => '🧳'],
            ['name' => 'Vito na Madini ya Thamani (Jewelry & Luxury)', 'slug' => 'jewelry-luxury', 'icon' => '💎'],
            ['name' => 'Vitabu, Muziki na Sanaa (Books, Art & Music)', 'slug' => 'books-art-music', 'icon' => '🎨'],
            ['name' => 'Wanyama Vipenzi na Vyakula (Pet Supplies)', 'slug' => 'pet-supplies', 'icon' => '🐾'],
            ['name' => 'Mashine na Vifaa vya Viwandani (Industrial Equipment)', 'slug' => 'industrial-machinery', 'icon' => '🏭'],
            ['name' => 'Nguo za Asili na Utamaduni (Cultural & Traditional)', 'slug' => 'cultural-traditional', 'icon' => '👘'],
            ['name' => 'Michezo ya Video na Console (Gaming & Consoles)', 'slug' => 'gaming-consoles', 'icon' => '🎮'],
            ['name' => 'Kamera, Picha na Video (Cameras & Photography)', 'slug' => 'cameras-photography', 'icon' => '📷'],
            ['name' => 'Bidhaa na Huduma za Kidijitali (Digital Products)', 'slug' => 'digital-services', 'icon' => '⚡'],
            ['name' => 'Ulinzi, Usalama na CCTV (Security & CCTV)', 'slug' => 'security-cctv', 'icon' => '🚨'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op to prevent accidental data loss
    }
};
