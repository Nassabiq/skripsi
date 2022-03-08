<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $product =  Product::with('categories.satuan')->get();
        $category = ProductCategory::with('satuan')->get();
        $satuan = Unit::get();

        // var_dump($product);

        return response()->json(
            [
                'products' => $product,
                'category' => $category,
                'satuan' => $satuan
            ],
            200
        );
    }

    public function details($slug)
    {
        $product =  Product::with('categories.satuan')->where('slug', $slug)->first();
        return response()->json($product, 200);
    }
    public function addProduk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required',
            'id_kategori_produk'   => 'required',
            'description'   => 'required',
            'image'   => 'required',
            'image.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $files = [];

        $i = 1;
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $filename = "image-" . Str::slug($request->nama_produk) . "-" . $i++ . "." . $image->getClientOriginalExtension();
                $img = $image->storeAs('image_produk/' . Str::slug($request->nama_produk), $filename);

                // $dir = DIRECTORY_SEPARATOR;
                $fileUrl = "image_produk/" . Str::slug($request->nama_produk) . "/" . $filename;
                $files[] = compact('filename', 'fileUrl');
            }
        } else {
            return response()->json('Invalid', 400);
        }


        $id = IdGenerator::generate(['table' => 'products', 'field' => 'id_produk', 'length' => 10, 'prefix' => 'Prod-']);

        $product = Product::create([
            'id_produk' => $id,
            'nama_produk' => $request->nama_produk,
            'slug' => Str::slug($request->nama_produk),
            'id_kategori_produk' => $request->id_kategori_produk,
            'description' => $request->description,
            'image' => json_encode($files)
        ]);
        return response()->json($product, 200);
    }

    public function updateProduk(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required',
            'description'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $product = Product::findOrFail($id);
        $product->nama_produk =  $request->nama_produk;
        $product->description = $request->description;
        $product->save();

        return response()->json($product, 200);
    }

    public function updateImage($id, Request $request)
    {
        var_dump($request->all());
        $product = Product::findOrFail($id);
        $image = json_decode($product->image);


        $path = public_path('storage/');

        foreach ($image as $key => $img) {
            foreach ($request->deletedImage as $imgDeleted) {
                $deletedImage = json_decode($imgDeleted);
                if ($img->filename == $deletedImage->filename) {
                    $filename = $path . $img->fileUrl;
                    unlink($filename);
                }
            }
        }
        $i = 1;
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $filename = "image-" . Str::slug($request->nama_produk) . "-" . $i++ . "." . $image->getClientOriginalExtension();
                $img = $image->storeAs('image_produk/' . Str::slug($request->nama_produk), $filename);

                // $dir = DIRECTORY_SEPARATOR;
                $fileUrl = "image_produk/" . Str::slug($request->nama_produk) . "/" . $filename;
                $files[] = compact('filename', 'fileUrl');
            }
        }
    }

    public function deleteProduk($id)
    {
        $product = Product::findOrFail($id);
        Storage::deleteDirectory('image_produk/' . $product->slug);
        $product->delete();
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

    public function updateKategori(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori'   => 'required',
            'id_satuan'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $category = ProductCategory::where('id_kategori_produk', $id)->first();

        $category->nama_kategori = $request->nama_kategori;
        $category->id_satuan = $request->id_satuan;
        $category->save();

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

    public function updateSatuan(Request $request, $id)
    {
        // var_dump($id);
        $validator = Validator::make($request->all(), [
            'nama_satuan'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $unit = Unit::where('id_satuan', $id)->first();
        $unit->nama_satuan = $request->nama_satuan;
        $unit->save();

        return response()->json($id, 200);
    }
    public function deleteSatuan($id)
    {
        $unit = Unit::where('id_satuan', $id)->first();
        $unit->delete();
        return response()->json($unit, 200);
    }
}
