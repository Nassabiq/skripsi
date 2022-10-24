<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('id_transaksi')->unique();
            $table->primary('id_transaksi');

            $table->string('id_pelanggan')->references('id_pelanggan')->on('pelanggan');

            $table->dateTime('tgl_transaksi');
            $table->integer('status_pesanan');
            $table->integer('status_pembayaran');
            $table->integer('total_harga');
            $table->longText('no_resi');
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
