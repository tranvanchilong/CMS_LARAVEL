<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->nullable();
            $table->string('name');
            $table->string('mobile');
            $table->dateTime('booking_date');
            $table->string('status')->default('new');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('category_service_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');
            $table->foreign('location_id')
                ->references('id')->on('locations')
                ->onDelete('cascade');
            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onDelete('cascade');
            $table->foreign('category_service_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
