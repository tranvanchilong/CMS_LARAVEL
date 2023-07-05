<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnDomainIdLmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            $table_name = current($table);
            if(str_contains($table_name,'lms_')){
                Schema::table($table_name, function (Blueprint $table) {
                    $table->bigInteger('domain_id')->default(241);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            $table_name = current($table);
            if(str_contains($table_name,'lms_')){
                Schema::table($table_name, function (Blueprint $table) {
                    $table->dropColumn('domain_id');
                });
            }
        }
    }
}
