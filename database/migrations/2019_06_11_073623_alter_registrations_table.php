<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('at_time')->comment = 'Morning; Afternoon; Full';
            $table->integer('status')->default(0)->nullable()->change()->comment = '0 - empty; 1- approved; 2 - disapproved; 3 - pending';
            $table->unsignedDecimal('annual_leave_total', 3, 1)->nullable()->change();
            $table->unsignedDecimal('annual_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('annual_leave_unused', 3, 1)->nullable()->change();
            $table->unsignedDecimal('sick_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('marriage_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('maternity_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('bereavement_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('long_term_unpaid_leave', 3, 1)->nullable()->change();
            $table->unsignedDecimal('short_term_unpaid_leave', 3, 1)->nullable()->change();
            $table->dropColumn('sick_leave');
            $table->dropColumn('marriage_leave');
            $table->dropColumn('maternity_leave');
            $table->dropColumn('bereavement_leave');
            $table->dropColumn('long_term_unpaid_leave');
            $table->dropColumn('short_term_unpaid_leave');
            $table->renameColumn('annual_leave', 'absence_days');

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
