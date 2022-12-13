<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_bahan_baku';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'bahan_baku';

    protected $fillable = ['id_bahan_baku', 'nama_bahan_baku', 'slug_bahan_baku', 'satuan_bahan_baku', 'jml_stok'];

    public function stok()
    {
        return $this->hasMany(SKU::class, 'id_bahan_baku', 'id_bahan_baku');
    }
    public function stok_masuk()
    {
        return $this->hasMany(DetailStokMasuk::class, 'id_bahan_baku', 'id_bahan_baku');
    }
}
