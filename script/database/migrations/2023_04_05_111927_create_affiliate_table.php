<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //affiliate_configs
        Schema::create('affiliate_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('type')->nullable();
            $table->text('value')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
        //affiliate_logs
        Schema::create('affiliate_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('guest_id')->nullable();
            $table->integer('referred_by_user');
            $table->double('amount',20,2);
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('order_detail_id')->nullable();
            $table->string('affiliate_type');
            $table->tinyInteger('status')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
        //affiliate_options
        Schema::create('affiliate_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('type')->nullable();
            $table->longText('details')->nullable();
            $table->double('percentage')->default(0);
            $table->integer('status')->default('1');

            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
        //affiliate_payments
        Schema::create('affiliate_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('affiliate_user_id');
            $table->unsignedBigInteger('user_id');
            $table->double('amount',8,2);
            $table->string('payment_method');
            $table->longText('payment_details')->nullable();
            $table->tinyInteger('status')->default('0');

            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
        //affiliate_stats
        Schema::create('affiliate_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('affiliate_user_id');
            $table->integer('no_of_clicks')->default('0');
            $table->integer('no_of_order_item')->default('0');
            $table->integer('no_of_delivered')->default('0');
            $table->integer('no_of_cancel')->default('0');

            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
        //affiliate_users
        Schema::create('affiliate_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('paypal_email')->nullable();
            $table->text('bank_information')->nullable();
            $table->text('informations')->nullable();
            $table->double('balance',10,2)->default('0.00');
            $table->integer('status')->default('0');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
        //affiliate_withdraw_requests
        Schema::create('affiliate_withdraw_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->double('amount',10,2);
            $table->integer('status');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('affiliate_configs');
        Schema::dropIfExists('affiliate_logs');
        Schema::dropIfExists('affiliate_options');
        Schema::dropIfExists('affiliate_payments');
        Schema::dropIfExists('affiliate_stats');
        Schema::dropIfExists('affiliate_users');
        Schema::dropIfExists('affiliate_withdraw_requests');
    }
}
