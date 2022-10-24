<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailStokMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_stok_masuk', function (Blueprint $table) {
            $table->string('id_detail_stok_masuk')->unique();
            $table->primary('id_detail_stok_masuk');

            $table->string('id_bahan_baku')->references('id_bahan_baku')->on('bahan_baku');
            $table->string('id_stok_masuk')->references('id_stok_masuk')->on('stok_masuk');

            $table->integer('qty_stok');
            $table->integer('harga_beli');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_stok_masuk');
    }
}
