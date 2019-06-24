<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
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
        //
    }
}
