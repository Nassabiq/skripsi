<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use Illuminate\Http\Request;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $stok_masuk = StokMasuk::with('detailStok.bahanBaku')->where('id_barang_masuk', 'like', $search)->paginate($request->show);
        return response()->json($stok_masuk, 200);
    }
}
