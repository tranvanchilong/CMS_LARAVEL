<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnStatusContactList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_list', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('contact_list', function (Blueprint $table) {
            $table->tinyInteger('is_show_topbar')->after('serial_number')->default(0);
            $table->tinyInteger('is_show_float_content')->after('is_show_topbar')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_list', function (Blueprint $table) {
            $table->dropColumn('is_show_topbar');
            $table->dropColumn('is_show_float_content');
        });
    }
}
