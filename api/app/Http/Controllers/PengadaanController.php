<?php

namespace App\Http\Controllers;

use App\Models\DetailPengadaanBarang;
use App\Models\Material;
use App\Models\PengadaanBarang;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $status = '%' . $request->status . '%';
        if ($request->show == null) {
            $pengadaan = PengadaanBarang::with('detailPengadaan.bahanBaku')->get();
        } else {
            $pengadaan = PengadaanBarang::with('detailPengadaan.bahanBaku')
                ->where('nama_pengadaan', 'like', $search)
                ->where('status_pengadaan', 'like', $status)
                ->paginate($request->show);
        }
        return response()->json($pengadaan, 200);
    }

    public function details($id)
    {
        $pengadaan = PengadaanBarang::with('detailPengadaan.bahanBaku')->where('id_pengadaan', $id)->first();
        return response()->json($pengadaan, 200);
    }

    public function addPengadaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pengadaan'   => 'required',
            'pengadaan.*.id_material'   => 'required',
            'pengadaan.*.jumlah_barang'   => 'required',
        ], [
            'pengadaan.*.nama_bahan_baku.required' => "field nama bahan baku harus diisi",
            'pengadaan.*.jumlah_barang.required' => "field jumlah barang harus diisi",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $id_pengadaan = IdGenerator::generate(['table' => 'pengadaan_barang', 'field' => 'id_pengadaan', 'length' => 17, 'prefix' => 'PBrg-' . date('dmY')]);

        $pengadaan = PengadaanBarang::create([
            'id_pengadaan' => $id_pengadaan,
            'nama_pengadaan' => $request->nama_pengadaan,
            'status_pengadaan' => 0,
            'tgl_dibuat' => Carbon::now(),
        ]);

        foreach ($request->pengadaan as $data) {
            $id_detail = IdGenerator::generate(['table' => 'detail_pengadaan_barang', 'field' => 'id_detail_pengadaan', 'length' => 12, 'prefix' => 'DPBrg-']);

            DetailPengadaanBarang::create([
                'id_detail_pengadaan' => $id_detail,
                'id_bahan_baku' => $data['id_material'],
                'id_pengadaan' => $pengadaan->id_pengadaan,
                'jumlah_barang' => $data['jumlah_barang'],
            ]);

            // return response()->json([$details, $pengadaan], 200);
        }
    }

    public function updatePengadaan($id, Request $request)
    {
        // var_dump($request->pengadaan);
        $validator = Validator::make($request->all(), [
            'nama_pengadaan'   => 'required',
            'pengadaan.*.id_material'   => 'required',
            'pengadaan.*.jumlah_barang'   => 'required',
        ], [
            'pengadaan.*.nama_bahan_baku.required' => "field nama bahan baku harus diisi",
            'pengadaan.*.jumlah_barang.required' => "field jumlah barang harus diisi",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        PengadaanBarang::where('id_pengadaan', $id)->update([
            'nama_pengadaan' => $request->nama_pengadaan
        ]);

        foreach ($request->pengadaan as $data) {
            DetailPengadaanBarang::where('id_detail_pengadaan', $data['id_detail_pengadaan'])->update([
                'id_bahan_baku' => $data['id_material'],
                'jumlah_barang' => $data['jumlah_barang'],
            ]);
        }
    }

    public function validasiPengadaan($id)
    {
        $pengadaan = PengadaanBarang::where('id_pengadaan', $id)->first();
        $pengadaan->status_pengadaan = 1;
        $pengadaan->tgl_disetujui = Carbon::now()->locale('id');
        $pengadaan->save();

        return response()->json($pengadaan, 200);
    }

    public function deletePengadaan($id)
    {
        $pengadaan = PengadaanBarang::where('id_pengadaan', $id)->first();
        $detail = DetailPengadaanBarang::where('id_pengadaan', $id)->delete();

        $pengadaan->delete();

        return response()->json(['pengadaan' => $pengadaan, 'detail' => $detail], 200);
    }
}
