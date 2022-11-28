<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('id_produk')->unique();
            $table->primary('id_produk');

            $table->string('id_kategori_produk')->references('id_kategori_produk')->on('kategori_produk');

            $table->string('nama_produk');
            $table->string('slug_produk');
            $table->longText('image_produk');
            $table->longText('deskripsi_produk');
            $table->longText('informasi_pemesanan');
            $table->string('satuan_produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
