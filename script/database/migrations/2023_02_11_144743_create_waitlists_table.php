<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaitlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_waitlists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('webinar_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('webinar_id')->on('lms_webinars')->references('id')->cascadeOnDelete();
            $table->foreign('user_id')->on('lms_users')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_waitlists');
    }
}
