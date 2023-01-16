<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hpp', function (Blueprint $table) {
            $table->string('id_hpp')->unique();
            $table->primary('id_hpp');

            $table->string('id_sku')->references('id_sku')->on('sku');

            $table->json('biaya_overhead');
            $table->dateTime('tgl_analisa');
            $table->integer('nilai_hpp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hpp');
    }
}
