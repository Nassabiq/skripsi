<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_penjualan', function (Blueprint $table) {
            $table->string('id_transaksi')->unique();
            $table->primary('id_transaksi');

            $table->string('nama_pemesan');
            $table->longText('alamat');
            $table->string('no_telp');
            $table->dateTime('tgl_transaksi');
            $table->integer('status_pesanan');
            $table->integer('total_harga');
            $table->integer('total_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
}
