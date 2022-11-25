<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
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
        DB::beginTransaction();
        try {
            $existedPelanggan = is_array($request->pelanggan);
            if ($existedPelanggan) {
                $validatePelanggan = Validator::make($request->pelanggan, [
                    'nama_pelanggan'   => 'required',
                    'no_telp'   => 'required',
                    'alamat'   => 'required',
                ]);
                if ($validatePelanggan->fails()) return response()->json($validatePelanggan->errors(), 400);

                $id_pelanggan = IdGenerator::generate(['table' => 'pelanggan', 'field' => 'id_pelanggan', 'length' => 10, 'prefix' => 'CUST-']);
                $pelanggan = Pelanggan::create([
                    'id_pelanggan' => $id_pelanggan,
                    'id_user' => $request->user,
                    'nama_pelanggan' => $request->pelanggan['nama_pelanggan'],
                    'alamat' => $request->pelanggan['alamat'],
                    'no_telp' => $request->pelanggan['no_telp'],
                ]);
            }

            $id_transaksi = IdGenerator::generate(['table' => 'transaksi', 'field' => 'id_transaksi', 'length' => 20, 'prefix' => 'ORDER-' . date('dmY')]);
            $transaksi = Transaksi::create([
                'id_transaksi' => $id_transaksi,
                'id_pelanggan' => $existedPelanggan ? $pelanggan->id_pelanggan : $request->pelanggan,
                'tgl_transaksi' => Carbon::now(),
                'status_pesanan' => 0,
                'status_pembayaran' => 0,
                // 'no_resi' => 0, //Ubah dulu
                'no_resi' => 0,
                'total_harga' => $request->total,
            ]);

            foreach (json_decode($request->transaksi) as $data) {
                $last_array = end($data->sku->harga);
                $price = $last_array->harga_produk;

                $satuanMeterPersegi = $data->sku->produk->satuan_produk == "m2";
                $ukuran = json_decode($data->ukuran);
                $resultUkuran = $satuanMeterPersegi ? $ukuran->panjang  * $ukuran->lebar : null;
                $totalPrice = $satuanMeterPersegi ? $price * $resultUkuran : $price;

                $result = $totalPrice * $data->qty_produk;

                $id_detail = IdGenerator::generate(['table' => 'detail_transaksi', 'field' => 'id_detail_transaksi', 'length' => 17, 'prefix' => 'DtlOrder-']);
                DetailTransaksi::create([
                    'id_detail_transaksi' => $id_detail,
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_sku' => $data->id_sku,
                    'qty_produk' => $data->qty_produk,
                    'ukuran' => $data->ukuran,
                    'id_finishing' => $data->id_finishing,

                    'subtotal' => $result,
                    'catatan' => "",
                    // 'catatan' => $data['catatan'],
                ]);

                Cart::where('id_cart', $data->id_cart)->delete();
            }

            DB::commit();
        } catch (\Throwable $th) {
            return response()->json('Something Went Wrong', 400);
            DB::rollback();
            //throw $th;
        }
    }

    public function changeStatus($id, Request $request)
    {
        $data = TransaksiPenjualan::findOrFail($id);
        $data->status_pesanan = $request->status_pesanan + 1;
        $data->save();

        return response()->json($data, 200);
    }
}
