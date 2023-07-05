<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency');
            $table->enum('currency_position', \App\Models\LMS\Currency::$currencyPositions);
            $table->enum('currency_separator', ["dot", "comma"]);
            $table->integer('currency_decimal')->unsigned()->nullable();
            $table->float('exchange_rate')->nullable();
            $table->integer('order')->unsigned()->nullable();
            $table->bigInteger('created_at');
            $table->bigInteger('domain_id')->default(241);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_currencies');
    }
}
