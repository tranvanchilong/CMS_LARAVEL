<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('transaction_type')->nullable();
            $table->float('amount', 20,2);
            $table->decimal('balance',24,3)->default(0);
            $table->integer('status')->defaut(0);
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');

            $table->foreign('customer_id')
            ->references('id')->on('customers')
            ->onDelete('cascade');
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
        Schema::dropIfExists('wallet_transactions');
    }
}
