<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Useroption;
use App\Menu;
use Auth;
use Cache;
use App\ProductFeature;
use File;
use App\Term;
use Session;
use App\Domain;
use Image;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $menus=Menu::where('user_id',Auth::id())->whereIn('position',['header','feature_page','left','center','right','top_bar_header'])->language($lang_id)->orderBy('id', 'DESC')->get();
        return view('seller.store.menu.index',compact('lang_id','menus'));
    }


    public function store(Request $request)
    {
        $info=new Menu;
        $info->user_id=Auth::id();
        $info->position=$request->position;
        $info->name=$request->name;
        if($request->lang_id){
            $info->lang_id = json_encode($request->lang_id);
        }
        $info->data='[]';
        $info->save();

        return response()->json(['success','Menu Created']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $fps = null;
        $info=Menu::where('user_id',Auth::id())->find($id);
        $page = Term::where('type','page')->where('user_id',Auth::id())->get();
        $landing_page = ProductFeature::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();

        if($info->position=='feature_page'){
            $menu_fp=Menu::where('user_id',Auth::id())->where('position','feature_page')->whereNotNull('fp_id')->where('fp_id','!=',$info->fp_id)->pluck('fp_id')->toArray() ?? [];
            $fps = ProductFeature::where('user_id', auth()->id())->whereNotIn('id',$menu_fp)->orderBy('id', 'DESC')->get();
        }

        return view('seller.store.menu.edit',compact('info','fps','page','landing_page'));
    }
    protected function saveImgBase64($param, $folder)
    {
        list($extension, $content) = explode(';', $param);
        $tmpExtension = explode('/', $extension);
        preg_match('/.([0-9]+) /', microtime(), $m);
        $fileName = sprintf('img%s%s.%s', date('YmdHis'), $m[1], $tmpExtension[1]);
        $content = explode(',', $content)[1];
        File::put($folder . '/' . $fileName, base64_decode($content));
        return $fileName;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $info=Menu::where('user_id',Auth::id())->findorFail($id);
        $info->name=$request->name;
        $info->lang_id = $request->lang_id;
        $data=json_decode($request->data);
        if(!empty($data)){
            foreach ($data as $key => $value) {
                if(!empty($value->children)){
                    foreach ($value->children as $key => $value) {
                        $this->upload_image($value);
                    }
                }
                $this->upload_image($value);
            }
        }
        $info->data=json_encode($data);
        $info->fp_id=$request->fp_id;
        $info->save();
        return response()->json('Menu Updated');
    }
    public function upload_image($value)
    {
        if(!empty($value->image)){
            if (substr($value->image, 0,4)=='data') {
                $base64String = $value->image;
                $path='uploads/'.auth()->id().'/menu'.'/';
                if(!File::exists($path)) {
                    File::makeDirectory($path);
                }
                $fileName = $this->saveImgBase64($base64String, $path);
                $ext = explode('.',$fileName);
                $compress= resizeImage($path.$fileName,$ext[1],60,$fileName,$path); 
                $filenames = $compress['data']['image'];  
                @unlink($path.'/'.$fileName);
                $value->image=$filenames;
            }
        }
        return true;
    }

    public function show_top_banner(Request $request)
    {
        $info=Menu::where('user_id',Auth::id())->where('name','banner')->where('position','banner')->first();
        $topbar_image=Menu::where('user_id',Auth::id())->where('name','imagebanner')->where('position','imagebanner')->first();
        $info=json_decode($info->data ?? '');

        return view('seller.store.menu.edit_top_banner',compact('info','topbar_image'));
    }

    public function store_top_banner(Request $request)
    {
        $user_id=Auth::id();
        $topbar = Menu::where('user_id',$user_id)->where('name','banner')->where('position','banner')->first();

        if (empty($topbar)) {
            $topbar = new Menu;
            $topbar->position ='banner';
            $topbar->name ='banner';
        }

        $topbar_Info['url'] = $request->url;
        $topbar_Info['status'] = $request->status;
        $topbar->data = json_encode($topbar_Info);
        $topbar->user_id = $user_id;
        $topbar->save();

        $topbar_image = Menu::where('user_id',$user_id)->where('name','imagebanner')->where('position','imagebanner')->first();
        if (empty($topbar_image)) {
            $topbar_image = new Menu;
            $topbar_image->position ='imagebanner';
            $topbar_image->name ='imagebanner';
        }
        $topbar_image->user_id = $user_id;
        if($request->image){
            $imageSizes= imageUploadSizes('top_bar');
            @unlink($topbar_image->data);
            $fileName = time().'.'.$request->image->extension();  
            $path = 'uploads/'.$user_id.'/'.date('y/m').'/';
            $request->image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $topbar_image->data = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            @unlink($filenames); 
            $topbar_image->save();
        }



        return response()->json(['Top Banner Updated']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $menu = Menu::find($id);
                    if($menu){
                        $menu->delete();
                    }
                }
            }
        }
        return response()->json('Menu Deleted');
    }

    public function permalinks() {
        $permalinks = Auth::user()->user_domain->permalinks;

        $data = [
            ['slug' => 1, 'name' => 'fp', 'default' => 'fp', 'text' => __('Landing Page')],
            ['slug' => 0, 'name' => 'shop', 'default' => 'shop', 'text' => __('Shop')],
            ['slug' => 0, 'name' => 'thanks', 'default' => 'thanks', 'text' => __('Thank')],
            ['slug' => 1, 'name' => 'page', 'default' => 'page', 'text' => __('Page')],
            ['slug' => 0, 'name' => 'blog', 'default' => 'blog', 'text' => __('Blog')],
            ['slug' => 1, 'name' => 'blog_detail', 'default' => 'blog-detail', 'text' => __('Blog Detail')],
            ['slug' => 1, 'name' => 'service', 'default' => 'service', 'text' => __('Service')],
            ['slug' => 1, 'name' => 'service_detail', 'default' => 'service-detail', 'text' => __('Service Detail')],
            ['slug' => 0, 'name' => 'portfolio', 'default' => 'portfolio', 'text' => __('Portfolio')],
            ['slug' => 1, 'name' => 'portfolio_detail', 'default' => 'portfolio-detail', 'text' => __('Portfolio Detail')],
            ['slug' => 0, 'name' => 'course', 'default' => 'course', 'text' => __('Course')],
            ['slug' => 1, 'name' => 'course_detail', 'default' => 'course-detail', 'text' => __('Course Detail')],
            ['slug' => 0, 'name' => 'career', 'default' => 'career', 'text' => __('Career')],
            ['slug' => 1, 'name' => 'career_detail', 'default' => 'career-detail', 'text' => __('Career Detail')],
            ['slug' => 0, 'name' => 'team', 'default' => 'team', 'text' => __('Team')],
            ['slug' => 1, 'name' => 'team_detail', 'default' => 'team-detail', 'text' => __('Team Detail')],
            ['slug' => 0, 'name' => 'knowledge', 'default' => 'knowledge', 'text' => __('Knowledge')],
            ['slug' => 0, 'name' => 'package', 'default' => 'package', 'text' => __('Package')],
            ['slug' => 0, 'name' => 'faq', 'default' => 'faq', 'text' => __('Faq')],
            ['slug' => 0, 'name' => 'testimonial', 'default' => 'testimonial', 'text' => __('Testimonial')],
            ['slug' => 0, 'name' => 'partner', 'default' => 'partner', 'text' => __('Partner')],
            ['slug' => 0, 'name' => 'checkout', 'default' => 'checkout', 'text' => __('Checkout')],
            ['slug' => 0, 'name' => 'contact_us', 'default' => 'contact-us', 'text' => __('Contact')],
            ['slug' => 0, 'name' => 'gallery', 'default' => 'gallery', 'text' => __('Gallery')],
            ['slug' => 0, 'name' => 'booking', 'default' => 'booking', 'text' => __('Booking')],
            ['slug' => 0, 'name' => 'instructor', 'default' => 'instructor', 'text' => __('Instructor')]
        ];

        return view('seller.store.menu.permalink', compact('permalinks', 'data'));
    }

    public function permalinksUpdate(Request $request)
    {
        $domain_id = Auth::user()->domain_id;

        Domain::where('id', $domain_id)->update(['permalinks' => $request->permalinks]);
        Cache::flush();

        Session::flash('success', 'Permalinks updated successfully');
        return back();
    }


}
