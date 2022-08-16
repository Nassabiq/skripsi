<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePencatatanProduksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pencatatan_produksi', function (Blueprint $table) {
            $table->string('id_pencatatan')->unique();
            $table->primary('id_pencatatan');

            $table->string('id_detail_transaksi')->references('id_detail_transaksi')->on('detail_transaksi');
            $table->dateTime('tgl_produksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pencatatan_produksi');
    }
}
