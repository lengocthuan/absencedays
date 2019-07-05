<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approvers', function (Blueprint $table) {
            $table->integer('type')->default(0)->comment = '0 => Approvers (to in Mail); 1 => Viewer(cc in Mail)';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approvers', function (Blueprint $table) {
        });
    }
}
