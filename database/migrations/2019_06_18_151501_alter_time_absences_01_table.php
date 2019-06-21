<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimeAbsences01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_absences', function (Blueprint $table) {
            $table->dateTime('time_details')->nullable()->change();
            $table->string('at_time')->nullable()->change()->comment = '1. Morning; 2. Afternoon; 3. Full';
            $table->dateTime('time_start')->nullable()->change();
            $table->dateTime('time_end')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_absences', function (Blueprint $table) {
            //
        });
    }
}
