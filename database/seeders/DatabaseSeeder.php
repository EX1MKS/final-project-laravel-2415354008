<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting! Service & Customer dulu sebelum Subscription
        $this->call([
            ServiceSeeder::class,
            CustomerSeeder::class,
            SubscriptionSeeder::class,
        ]);
    }
}