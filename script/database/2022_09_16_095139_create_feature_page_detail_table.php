<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturePageDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_page_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('feature_page_id');
            $table->string('feature_title')->nullable();
            $table->string('feature_subtitle')->nullable();
            $table->string('feature_type');
            $table->tinyInteger('feature_position')->default(1);
            $table->tinyInteger('feature_status')->default(1);
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
        Schema::dropIfExists('feature_page_detail');
    }
}
