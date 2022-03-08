<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\Material;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
{
    public function addStok(Request $request)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            'stok.*.nama_bahan_baku'   => 'required',
            'stok.*.harga'   => 'required',
            'stok.*.qty'   => 'required',
        ], [
            'stok.*.nama_bahan_baku.required' => "field nama bahan baku harus diisi",
            'stok.*.harga.required' => "field harga harus diisi",
            'stok.*.qty.required' => "field qty harus diisi",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $id_barang_masuk = IdGenerator::generate(['table' => 'barang_masuk', 'field' => 'id_barang_masuk', 'length' => 10, 'prefix' => 'STOK-']);

        $barang_masuk = BarangMasuk::create([
            'id_barang_masuk' => $id_barang_masuk,
            'tgl_barang_masuk' => Carbon::now(),
            'total_harga_beli' => $request->total,
        ]);

        foreach ($request->stok as $data) {
            $id_detail = IdGenerator::generate(['table' => 'detail_barang_masuk', 'field' => 'id_detail_barang_masuk', 'length' => 10, 'prefix' => 'DStok-']);

            $details = DetailBarangMasuk::create([
                'id_detail_barang_masuk' => $id_detail,
                'id_bahan_baku' => $data['nama_bahan_baku'],
                'id_barang_masuk' => $barang_masuk->id_barang_masuk,
                'qty' => $data['qty'],
                'harga' => $data['harga'],
            ]);

            $material = Material::where('id_material', $data['nama_bahan_baku'])->first();
            $stok =  $material->stok + $data['qty'];
            $material->stok = $stok;
            $material->save();

            // return response()->json([$barang_masuk, $details, $material], 200);
        }
    }
}
