<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_installment_reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('installment_order_id')->unsigned();
            $table->integer('installment_step_id')->unsigned();
            $table->enum('type', ['before_due', 'due', 'after_due']);
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

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
        Schema::dropIfExists('lms_installment_reminders');
    }
}
