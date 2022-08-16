<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'transaksi_penjualan';

    protected $fillable = ['id_transaksi', 'nama_pemesan', 'alamat', 'no_telp', 'tgl_transaksi', 'status_pesanan', 'total_harga', 'total_pembayaran'];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
