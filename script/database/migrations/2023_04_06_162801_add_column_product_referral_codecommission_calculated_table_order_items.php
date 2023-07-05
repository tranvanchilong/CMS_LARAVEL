<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnProductReferralCodeCommissionCalculatedTableOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orderitems', function (Blueprint $table) {
            $table->string('product_referral_code')->nullable();
            $table->integer('commission_calculated')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orderitems', function (Blueprint $table) {
            $table->dropColumn('product_referral_code');
            $table->dropColumn('commission_calculated');
        });
    }
}
