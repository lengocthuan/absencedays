<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTracks01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->unsignedDecimal('sick_leave', 3, 1)->default(0)->nullable();
            $table->unsignedDecimal('marriage_leave', 3, 1)->default(0)->nullable();
            $table->unsignedDecimal('maternity_leave', 3, 1)->default(0)->nullable();
            $table->unsignedDecimal('bereavement_leave', 3, 1)->default(0)->nullable();
            $table->unsignedDecimal('unpaid_leave', 3, 1)->default(0)->nullable();
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
