<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $id_user = $request->user;
        $status_pesanan = '%' . $request->status_pesanan . '%';

        $data = Transaksi::whereHas('pelanggan', function ($q) use ($id_user) {
            $q->where('id_user', $id_user);
        })->where('status_pesanan', 'like', $status_pesanan)
            ->with([
                'detailTransaksi.sku.harga',
                'detailTransaksi.sku.produk',
                'detailTransaksi.sku.bahanBaku',
                'detailTransaksi.finishing',
                'pelanggan'
            ])->orderBy('tgl_transaksi', 'desc')->get();
        return response()->json($data, 200);
    }
    public function dataTransaksi(Request $request)
    {
        $search = '%' . $request->search . '%';
        $status = '%' . $request->status_pesanan . '%';

        $data = Transaksi::where('id_transaksi', 'like', $search)
            ->where('status_pesanan', 'like', $status)
            ->with(
                'detailTransaksi.sku.harga',
                'detailTransaksi.sku.produk',
                'detailTransaksi.sku.bahanBaku',
                'detailTransaksi.finishing',
                'pelanggan'
            )->orderBy('tgl_transaksi', 'desc')->paginate($request->show);
        return response()->json($data, 200);
    }



    public function detailTransaksi($id)
    {
        $data = Transaksi::with(
            'detailTransaksi.sku.harga',
            'detailTransaksi.sku.produk',
            'detailTransaksi.sku.bahanBaku',
            'detailTransaksi.finishing',
            'pelanggan'
        )->where('id_transaksi', $id)->first();
        return response()->json($data, 200);
    }

    public function submitTransaksi(Request $request)
    {
        // var_dump($request->except('pelanggan'));
        // DB::beginTransaction();
        // try {
        $existedPelanggan = is_array($request->pelanggan);
        $errors = collect();
        if ($existedPelanggan) {
            $validatePelanggan = Validator::make($request->pelanggan, [
                'nama_pelanggan'   => 'required',
                'no_telp'   => 'required',
                'alamat'   => 'required',
            ], [
                'required' => 'form ini harus diisi'
            ]);
            // if ($validatePelanggan->fails()) return response()->json($validatePelanggan->errors(), 400);
            if ($validatePelanggan->fails()) $errors->push($validatePelanggan->errors());

            // $id_pelanggan = IdGenerator::generate(['table' => 'pelanggan', 'field' => 'id_pelanggan', 'length' => 10, 'prefix' => 'CUST-']);
            // $pelanggan = Pelanggan::create([
            //     'id_pelanggan' => $id_pelanggan,
            //     'id_user' => $request->user,
            //     'nama_pelanggan' => $request->pelanggan['nama_pelanggan'],
            //     'alamat' => $request->pelanggan['alamat'],
            //     'no_telp' => $request->pelanggan['no_telp'],
            // ]);
        }

        $validator = Validator::make($request->except('pelanggan'), ['shipment' => 'required',], ['required' => 'form ini harus diisi']);
        if ($validator->fails()) $errors->push($validator->errors());
        // if ($validator->fails()) return response()->json($validator->errors(), 400);
        // var_dump($errors);
        // foreach ($errors as $key => $error) {
        //     # code...
        //     array_merge()
        // }
        if (count($errors) > 0) return response()->json($errors, 400);

        // $id_transaksi = IdGenerator::generate(['table' => 'transaksi', 'field' => 'id_transaksi', 'length' => 20, 'prefix' => 'ORDER-' . date('dmY')]);
        // $transaksi = Transaksi::create([
        //     'id_transaksi' => $id_transaksi,
        //     'id_pelanggan' => $existedPelanggan ? $pelanggan->id_pelanggan : $request->pelanggan,
        //     'tgl_transaksi' => Carbon::now(),
        //     'status_pesanan' => 1,
        //     'status_pembayaran' => 0,
        //     'total_harga' => $request->total,
        //     'catatan' => $request->catatan,
        //     'pengiriman' => $request->shipment,
        // ]);

        // foreach (json_decode($request->transaksi) as $data) {
        //     $last_array = end($data->sku->harga);
        //     $price = $last_array->harga_produk;

        //     $satuanMeterPersegi = $data->sku->produk->satuan_produk == "m2";
        //     $ukuran = json_decode($data->ukuran);
        //     $resultUkuran = $satuanMeterPersegi ? $ukuran->panjang  * $ukuran->lebar : null;
        //     $totalPrice = $satuanMeterPersegi ? $price * $resultUkuran : $price;

        //     $result = $totalPrice * $data->qty_cart;

        //     $id_detail = IdGenerator::generate(['table' => 'detail_transaksi', 'field' => 'id_detail_transaksi', 'length' => 17, 'prefix' => 'DtlOrder-']);
        //     DetailTransaksi::create([
        //         'id_detail_transaksi' => $id_detail,
        //         'id_transaksi' => $transaksi->id_transaksi,
        //         'id_sku' => $data->id_sku,
        //         'qty_produk' => $data->qty_cart,
        //         'ukuran' => $data->ukuran,
        //         'id_finishing' => $data->id_finishing,

        //         'subtotal' => $result,
        //     ]);

        //     Cart::where('id_cart', $data->id_cart)->delete();
        // }

        //     DB::commit();
        // } catch (\Throwable $th) {
        //     return response()->json('Something Went Wrong', 400);
        //     DB::rollback();
        //     //throw $th;
        // }
        // return response()->json('Something Went Wrong', 400);
    }

    public function changeStatus($id, Request $request)
    {
        $data = Transaksi::findOrFail($id);
        $data->status_pesanan = $request->status_pesanan + 1;
        $data->save();

        return response()->json($data, 200);
    }

    public function inputResi(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'resi'   => 'required',
        ], [
            'required' => 'form ini harus diisi'
        ]);
        if ($validate->fails()) return response()->json($validate->errors(), 400);

        $data = Transaksi::findOrFail($id);
        $data->no_resi = $request->resi;
        $data->save();

        return response()->json($data, 200);
    }
}
