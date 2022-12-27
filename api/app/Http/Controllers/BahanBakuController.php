<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $bahan_baku = $request->show == null ?  BahanBaku::get() : BahanBaku::where('nama_bahan_baku', 'like', $search)->paginate($request->show);

        return response()->json($bahan_baku, 200);
    }

    public function addBahanBaku(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku'   => 'required',
            'satuan_bahan_baku'   => 'required',
        ], [
            'nama_bahan_baku.required'   => 'Field ini harus diisi',
            'satuan_bahan_baku.required'   => 'Field ini harus diisi',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);
        $id_bahan_baku = IdGenerator::generate(['table' => 'bahan_baku', 'field' => 'id_bahan_baku', 'length' => 8, 'prefix' => 'BB-']);
        BahanBaku::create([
            'id_bahan_baku' => $id_bahan_baku,
            'nama_bahan_baku' => $request->nama_bahan_baku,
            'slug_bahan_baku' => Str::slug($request->nama_bahan_baku),
            'satuan_bahan_baku' => $request->satuan_bahan_baku,
            'jml_stok' => 0,
        ]);
        return response()->json(["message" => "Data Berhasil disimpan"], 200);
    }

    public function editBahanBaku(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku'   => 'required',
            'satuan_bahan_baku'   => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $material = BahanBaku::find($id);

        $material->nama_bahan_baku = $request->nama_bahan_baku;
        $material->slug_bahan_baku = Str::slug($request->nama_bahan_baku);
        $material->satuan_bahan_baku = $request->satuan_bahan_baku;

        $material->save();

        return response()->json(['bahan_baku' => $material], 200);
    }

    public function deleteBahanBaku($id)
    {
        $data = BahanBaku::findOrFail($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
