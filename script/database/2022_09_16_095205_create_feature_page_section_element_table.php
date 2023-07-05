<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturePageSectionElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_page_section_element', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('feature_page_detail_id');
            $table->string('title');
            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('btn_url')->nullable();
            $table->integer('serial_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_page_section_element');
    }
}
