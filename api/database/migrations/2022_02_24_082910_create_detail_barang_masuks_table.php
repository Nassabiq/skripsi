<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailBarangMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_barang_masuk', function (Blueprint $table) {
            $table->string('id_detail_barang_masuk')->unique();
            $table->primary('id_detail_barang_masuk');

            $table->string('id_bahan_baku')->references('id_bahan_baku')->on('materials');
            $table->string('id_barang_masuk')->references('id_barang_masuk')->on('barang_masuk');

            $table->integer('qty');
            $table->integer('harga');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_barang_masuk');
    }
}
