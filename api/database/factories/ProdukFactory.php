<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'id_produk' => $this->faker->uuid3(),
            'id_kategori_produk' => $this->faker->numberBetween(1, 10),
            'nama_produk' => $this->faker->name(),
            'slug_produk' => $this->faker->slug(),
            'image' => $this->faker->imageUrl(480, 480, 'animals', true),
            'description' => $this->faker->paragraph(),
            'informasi_pemesanan' => $this->faker->paragraph(),
            'satuan_produk' => $this->faker->word(),
        ];
    }
}
