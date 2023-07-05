<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifyEmailTableUsersCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('email_verified')->after('email_verified_at')->default(0)->comment('1 - verified, 0 - not verified');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->tinyInteger('email_verified')->after('email_verified_at')->default(0)->comment('1 - verified, 0 - not verified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('email_verified');
        });
    }
}
