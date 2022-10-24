<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'produk';

    protected $fillable = ['id_produk', 'id_kategori_produk', 'nama_produk', 'slug_produk', 'description', 'informasi_pemesanan', 'satuan_produk', 'image'];

    public function kategori()
    {
        return $this->hasOne(KategoriProduk::class, 'id_kategori_produk', 'id_kategori_produk');
    }

    public function stok()
    {
        return $this->belongsTo(SKU::class, 'id_sku', 'id_sku');
    }
}
