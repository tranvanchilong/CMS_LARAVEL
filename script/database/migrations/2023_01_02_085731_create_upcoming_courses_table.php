<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpcomingCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_upcoming_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->unsigned();
            $table->integer('teacher_id')->unsigned();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('webinar_id')->unsigned()->nullable()->comment('when assigned a course');
            $table->enum('type', ['webinar', 'course', 'text_lesson']);
            $table->string('slug')->unique()->index();
            $table->string('thumbnail');
            $table->string('image_cover');
            $table->string('video_demo')->nullable();
            $table->enum('video_demo_source', ['upload', 'youtube', 'vimeo', 'external_link'])->nullable();
            $table->bigInteger('publish_date')->unsigned()->nullable();
            $table->string('timezone')->nullable();
            $table->integer('points')->unsigned()->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->float('price', 15, 2)->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->integer('sections')->unsigned()->nullable();
            $table->integer('parts')->unsigned()->nullable();
            $table->integer('course_progress')->unsigned()->nullable();
            $table->boolean('support')->default(false);
            $table->boolean('certificate')->default(false);
            $table->boolean('include_quizzes')->default(false);
            $table->boolean('downloadable')->default(false);
            $table->boolean('forum')->default(false);
            $table->text('message_for_reviewer')->nullable();
            $table->enum('status', ['active', 'pending', 'is_draft', 'inactive'])->default('is_draft');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('creator_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('teacher_id')->on('lms_users')->references('id')->cascadeOnDelete();
            $table->foreign('category_id')->on('lms_categories')->references('id')->cascadeOnDelete();
            $table->foreign('webinar_id')->on('lms_webinars')->references('id')->nullOnDelete();
        });


        Schema::create('lms_upcoming_course_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('upcoming_course_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('seo_description')->nullable();
            $table->longText('description')->nullable();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('upcoming_course_id')->on('lms_upcoming_courses')->references('id')->onDelete('cascade');
        });

        Schema::create('lms_upcoming_course_filter_option', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upcoming_course_id')->unsigned();
            $table->integer('filter_option_id')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('upcoming_course_id')->references('id')->on('lms_upcoming_courses')->onDelete('cascade');;
            $table->foreign('filter_option_id')->references('id')->on('lms_filter_options')->onDelete('cascade');;
        });

        Schema::create('lms_upcoming_course_followers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upcoming_course_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('upcoming_course_id')->references('id')->on('lms_upcoming_courses')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('lms_users')->onDelete('cascade');;
        });

        Schema::create('lms_upcoming_course_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upcoming_course_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('reason');
            $table->text('message');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('domain_id')->default(241);

            $table->foreign('upcoming_course_id')->references('id')->on('lms_upcoming_courses')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('lms_users')->onDelete('cascade');;
        });

        Schema::table('lms_tags', function (Blueprint $table) {
            $table->integer('upcoming_course_id')->unsigned()->nullable();

            $table->foreign('upcoming_course_id')->on('lms_upcoming_courses')->references('id')->onDelete('cascade');
        });

        Schema::table('lms_faqs', function (Blueprint $table) {
            $table->integer('upcoming_course_id')->unsigned()->nullable()->after('bundle_id');

            $table->foreign('upcoming_course_id')->on('lms_upcoming_courses')->references('id')->onDelete('cascade');
        });

        Schema::table('lms_favorites', function (Blueprint $table) {
            $table->integer('upcoming_course_id')->unsigned()->nullable()->after('bundle_id');

            $table->foreign('upcoming_course_id')->on('lms_upcoming_courses')->references('id')->onDelete('cascade');
        });

        Schema::table('lms_comments', function (Blueprint $table) {
            $table->integer('upcoming_course_id')->unsigned()->nullable()->after('bundle_id');

            $table->foreign('upcoming_course_id')->on('lms_upcoming_courses')->references('id')->onDelete('cascade');
        });

        Schema::table('lms_webinar_extra_descriptions', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `lms_webinar_extra_descriptions` MODIFY COLUMN `webinar_id` int UNSIGNED NULL AFTER `creator_id`");
            $table->integer('upcoming_course_id')->unsigned()->nullable()->after('webinar_id');

            $table->foreign('upcoming_course_id')->on('lms_upcoming_courses')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_upcoming_courses');
        Schema::dropIfExists('lms_upcoming_course_translations');
        Schema::dropIfExists('lms_upcoming_course_filter_option');
    }
}
