<?php

namespace App\Http\Controllers;

use App\Models\DetailPengadaan;
use App\Models\Pengadaan;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $status = '%' . $request->status . '%';

        if ($request->show == null) $pengadaan = Pengadaan::with('detailPengadaan.bahanBaku')->get();
        else $pengadaan = Pengadaan::with('detailPengadaan.bahanBaku')->where('nama_pengadaan', 'like', $search)->where('status_pengadaan', 'like', $status)->paginate($request->show);

        return response()->json($pengadaan, 200);
    }

    public function details($id)
    {
        $pengadaan = Pengadaan::with('detailPengadaan.bahanBaku')->findOrFail($id);
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

        DB::beginTransaction();
        try {
            $id_pengadaan = IdGenerator::generate(['table' => 'pengadaan', 'field' => 'id_pengadaan', 'length' => 17, 'prefix' => 'PP-' . date('dmY')]);
            $pengadaan = Pengadaan::create([
                'id_pengadaan' => $id_pengadaan,
                'id_user' => $request->user,
                'nama_pengadaan' => $request->nama_pengadaan,
                'status_pengadaan' => 0,
                'tgl_dibuat' => Carbon::now(),
            ]);

            foreach ($request->pengadaan as $data) {
                $id_detail = IdGenerator::generate(['table' => 'detail_pengadaan', 'field' => 'id_detail_pengadaan', 'length' => 9, 'prefix' => 'DPP-']);

                DetailPengadaan::create([
                    'id_detail_pengadaan' => $id_detail,
                    'id_bahan_baku' => $data['id_bahan_baku'],
                    'id_pengadaan' => $pengadaan->id_pengadaan,
                    'jumlah_barang' => $data['jumlah_barang'],
                ]);
            }
            DB::commit();
            return response()->json(200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something Went Wrong'], 400);
            DB::rollback();
            //throw $th;
        }
    }

    public function updatePengadaan($id, Request $request)
    {
        var_dump($request->all());
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) return response()->json($validator->messages(), 400);

        Pengadaan::where('id_pengadaan', $id)->update([
            'nama_pengadaan' => $request->nama_pengadaan
        ]);

        foreach ($request->pengadaan as $data) {
            if (isset($data['id_detail_pengadaan'])) {
                # code...
                DetailPengadaan::where('id_detail_pengadaan', $data['id_detail_pengadaan'])->update([
                    'id_bahan_baku' => $data['id_bahan_baku'],
                    'jumlah_barang' => $data['jumlah_barang'],
                ]);
            } else {
                $id_detail = IdGenerator::generate(['table' => 'detail_pengadaan', 'field' => 'id_detail_pengadaan', 'length' => 9, 'prefix' => 'DPP-']);
                DetailPengadaan::updateOrCreate([
                    'id_detail_pengadaan' => $id_detail,
                    'id_bahan_baku' => $data['id_bahan_baku'],
                    'id_pengadaan' => $id,
                    'jumlah_barang' => $data['jumlah_barang'],
                ]);
            }
        }
        if (count($request->deletedPengadaan) > 0) {
            foreach ($request->deletedPengadaan as $data) {
                DetailPengadaan::where('id_detail_pengadaan', $data['id_detail_pengadaan'])->delete();
            }
        }
        return response()->json(200);
    }

    public function validasiPengadaan($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->status_pengadaan = 1;
        $pengadaan->tgl_disetujui = Carbon::now()->locale('id');
        $pengadaan->save();

        return response()->json($pengadaan, 200);
    }

    public function deletePengadaan($id)
    {
        $pengadaan = Pengadaan::find($id);
        $detail = DetailPengadaan::where('id_pengadaan', $id)->delete();
        $pengadaan->delete();

        return response()->json(['pengadaan' => $pengadaan, 'detail' => $detail], 200);
    }
}
