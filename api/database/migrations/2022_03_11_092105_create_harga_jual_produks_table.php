<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaJualProduksTable extends Migration
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

            $table->string('id_produk')->references('id_produk')->on('products');

            $table->integer('harga');
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
