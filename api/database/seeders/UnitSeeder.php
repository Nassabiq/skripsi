<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        foreach (range(1, 10) as $id) {
            # code...
            Unit::create([
                'id_satuan' => $id,
                'nama_satuan' => $faker->name()
            ]);
        }
    }
}
