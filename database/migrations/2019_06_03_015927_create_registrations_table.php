<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('types');
            $table->text('note')->nullable();
            $table->integer('status')->default(0)->comment = '0 - empty; 1- approved; 2 - disapproved; 3 - pending';
            $table->dateTime('requested_date')->nullable();
            $table->dateTime('aprroved_date')->nullable();
            $table->dateTime('time_off_beginning');
            $table->dateTime('time_off_ending');
            $table->year('current_year')->nullable();
            $table->unsignedDecimal('annual_leave_total', 2, 1)->nullable();
            $table->unsignedDecimal('annual_leave', 2, 1)->nullable();
            $table->unsignedDecimal('annual_leave_unused', 2, 1)->nullable();
            $table->unsignedDecimal('sick_leave', 2, 1)->nullable();
            $table->unsignedDecimal('marriage_leave', 2, 1)->nullable();
            $table->unsignedDecimal('maternity_leave', 2, 1)->nullable();
            $table->unsignedDecimal('bereavement_leave', 2, 1)->nullable();
            $table->unsignedDecimal('long_term_unpaid_leave', 2, 1)->nullable();
            $table->unsignedDecimal('short_term_unpaid_leave', 2, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrations');
    }
}
