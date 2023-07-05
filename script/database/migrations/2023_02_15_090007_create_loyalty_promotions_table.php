<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Bảng này lưu thông tin khuyến mãi
        Schema::create('loyalty_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->string('code');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('description')->nullable();
            $table->string('content')->nullable();
            $table->date('start_at');
            $table->date('end_at');
            $table->tinyInteger('expiry')->comment('Thời gian sử dụng của khuyến mãi')->default(0);
            $table->string('point')->default(0)->comment('Số điểm để đổi khuyến mãi');
            $table->string('image');
            $table->string('background')->nullable();
            $table->integer('featured')->default(0);
            $table->string('source')->nullable()->comment('Khuyến mãi này được tài trợ từ đâu');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
        // Bảng này lưu các sản phẩm khuyến mãi
        Schema::create('loyalty_promotions_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loyalty_promotion_id')->nullable();
            $table->unsignedBigInteger('term_id')->nullable();
            $table->string('type')->comment('loại giảm giá');
            $table->decimal('reduction_rate', 22, 4)->default(0)->comment('số tiền được giảm');
            $table->timestamps();

            $table->foreign('loyalty_promotion_id')
                ->references('id')->on('loyalty_promotions')
                ->onDelete('cascade');
            $table->foreign('term_id')
                ->references('id')->on('terms')
                ->onDelete('cascade');
        });
        // Bảng này lưu thông tin các loại giảm giá
        Schema::create('discount_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable()->comment('giám giá vận chuyển');
            $table->tinyInteger('order_amount')->nullable()->comment('Giảm theo số sản phẩm');
            $table->decimal('order_price', 22, 4)->nullable()->comment('Giảm theo giá tiền của đơn hàng');
            $table->unsignedBigInteger('term_id')->nullable()->comment('Giảm theo sản phẩm');
            $table->decimal('reduction_rate', 22, 4)->default(0)->comment('số tiền được giảm');
            $table->timestamps();

            $table->foreign('discount_id')
                ->references('id')->on('discounts')
                ->onDelete('cascade');

            $table->foreign('shipping_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');

            $table->foreign('term_id')
                ->references('id')->on('terms')
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
        Schema::dropIfExists('loyalty_promotions');
        Schema::dropIfExists('loyalty_promotions_products');
        Schema::dropIfExists('discount_promotions');
    }
}
