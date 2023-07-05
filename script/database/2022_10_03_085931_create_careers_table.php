<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->text('summary')->nullable();
            $table->string('salary')->nullable();
            $table->tinyInteger('featured')->defaut(0);
            $table->bigInteger('user_id');
            $table->bigInteger('category_id');
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('serial_number');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('careers');
    }
}
