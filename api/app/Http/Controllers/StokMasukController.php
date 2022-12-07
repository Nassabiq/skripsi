<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\DetailStokMasuk;
use App\Models\PengadaanPersediaan;
use App\Models\SKU;
use App\Models\StokMasuk;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $stok_masuk = StokMasuk::with('detailStok.bahanBaku')->where('id_stok_masuk', 'like', $search)->paginate($request->show);
        return response()->json($stok_masuk, 200);
    }
    public function addStok(Request $request)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            'stok.*.id_bahan_baku'   => 'required',
            'stok.*.harga'   => 'required',
            'stok.*.qty'   => 'required',
        ], [
            'stok.*.id_bahan_baku.required' => "field nama bahan baku harus diisi",
            'stok.*.harga.required' => "field harga harus diisi",
            'stok.*.qty.required' => "field qty harus diisi",
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 400);

        DB::beginTransaction();
        try {
            $pengadaan = PengadaanPersediaan::findOrFail($request->id_pengadaan);
            $pengadaan->status_pengadaan = 2;
            $pengadaan->save();

            $id_stok_masuk = IdGenerator::generate(['table' => 'stok_masuk', 'field' => 'id_stok_masuk', 'length' => 19, 'prefix' => 'Stok-' . date('dmY')]);
            $stok_masuk = StokMasuk::create([
                'id_stok_masuk' => $id_stok_masuk,
                'id_user' => $request->user,
                'tgl_stok_masuk' => Carbon::now(),
                'total_harga_beli' => $request->total,
            ]);

            foreach ($request->stok as $data) {
                $id_detail = IdGenerator::generate(['table' => 'detail_stok_masuk', 'field' => 'id_detail_stok_masuk', 'length' => 11, 'prefix' => 'DStok-']);

                DetailStokMasuk::create([
                    'id_detail_stok_masuk' => $id_detail,
                    'id_bahan_baku' => $data['id_bahan_baku'],
                    'id_stok_masuk' => $stok_masuk->id_stok_masuk,
                    'qty_stok' => $data['qty'],
                    'harga_beli' => $data['harga'],
                ]);

                $stok = BahanBaku::where('id_bahan_baku', $data['id_bahan_baku'])->first();
                $stok->jml_stok += $data['qty'];
                $stok->save();
            }

            DB::commit();
            return response()->json(200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json($th, 400);
        }
    }

    public function laporanStokMasuk(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $data = StokMasuk::with('detailStok.bahanBaku', 'user')->orderBy('tgl_stok_masuk', 'asc')->whereBetween('tgl_stok_masuk', [$from, $to])->get();
        return response()->json($data, 200);
    }
}
