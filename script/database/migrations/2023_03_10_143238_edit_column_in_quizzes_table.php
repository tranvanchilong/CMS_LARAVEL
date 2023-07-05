<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EditColumnInQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lms_quizzes', function (Blueprint $table) {
            DB::statement("ALTER TABLE `lms_quizzes` DROP COLUMN `webinar_title`");
        });
    }

}
