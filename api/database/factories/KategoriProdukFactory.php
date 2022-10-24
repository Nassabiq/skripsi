<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_kategori_produk' => $this->faker->unique()->numberBetween(1, 10),
            'nama_kategori' => $this->faker->name(),
        ];
    }
}
