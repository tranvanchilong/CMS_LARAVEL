<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->integer('target_id')->after('customer_id')->nullable();
            $table->string('blockchain_address_from')->after('status')->nullable();
            $table->string('blockchain_transaction')->after('status')->nullable();
            $table->text('blockchain_result')->after('status')->nullable();
            $table->decimal('blockchain_amount', 20, 2)->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('target_id');
            $table->dropColumn('blockchain_address_from');
            $table->dropColumn('blockchain_transaction');
            $table->dropColumn('blockchain_result');
            $table->dropColumn('blockchain_amount');
        });
    }
}
