<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->string('id_detail_transaksi')->unique();
            $table->primary('id_detail_transaksi');

            $table->string('id_transaksi')->references('id_transaksi')->on('transaksi_penjualan');
            $table->string('id_sku')->references('id_sku')->on('sku');

            $table->integer('qty_produk');
            $table->json('ukuran')->nullable();
            $table->string('finishing')->nullable();
            $table->longText('catatan')->nullable();
            $table->integer('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_transaksi');
    }
}
