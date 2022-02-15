<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
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
            'id_satuan' => $this->faker->numberBetween(1, 5),
            'nama_kategori' => $this->faker->name(),
        ];
    }
}
