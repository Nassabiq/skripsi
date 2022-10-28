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
        else $pengadaan = PengadaanPersediaan::with('detailPengadaan.bahanBaku')->where('nama_pengadaan', 'like', $search)->where('status_pengadaan', 'like', $status)->paginate($request->show);

        return response()->json($pengadaan, 200);
    }

    public function details($id)
    {
        $pengadaan = PengadaanPersediaan::with('detailPengadaan.bahanBaku')->findOrFail($id);
        return response()->json($pengadaan, 200);
    }

    public $rules = [
        'nama_pengadaan'   => 'required',
        'pengadaan.*.id_bahan_baku'   => 'required',
        'pengadaan.*.jumlah_barang'   => 'required|numeric',
    ];

    public $messages = [
        'required' => 'field nama pengadaan harus diisi',
        'pengadaan.*.id_bahan_baku.required' => "field :attributes harus diisi",
        'pengadaan.*.jumlah_barang.required' => "field :attributes harus diisi",
    ];

    public function addPengadaan(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
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
        }
        return response()->json(200);
    }

    public function updatePengadaan($id, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) return response()->json($validator->messages(), 400);

        PengadaanPersediaan::where('id_pengadaan', $id)->update([
            'nama_pengadaan' => $request->nama_pengadaan
        ]);

        foreach ($request->pengadaan as $data) {
            DetailPengadaan::where('id_detail_pengadaan', $data['id_detail_pengadaan'])->update([
                'id_bahan_baku' => $data['id_bahan_baku'],
                'jumlah_barang' => $data['jumlah_barang'],
            ]);
        }
        return response()->json(200);
    }

    public function validasiPengadaan($id)
    {
        $pengadaan = PengadaanPersediaan::findOrFail($id);
        $pengadaan->status_pengadaan = 1;
        $pengadaan->tgl_disetujui = Carbon::now()->locale('id');
        $pengadaan->save();

        return response()->json($pengadaan, 200);
    }

    public function deletePengadaan($id)
    {
        $pengadaan = PengadaanPersediaan::find($id);
        $detail = DetailPengadaan::where('id_pengadaan', $id)->delete();
        $pengadaan->delete();

        return response()->json(['pengadaan' => $pengadaan, 'detail' => $detail], 200);
    }
}
