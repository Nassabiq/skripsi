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
}
