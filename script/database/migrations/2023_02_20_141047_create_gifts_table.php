<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_gifts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('webinar_id')->unsigned()->nullable();
            $table->integer('bundle_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('email');
            $table->bigInteger('date')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->boolean('viewed')->default(false)->comment('for show modal in recipient user panel');
            $table->enum('status', ['active', 'pending', 'cancel'])->default('pending');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('user_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('webinar_id')->on('lms_webinars')->references('id')->cascadeOnDelete();
            $table->foreign('bundle_id')->on('lms_bundles')->references('id')->cascadeOnDelete();
            $table->foreign('product_id')->on('lms_products')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_sales', function (Blueprint $table) {
            $table->integer('gift_id')->unsigned()->nullable()->after('installment_payment_id');
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `lms_sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment', 'gift') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");
        });

        Schema::table('lms_cart', function (Blueprint $table) {
            $table->integer('gift_id')->unsigned()->nullable()->after('promotion_id');

            $table->foreign('gift_id')->on('lms_gifts')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_order_items', function (Blueprint $table) {
            $table->integer('gift_id')->unsigned()->nullable()->after('promotion_id');

            $table->foreign('gift_id')->on('lms_gifts')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_accounting', function (Blueprint $table) {
            $table->integer('gift_id')->unsigned()->nullable()->after('installment_payment_id');
        });

        Schema::table('lms_product_orders', function (Blueprint $table) {
            $table->integer('gift_id')->unsigned()->nullable()->after('installment_order_id');
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `lms_product_orders` MODIFY COLUMN `buyer_id` int UNSIGNED NULL AFTER `seller_id`");

            $table->foreign('gift_id')->on('lms_gifts')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_gifts');
    }
}
