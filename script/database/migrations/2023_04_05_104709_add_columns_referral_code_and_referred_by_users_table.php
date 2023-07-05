<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsReferralCodeAndReferredByUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('referral_code')->comment('Code Gioi Thieu')->nullable()->unique();
            $table->bigInteger('referred_by')->comment('Id nguoi Gioi Thieu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('referral_code');
            $table->dropColumn('referred_by');
        });
    }
}
