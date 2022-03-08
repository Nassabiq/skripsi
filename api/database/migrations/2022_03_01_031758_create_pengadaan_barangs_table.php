<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengadaanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengadaan_barang', function (Blueprint $table) {
            $table->string('id_pengadaan')->unique();
            $table->primary('id_pengadaan');

            $table->string('nama_pengadaan');
            $table->integer('status_pengadaan');
            $table->dateTime('tgl_dibuat');
            $table->dateTime('tgl_disetujui')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengadaan_barang');
    }
}
