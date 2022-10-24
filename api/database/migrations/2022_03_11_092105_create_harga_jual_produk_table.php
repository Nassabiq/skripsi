<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaJualProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harga_jual_produk', function (Blueprint $table) {
            $table->string('id_harga_jual')->unique();
            $table->primary('id_harga_jual');

            $table->string('id_sku')->references('id_sku')->on('sku');

            $table->integer('harga_produk');
            $table->dateTime('tgl_diubah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('harga_jual_produk');
    }
}
