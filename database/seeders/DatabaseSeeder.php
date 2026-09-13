<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@tanzamart.com'],
            [
                'name' => 'TanzaMart Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+255 700 000 001',
                'city' => 'Dar es Salaam',
                'address' => 'Kariakoo, Dar es Salaam',
            ]
        );

        // 2. Vendor User
        $vendor = User::firstOrCreate(
            ['email' => 'vendor@tanzamart.com'],
            [
                'name' => 'Juma Electronics & Fashion',
                'shop_name' => 'Juma SuperStore',
                'password' => Hash::make('vendor123'),
                'role' => 'vendor',
                'phone' => '+255 712 345 678',
                'city' => 'Dar es Salaam',
                'address' => 'Samora Avenue, Dar es Salaam',
                'balance' => 450000.00,
            ]
        );

        // 3. Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer@tanzamart.com'],
            [
                'name' => 'Amina Said',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '+255 754 112 233',
                'city' => 'Arusha',
                'address' => 'Njiro, Arusha',
            ]
        );

        // 4. Categories
        $categoriesList = [
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

        $catElectronics = null;
        $catFashion = null;
        $catWatches = null;
        $catStationery = null;

        foreach ($categoriesList as $item) {
            $cat = Category::firstOrCreate(['slug' => $item['slug']], ['name' => $item['name'], 'icon' => $item['icon']]);
            if ($item['slug'] === 'electronics') $catElectronics = $cat;
            if ($item['slug'] === 'fashion') $catFashion = $cat;
            if ($item['slug'] === 'watches') $catWatches = $cat;
            if ($item['slug'] === 'stationery') $catStationery = $cat;
        }

        // 5. Products
        $products = [
            [
                'category_id' => $catElectronics->id,
                'vendor_id' => $vendor->id,
                'name' => 'Apple iPhone 17 Pro Max (256GB)',
                'price' => 3850000.00,
                'image' => 'Apple iPhone 17 Pro Max.jpg',
                'details' => 'Simu ya kisasa yenye uwezo wa juu, betri inayodumu na kamera yenye ubora wa 8K Ultra HDR.',
                'stock' => 15,
            ],
            [
                'category_id' => $catElectronics->id,
                'vendor_id' => $vendor->id,
                'name' => 'Google Pixel 8 Pro (128GB)',
                'price' => 2100000.00,
                'image' => 'pixel.jpg',
                'details' => 'Kamera ya kisasa yenye nguvu ya Google AI na mfumo bora wa uendeshaji wa Android.',
                'stock' => 20,
            ],
            [
                'category_id' => $catElectronics->id,
                'vendor_id' => $vendor->id,
                'name' => 'Samsung Galaxy S24 Ultra',
                'price' => 3200000.00,
                'image' => 'samsung.jpg',
                'details' => 'Skrini ya Dynamic AMOLED 2X, S-Pen iliyojengewa ndani na titanium frame imara.',
                'stock' => 12,
            ],
            [
                'category_id' => $catElectronics->id,
                'vendor_id' => $vendor->id,
                'name' => 'Tecno Camon 30 Pro 5G',
                'price' => 750000.00,
                'image' => 'tecno.jpg',
                'details' => 'Kamera kali ya 50MP Sony IMX890, 70W Fast Charging na spika za stereo zenye Dolby Atmos.',
                'stock' => 35,
            ],
            [
                'category_id' => $catElectronics->id,
                'vendor_id' => $vendor->id,
                'name' => 'Sony WH-1000XM5 Wireless Headphones',
                'price' => 950000.00,
                'image' => 'sony.jpg',
                'details' => 'Sauti safi yenye mfumo wa kuzuia kelele (Active Noise Cancelling) na masaa 30 ya betri.',
                'stock' => 8,
            ],
            [
                'category_id' => $catFashion->id,
                'vendor_id' => $vendor->id,
                'name' => 'Air Jordan Retro High Sneakers',
                'price' => 180000.00,
                'image' => 'sneaker.jpg',
                'details' => 'Viatu vya mtindo wa kisasa, vyepesi na vizuri kwa michezo na matembezi ya kawaida.',
                'stock' => 25,
            ],
            [
                'category_id' => $catFashion->id,
                'vendor_id' => $vendor->id,
                'name' => 'Nike Air Max Running Shoes',
                'price' => 165000.00,
                'image' => 'sneaker1.jpg',
                'details' => 'Uso laini unaopitisha hewa na soli yenye mto laini kwa ajili ya kukimbia au matembezi.',
                'stock' => 18,
            ],
            [
                'category_id' => $catFashion->id,
                'vendor_id' => $vendor->id,
                'name' => 'Urban Street Classic Sneaker',
                'price' => 140000.00,
                'image' => 'sneaker3.jpg',
                'details' => 'Viatu imara vya ngozi bandia ya ubora wa juu na muundo unaovutia.',
                'stock' => 30,
            ],
            [
                'category_id' => $catWatches->id,
                'vendor_id' => $vendor->id,
                'name' => 'Luxury Chronograph Men Watch',
                'price' => 120000.00,
                'image' => 'watch.jpg',
                'details' => 'Saa ya chuma cha pua (Stainless steel), haipitishi maji na ina muonekano wa heshima.',
                'stock' => 40,
            ],
            [
                'category_id' => $catWatches->id,
                'vendor_id' => $vendor->id,
                'name' => 'Smart Fitness Tracker Watch Series 9',
                'price' => 195000.00,
                'image' => 'watch1.jpg',
                'details' => 'Kupima mapigo ya moyo, hatua, usingizi, na kupokea simu na arifa za WhatsApp moja kwa moja.',
                'stock' => 22,
            ],
            [
                'category_id' => $catStationery->id,
                'vendor_id' => $vendor->id,
                'name' => 'Hardcover Counter Book (A4 4-Quire Pack)',
                'price' => 18000.00,
                'image' => 'counterbook1.jpg',
                'details' => 'Daftari imara la kumbukumbu zenye jalada gumu kwa ajili ya biashara na wanafunzi.',
                'stock' => 100,
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['name' => $p['name']],
                $p
            );
        }
    }
}
