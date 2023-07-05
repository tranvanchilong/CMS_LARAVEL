<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFilenameBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('slot');
            $table->dropColumn('status');
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->tinyInteger('status')->nullable()->after('user_id');
            $table->tinyInteger('slot')->nullable()->after('status');
            $table->renameColumn('mobile', 'phone');
            $table->renameColumn('thumbnail', 'image');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->tinyInteger('status')->nullable()->after('booking_date');
            $table->renameColumn('mobile', 'phone');
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
