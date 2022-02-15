<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_produk' => Str::random(10),
            'id_kategori_produk' => $this->faker->numberBetween(1, 10),
            'nama_produk' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->text()
        ];
    }
}
