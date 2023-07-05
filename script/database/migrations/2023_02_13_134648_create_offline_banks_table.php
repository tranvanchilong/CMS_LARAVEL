<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_offline_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);
        });

        Schema::create('lms_offline_bank_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offline_bank_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('offline_bank_id')->on('lms_offline_banks')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_offline_bank_specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offline_bank_id')->unsigned();
            $table->string('value');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('offline_bank_id')->on('lms_offline_banks')->references('id')->cascadeOnDelete();
        });

        Schema::create('lms_offline_bank_specification_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offline_bank_specification_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('offline_bank_specification_id', 'offline_bank_specification_id')->on('lms_offline_bank_specifications')->references('id')->cascadeOnDelete();
        });

        Schema::table('lms_offline_payments',function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `lms_offline_payments` DROP COLUMN `bank`");

            $table->integer('offline_bank_id')->unsigned()->nullable()->after('amount');

            $table->foreign('offline_bank_id')->on('lms_offline_banks')->references('id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_offline_banks_credits');
        Schema::dropIfExists('lms_offline_bank_translations');
        Schema::dropIfExists('lms_offline_bank_specifications');
        Schema::dropIfExists('lms_offline_bank_specification_translations');
    }
}
