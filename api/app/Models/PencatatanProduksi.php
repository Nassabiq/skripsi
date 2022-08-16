<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencatatanProduksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pencatatan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'pencatatan_produksi';

    protected $fillable = ['id_pencatatan', 'id_detail_transaksi', 'tgl_produksi'];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'produksi_has_materials', 'id_pencatatan', 'id_material')->withPivot('stok_digunakan');
    }

    public function transaksi()
    {
        return $this->belongsTo(DetailTransaksi::class, 'id_detail_transaksi');
    }
}
