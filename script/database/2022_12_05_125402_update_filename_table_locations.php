<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFilenameTableLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('location_lat', 'latitude');
            $table->renameColumn('location_lng', 'longitude');
            $table->dropColumn('is_admin');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->smallInteger('status')->change();
            $table->smallInteger('slot')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
