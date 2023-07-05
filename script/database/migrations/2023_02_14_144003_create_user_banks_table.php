<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_user_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);
        });

        Schema::create('lms_user_bank_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_bank_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('user_bank_id')->on('lms_user_banks')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_user_bank_specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_bank_id')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('user_bank_id')->on('lms_user_banks')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_user_bank_specification_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_bank_specification_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('user_bank_specification_id', 'user_bank_specification_id')->on('lms_user_bank_specifications')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_users', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `lms_users`
                    DROP COLUMN `account_type`,
                    DROP COLUMN `iban`,
                    DROP COLUMN `account_id`;");
        });

        Schema::create('lms_user_selected_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('user_bank_id')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('user_bank_id')->on('lms_user_banks')->references('id')->cascadeOnDelete();
            $table->foreign('user_id')->on('lms_users')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_user_selected_bank_specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_selected_bank_id')->unsigned();
            $table->integer('user_bank_specification_id')->unsigned();
            $table->text('value');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('user_selected_bank_id', 'user_selected_bank_id_specifications')->on('lms_user_selected_banks')->references('id')->cascadeOnDelete();
            $table->foreign('user_bank_specification_id', 'user_bank_specification_id_specifications')->on('lms_user_bank_specifications')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_user_banks');
        Schema::dropIfExists('lms_user_bank_translations');
        Schema::dropIfExists('lms_user_bank_specifications');
        Schema::dropIfExists('lms_user_bank_specification_translations');
        Schema::dropIfExists('lms_user_selected_banks');
        Schema::dropIfExists('lms_user_selected_bank_specifications');
    }
}
