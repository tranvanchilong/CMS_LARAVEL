<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyCustomerPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_customer_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loyalty_promotion_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('redemtion_date')->comment('ngày customer dổi phiéu thưởng');
            $table->date('expiration_date')->nullable()->comment('Ngày hết hạn');
            $table->timestamps();

            $table->foreign('loyalty_promotion_id')
            ->references('id')->on('loyalty_promotions')
            ->onDelete('cascade');
            $table->foreign('customer_id')
            ->references('id')->on('customers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loyalty_customer_promotions');
    }
}
