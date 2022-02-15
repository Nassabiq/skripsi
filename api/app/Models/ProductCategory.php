<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProductCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kategori_produk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_kategori_produk', 'id_satuan', 'nama_kategori'];

    public function satuan()
    {
        return $this->hasOne(Unit::class, 'id_satuan', 'id_satuan');
    }
}
