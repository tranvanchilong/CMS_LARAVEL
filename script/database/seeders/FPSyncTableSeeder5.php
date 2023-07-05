<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ProductFeatureDetail;

class FPSyncTableSeeder5 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array_section = ['intro','intro 2','feature list 2','feature list 3','feature list 4','feature list 5',
                            'faq','faq 2'];
        $sections = ProductFeatureDetail::whereIn('feature_type',$array_section)->get();
        foreach($sections as $section){
            $section_element = $section->section_elements_image->first();
            if($section_element){
                $section->image = $section_element->image;
                $section->save();
                $section_element->delete();
            }
        }
    }
}
