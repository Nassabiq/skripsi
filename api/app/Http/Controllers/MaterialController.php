<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\Material;
use App\Models\Unit;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index()
    {
        $bahan_baku = Material::with('satuan')->get();
        $satuan = Unit::get();
        $barang_masuk = BarangMasuk::with('detailBarang.bahanBaku')->get();

        return response()->json(
            [
                'materials' => $bahan_baku,
                'satuan' => $satuan,
                'barangMasuk' => $barang_masuk
            ],
            200
        );
    }

    public function addDataMaterial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku'   => 'required',
            'id_satuan'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = IdGenerator::generate(['table' => 'materials', 'field' => 'id_material', 'length' => 10, 'prefix' => 'MTRL-']);

        $material = Material::create([
            'id_material' => $id,
            'id_satuan' => $request->id_satuan,
            'nama_bahan_baku' => $request->nama_bahan_baku,
            'slug' => Str::slug($request->nama_bahan_baku),
            'stok' => 0,
        ]);

        return response()->json($material, 200);
    }
}
