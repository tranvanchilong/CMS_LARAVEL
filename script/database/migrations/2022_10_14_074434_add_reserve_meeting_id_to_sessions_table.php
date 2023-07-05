<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class AddReserveMeetingIdToSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lms_sessions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `lms_sessions` MODIFY COLUMN `webinar_id` int UNSIGNED NULL AFTER `creator_id`");

            $table->integer('reserve_meeting_id')->unsigned()->after('chapter_id')->nullable();

            $table->foreign('reserve_meeting_id')->on('lms_reserve_meetings')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lms_sessions', function (Blueprint $table) {
            //
        });
    }
}
