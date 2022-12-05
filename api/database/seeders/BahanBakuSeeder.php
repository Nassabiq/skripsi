<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nama_bahan_baku = ['Banner Flexi 280 China', 'Banner Flexi 240 China', 'Banner Jerman', 'Sticker Vinyl', 'Sticker Chromo'];
        $satuan_produk = ['m2', 'pcs'];

        foreach ($nama_bahan_baku as $data) {
            $id_bahan_baku = IdGenerator::generate(['table' => 'bahan_baku', 'field' => 'id_bahan_baku', 'length' => 10, 'prefix' => 'BB-']);
            # code...
            BahanBaku::create([
                "id_bahan_baku" => $id_bahan_baku,
                "nama_bahan_baku" => $data,
                "slug_bahan_baku" => Str::slug($data),
                "satuan_bahan_baku" => $satuan_produk[array_rand($satuan_produk)],
                'jml_stok' => 0
            ]);
        }
    }
}
