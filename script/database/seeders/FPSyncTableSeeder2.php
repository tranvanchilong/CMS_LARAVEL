<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ProductFeatureDetail;

class FPSyncTableSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fp_details = ProductFeatureDetail::whereIn('feature_type',['intro','feature list','hero slide'])->get();
        foreach ($fp_details as $key => $value) {
          if($value->feature_type=='intro'){
            $value->data_type='input';
            if($value->feature_position==0){
              $value->feature_type='intro';
            }
            if($value->feature_position==1){
              $value->feature_type='intro 2';
            }
            if($value->feature_position==2){
              $value->feature_type='intro 2';
            }
          }
          if($value->feature_type=='feature list'){
            $value->data_type='input';
            if($value->feature_position==0){
              $value->feature_type='feature list 3';
            }
            if($value->feature_position==1){
              $value->feature_type='feature list';
            }
            if($value->feature_position==2){
              $value->feature_type='feature list 2';
            }
          }
          if($value->feature_type=='hero slide'){
            $value->data_type='slider';
            if($value->feature_position==0){
              $value->feature_type='hero slide 3';
            }
            if($value->feature_position==1){
              $value->feature_type='hero slide 2';
            }
            if($value->feature_position==2){
              $value->feature_type='hero slide';
            }
          }
          $value->save();
        }
    }
}
