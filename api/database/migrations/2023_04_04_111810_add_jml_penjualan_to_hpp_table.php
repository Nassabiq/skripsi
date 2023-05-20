<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJmlPenjualanToHppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hpp', function (Blueprint $table) {
            $table->integer('jml_penjualan')->after('nilai_hpp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hpp', function (Blueprint $table) {
            $table->dropColumn('jml_penjualan');
        });
    }
}
