<?php

namespace App\Http\Controllers;

use App\Models\Finishing;
use App\Models\HargaJualProduk;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\SKU;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function GuzzleHttp\Promise\all;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $produk = $request->show == null
            ? Produk::with('kategori', 'stok.harga')->get()
            : Produk::with('kategori', 'stok.harga')->where('nama_produk', 'like', $search)->paginate($request->show);

        return response()->json($produk, 200);
    }

    public function detail($id_produk)
    {
        $produk =  Produk::with('kategori', 'stok.bahanBaku', 'stok.harga', 'finishing')->find($id_produk);
        return response()->json($produk, 200);
    }
    public function katalog(Request $request)
    {
        $kategori = '%' . $request->kategori . '%';
        $produk =  Produk::with(['stok.harga', 'stok.bahanBaku', 'finishing'])->where('id_kategori_produk', 'like', $kategori)->get();
        return response()->json($produk, 200);
    }

    public function harga(Request $request)
    {
        $sku = SKU::with('harga')->where('id_produk', $request->id_produk)->where('id_bahan_baku', $request->id_bahan_baku)->first();
        return response()->json($sku, 200);
    }

    public function kategori()
    {
        $kategori = KategoriProduk::get();
        return response()->json($kategori, 200);
    }

    // CREATE RECORD INTO DATABASE
    public function addProduk(Request $request)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required',
            'id_kategori_produk'   => 'required',
            'satuan_produk'   => 'required',
            'deskripsi_produk'   => 'required',
            'informasi_pemesanan'   => 'required',
            'image_produk'   => 'required|max:5',
            'image_produk.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            'finishing.*'   => 'required',
            'id_bahan.*'   => 'required',
            'harga.*'   => 'required',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        DB::beginTransaction();
        try {
            $id_produk = IdGenerator::generate(['table' => 'produk', 'field' => 'id_produk', 'length' => 10, 'prefix' => 'Prod-']);

            // SAVE IMAGE TO STORAGE
            $i = 1;
            $files = [];
            if ($request->hasFile('image_produk')) {
                foreach ($request->file('image_produk') as $key => $image) {
                    $filename = "image-" . $id_produk . "-" . $i++ . "." . $image->getClientOriginalExtension();
                    $image->storeAs('image_produk/' . $id_produk, $filename);

                    // $dir = DIRECTORY_SEPARATOR;
                    $fileUrl = "image_produk/" . $id_produk . "/" . $filename;
                    $files[] = compact('filename', 'fileUrl');
                }
            } else return response()->json('Invalid', 400);

            $produk = Produk::create([
                'id_produk' => $id_produk,
                'id_kategori_produk' => $request->id_kategori_produk,
                'nama_produk' => $request->nama_produk,
                'slug_produk' => Str::slug($request->nama_produk),
                'image_produk' => json_encode($files),
                'satuan_produk' => $request->satuan_produk,
                'deskripsi_produk' => $request->deskripsi_produk,
                'informasi_pemesanan' => $request->informasi_pemesanan,
            ]);

            foreach ($request->finishing as $item) {
                $id_finishing = IdGenerator::generate(['table' => 'finishing', 'field' => 'id_finishing', 'length' => 8, 'prefix' => 'FP-']);
                Finishing::create(['id_finishing' => $id_finishing, 'id_produk' => $id_produk, 'nama_finishing' => $item]);
            }
            foreach (array_combine($request->id_bahan, $request->harga)  as $id_bahan => $harga) {
                $id_sku = IdGenerator::generate(['table' => 'sku', 'field' => 'id_sku', 'length' => 9, 'prefix' => 'SKU-']);
                SKU::create(['id_sku' => $id_sku, 'id_produk' => $id_produk, 'id_bahan_baku' => $id_bahan]);

                $id_harga_jual = IdGenerator::generate(['table' => 'harga_jual_produk', 'field' => 'id_harga_jual', 'length' => 8, 'prefix' => 'HP-']);
                HargaJualProduk::create([
                    'id_harga_jual' => $id_harga_jual,
                    'id_sku' => $id_sku,
                    'harga_produk' => $harga,
                    'tgl_diubah' => Carbon::now()
                ]);
            }

            DB::commit();
            return response()->json($produk, 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['messages' => "Something Went Wrong"], 400);
        }
    }

    // UPDATE DATA INTO DATABASE
    public function updateProduk(Request $request, $id)
    {
        // var_dump($request->all());
        // $validator = Validator::make($request->all(), [
        //     'nama_produk'   => 'required',
        //     'id_kategori_produk'   => 'required',
        //     'satuan_produk'   => 'required',
        //     'deskripsi_produk'   => 'required',
        //     'informasi_pemesanan'   => 'required',
        // ]);

        // if ($validator->fails()) return response()->json($validator->errors(), 400);

        // $produk = Produk::findOrFail($id);
        // $produk->nama_produk =  $request->nama_produk;
        // $produk->slug_produk =  Str::slug($request->nama_produk);
        // $produk->satuan_produk =  $request->satuan_produk;
        // $produk->id_kategori_produk =  $request->id_kategori_produk;
        // $produk->deskripsi_produk = $request->deskripsi_produk;
        // $produk->informasi_pemesanan = $request->informasi_pemesanan;

        // $produk->save();

        // $finishing = Finishing::where('id_produk', $id)->get();

        // $finishing_update = json_decode($request->finishing);
        // foreach ($finishing as $item) {
        //     foreach ($finishing_update as $fu) {
        //         if ($item->id_finishing == $fu->id_finishing) $item->nama_finishing = $fu->nama_finishing;
        //         $item->save();
        //     }
        // }

        // $jenis_bahan_update = json_decode($request->jenis_bahan);

        // foreach ($jenis_bahan_update as $data) {
        //     $harga = HargaJualProduk::findOrFail($data->id_harga_jual);
        //     $harga->harga_produk = $data->harga_produk;
        //     $harga->save();
        // }

        // if ($request->new_finishing != null) {
        //     $new_finishing = json_decode($request->new_finishing);
        //     foreach ($new_finishing as $data) {
        //         $id_finishing = IdGenerator::generate(['table' => 'finishing', 'field' => 'id_finishing', 'length' => 8, 'prefix' => 'FP-']);
        //         Finishing::create(['id_finishing' => $id_finishing, 'id_produk' => $id, 'nama_finishing' => $data]);                # code...
        //     }
        // }

        if ($request->new_jenis_bahan != null) {
            $new_jenis_bahan = json_decode($request->new_jenis_bahan);

            foreach ($new_jenis_bahan as $item) {
                $sku = SKU::where('id_produk', $id)->get();
                foreach ($sku as $data) {
                    if ($item->id !== $data->id_bahan_baku) {
                        $id_sku = IdGenerator::generate(['table' => 'sku', 'field' => 'id_sku', 'length' => 9, 'prefix' => 'SKU-']);
                        $id_harga_jual = IdGenerator::generate(['table' => 'harga_jual_produk', 'field' => 'id_harga_jual', 'length' => 8, 'prefix' => 'HP-']);

                        SKU::create(['id_sku' => $id_sku, 'id_produk' => $id, 'id_bahan_baku' => $item->id]);
                        HargaJualProduk::create([
                            'id_harga_jual' => $id_harga_jual,
                            'id_sku' => $id_sku,
                            'harga_produk' => $item->harga,
                            'tgl_diubah' => Carbon::now()
                        ]);
                        // var_dump(true);
                        // } else {
                        //     var_dump(false);
                    }
                }
            }
        }


        // return response()->json(["message" => "Data Inserted Successfully"], 200);
        return response()->json(400);
    }

    public function updateImage($id, Request $request)
    {
        $product = Produk::findOrFail($id);
        $image = json_decode($product->image);

        $path = public_path('storage/');

        foreach ($image as $key => $img) {
            if ($request->deletedImage) {
                foreach ($request->deletedImage as $imgDeleted) {
                    $deletedImage = json_decode($imgDeleted);
                    if ($img->filename == $deletedImage->filename) {
                        $filename = $path . $img->fileUrl;
                        unlink($filename);
                        unset($image[$key]);
                    }
                }
            }
        }
        $i = count($image) + 1;
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                $filename = "image-" . $product->id_produk . "-" . $i++ . "." . $img->getClientOriginalExtension();
                $img = $img->storeAs('image_produk/' . $product->id_produk, $filename);

                // $dir = DIRECTORY_SEPARATOR;
                $fileUrl = "image_produk/" . $product->id_produk . "/" . $filename;
                $files = compact('filename', 'fileUrl');

                $image[] = $files;
            }
        }

        $product->image = json_encode($image);
        $product->save();

        return response()->json($product, 200);
    }

    // DELETE DATA FROM DATABASE
    public function deleteProduk($id)
    {
        $produk = Produk::findOrFail($id);
        Storage::deleteDirectory('image_produk/' . $produk->id_produk);
        $produk->delete();
        return response()->json($produk, 200);
    }

    public function updateHarga(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'harga'   => 'required|numeric',
            'sku'   => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $id = IdGenerator::generate([
            'table' => 'harga_jual_produk', 'field' => 'id_harga_jual', 'length' => 8, 'prefix' => 'HP-'
        ]);

        $data = HargaJualProduk::create([
            'id_harga_jual' => $id,
            'id_sku' => $request->sku,
            'harga_produk' => $request->harga,
            'tgl_diubah' => Carbon::now()
        ]);
        return response()->json($data, 200);
    }
}
