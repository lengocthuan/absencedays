<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->unsignedDecimal('annual_leave_total', 3, 1)->nullable()->change();
            $table->unsignedDecimal('annual_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('annual_leave_unused', 3, 1)->nullable()->change();
            $table->unsignedDecimal('sick_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('marriage_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('maternity_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('bereavement_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('long_term_unpaid_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('short_term_unpaid_leave', 3, 1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            //
        });
    }
}
