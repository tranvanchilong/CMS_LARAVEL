<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeroImageSizeTableFeaturePageDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_page_detail', function (Blueprint $table) {
            $table->string('hero_image_size')->nullable();
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
            $table->dropColumn('hero_image_size');
        });
    }
}
