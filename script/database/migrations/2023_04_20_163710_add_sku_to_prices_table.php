<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkuToPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Thêm cột 'sku' vào bảng 'prices' với thuộc tính unique
        Schema::table('prices', function (Blueprint $table) {
            $table->string('sku')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prices', function($table) {
            $table->dropColumn('sku');
        });
    }
}
