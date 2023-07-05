<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBtnTextUrlTableFeaturePageSectionElement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_page_section_element', function (Blueprint $table) {
            $table->string('btn_text_1')->after('btn_url')->nullable();
            $table->string('btn_url_1')->after('btn_text_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_page_section_element', function (Blueprint $table) {
            $table->dropColumn('btn_text_1');
            $table->dropColumn('btn_url_1');
        });
    }
}
