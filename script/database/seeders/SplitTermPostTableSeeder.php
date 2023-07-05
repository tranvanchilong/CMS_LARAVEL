<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Term;
use App\Post;

class SplitTermPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Term::whereIn('type',['blog','page'])->get();
        foreach($posts as $post){
            $check_exist = Post::where(['slug'=>$post->slug,'user_id'=>$post->user_id,'is_admin'=>$post->is_admin])->first();
            if(!$check_exist){
                $data=[
                    'title'=>$post->title,
                    'slug'=>$post->slug,
                    'user_id'=>$post->user_id,
                    'status'=>$post->status,
                    'featured'=>$post->featured,
                    'type'=>$post->type,
                    'is_admin'=>$post->is_admin,
                    'created_at'=>$post->created_at,
                    'updated_at'=>$post->updated_at,
                    'lang_id'=>$post->lang_id,
                ];
                if($post->excerpt){
                    $data['excerpt']=$post->excerpt->value;
                    $post->excerpt->delete();
                }
                if($post->content){
                    $data['content']=$post->content->value;
                    $post->content->delete();
                }
                if($post->medias->first()){
                    $data['image']=$post->medias->first()->name ?? '';
                    $post->medias->first()->delete();
                }
                if($post->post_categories->first()){
                    $data['category_id']=$post->post_categories->first()->category_id ?? '';
                    $post->post_categories->first()->delete();
                }
                if($post->seo){
                    $data['meta_keyword']=json_decode($post->seo->value)->meta_keyword ?? '';
                    $data['meta_description']=json_decode($post->seo->value)->meta_description ?? '';
                    $post->seo->delete();
                }
                Post::create($data);
            }
            $post->delete();
        }
    }
}
