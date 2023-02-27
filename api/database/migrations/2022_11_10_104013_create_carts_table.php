<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->string('id_cart')->unique();
            $table->primary('id_cart');

            $table->string('id_user')->references('id_user')->on('users');
            $table->string('id_sku')->references('id_sku')->on('sku');
            $table->string('id_finishing')->references('id_finishing')->on('finishing')->nullable();

            $table->integer('qty_cart');
            $table->json('ukuran')->nullable();
            $table->json('file_upload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
