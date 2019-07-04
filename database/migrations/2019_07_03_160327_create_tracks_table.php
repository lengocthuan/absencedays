<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->year('year');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedDecimal('annual_leave_total', 3, 1)->nullable();
            $table->decimal('annual_leave_unused', 3, 1)->nullable();
            $table->integer('January')->nullable();
            $table->integer('February')->nullable();
            $table->integer('March')->nullable();
            $table->integer('April')->nullable();
            $table->integer('May')->nullable();
            $table->integer('June')->nullable();
            $table->integer('July')->nullable();
            $table->integer('August')->nullable();
            $table->integer('September')->nullable();
            $table->integer('October')->nullable();
            $table->integer('November')->nullable();
            $table->integer('December')->nullable();
            $table->timestamps();
        });

        Schema::table('time_absences', function (Blueprint $table) {
            $table->dropColumn('annual_leave_total');
            $table->dropColumn('annual_leave_unused');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('time_absences');
    }
}
