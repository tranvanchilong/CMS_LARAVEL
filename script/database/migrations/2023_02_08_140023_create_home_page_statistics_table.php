<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_home_page_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon');
            $table->string('color');
            $table->integer('count')->unsigned();
            $table->integer('order')->nullable()->unsigned();
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);
        });

        Schema::create('lms_home_page_statistic_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_page_statistic_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('home_page_statistic_id', 'home_page_statistic_id')->on('lms_home_page_statistics')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_home_page_statistics');
    }
}
