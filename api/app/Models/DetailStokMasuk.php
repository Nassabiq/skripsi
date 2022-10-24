<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailStokMasuk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_stok_masuk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'detail_stok_masuk';

    protected $fillable = ['id_detail_stok_masuk', 'id_bahan_baku', 'id_stok_masuk', 'qty_stok', 'harga_beli'];

    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class, 'id_stok_masuk');
    }
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
