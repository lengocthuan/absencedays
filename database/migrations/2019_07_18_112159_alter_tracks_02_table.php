<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTracks02Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->unsignedDecimal('January', 3, 1)->default(0)->change();
            $table->unsignedDecimal('February', 3, 1)->default(0)->change();
            $table->unsignedDecimal('March', 3, 1)->default(0)->change();
            $table->unsignedDecimal('April', 3, 1)->default(0)->change();
            $table->unsignedDecimal('May', 3, 1)->default(0)->change();
            $table->unsignedDecimal('June', 3, 1)->default(0)->change();
            $table->unsignedDecimal('July', 3, 1)->default(0)->change();
            $table->unsignedDecimal('August', 3, 1)->default(0)->change();
            $table->unsignedDecimal('September', 3, 1)->default(0)->change();
            $table->unsignedDecimal('October', 3, 1)->default(0)->change();
            $table->unsignedDecimal('November', 3, 1)->default(0)->change();
            $table->unsignedDecimal('December', 3, 1)->default(0)->change();
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
