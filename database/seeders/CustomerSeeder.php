<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'customer_id' => 'CUST-0001',
                'name'        => 'Budi Santoso',
                'email'       => 'budi.santoso@gmail.com',
                'phone'       => '081234567890',
                'address'     => 'Jl. Sudirman No. 10, Jakarta Pusat, DKI Jakarta',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0002',
                'name'        => 'Siti Rahayu',
                'email'       => 'siti.rahayu@yahoo.com',
                'phone'       => '082345678901',
                'address'     => 'Jl. Gatot Subroto No. 25, Bandung, Jawa Barat',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0003',
                'name'        => 'Ahmad Fauzi',
                'email'       => 'ahmad.fauzi@outlook.com',
                'phone'       => '083456789012',
                'address'     => 'Jl. Pemuda No. 5, Surabaya, Jawa Timur',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0004',
                'name'        => 'Dewi Lestari',
                'email'       => 'dewi.lestari@gmail.com',
                'phone'       => '084567890123',
                'address'     => 'Jl. Diponegoro No. 88, Yogyakarta, DIY',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0005',
                'name'        => 'Rizky Pratama',
                'email'       => 'rizky.pratama@gmail.com',
                'phone'       => '085678901234',
                'address'     => 'Jl. Ahmad Yani No. 33, Medan, Sumatera Utara',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0006',
                'name'        => 'Rina Marlina',
                'email'       => 'rina.marlina@gmail.com',
                'phone'       => '086789012345',
                'address'     => 'Jl. Raya Kuta No. 12, Kuta, Bali',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0007',
                'name'        => 'Hendra Gunawan',
                'email'       => 'hendra.gunawan@gmail.com',
                'phone'       => '087890123456',
                'address'     => 'Jl. Veteran No. 7, Makassar, Sulawesi Selatan',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0008',
                'name'        => 'Nurul Hidayah',
                'email'       => 'nurul.hidayah@gmail.com',
                'phone'       => '088901234567',
                'address'     => 'Jl. Imam Bonjol No. 45, Semarang, Jawa Tengah',
                'status'      => false, // nonaktif
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0009',
                'name'        => 'Farhan Maulana',
                'email'       => 'farhan.maulana@gmail.com',
                'phone'       => '089012345678',
                'address'     => 'Jl. Pahlawan No. 19, Palembang, Sumatera Selatan',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'customer_id' => 'CUST-0010',
                'name'        => 'Yuliana Putri',
                'email'       => 'yuliana.putri@gmail.com',
                'phone'       => '081122334455',
                'address'     => 'Jl. Raya Darmo No. 60, Surabaya, Jawa Timur',
                'status'      => false, // nonaktif
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}