<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengadaanPersediaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengadaan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'pengadaan_persediaan';

    protected $fillable = ['id_pengadaan', 'nama_pengadaan', 'status_pengadaan', 'tgl_dibuat', 'tgl_disetujui'];

    public function detailPengadaan()
    {
        return $this->hasMany(DetailPengadaanBarang::class, 'id_pengadaan');
    }
}
