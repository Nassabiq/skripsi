<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_masuk', function (Blueprint $table) {
            $table->string('id_stok_masuk')->unique();
            $table->primary('id_stok_masuk');

            $table->string('id_user')->references('id_user')->on('users');
            $table->dateTime('tgl_stok_masuk');
            $table->integer('total_harga_beli');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_masuk');
    }
}
