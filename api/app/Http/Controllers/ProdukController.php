<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        if ($request->show == null) {
            $produk =  Produk::with('kategori')->get();
        } else {
            $produk =  Produk::with('kategori')->where('nama_produk', 'like', $search)->paginate($request->show);
        }
        return response()->json($produk, 200);
    }

    public function detail($id_produk)
    {
        $produk =  Produk::with('kategori')->find($id_produk);
        return response()->json($produk, 200);
    }

    public function kategori()
    {
        $kategori = KategoriProduk::get();
        return response()->json($kategori, 200);
    }
    // CREATE RECORD INTO DATABASE
    public function addProduk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required',
            'id_kategori_produk'   => 'required',
            'satuan_produk'   => 'required',
            'description'   => 'required',
            'informasi_pemesanan'   => 'required',
            'image'   => 'required',
            'image.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $files = [];

        $i = 1;
        $path = '/temporary';
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                // var_dump($request->file('image'));
                $filename = "image-" . Str::slug($request->nama_produk) . "-" . $i++ . "." . $image->getClientOriginalExtension();

                $imgFile = Image::make($image->getRealPath() . $image->getClientOriginalExtension());
                $imgFile->resizeCanvas(300, 300)->save($path . '/' . $filename, 60, 'jpg');

                $image->move(storage_path('image_produk/' . Str::slug($request->nama_produk) . '/' . $imgFile));

                // $dir = DIRECTORY_SEPARATOR;
                $fileUrl = "image_produk/" . Str::slug($request->nama_produk) . "/" . $filename;
                $files[] = compact('filename', 'fileUrl');
            }
        } else {
            return response()->json('Invalid', 400);
        }


        $id = IdGenerator::generate(['table' => 'produk', 'field' => 'id_produk', 'length' => 10, 'prefix' => 'Prod-']);

        $produk = Produk::create([
            'id_produk' => $id,
            'id_kategori_produk' => $request->id_kategori_produk,
            'nama_produk' => $request->nama_produk,
            'slug_produk' => Str::slug($request->nama_produk),
            'image' => json_encode($files),
            'satuan_produk' => $request->satuan_produk,
            'description' => $request->description,
            'informasi_pemesanan' => $request->informasi_pemesanan,
        ]);
        return response()->json($produk, 200);
    }

    // UPDATE DATA INTO DATABASE
    public function updateProduk(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required',
            'id_kategori_produk'   => 'required',
            'satuan_produk'   => 'required',
            'description'   => 'required',
            'informasi_pemesanan'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $produk = Produk::findOrFail($id);
        $produk->nama_produk =  $request->nama_produk;
        $produk->slug_produk =  Str::slug($request->nama_produk);
        $produk->satuan_produk =  $request->satuan_produk;
        $produk->id_kategori_produk =  $request->id_kategori_produk;
        $produk->description = $request->description;
        $produk->informasi_pemesanan = $request->informasi_pemesanan;
        $produk->save();

        return response()->json($produk, 200);
    }

    // DELETE DATA FROM DATABASE
    public function deleteProduk($id)
    {
        $produk = Produk::findOrFail($id);
        Storage::deleteDirectory('image_produk/' . $produk->slug);
        $produk->delete();
        return response()->json($produk, 200);
    }
}
