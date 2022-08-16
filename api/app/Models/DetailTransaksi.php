<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'detail_transaksi';

    protected $fillable = ['id_detail_transaksi', 'id_transaksi', 'id_produk', 'jenis_bahan', 'qty', 'subtotal', 'ukuran', 'finishing', 'laminasi'];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi');
    }
    public function produk()
    {
        return $this->hasOne(Product::class, 'id_produk', 'id_produk');
    }

    public function pencatatan()
    {
        return $this->hasOne(PencatatanProduksi::class, 'id_pencatatan', 'id_pencatatan');
    }
}
