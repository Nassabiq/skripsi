<?php

namespace App\Http\Controllers;

use App\Models\DetailPengadaan;
use App\Models\PengadaanPersediaan;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengadaanPersediaanController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $status = '%' . $request->status . '%';

        if ($request->show == null) $pengadaan = PengadaanPersediaan::with('detailPengadaan.bahanBaku')->get();
        else $pengadaan = PengadaanPersediaan::with('detailPengadaan.bahanBaku')
            ->where('nama_pengadaan', 'like', $search)
            ->where('status_pengadaan', 'like', $status)
            ->paginate($request->show);

        return response()->json($pengadaan, 200);
    }

    public function addPengadaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pengadaan'   => 'required',
            'pengadaan.*.id_bahan_baku'   => 'required',
            'pengadaan.*.jumlah_barang'   => 'required|numeric',
        ], [
            'required' => 'field nama pengadaan harus diisi',
            'pengadaan.*.id_bahan_baku.required' => "field :attributes harus diisi",
            'pengadaan.*.jumlah_barang.required' => "field :attributes harus diisi",
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $id_pengadaan = IdGenerator::generate(['table' => 'pengadaan_persediaan', 'field' => 'id_pengadaan', 'length' => 17, 'prefix' => 'PP-' . date('dmY')]);

        $pengadaan = PengadaanPersediaan::create([
            'id_pengadaan' => $id_pengadaan,
            'nama_pengadaan' => $request->nama_pengadaan,
            'status_pengadaan' => 0,
            'tgl_dibuat' => Carbon::now(),
        ]);

        foreach ($request->pengadaan as $data) {
            $id_detail = IdGenerator::generate(['table' => 'detail_pengadaan', 'field' => 'id_detail_pengadaan', 'length' => 12, 'prefix' => 'DPP-']);

            DetailPengadaan::create([
                'id_detail_pengadaan' => $id_detail,
                'id_bahan_baku' => $data['id_bahan_baku'],
                'id_pengadaan' => $pengadaan->id_pengadaan,
                'jumlah_barang' => $data['jumlah_barang'],
            ]);

            return response()->json(200);
        }
    }
}
