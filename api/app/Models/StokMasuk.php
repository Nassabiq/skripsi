<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_stok_masuk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'stok_masuk';

    protected $fillable = ['id_stok_masuk', 'id_user', 'tgl_stok_masuk', 'total_harga_beli'];

    public function detailStok()
    {
        return $this->hasMany(DetailStokMasuk::class, 'id_stok_masuk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
