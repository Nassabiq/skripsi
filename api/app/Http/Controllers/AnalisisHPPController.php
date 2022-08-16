<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class AnalisisHPPController extends Controller
{

    protected $stock = [], $resultHPP = [];

    public function index(Request $request)
    {
        $produksi = Product::where('id_produk', $request->id)
            ->with(
                'material.produksi.transaksi',
            )
            ->whereHas('material.produksi', function ($query) use ($request) {
                $query->whereMonth('tgl_produksi', intval($request->month));
                $query->whereYear('tgl_produksi', intval($request->year));
            })
            ->first();

        $pembelian = Product::where('id_produk', $request->id)
            ->with(
                'material.detailBarang.barangMasuk'
            )
            ->whereHas('material.detailBarang.barangMasuk', function ($query) use ($request) {
                $query->whereMonth('tgl_barang_masuk', intval($request->month));
                $query->whereYear('tgl_barang_masuk', intval($request->year));
            })
            ->first();

        $result = [
            'produksi' => $produksi == null ? null : $produksi['material'],
            'pembelian' => $pembelian == null ? null : $pembelian['material'],
        ];
        return $this->concatData(json_encode($result));
    }

    public function concatData($item)
    {
        $hpp = [];
        $result = [];
        $data = json_decode($item);

        if (count($hpp) > 0) array_pop($hpp);

        // Separate Pembelian dan Produksi
        if ($data->produksi && $data->pembelian) {
            foreach ($data->produksi as $produksi) {
                foreach ($data->pembelian as $pembelian) {
                    if ($produksi->id_material == $pembelian->id_material) {
                        $concat = [
                            'bahan_baku' => $produksi->nama_bahan_baku,
                            'produksi' => $produksi->produksi,
                            'pembelian' =>  $pembelian->detail_barang,
                        ];
                        array_push($hpp, $concat);
                    }
                }
            }
        } elseif (isNull($data->produksi)) {
            foreach ($data->pembelian as $pembelian) {
                $concat = [
                    'bahan_baku' => $pembelian->nama_bahan_baku,
                    'pembelian' =>  $pembelian->detail_barang,
                ];
                array_push($hpp, $concat);
            }
        } elseif (isNull($data->pembelian)) {
            foreach ($data->produksi as $produksi) {
                $concat = [
                    'bahan_baku' => $produksi->nama_bahan_baku,
                    'produksi' => $produksi->produksi,
                ];
                array_push($hpp, $concat);
            }
        }

        // Gabungin Data
        foreach ($hpp as $item) {
            foreach ($item['pembelian'] as $data) {
                $concat = [
                    'id' => "pembelian",
                    'tgl' => Carbon::parse($data->barang_masuk->tgl_barang_masuk)->format("d-m-Y"),
                    'qty' => $data->qty,
                    'harga' => $data->harga,
                    'total' => $data->qty * $data->harga,
                ];
                array_push($result, $concat);
            }
            foreach ($item['produksi'] as $data) {
                $concat = [
                    'id' => "produksi",
                    'tgl' => Carbon::parse($data->tgl_produksi)->format("d-m-Y"),
                    'qty' => $data->transaksi->qty
                ];
                array_push($result, $concat);
            }
        }
        $this->analisisHPP(json_encode($result));
    }


    public function analisisHPP($data)
    {
        $dataItem = json_decode($data);

        foreach ($dataItem as $key => $item) {
            # code...
            $this->hitungStok($item);
            $item->id == "pembelian" ? $this->pembelian($item, $key) : $this->produksi($item, $key);
        }
    }

    public function hitungStok($dataItem)
    {
        // var_dump($dataItem->harga);
        $temporaryStock = [];
        if ($dataItem->harga) {
            array_push(
                $temporaryStock,
                [

                    'harga' => $dataItem->harga,
                    'stock' => $dataItem->qty
                ]
            );
        }
        var_dump($temporaryStock);
        for ($i = 0; $i < count($temporaryStock); $i++) {
            for ($j = 0; $j < count($temporaryStock); $j++) {
                // if ($temporaryStock[i]['harga'] == $temporaryStock[j]['harga']) {
                //     $sum = $temporaryStock[j]['harga'] += $temporaryStock[j]['harga'];
                // }
            }
        }
        // print_r($sum);
    }

    public function pembelian($dataItem, $index)
    {
        // var_dump($dataItem);
    }
    public function produksi($dataItem, $index)
    {
        // var_dump($dataItem);
    }
}
