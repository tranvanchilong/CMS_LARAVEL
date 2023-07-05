<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDataTypeCategoryFeaturePageSectionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_page_detail', function (Blueprint $table) {
            $table->string('data_type')->nullable();
            $table->tinyInteger('category')->default(1);
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
            $table->string('data_type');
            $table->tinyInteger('category');
        });
    }
}
