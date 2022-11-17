<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailTransaksi;
use App\Models\PencatatanProduksi;
use App\Models\Product;
use App\Models\TransaksiPenjualan;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        $produk = Product::with('material', 'categories.satuan', 'harga')->get();
        return response()->json($produk, 200);
    }

    public function dataTransaksi(Request $request)
    {
        $search = '%' . $request->search . '%';
        $status = '%' . $request->status . '%';
        if ($request->show == null) {
            $data = TransaksiPenjualan::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')->orderBy('tgl_transaksi', 'desc')->get();
        } else {
            $data = TransaksiPenjualan::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')
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

    public function cart(Request $request)
    {
        $data = Cart::with('sku.produk', 'sku.bahanBaku', 'sku.harga')->where('id_user', auth()->user()->id_user)->get();
        return response()->json($data, 200);
    }

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user'   => 'required',
            'id_sku'   => 'required',
            'finishing'   => 'required',
            'qty_produk'   => 'required|numeric',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $id_cart = IdGenerator::generate(['table' => 'carts', 'field' => 'id_cart', 'length' => 10, 'prefix' => 'cart-']);

        Cart::create([
            'id_cart' => $id_cart,
            'id_user' => $request->id_user,
            'id_sku' => $request->id_sku,
            'id_finishing' => $request->finishing,
            'qty_produk' => $request->qty_produk,
            'ukuran' => $request->ukuran,
        ]);
    }

    public function laporanPenjualan(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $data = TransaksiPenjualan::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')
            ->orderBy('tgl_transaksi', 'asc')
            ->whereBetween('tgl_transaksi', [$from, $to])
            ->get();

        return response()->json($data, 200);
    }

    public function detailTransaksi($id)
    {
        $data = TransaksiPenjualan::with('detailTransaksi.produk.harga', 'detailTransaksi.produk.categories.satuan')->where('id_transaksi', $id)->first();
        return response()->json($data, 200);
    }

    public function submitTransaksi(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'nama_pemesan'   => 'required',
                'no_telp'   => 'required',
                'alamat'   => 'required',
                'total_pembayaran'   => 'required|numeric',
                'total'   => 'required|numeric',
            ]);

            if ($validator->fails()) return response()->json($validator->errors(), 400);

            $id_transaksi = IdGenerator::generate(['table' => 'transaksi_penjualan', 'field' => 'id_transaksi', 'length' => 20, 'prefix' => 'ORDER-' . date('dmY')]);

            $transaksi = TransaksiPenjualan::create([
                'id_transaksi' => $id_transaksi,
                'nama_pemesan' => $request->nama_pemesan,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'tgl_transaksi' => Carbon::now(),
                'status_pesanan' => 0,
                'total_harga' => $request->total,
                'total_pembayaran' => $request->total_pembayaran
            ]);

            foreach ($request->pesanan as $data) {
                $id_detail = IdGenerator::generate([
                    'table' => 'detail_transaksi',
                    'field' => 'id_detail_transaksi',
                    'length' => 17,
                    'prefix' => 'DtlOrder-'
                ]);

                $id_produksi = IdGenerator::generate([
                    'table' => 'pencatatan_produksi',
                    'field' => 'id_pencatatan',
                    'length' => 20,
                    'prefix' => 'Produksi-'
                ]);

                $detail_transaksi = DetailTransaksi::create([
                    'id_detail_transaksi' => $id_detail,
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_produk' => $data['id_produk'],
                    'jenis_bahan' => $data['jenis_bahan'],
                    'qty' => $data['qty'],
                    'subtotal' => $data['qty'] * $data['harga'],
                    'finishing' => $data['finishing'],
                    'laminasi' => $data['laminasi'],
                ]);

                $produksi = PencatatanProduksi::create([
                    'id_pencatatan' => $id_produksi,
                    'id_detail_transaksi' => $detail_transaksi->id_detail_transaksi,
                    'tgl_produksi' => Carbon::now()
                ]);


                $product = Product::with('material')->where('id_produk', $detail_transaksi->id_produk)->first();
                foreach ($product->material as $data) {
                    $produksi->materials()->attach($data->id_material, ['stok_digunakan' => $detail_transaksi->qty]);

                    if ($data->stok >= $detail_transaksi->qty) {
                        $data->stok = $data->stok - $detail_transaksi->qty;
                        $data->save();
                    } else {
                        return response()->json('Stok tidak cukup', 400);
                    }
                }

                DB::commit();
            }
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
