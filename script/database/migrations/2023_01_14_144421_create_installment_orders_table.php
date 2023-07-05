<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_installment_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('webinar_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('bundle_id')->unsigned()->nullable();
            $table->integer('subscribe_id')->unsigned()->nullable();
            $table->integer('registration_package_id')->unsigned()->nullable();
            $table->integer('product_order_id')->unsigned()->nullable();
            $table->enum('status', ['paying', 'open', 'rejected', 'pending_verification', 'canceled', 'refunded'])->default('paying');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('refund_at')->unsigned()->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_id')->on('lms_installments')->references('id')->cascadeOnDelete();
            $table->foreign('user_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('webinar_id')->on('lms_webinars')->references('id')->cascadeOnDelete();
            $table->foreign('product_id')->on('lms_products')->references('id')->cascadeOnDelete();
            $table->foreign('bundle_id')->on('lms_bundles')->references('id')->cascadeOnDelete();
            $table->foreign('subscribe_id')->on('lms_subscribes')->references('id')->cascadeOnDelete();
            $table->foreign('registration_package_id')->on('lms_registration_packages')->references('id')->cascadeOnDelete();
            $table->foreign('product_order_id')->on('lms_product_orders')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_installment_order_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_order_id')->unsigned();
            $table->integer('sale_id')->unsigned()->nullable();
            $table->enum('type', ['upfront', 'step']);
            $table->integer('step_id')->unsigned()->nullable();
            $table->float('amount', 15, 2);
            $table->enum('status', ['paying', 'paid', 'canceled', 'refunded'])->default('paying');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_order_id', 'installment_order_id')->on('lms_installment_orders')->references('id')->cascadeOnDelete();
            $table->foreign('step_id')->on('lms_installment_steps')->references('id')->cascadeOnDelete();
            $table->foreign('sale_id')->on('lms_sales')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_installment_order_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_order_id')->unsigned();
            $table->string('title');
            $table->string('file');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_order_id', 'installment_order_id_attachment')->on('lms_installment_orders')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_cart', function (Blueprint $table) {
            $table->integer('installment_payment_id')->unsigned()->nullable()->after('product_discount_id');

            $table->foreign('installment_payment_id')->on('lms_installment_order_payments')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_product_orders', function (Blueprint $table) {
            $table->integer('installment_order_id')->unsigned()->nullable()->after('sale_id');

            $table->foreign('installment_order_id')->on('lms_installment_orders')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_accounting', function (Blueprint $table) {
            $table->integer('installment_payment_id')->unsigned()->nullable()->after('product_id');
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `lms_accounting` MODIFY COLUMN `type_account` enum('income', 'asset', 'subscribe', 'promotion', 'registration_package', 'installment_payment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `type`");

            $table->foreign('installment_payment_id')->on('lms_installment_order_payments')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_sales', function (Blueprint $table) {
            $table->integer('installment_payment_id')->unsigned()->nullable()->after('registration_package_id');
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `lms_sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");

            $table->foreign('installment_payment_id')->on('lms_installment_order_payments')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_order_items', function (Blueprint $table) {
            $table->integer('installment_payment_id')->unsigned()->nullable()->after('product_order_id');
        });

        Schema::table('lms_subscribe_uses', function (Blueprint $table) {
            $table->integer('installment_order_id')->unsigned()->nullable();

            $table->foreign('installment_order_id')->on('lms_installment_orders')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_installment_orders');
        Schema::dropIfExists('lms_installment_order_payments');
        Schema::dropIfExists('lms_installment_order_attachments');
    }
}
