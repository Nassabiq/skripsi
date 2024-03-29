<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Product::factory(20)->create();
        // \App\Models\ProductCategory::factory(10)->create();

        $this->call([RoleSeeder::class, UserSeeder::class, BahanBakuSeeder::class]);
        // Produk::factory(50)->create();
        // KategoriProduk::factory(10)->create();
    }
}
