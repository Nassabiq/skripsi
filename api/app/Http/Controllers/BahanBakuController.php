<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\SKU;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BahanBakuController extends Controller
{
    //
    public function index()
    {
        $bahan_baku = BahanBaku::with('stok.produk')->get();
        return response()->json($bahan_baku, 200);
    }

    public function addBahanBaku(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku'   => 'required',
            'satuan_bahan_baku'   => 'required',
            'id_produk'   => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $id_bahan_baku = IdGenerator::generate(['table' => 'bahan_baku', 'field' => 'id_bahan_baku', 'length' => 10, 'prefix' => 'BB-']);
        $id_sku = IdGenerator::generate(['table' => 'sku', 'field' => 'id_sku', 'length' => 10, 'prefix' => 'SKU-']);

        $material = BahanBaku::create([
            'id_bahan_baku' => $id_bahan_baku,
            'nama_bahan_baku' => $request->nama_bahan_baku,
            'slug_bahan_baku' => Str::slug($request->nama_bahan_baku),
            'satuan_bahan_baku' => $request->satuan_bahan_baku,
        ]);

        SKU::create([
            'id_sku' => $id_sku,
            'id_bahan_baku' => $id_bahan_baku,
            'id_produk' => $request->id_produk,
            'jml_stok' => 0
        ]);

        return response()->json($material, 200);
    }

    public function editBahanBaku(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku'   => 'required',
            'satuan_bahan_baku'   => 'required',
            'id_produk'   => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $material = BahanBaku::find($id);
        $material->nama_bahan_baku = $request->nama_bahan_baku;
        $material->slug_bahan_baku = Str::slug($request->nama_bahan_baku);
        $material->satuan_bahan_baku = $request->satuan_bahan_baku;
        $material->save();

        $sku = SKU::where('id_bahan_baku', $id)->first();
        $sku->id_produk = $request->id_produk;
        $sku->save();

        return response()->json(['bahan_baku' => $material, 'sku' => $sku], 200);
    }

    public function deleteBahanBaku($id)
    {
        $data = BahanBaku::findOrFail($id);
        $data->delete();
        SKU::where('id_bahan_baku', $id)->delete();
        return response()->json($data, 200);
    }
}
