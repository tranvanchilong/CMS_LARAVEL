<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashbackRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_cashback_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('target_type', \App\Models\LMS\CashbackRule::$targetTypes);
            $table->string('target')->nullable();
            $table->bigInteger('start_date')->unsigned()->nullable();
            $table->bigInteger('end_date')->unsigned()->nullable();
            $table->float('amount', 15, 2)->nullable();
            $table->enum('amount_type', ['fixed_amount', 'percent'])->nullable();
            $table->boolean('apply_cashback_per_item')->default(false);
            $table->float('max_amount', 15, 2)->nullable();
            $table->float('min_amount', 15, 2)->nullable();
            $table->boolean('enable')->default(false);
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);
        });

        Schema::create('lms_cashback_rule_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashback_rule_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('cashback_rule_id')->on('lms_cashback_rules')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_cashback_rule_specification_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashback_rule_id')->unsigned();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('instructor_id')->unsigned()->nullable();
            $table->integer('seller_id')->unsigned()->nullable();
            $table->integer('webinar_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('bundle_id')->unsigned()->nullable();
            $table->integer('subscribe_id')->unsigned()->nullable();
            $table->integer('registration_package_id')->unsigned()->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('cashback_rule_id')->on('lms_cashback_rules')->references('id')->cascadeOnDelete();
            $table->foreign('category_id')->on('lms_categories')->references('id')->cascadeOnDelete();
            $table->foreign('instructor_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('seller_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('webinar_id')->on('lms_webinars')->references('id')->cascadeOnDelete();
            $table->foreign('product_id')->on('lms_products')->references('id')->cascadeOnDelete();
            $table->foreign('bundle_id')->on('lms_bundles')->references('id')->cascadeOnDelete();
            $table->foreign('subscribe_id')->on('lms_subscribes')->references('id')->cascadeOnDelete();
            $table->foreign('registration_package_id', 'rules_registration_package_id')->on('lms_registration_packages')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_cashback_rule_users_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashback_rule_id')->unsigned();
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('cashback_rule_id')->on('lms_cashback_rules')->references('id')->cascadeOnDelete();
            $table->foreign('group_id')->on('lms_groups')->references('id')->cascadeOnDelete();
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
        Schema::dropIfExists('lms_cashback_rules');
        Schema::dropIfExists('lms_cashback_rule_translations');
        Schema::dropIfExists('lms_cashback_rule_specification_items');
        Schema::dropIfExists('lms_cashback_rule_users_groups');
    }
}
