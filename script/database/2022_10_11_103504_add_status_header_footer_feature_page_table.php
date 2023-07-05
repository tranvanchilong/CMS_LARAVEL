<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusHeaderFooterFeaturePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_page', function (Blueprint $table) {
            $table->tinyInteger('header_status')->after('status')->default(1);
            $table->tinyInteger('footer_status')->after('header_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_page', function (Blueprint $table) {
            $table->dropColumn('header_status');
            $table->dropColumn('footer_status');
        });
    }
}
