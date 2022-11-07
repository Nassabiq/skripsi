<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaJualProduk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_harga_jual';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'harga_jual_produk';

    protected $fillable = ['id_harga_jual', 'id_sku', 'harga_produk', 'tgl_diubah'];
}
