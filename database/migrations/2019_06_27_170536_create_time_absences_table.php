<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_absences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registration_id')->unsigned();
            $table->foreign('registration_id')->references('id')->on('registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('type')->comment = '1. From day to day; 2. The specific day';
            $table->dateTime('time_details')->nullable();
            $table->string('at_time')->nullable()->comment = '1. Morning; 2. Afternoon; 3. Full';
            $table->unsignedDecimal('absence_days', 3, 1)->default(0);
            $table->unsignedDecimal('annual_leave_total', 3, 1)->nullable();
            $table->unsignedDecimal('annual_leave_unused', 3, 1)->nullable();
            $table->year('current_year')->nullable();
            $table->string('general_information')->nullable();
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
        Schema::dropIfExists('time_absences');
    }
}
