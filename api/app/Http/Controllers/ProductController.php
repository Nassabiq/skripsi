<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $product =  Product::with('categories.satuan')->get();
        $category = ProductCategory::with('satuan')->get();
        $satuan = Unit::get();

        return response()->json(
            [
                'products' => $product,
                'category' => $category,
                'satuan' => $satuan
            ],
            200
        );
    }
    public function addProduk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required',
            'id_kategori_produk'   => 'required',
            'description'   => 'required',
            'image'   => 'required',
            'image.*'   => 'required|image|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = IdGenerator::generate(['table' => 'products', 'field' => 'id_produk', 'length' => 10, 'prefix' => 'Prod-']);

        $product = Product::create([
            'id_produk' => $id,
            'nama_produk' => $request->nama_produk,
            'slug' => Str::slug($request->nama_produk),
            'id_kategori_produk' => $request->id_kategori_produk,
            'description' => $request->description,
            // 'image' => 
        ]);
        return response()->json($product, 200);
    }

    public function addKategori(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori'   => 'required',
            'id_satuan'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = IdGenerator::generate(['table' => 'product_categories', 'field' => 'id_kategori_produk', 'length' => 10, 'prefix' => 'PrdC-']);

        $category = ProductCategory::create([
            'id_kategori_produk' => $id,
            'id_satuan' => $request->id_satuan,
            'nama_kategori' => $request->nama_kategori,
        ]);

        return response()->json($category, 200);
    }

    public function deleteKategori($id)
    {
        $category = ProductCategory::where('id_kategori_produk', $id)->first();
        $category->delete();
        return response()->json($category, 200);
    }

    public function addSatuan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = IdGenerator::generate(['table' => 'units', 'field' => 'id_satuan', 'length' => 10, 'prefix' => 'UNIT-']);

        $unit = Unit::create([
            'id_satuan' => $id,
            'nama_satuan' => $request->nama_satuan
        ]);

        return response()->json($unit, 200);
    }
    public function deleteSatuan($id)
    {
        $unit = Unit::where('id_satuan', $id)->first();
        $unit->delete();
        return response()->json($unit, 200);
    }
}
