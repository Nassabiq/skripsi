<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
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
}
