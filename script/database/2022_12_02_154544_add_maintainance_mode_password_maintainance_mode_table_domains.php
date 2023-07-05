<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaintainanceModePasswordMaintainanceModeTableDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('is_maintainance_mode')->default(0)->comment('1 - active, 0 - deactive');
            $table->string('maintainance_mode_password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('is_maintainance_mode');
            $table->dropColumn('maintainance_mode_password');
        });
    }
}
