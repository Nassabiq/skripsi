<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\SKU;
use App\Models\StokMasuk;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function dashboard()
    {
    }
    public function laporanPenjualan(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $data = Transaksi::with(
            'detailTransaksi.sku.harga',
            'detailTransaksi.sku.produk',
            'detailTransaksi.sku.bahanBaku',
            'detailTransaksi.finishing',
            'pelanggan'
        )
            ->orderBy('tgl_transaksi', 'asc')
            ->whereBetween('tgl_transaksi', [$from, $to])
            ->where('status_pesanan', 6)
            ->get();

        return response()->json($data, 200);
    }

    public function laporanStokMasuk(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $data = StokMasuk::with('detailStok.bahanBaku', 'user')->orderBy('tgl_stok_masuk', 'asc')->whereBetween('tgl_stok_masuk', [$from, $to])->get();
        return response()->json($data, 200);
    }

    public function LaporanOmset(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $result = [];
        // GET DATA PRODUK
        $prod =  Produk::with('stok.transaksi.transaksi', 'stok.bahanBaku', 'stok.hpp')
            ->whereHas('stok.transaksi.transaksi', function ($q) use ($from, $to) {
                $q->whereBetween('tgl_transaksi', [$from, $to]);
                $q->where('status_pesanan', 6);
            })->get();
        // $id_sku = SKU::pluck('id_sku');
        // $dtl_transaksi = DetailTransaksi::with('transaksi', 'sku.produk')
        //     ->whereHas('transaksi', function ($q) use ($from, $to) {
        //         $q->whereBetween('tgl_transaksi', [$from, $to]);
        //     })
        //     ->whereIn('id_sku', $id_sku)
        //     ->get();

        /* 
            NAMA PRODUK
                BAHAN BAKU
                    OMSET PENDAPATAN
                    NILAI HPP
                    --------------------------------------
                                        TOTAL PENDAPATAN

            --------------LOOP----------------------------

            TOTAL OMSET KESELURUHAN
            TOTAL NILAI HPP
            ----------------------------------------------
                                TOTAL PENDAPATAN BERSIH
        */

        // Data Transaksi Selesai
        // $data = Transaksi::with(
        //     'detailTransaksi.sku.harga',
        //     'detailTransaksi.sku.produk',
        //     'detailTransaksi.sku.bahanBaku',
        //     'detailTransaksi.finishing',
        //     'pelanggan'
        // )
        //     ->orderBy('tgl_transaksi', 'asc')
        //     ->whereBetween('tgl_transaksi', [$from, $to])
        //     ->where('status_pesanan', 6)
        //     ->get();

        // Data Nilai HPP

        return response()->json($prod, 200);
    }
}
