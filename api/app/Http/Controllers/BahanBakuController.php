<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\HargaJualProduk;
use App\Models\SKU;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahan_baku = BahanBaku::with(['stok.produk', 'stok.harga'])->get();
        return response()->json($bahan_baku, 200);
    }

    public function addBahanBaku(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku'   => 'required',
            'satuan_bahan_baku'   => 'required',
            'id_produk'   => 'required',
            'harga' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        DB::beginTransaction();
        try {

            $id_bahan_baku = IdGenerator::generate(['table' => 'bahan_baku', 'field' => 'id_bahan_baku', 'length' => 10, 'prefix' => 'BB-']);
            $id_sku = IdGenerator::generate(['table' => 'sku', 'field' => 'id_sku', 'length' => 10, 'prefix' => 'SKU-']);
            $id_harga_jual = IdGenerator::generate(['table' => 'harga_jual_produk', 'field' => 'id_harga_jual', 'length' => 10, 'prefix' => 'PPrc-']);

            BahanBaku::create([
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

            HargaJualProduk::create([
                'id_harga_jual' => $id_harga_jual,
                'id_sku' => $id_sku,
                'harga_produk' => $request->harga,
                'tgl_diubah' => Carbon::now()
            ]);

            DB::commit();
            return response()->json(["message" => "Data Berhasil disimpan"], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json($th, 400);
        }
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
