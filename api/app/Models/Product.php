<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_produk', 'id_kategori_produk', 'nama_produk', 'slug', 'description', 'image'];

    public function categories()
    {
        return $this->hasOne(ProductCategory::class, 'id_kategori_produk', 'id_kategori_produk');
    }
}
