<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('featured')->defaut(0);
            $table->string('website_link')->nullable();
            $table->bigInteger('category_id');
            $table->bigInteger('user_id');
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('submission_date')->nullable();
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
        Schema::dropIfExists('portfolios');
    }
}
