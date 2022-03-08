<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengadaanBarang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_pengadaan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'detail_pengadaan_barang';

    protected $fillable = ['id_detail_pengadaan', 'id_bahan_baku', 'id_pengadaan', 'jumlah_barang'];

    public function pengadaan()
    {
        return $this->belongsTo(PengadaanBarang::class, 'id_pengadaan');
    }
    public function bahanBaku()
    {
        return $this->hasOne(Material::class, 'id_material', 'id_bahan_baku');
    }
}
