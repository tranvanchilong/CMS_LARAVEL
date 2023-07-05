<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ProductFeatureDetail;

class FPSyncTableSeeder4 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductFeatureDetail::where('category',1)->update(['category' => 0]);
        ProductFeatureDetail::where('category',2)->update(['category' => 1]);
        ProductFeatureDetail::where('category',3)->update(['category' => 2]);
        ProductFeatureDetail::where('category',4)->update(['category' => 3]);
    }
}
