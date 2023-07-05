<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableFullDomainTableRequestDomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requestdomains', function (Blueprint $table) {
            $table->string('full_domain')->after('domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requestdomains', function (Blueprint $table) {
            $table->dropColumn('full_domain');
        });
    }
}
