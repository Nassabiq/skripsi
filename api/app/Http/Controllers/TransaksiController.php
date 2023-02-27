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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Xendit\Xendit;

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

    public function getInvoice($invoice_id)
    {
        Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));
        $getInvoice = \Xendit\Invoice::retrieve($invoice_id);
        return response()->json($getInvoice, 200);
    }

    public function callback()
    {
        $data =  request()->all();
        $status = $data['status'];
        $external_id = $data['external_id'];

        Transaksi::where('id_transaksi', $external_id)->update([
            'status_pembayaran' => $status == "PAID" ? 1 : 0,
            'status_pesanan' => 2
        ]);
        return response()->json($data, 200);
    }

    public function submitTransaksi(Request $request)
    {
        DB::beginTransaction();
        try {
            $errors = collect();
            Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));

            $validator = Validator::make($request->except('pelanggan'), ['shipment' => 'required',], ['required' => 'form ini harus diisi']);
            if ($validator->fails()) $errors = $validator->errors();

            $existedPelanggan = is_array($request->pelanggan);
            if ($existedPelanggan) {
                $validatePelanggan = Validator::make($request->pelanggan, [
                    'nama_pelanggan'   => 'required',
                    'no_telp'   => 'required',
                    'alamat'   => 'required',
                ], [
                    'required' => 'form ini harus diisi'
                ]);

                if ($validatePelanggan->fails()) $errors->merge($validatePelanggan->errors());
                if (count($errors) > 0) return response()->json($errors, 400);

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
                'status_pesanan' => 1,
                'status_pembayaran' => 0,
                'total_harga' => $request->total,
                'catatan' => $request->catatan,
                'pengiriman' => $request->shipment,
            ]);

            foreach (json_decode($request->transaksi) as $data) {
                $last_array = end($data->sku->harga);
                $price = $last_array->harga_produk;

                $satuanMeterPersegi = $data->sku->produk->satuan_produk == "m2";
                $ukuran = json_decode($data->ukuran);
                $resultUkuran = $satuanMeterPersegi ? $ukuran->panjang  * $ukuran->lebar : null;
                $totalPrice = $satuanMeterPersegi ? $price * $resultUkuran : $price;

                $result = $totalPrice * $data->qty_cart;

                $id_detail = IdGenerator::generate(['table' => 'detail_transaksi', 'field' => 'id_detail_transaksi', 'length' => 17, 'prefix' => 'DtlOrder-']);

                $i = 1;
                $files = [];
                foreach (json_decode($data->file_upload) as $item) {
                    $fileExt = $item->fileExt;
                    $filename = "image-" . $id_detail . "-" . $i++ . "." . $item->fileExt;
                    $fileUrl = "item-transaksi/" . $id_detail . '/' . $filename;

                    Storage::move($item->fileUrl, $fileUrl);

                    $files[] = compact('filename', 'fileUrl', 'fileExt');
                }

                DetailTransaksi::create([
                    'id_detail_transaksi' => $id_detail,
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_sku' => $data->id_sku,
                    'qty_produk' => $data->qty_cart,
                    'ukuran' => $data->ukuran,
                    'id_finishing' => $data->id_finishing,
                    'file_upload' => json_encode($files),

                    'subtotal' => $result,
                ]);

                $cart = Cart::where('id_cart', $data->id_cart)->first();
                Storage::deleteDirectory('image_cart/' . $data->id_cart);

                $cart->delete();
            }
            $params = [
                'external_id' => $id_transaksi,
                'payer_email' => Auth::user()->email,
                'description' => 'Invoice Transaksi UQI Media',
                'amount' => $transaksi->total_harga,

            ];
            $createInvoice = \Xendit\Invoice::create($params);

            $transaksi->invoice_id = $createInvoice['id'];
            $transaksi->save();

            DB::commit();
        } catch (\Throwable $th) {
            return response()->json('Something Went Wrong', 400);
            DB::rollback();
            //throw $th;
        }
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
        $data->status_pesanan = 6;
        $data->save();

        return response()->json($data, 200);
    }
}
