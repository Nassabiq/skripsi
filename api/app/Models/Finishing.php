<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_finishing';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'finishing';
    protected $fillable = ['id_finishing', 'nama_finishing', 'id_produk'];


    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
