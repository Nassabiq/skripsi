<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
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
            'qty_cart'   => 'required|numeric',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $id_cart = IdGenerator::generate(['table' => 'carts', 'field' => 'id_cart', 'length' => 10, 'prefix' => 'cart-']);
        Cart::create([
            'id_cart' => $id_cart,
            'id_user' => $request->id_user,
            'id_sku' => $request->id_sku,
            'id_finishing' => $request->finishing,
            'qty_cart' => $request->qty_cart,
            'ukuran' => $request->ukuran,
        ]);

        return response()->json(['messages' => 'Data Keranjang Berhasil Ditambahkan'], 200);
    }

    public function removeCart($id_cart)
    {
        $data = Cart::where('id_cart', $id_cart)->first();
        $data->delete();
        return response()->json($data, 200);
    }
}
