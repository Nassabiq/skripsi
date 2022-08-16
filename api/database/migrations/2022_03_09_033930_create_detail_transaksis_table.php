<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransaksisTable extends Migration
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
            $table->string('id_produk')->references('id_produk')->on('products');

            $table->string('jenis_bahan');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->string('finishing')->nullable();
            $table->string('laminasi')->nullable();
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
