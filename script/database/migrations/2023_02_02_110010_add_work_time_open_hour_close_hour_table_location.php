<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkTimeOpenHourCloseHourTableLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('work_time')->nullable()->after('slot');
            $table->string('open_hour')->nullable()->after('work_time');
            $table->string('close_hour')->nullable()->after('open_hour');
            $table->tinyInteger('is_default')->default(0)->after('close_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('work_time');
            $table->dropColumn('open_hour');
            $table->dropColumn('close_hour');
            $table->dropColumn('is_default');
        });
    }
}
