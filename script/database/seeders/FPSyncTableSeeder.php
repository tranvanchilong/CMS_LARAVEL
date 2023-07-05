<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ProductFeatureDetail;
use App\Domain;

class FPSyncTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fp_details = ProductFeatureDetail::all();
        foreach ($fp_details as $key => $value) {
            if(in_array($value->feature_type,['list image'])){
              $value->feature_type='feature list';
            }
            if($value->feature_type=='new arrival product'){
              $value->feature_type='new product';
            }
            if($value->feature_type=='slide image'){
              $value->data_type='input';
            }

            $value->data_type=$value->feature_type;
            if($value->feature_type=='slider'){
              $value->feature_type='hero slide';
            }
            if(in_array($value->feature_type,['service','portfolio','slide image'])){
              $value->feature_type='blog';
            }
            if(in_array($value->feature_type,['brand banner'])){
              $value->feature_type='category';
            }
            if(in_array($value->feature_type,['random product','new product','best selling product','trending product','top rate product'])){
              $value->feature_type='list product';
            }
            
            if($value->feature_type=='only image'){
              $value->delete();
            }
            $value->save();
        }
        Domain::query()->update(['template_id'=>5]);
    }
}
