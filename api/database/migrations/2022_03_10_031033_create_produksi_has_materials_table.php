<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksiHasMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produksi_has_materials', function (Blueprint $table) {
            $table->id();
            $table->string('id_pencatatan')->references('id_pencatatan')->on('pencatatan_produksi');
            $table->string('id_material')->references('id_material')->on('materials');
            $table->integer('stok_digunakan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produksi_has_materials');
    }
}
