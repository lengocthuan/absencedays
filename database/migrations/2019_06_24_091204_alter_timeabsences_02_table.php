<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimeabsences02Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_absences', function (Blueprint $table) {
            $table->unsignedDecimal('absence_days', 3, 1)->default(0);
            $table->dropColumn('time_start');
            $table->dropColumn('time_end');
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
