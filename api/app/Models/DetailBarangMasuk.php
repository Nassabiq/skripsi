<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangMasuk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_material';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'detail_barang_masuk';

    protected $fillable = ['id_detail_barang_masuk', 'id_bahan_baku', 'id_barang_masuk', 'qty', 'harga'];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk');
    }
    public function bahanBaku()
    {
        return $this->hasOne(Material::class, 'id_material', 'id_bahan_baku');
    }
}
