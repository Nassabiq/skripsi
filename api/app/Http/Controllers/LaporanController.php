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

        $total_omset = $this->total_omset($prod);
        $total_hpp = $this->total_hpp($prod);
        // return $total_hpp;

        return response()->json(['reports' => $prod, 'total_omset' => $total_omset, 'total_hpp' => $total_hpp], 200);
    }

    public function total_omset($data)
    {
        $result = 0;
        foreach ($data as $item) {
            foreach ($item->stok as $stok) {
                foreach ($stok->transaksi as $item) $result += $item->transaksi->total_harga;
            }
        }

        return $result;
    }

    public function total_hpp($data)
    {
        $result = 0;
        foreach ($data as $item) {
            foreach ($item->stok as $stok) {
                foreach ($stok->hpp as $hpp) $result += $hpp->nilai_hpp;
            }
        }
        return $result;
    }
}
