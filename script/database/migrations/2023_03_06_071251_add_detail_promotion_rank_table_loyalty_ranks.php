<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailPromotionRankTableLoyaltyRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyalty_ranks', function (Blueprint $table) {
            $table->text('content')->nullable()->after('user_id');
            $table->unsignedBigInteger('term_id')->nullable()->after('content');
            $table->unsignedBigInteger('category_id')->nullable()->after('term_id');
            $table->unsignedBigInteger('discount_id')->nullable()->after('category_id');
            $table->decimal('increase_point',4,1)->nullable()->after('discount_id')->comment('mức tăng điểm tích lũy');
            
            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade');
            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');
            $table->foreign('discount_id')
            ->references('id')->on('discounts')
            ->onDelete('cascade');
        });

        Schema::create('loyalty_benefits', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('name');
            $table->string('content');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')->on('users')
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
        Schema::table('loyalty_ranks', function (Blueprint $table) {
            //
        });
    }
}
