<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('price')->nullable();
            $table->text('package_feature')->nullable();
            $table->text('not_package_feature')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('featured')->defaut(0);
            $table->string('btn_text')->nullable();
            $table->string('btn_url')->nullable();
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('packages');
    }
}
