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

    protected $fillable = ['id_produk', 'nama_produk', 'slug_produk', 'deskripsi_produk', 'informasi_pemesanan', 'satuan_produk', 'image_produk'];

    public function kategori()
    {
        return $this->hasOne(KategoriProduk::class, 'id_kategori_produk', 'id_kategori_produk');
    }

    public function stok()
    {
        return $this->hasMany(SKU::class, 'id_produk', 'id_produk');
    }
    public function finishing()
    {
        return $this->hasMany(Finishing::class, 'id_produk', 'id_produk');
    }
}
