<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'pelanggan';

    protected $fillable = ['id_pelanggan', 'id_user', 'nama_pelanggan', 'alamat', 'no_telp'];

    // Tambahkan Relasi ke User $this->belongsTo
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
