<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableLoyaltyPromotion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyalty_promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('term_id')->nullable()->after('source');
            $table->string('type')->comment('loại giảm giá')->after('term_id');
            $table->decimal('reduction_rate', 22, 1)->default(0)->comment('số tiền được giảm')->after('type');

            $table->foreign('term_id')
                ->references('id')->on('terms')
                ->onDelete('cascade');
        });
        Schema::dropIfExists('loyalty_promotions_products');

        Schema::table('discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_id')->nullable()->after('discount_amount');
            $table->unsignedBigInteger('term_id')->nullable()->after('shipping_id');
            $table->tinyInteger('order_amount')->nullable()->after('term_id');
            $table->decimal('order_price', 22, 1)->nullable()->after('order_amount');

            $table->foreign('shipping_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');

            $table->foreign('term_id')
                ->references('id')->on('terms')
                ->onDelete('cascade');
        });
        Schema::dropIfExists('discount_promotions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loyalty_promotions', function (Blueprint $table) {
            //
        });
    }
}
