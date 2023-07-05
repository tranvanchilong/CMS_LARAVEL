<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ProductFeatureDetail;
use App\ProductFeatureSectionElement;

class ElementImageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = ProductFeatureDetail::whereIn('feature_type',['intro','intro 2','feature list 2',
        'feature list 3','feature list 4','feature list 5','faq','faq 2'])->get();
        foreach($sections as $section){
            foreach($section->section_elements as $key => $element){
                if($key == 0){
                  $data['feature_page_detail_id'] = $element->feature_page_detail_id;
                  $data['image'] = $element->image;
                  ProductFeatureSectionElement::create($data);
                }
                $element->image=null;
                $element->save();
            }
        }
    }
}
