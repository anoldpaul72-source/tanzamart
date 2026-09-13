<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Vendors matching Screenshot 5
        $vendors = [
            [
                'name' => 'Hamad',
                'email' => 'hamad@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'vendor',
                'shop_name' => 'Smart Electronics',
                'plan_type' => 'monthly',
                'is_verified' => true,
                'is_paid' => false,
                'subscription_active' => false,
                'subscription_end_date' => '2026-09-09 11:15:43',
            ],
            [
                'name' => 'Sylvester Arnold',
                'email' => 'sylvesterarnold72@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'vendor',
                'shop_name' => 'PIKIPIKI USED STORE',
                'plan_type' => 'yearly',
                'is_verified' => true,
                'is_paid' => true,
                'subscription_active' => true,
                'subscription_end_date' => '2027-08-11 11:44:23',
            ],
            [
                'name' => 'Ally',
                'email' => 'ally@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'vendor',
                'shop_name' => 'Ally shop',
                'plan_type' => 'weekly',
                'is_verified' => true,
                'is_paid' => false,
                'subscription_active' => false,
                'subscription_end_date' => '2026-08-21 17:32:41',
            ],
            [
                'name' => 'Anold',
                'email' => 'anold@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'vendor',
                'shop_name' => 'ANOLD STORE',
                'plan_type' => 'monthly',
                'is_verified' => false,
                'is_paid' => false,
                'subscription_active' => false,
                'subscription_end_date' => null,
            ],
        ];

        foreach ($vendors as $v) {
            User::updateOrCreate(['email' => $v['email']], $v);
        }

        // 2. Orders matching Screenshot 2 & 3
        $orders = [
            [
                'id' => 1,
                'order_number' => 'TZM-ORD-0001',
                'name' => 'Juma Khamis',
                'phone' => '0712345671',
                'address' => 'Kariakoo, Mtaa wa Lumumba',
                'city' => 'Dar es Salaam',
                'total' => 2000000.00,
                'status' => 'Refunded',
                'payment_status' => 'refunded',
                'payment_method' => 'Bank',
                'created_at' => '2026-08-11 02:38:32',
            ],
            [
                'id' => 2,
                'order_number' => 'TZM-ORD-0002',
                'name' => 'Juma Khamis',
                'phone' => '0712345671',
                'address' => 'Kariakoo, Mtaa wa Lumumba',
                'city' => 'Dar es Salaam',
                'total' => 3000000.00,
                'status' => 'Completed',
                'payment_status' => 'released',
                'payment_method' => 'Bank',
                'created_at' => '2026-08-11 02:49:10',
            ],
            [
                'id' => 3,
                'order_number' => 'TZM-ORD-0003',
                'name' => 'Salome manase',
                'phone' => '0755123456',
                'address' => 'Sinza Mori',
                'city' => 'Dar es Salaam',
                'total' => 10000.00,
                'status' => 'Processing',
                'payment_status' => 'held',
                'payment_method' => 'M-Pesa',
                'created_at' => '2026-08-11 02:54:02',
            ],
            [
                'id' => 4,
                'order_number' => 'TZM-ORD-0004',
                'name' => 'Mwingi',
                'phone' => '0766123456',
                'address' => 'Mikocheni B',
                'city' => 'Dar es Salaam',
                'total' => 80000.00,
                'status' => 'Processing',
                'payment_status' => 'held',
                'payment_method' => 'Tigo Pesa',
                'created_at' => '2026-08-14 01:30:22',
            ],
            [
                'id' => 5,
                'order_number' => 'TZM-ORD-0005',
                'name' => 'irene mariki',
                'phone' => '0788123456',
                'address' => 'Mwenge',
                'city' => 'Dar es Salaam',
                'total' => 23000.00,
                'status' => 'Delivered',
                'payment_status' => 'held',
                'payment_method' => 'Airtel Money',
                'created_at' => '2026-08-14 02:02:29',
            ],
            [
                'id' => 6,
                'order_number' => 'TZM-ORD-0006',
                'name' => 'Anold Sylvester',
                'phone' => '0621530804',
                'address' => 'Sinza Kumekucha',
                'city' => 'Dar es Salaam',
                'total' => 12000.00,
                'status' => 'Completed',
                'payment_status' => 'released',
                'payment_method' => 'M-Pesa',
                'created_at' => '2026-08-14 14:42:57',
            ],
            [
                'id' => 7,
                'order_number' => 'TZM-ORD-0007',
                'name' => 'Ester ally',
                'phone' => '0711998877',
                'address' => 'Mbezi Beach',
                'city' => 'Dar es Salaam',
                'total' => 3080000.00,
                'status' => 'Pending',
                'payment_status' => 'held',
                'payment_method' => 'CRDB Bank',
                'created_at' => '2026-08-15 03:10:19',
            ],
            [
                'id' => 8,
                'order_number' => 'TZM-ORD-0008',
                'name' => 'Anold Sylvester',
                'phone' => '0621530804',
                'address' => 'Sinza Kumekucha',
                'city' => 'Dar es Salaam',
                'total' => 160000.00,
                'status' => 'Pending',
                'payment_status' => 'held',
                'payment_method' => 'M-Pesa',
                'created_at' => '2026-09-12 11:06:44',
            ],
        ];

        foreach ($orders as $ord) {
            Order::updateOrCreate(['id' => $ord['id']], $ord);
        }
    }
}
