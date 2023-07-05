<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLangIdAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('feature_page', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('terms', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('lang_id')->default('["vi"]');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('feature_page', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
    }
}
