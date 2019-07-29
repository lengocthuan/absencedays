<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTracks03Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->unsignedDecimal('sick_leave', 3, 1)->default(5)->change();
            $table->unsignedDecimal('marriage_leave', 3, 1)->default(3)->change();
            $table->unsignedDecimal('maternity_leave', 3, 1)->default(5)->change();
            $table->unsignedDecimal('bereavement_leave', 3, 1)->default(3)->change();
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
