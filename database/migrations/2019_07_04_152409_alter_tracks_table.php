<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->unsignedDecimal('January', 3, 1)->change()->nullable();
            $table->unsignedDecimal('February', 3, 1)->change()->nullable();
            $table->unsignedDecimal('March', 3, 1)->change()->nullable();
            $table->unsignedDecimal('April', 3, 1)->change()->nullable();
            $table->unsignedDecimal('May', 3, 1)->change()->nullable();
            $table->unsignedDecimal('June', 3, 1)->change()->nullable();
            $table->unsignedDecimal('July', 3, 1)->change()->nullable();
            $table->unsignedDecimal('August', 3, 1)->change()->nullable();
            $table->unsignedDecimal('September', 3, 1)->change()->nullable();
            $table->unsignedDecimal('October', 3, 1)->change()->nullable();
            $table->unsignedDecimal('November', 3, 1)->change()->nullable();
            $table->unsignedDecimal('December', 3, 1)->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            //
        });
    }
}
