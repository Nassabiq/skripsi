<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HPP extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_hpp';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_hpp', 'id_sku', 'biaya_overhead', 'nilai_hpp', 'tgl_analisa', 'jml_penjualan'];
    protected $table = 'hpp';
}
