<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_cart';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_cart', 'id_user', 'id_sku', 'id_finishing', 'qty_cart', 'ukuran'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function sku()
    {
        return $this->belongsTo(SKU::class, 'id_sku', 'id_sku');
    }
    public function finishing()
    {
        return $this->belongsTo(Finishing::class, 'id_sku', 'id_sku');
    }
}
