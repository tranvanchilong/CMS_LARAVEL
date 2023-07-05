<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHideTitleFeaturePageDetailTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_page_detail', function (Blueprint $table) {
            $table->tinyInteger('hide_title')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_page_detail', function (Blueprint $table) {
            $table->dropColumn('hide_title');
        });
    }
}
