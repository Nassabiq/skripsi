<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finishing', function (Blueprint $table) {
            $table->string('id_finishing')->unique();
            $table->primary('id_finishing');

            $table->string('id_produk')->references('id_produk')->on('produk');
            $table->string('nama_finishing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finishing');
    }
}
