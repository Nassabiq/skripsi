<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'detail_transaksi';

    protected $fillable = ['id_detail_transaksi', 'id_transaksi', 'id_sku', 'qty_produk', 'subtotal', 'ukuran', 'id_finishing'];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi');
    }
    public function sku()
    {
        return $this->belongsTo(SKU::class, 'id_sku', 'id_sku');
    }
    public function finishing()
    {
        return $this->belongsTo(Finishing::class, 'id_finishing', 'id_finishing');
    }
}
