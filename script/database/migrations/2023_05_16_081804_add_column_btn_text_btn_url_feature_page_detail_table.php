<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBtnTextBtnUrlFeaturePageDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_page_detail', function (Blueprint $table) {
            $table->string('btn_text')->nullable();
            $table->string('btn_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_page_detail', function($table) {
            $table->dropColumn('btn_text');
            $table->dropColumn('btn_url');
        });
    }
}
