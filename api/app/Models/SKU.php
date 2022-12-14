<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKU extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sku';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'sku';
    protected $fillable = ['id_sku', 'id_produk', 'id_bahan_baku'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
    public function harga()
    {
        return $this->hasMany(HargaJualProduk::class, 'id_sku');
    }
    public function transaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_sku');
    }
    // public function hpp()
    // {
    //     return $this->hasMany(DetailTransaksi::class, 'id_sku');
    // }
}
