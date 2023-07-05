<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateDbLms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lms_webinars', function (Blueprint $table) {
            $table->float('price', 15, 3)->unsigned()->change();
        });
        Schema::table('lms_users_metas', function (Blueprint $table) {
            $table->text('value')->change();
        });
        Schema::table('lms_sales', function (Blueprint $table) {
            $table->integer('meeting_time_id')->unsigned()->nullable();
        });
        Schema::table('lms_comments_reports', function (Blueprint $table) {
            $table->integer('bundle_id')->unsigned()->nullable();
        });
        Schema::table('lms_notifications', function (Blueprint $table) {
            $table->integer('webinar_id')->unsigned()->nullable();
            $table->foreign('webinar_id')->on('lms_webinars')->references('id')->onDelete('cascade');
        });
        Schema::table('lms_users', function (Blueprint $table) {
            $table->boolean('installment_approval')->default(false)->after('financial_approval');
            $table->boolean('enable_installments')->default(true)->after('installment_approval');
            $table->boolean('disable_cashback')->default(false)->after('enable_installments');
        });
        Schema::table('lms_rewards_accounting', function (Blueprint $table) {
            DB::statement("ALTER TABLE `lms_rewards_accounting` MODIFY COLUMN `type` enum('account_charge','create_classes','buy','pass_the_quiz','certificate','comment','register','review_courses','instructor_meeting_reserve','student_meeting_reserve','newsletters','badge','referral','learning_progress_100','charge_wallet','withdraw','buy_store_product','pass_assignment','send_post_in_topic','make_topic', 'create_blog_by_instructor','comment_for_instructor_blog') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `item_id`");
        });
        Schema::table('lms_webinars', function (Blueprint $table) {
            $table->float('organization_price', 15, 3)->unsigned()->nullable()->after('price')->change();
        });
        Schema::table('lms_navbar_buttons', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->nullable()->change();
            $table->boolean('for_guest')->default(false);
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
