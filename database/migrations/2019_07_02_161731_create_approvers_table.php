<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->timestamps();
        });
        Schema::create('approver_registration', function (Blueprint $table) {
            $table->integer('approver_id')->unsigned();
            $table->integer('registration_id')->unsigned();
            $table->timestamps();
            $table->foreign('approver_id')->references('id')->on('approvers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('registration_id')->references('id')->on('registrations')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('approver_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approved_role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvers');
        Schema::dropIfExists('approver_registration');
    }
}
