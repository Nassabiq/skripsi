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
        $data = array(
            (object) array(
                'data' => 'Banner Flexi 280 China',
                'satuan' => 'm2'
            ),
            (object) array(
                'data' => 'Banner Flexi 240 China',
                'satuan' => 'm2'
            ),
            (object) array(
                'data' => 'Banner Jerman',
                'satuan' => 'm2'
            ),
            (object) array(
                'data' => 'Sticker Vinyl',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Sticker Chromo',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Art Cartoon A3',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Art Paper A3',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Art Cartoon A2',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Art Paper A2',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Art Cartoon A1',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Art Paper A1',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Gantungan Kunci 44mm',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Gantungan Kunci 58mm',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Pin 44mm',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Pin 58mm',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Gelas Cup Plastik 14oz',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Gelas Cup Plastik 16oz',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Gelas Cup Plastik 18oz',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan Gelas Cup Plastik 22oz',
                'satuan' => 'pcs'
            ),
            (object) array(
                'data' => 'Bahan ID Card PVC',
                'satuan' => 'pcs'
            ),
        );

        foreach ($data as $item) {
            $id_bahan_baku = IdGenerator::generate(['table' => 'bahan_baku', 'field' => 'id_bahan_baku', 'length' => 10, 'prefix' => 'BB-']);
            # code...
            BahanBaku::create([
                "id_bahan_baku" => $id_bahan_baku,
                "nama_bahan_baku" => $item->data,
                "slug_bahan_baku" => Str::slug($item->data),
                "satuan_bahan_baku" => $item->satuan,
                'jml_stok' => 0
            ]);
        }
    }
}
