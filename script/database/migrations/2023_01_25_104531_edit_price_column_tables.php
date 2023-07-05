<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class EditPriceColumnTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lms_products', function (Blueprint $table) {
            DB::statement("ALTER TABLE `lms_products`
                MODIFY COLUMN `price` double(15, 2) UNSIGNED NULL DEFAULT NULL AFTER `category_id`,
                MODIFY COLUMN `delivery_fee` double(15, 2) UNSIGNED NULL DEFAULT NULL AFTER `inventory_updated_at`");
        });

        Schema::table('lms_meetings', function (Blueprint $table) {
                DB::statement("ALTER TABLE `lms_meetings`
                    MODIFY COLUMN `amount` double(15, 2) UNSIGNED NULL DEFAULT NULL AFTER `creator_id`,
                    MODIFY COLUMN `in_person_amount` double(15, 2) NULL DEFAULT NULL AFTER `in_person`,
                    MODIFY COLUMN `online_group_amount` double(15, 2) NULL DEFAULT NULL AFTER `online_group_max_student`,
                    MODIFY COLUMN `in_person_group_amount` double(15, 2) NULL DEFAULT NULL AFTER `in_person_group_max_student`");
        });

        Schema::table('lms_orders', function (Blueprint $table) {
                DB::statement("ALTER TABLE `lms_orders`
                    MODIFY COLUMN `amount` double(15, 2) UNSIGNED NOT NULL AFTER `is_charge_account`");
        });

        Schema::table('lms_order_items', function (Blueprint $table) {
                DB::statement("ALTER TABLE `lms_order_items`
                    MODIFY COLUMN `amount` double(15, 2) UNSIGNED NULL DEFAULT NULL AFTER `become_instructor_id`");
        });

        Schema::table('lms_registration_packages', function (Blueprint $table) {
                DB::statement("ALTER TABLE `lms_registration_packages`
                            MODIFY COLUMN `price` double(15, 2) UNSIGNED NOT NULL AFTER `days`");
        });

        Schema::table('lms_registration_packages', function (Blueprint $table) {
                DB::statement("ALTER TABLE `lms_subscribes`
                        MODIFY COLUMN `price` double(15, 2) UNSIGNED NOT NULL AFTER `days`");
        });


    }
}
