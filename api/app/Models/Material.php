<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_material';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_material', 'id_satuan', 'nama_bahan_baku', 'slug', 'stok'];

    public function satuan()
    {
        return $this->hasOne(Unit::class, 'id_satuan', 'id_satuan');
    }

    public function produk()
    {
        return $this->belongsToMany(Product::class, 'products_has_materials', 'id_material', 'id_produk');
    }

    public function produksi()
    {
        return $this->belongsToMany(PencatatanProduksi::class, 'produksi_has_materials', 'id_material', 'id_pencatatan')->withPivot('stok_digunakan');
    }
    public function detailBarang()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_bahan_baku', 'id_material');
    }
}
