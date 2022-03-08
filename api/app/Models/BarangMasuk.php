<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_barang_masuk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'barang_masuk';

    protected $fillable = ['id_barang_masuk', 'tgl_barang_masuk', 'total_harga_beli'];

    public function detailBarang()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_barang_masuk');
    }
}
