<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->string('type');
            $table->string('image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('status')->defaut(1);
            $table->tinyInteger('featured')->defaut(0);
            $table->tinyInteger('is_admin')->defaut(0);
            $table->bigInteger('user_id');
            $table->bigInteger('category_id')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('lang_id')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
