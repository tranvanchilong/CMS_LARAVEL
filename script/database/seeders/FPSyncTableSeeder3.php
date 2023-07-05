<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ProductFeatureDetail;

class FPSyncTableSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fp_details = ProductFeatureDetail::whereIn('feature_type',['hero slide','hero slide 3'])->update(['feature_type'=>'hero slide 2']);
    }
}
