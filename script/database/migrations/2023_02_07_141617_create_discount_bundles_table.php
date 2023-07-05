<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_discount_bundles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('discount_id')->unsigned();
            $table->integer('bundle_id')->unsigned();
            $table->integer('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('discount_id')->references('id')->on('lms_discounts')->onDelete('cascade');
            $table->foreign('bundle_id')->references('id')->on('lms_bundles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_discount_bundles');
    }
}
