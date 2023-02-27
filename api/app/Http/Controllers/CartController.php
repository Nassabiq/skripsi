<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(Request $request)
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
            'qty_cart'   => 'required|numeric|gt:0',
            'file_upload'   => 'required|max:5',
            'file_upload.*'   => 'mimes:jpeg,png,jpg,pdf|max:20000',
        ], [
            'id_sku.required' => "field jenis bahan tidak boleh kosong",
            'qty_cart.gt' => "jumlah minimal pemesanan harus lebih dari 0"
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);
        $id_cart = IdGenerator::generate(['table' => 'carts', 'field' => 'id_cart', 'length' => 10, 'prefix' => 'cart-']);

        $i = 1;
        $files = [];
        if ($request->hasFile('file_upload')) {
            foreach ($request->file('file_upload') as $key => $image) {
                $filename = "image-" . $id_cart . "-" . $i++ . "." . $image->getClientOriginalExtension();
                $image->storeAs('image_cart/' . $id_cart, $filename);

                // $dir = DIRECTORY_SEPARATOR;
                $fileUrl = "image_cart/" . $id_cart . "/" . $filename;
                $fileExt = $image->getClientOriginalExtension();
                $files[] = compact('filename', 'fileUrl', 'fileExt');
            }
        } else return response()->json('Invalid', 400);

        Cart::create([
            'id_cart' => $id_cart,
            'id_user' => $request->id_user,
            'id_sku' => $request->id_sku,
            'id_finishing' => $request->finishing,
            'qty_cart' => $request->qty_cart,
            'ukuran' => $request->ukuran,
            'file_upload' => json_encode($files),
        ]);

        return response()->json(['messages' => 'Data Keranjang Berhasil Ditambahkan'], 200);
    }

    public function removeCart($id_cart)
    {
        $data = Cart::where('id_cart', $id_cart)->first();
        Storage::deleteDirectory('image_cart/' . $data->id_cart);

        $data->delete();
        return response()->json($data, 200);
    }
}
