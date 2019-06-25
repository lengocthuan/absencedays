<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimeAbsences03Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_absences', function (Blueprint $table) {
            $table->unsignedDecimal('annual_leave_total', 3, 1)->nullable();
            $table->unsignedDecimal('annual_leave_unused', 3, 1)->nullable();
            $table->year('current_year')->nullable();
            $table->string('general_information')->nullable();
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
