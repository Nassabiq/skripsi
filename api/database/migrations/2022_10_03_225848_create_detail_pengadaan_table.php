<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPengadaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pengadaan', function (Blueprint $table) {
            $table->string('id_detail_pengadaan')->unique();
            $table->primary('id_detail_pengadaan');

            $table->string('id_bahan_baku')->references('id_bahan_baku')->on('bahan_baku');
            $table->string('id_pengadaan')->references('id_pengadaan')->on('pengadaan_persediaan');

            $table->integer('jumlah_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pengadaan');
    }
}
