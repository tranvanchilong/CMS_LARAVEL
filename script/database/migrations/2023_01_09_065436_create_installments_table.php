<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_installments', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('target_type', \App\Models\LMS\Installment::$targetTypes);
            $table->string('target')->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->bigInteger('start_date')->unsigned()->nullable();
            $table->bigInteger('end_date')->unsigned()->nullable();
            $table->boolean('verification')->default(false);
            $table->boolean('request_uploads')->default(false);
            $table->boolean('bypass_verification_for_verified_users')->default(false);
            $table->float('upfront', 15, 2)->nullable();
            $table->enum('upfront_type', ['fixed_amount', 'percent'])->nullable();
            $table->boolean('enable')->default(false);
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);
        });

        Schema::create('lms_installment_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->string('main_title');
            $table->text('description');
            $table->string('banner')->nullable();
            $table->text('options')->nullable();
            $table->text('verification_description')->nullable();
            $table->string('verification_banner')->nullable();
            $table->string('verification_video')->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_id')->on('lms_installments')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_installment_specification_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_id')->unsigned();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('instructor_id')->unsigned()->nullable();
            $table->integer('seller_id')->unsigned()->nullable();
            $table->integer('webinar_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('bundle_id')->unsigned()->nullable();
            $table->integer('subscribe_id')->unsigned()->nullable();
            $table->integer('registration_package_id')->unsigned()->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_id')->on('lms_installments')->references('id')->cascadeOnDelete();
            $table->foreign('category_id')->on('lms_categories')->references('id')->cascadeOnDelete();
            $table->foreign('instructor_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('seller_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('webinar_id')->on('lms_webinars')->references('id')->cascadeOnDelete();
            $table->foreign('product_id')->on('lms_products')->references('id')->cascadeOnDelete();
            $table->foreign('bundle_id')->on('lms_bundles')->references('id')->cascadeOnDelete();
            $table->foreign('subscribe_id')->on('lms_subscribes')->references('id')->cascadeOnDelete();
            $table->foreign('registration_package_id','installment_specification_items_registration_package_id_foreign')->on('lms_registration_packages')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_installment_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_id')->unsigned();
            $table->integer('deadline')->unsigned()->nullable();
            $table->float('amount', 15, 2)->nullable();
            $table->enum('amount_type', ['fixed_amount', 'percent'])->nullable();
            $table->integer('order')->unsigned()->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_id')->on('lms_installments')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_installment_step_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_step_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_step_id')->on('lms_installment_steps')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_installment_user_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installment_id')->unsigned();
            $table->integer('group_id')->unsigned()->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('installment_id')->on('lms_installments')->references('id')->cascadeOnDelete();
            $table->foreign('group_id')->on('lms_groups')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_installments');
        Schema::dropIfExists('lms_installment_translations');
        Schema::dropIfExists('lms_installment_specification_items');
        Schema::dropIfExists('lms_installment_steps');
        Schema::dropIfExists('lms_installment_step_translations');
        Schema::dropIfExists('lms_installment_user_groups');
    }
}
