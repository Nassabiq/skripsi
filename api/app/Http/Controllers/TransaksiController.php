<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TransaksiController extends Controller
{
    public function index()
    {
        // $produk = Product::with('material', 'categories.satuan', 'harga')->get();
        // return response()->json($produk, 200);
    }

    public function dataTransaksi(Request $request)
    {
        $search = '%' . $request->search . '%';
        $status = '%' . $request->status . '%';
        if ($request->show == null) {
            $data = Transaksi::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')->orderBy('tgl_transaksi', 'desc')->get();
        } else {
            $data = TransakTransaksisiPenjualan::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')
                ->where(function ($q) use ($search) {
                    $q->where('id_transaksi', 'like', $search)
                        ->orWhere('nama_pemesan', 'like', $search);
                })
                ->where('status_pesanan', 'like', $status)
                ->orderBy('tgl_transaksi', 'desc')
                ->paginate($request->show);
        }
        return response()->json($data, 200);
    }


    public function laporanPenjualan(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $data = Transaksi::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')
            ->orderBy('tgl_transaksi', 'asc')
            ->whereBetween('tgl_transaksi', [$from, $to])
            ->get();

        return response()->json($data, 200);
    }

    public function detailTransaksi($id)
    {
        $data = Transaksi::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')->where('id_transaksi', $id)->first();
        return response()->json($data, 200);
    }

    public function submitTransaksi(Request $request)
    {
        // return response()->json($request->pelanggan, 200);
        // $data = $request->pelanggan;
        var_dump(is_array($request->transaksi) ? true : false);
        // foreach ($request->transaksi as $data) {
        //     # code...
        //     print_r($data);
        // }

        // DB::beginTransaction();
        // try {

        //     $validator = Validator::make($request->all(), [
        //         'nama_pemesan'   => 'required',
        //         'no_telp'   => 'required',
        //         'alamat'   => 'required',
        //     ]);

        //     if ($validator->fails()) return response()->json($validator->errors(), 400);

        //     $id_transaksi = IdGenerator::generate(['table' => 'transaksi_penjualan', 'field' => 'id_transaksi', 'length' => 20, 'prefix' => 'ORDER-' . date('dmY')]);
        //     $transaksi = Transaksi::create([
        //         'id_transaksi' => $id_transaksi,
        //         'nama_pemesan' => $request->nama_pemesan,
        //         'alamat' => $request->alamat,
        //         'no_telp' => $request->no_telp,
        //         'tgl_transaksi' => Carbon::now(),
        //         'status_pesanan' => 0,
        //         'total_harga' => $request->total,
        //         'total_pembayaran' => $request->total_pembayaran
        //     ]);

        //     foreach ($request->pesanan as $data) {
        //         $id_detail = IdGenerator::generate(['table' => 'detail_transaksi', 'field' => 'id_detail_transaksi', 'length' => 17, 'prefix' => 'DtlOrder-']);
        //         $detail_transaksi = DetailTransaksi::create([
        //             'id_detail_transaksi' => $id_detail,
        //             'id_transaksi' => $transaksi->id_transaksi,
        //             'id_produk' => $data['id_produk'],
        //             'jenis_bahan' => $data['jenis_bahan'],
        //             'qty' => $data['qty'],
        //             'subtotal' => $data['qty'] * $data['harga'],
        //             'finishing' => $data['finishing'],
        //             'laminasi' => $data['laminasi'],
        //         ]);

        //         DB::commit();
        //     }
        // } catch (\Throwable $th) {
        //     return response()->json('Something Went Wrong', 400);
        //     DB::rollback();
        //     //throw $th;
        // }
    }

    public function changeStatus($id, Request $request)
    {
        $data = TransaksiPenjualan::findOrFail($id);
        $data->status_pesanan = $request->status_pesanan + 1;
        $data->save();

        return response()->json($data, 200);
    }
}
